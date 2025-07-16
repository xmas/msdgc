<script setup>
import { ref, computed, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';
import KeyValueEditor from '@/Components/KeyValueEditor.vue';

const props = defineProps({
    event: Object,
    availableUsers: Array
});

const showAddUserForm = ref(false);
const editingUsers = ref(new Set());
const searchQuery = ref('');
const sortBy = ref('name');
const sortOrder = ref('asc');

const addUserForm = useForm({
    user_id: '',
    attrs: ''
});

// Initialize form with default user attributes
onMounted(() => {
    const userAttrs = props.event.user_attrs || {};
    if (Object.keys(userAttrs).length > 0) {
        addUserForm.attrs = JSON.stringify(userAttrs);
    }
});

// Store for user attribute forms
const userAttrsForms = ref({});

// Transform users for SearchableSelect component
const userOptions = computed(() => {
    return props.availableUsers.map(user => ({
        value: user.id,
        label: `${user.first_name} ${user.last_name} (${user.email})`
    }));
});

// Filter and sort participants
const filteredAndSortedUsers = computed(() => {
    let users = [...props.event.users];

    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        users = users.filter(user => {
            const fullName = `${user.first_name} ${user.last_name}`.toLowerCase();
            return fullName.includes(query) ||
                user.email.toLowerCase().includes(query) ||
                (user.pivot.attrs && Object.keys(user.pivot.attrs).some(key =>
                    key.toLowerCase().includes(query) ||
                    String(user.pivot.attrs[key]).toLowerCase().includes(query)
                ));
        });
    }

    // Apply sorting
    users.sort((a, b) => {
        let aValue, bValue;

        switch (sortBy.value) {
            case 'name':
                aValue = `${a.first_name} ${a.last_name}`.toLowerCase();
                bValue = `${b.first_name} ${b.last_name}`.toLowerCase();
                break;
            case 'email':
                aValue = a.email.toLowerCase();
                bValue = b.email.toLowerCase();
                break;
            case 'joined':
                aValue = new Date(a.pivot.created_at);
                bValue = new Date(b.pivot.created_at);
                break;
            default:
                aValue = `${a.first_name} ${a.last_name}`.toLowerCase();
                bValue = `${b.first_name} ${b.last_name}`.toLowerCase();
        }

        if (aValue < bValue) return sortOrder.value === 'asc' ? -1 : 1;
        if (aValue > bValue) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });

    return users;
});

const setSortBy = (field) => {
    if (sortBy.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = field;
        sortOrder.value = 'asc';
    }
};

const deleteEvent = () => {
    if (confirm('Are you sure you want to delete this event?')) {
        router.delete(route('events.destroy', props.event.id));
    }
};

const removeUser = (userId) => {
    if (confirm('Are you sure you want to remove this user from the event?')) {
        router.delete(route('events.users.remove', { event: props.event.id, user: userId }));
    }
};

const submitAddUser = () => {
    addUserForm.post(route('events.users.add', props.event.id), {
        onSuccess: () => {
            addUserForm.reset();
            // Reset attrs to default user_attrs
            const userAttrs = props.event.user_attrs || {};
            if (Object.keys(userAttrs).length > 0) {
                addUserForm.attrs = JSON.stringify(userAttrs);
            }
            showAddUserForm.value = false;
        }
    });
};

const startEditingUser = (user) => {
    editingUsers.value.add(user.id);
    // Initialize form with current attrs
    const currentAttrs = user.pivot.attrs || {};
    userAttrsForms.value[user.id] = useForm({
        attrs: Object.keys(currentAttrs).length > 0 ? JSON.stringify(currentAttrs) : ''
    });
};

const cancelEditingUser = (userId) => {
    editingUsers.value.delete(userId);
    delete userAttrsForms.value[userId];
};

const saveUserAttrs = async (userId) => {
    const form = userAttrsForms.value[userId];
    if (!form) return;

    try {
        const response = await axios.put(`/api/events/${props.event.id}/users/${userId}/attributes`, {
            attrs: form.attrs
        });

        // Refresh the page to get updated data
        router.visit(route('events.show', props.event.id), {
            preserveScroll: true
        });

        editingUsers.value.delete(userId);
        delete userAttrsForms.value[userId];
    } catch (error) {
        console.error('Error updating user attributes:', error);
        alert('Error updating user attributes. Please try again.');
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZoneName: 'short'
    });
};

const formatAttrs = (attrs) => {
    if (!attrs || Object.keys(attrs).length === 0) return null;
    return attrs;
};

const formatPivotAttrs = (attrs) => {
    if (!attrs || Object.keys(attrs).length === 0) return 'None';
    return JSON.stringify(attrs, null, 2);
};

// CSV Download functionality
const downloadParticipantsCSV = () => {
    const participants = filteredAndSortedUsers.value;
    if (participants.length === 0) {
        alert('No participants to download');
        return;
    }

    // Get all unique attribute keys across all participants
    const allAttributeKeys = new Set();
    participants.forEach(user => {
        if (user.pivot.attrs) {
            Object.keys(user.pivot.attrs).forEach(key => {
                allAttributeKeys.add(key);
            });
        }
    });

    // Create CSV headers
    const headers = ['Name', 'Email', 'Joined Date'];
    const attributeKeysArray = Array.from(allAttributeKeys).sort();
    headers.push(...attributeKeysArray);

    // Create CSV rows
    const csvRows = [];
    csvRows.push(headers.join(','));

    participants.forEach(user => {
        const row = [];

        // Basic user info
        row.push(`"${user.first_name} ${user.last_name}"`);
        row.push(`"${user.email}"`);
        row.push(`"${formatDate(user.pivot.created_at)}"`);

        // Attribute values
        attributeKeysArray.forEach(key => {
            const value = user.pivot.attrs && user.pivot.attrs[key] ? user.pivot.attrs[key] : '';
            row.push(`"${value}"`);
        });

        csvRows.push(row.join(','));
    });

    // Create and download CSV file
    const csvContent = csvRows.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `${props.event.name}_participants.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};
</script>

<template>
    <AppLayout title="Event Details">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Event: {{ event.name }}
                </h2>
                <div class="flex space-x-2">
                    <SecondaryButton @click="router.visit(route('events.index'))">
                        Back to Events
                    </SecondaryButton>
                    <SecondaryButton @click="router.visit(route('events.edit', event.id))">
                        Edit Event
                    </SecondaryButton>
                    <DangerButton @click="deleteEvent">
                        Delete Event
                    </DangerButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Success/Error Messages -->
                <div v-if="$page.props.flash?.success"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                </div>

                <div v-if="$page.props.flash?.error"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                </div>

                <!-- Event Details -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Event Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ event.name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event Group</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ event.event_group }}
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(event.created_at) }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ formatDate(event.updated_at) }}</dd>
                            </div>
                        </div>

                        <!-- Event Attributes and Default User Attributes -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Attributes -->
                            <div v-if="formatAttrs(event.attrs)">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Event Attributes</dt>
                                <div v-if="event.attrs && Object.keys(event.attrs).length > 0" class="space-y-1">
                                    <div v-for="(value, key) in event.attrs" :key="key" class="flex items-center space-x-2">
                                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ key }}</span>
                                        <span class="text-xs text-gray-800">{{ value }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Default User Attributes -->
                            <div v-if="formatAttrs(event.user_attrs)">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Default User Attributes</dt>
                                <div v-if="event.user_attrs && Object.keys(event.user_attrs).length > 0" class="space-y-1">
                                    <div v-for="(value, key) in event.user_attrs" :key="key" class="flex items-center space-x-2">
                                        <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded">{{ key }}</span>
                                        <span class="text-xs text-gray-800">{{ value }}</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    These attributes will be pre-filled when adding new users to this event.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Participants
                                <span v-if="searchQuery && filteredAndSortedUsers.length !== event.users.length" class="text-sm font-normal text-gray-500">
                                    ({{ filteredAndSortedUsers.length }} of {{ event.users.length }})
                                </span>
                                <span v-else class="text-sm font-normal text-gray-500">
                                    ({{ event.users.length }})
                                </span>
                            </h3>
                            <div class="flex space-x-2">
                                <SecondaryButton
                                    v-if="event.users.length > 0"
                                    @click="downloadParticipantsCSV"
                                    class="flex items-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Download CSV</span>
                                </SecondaryButton>
                                <PrimaryButton v-if="availableUsers.length > 0" @click="showAddUserForm = !showAddUserForm">
                                    {{ showAddUserForm ? 'Cancel' : 'Add User' }}
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Add User Form -->
                        <div v-if="showAddUserForm" class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <form @submit.prevent="submitAddUser">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="user_id" value="Select User" />
                                        <SearchableSelect v-model="addUserForm.user_id" :options="userOptions"
                                            placeholder="Choose a user..." search-placeholder="Search users..."
                                            :required="true" />
                                        <InputError :message="addUserForm.errors.user_id" class="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel for="attrs" value="Per-Event Attributes" />
                                        <KeyValueEditor v-model="addUserForm.attrs"
                                            placeholder="Add per-event attributes..."
                                            key-placeholder="Attribute name (e.g., role, notes)"
                                            value-placeholder="Attribute value" />
                                        <InputError :message="addUserForm.errors.attrs" class="mt-2" />
                                        <p class="mt-2 text-sm text-gray-500">
                                            Optional: Add custom attributes for this user's participation. Example: role
                                            =
                                            "speaker", notes = "Special requirements"
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <SecondaryButton type="button" @click="showAddUserForm = false">
                                        Cancel
                                    </SecondaryButton>
                                    <PrimaryButton :class="{ 'opacity-25': addUserForm.processing }"
                                        :disabled="addUserForm.processing">
                                        Add User
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <!-- Search and Sort Controls -->
                        <div v-if="event.users.length > 0" class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex-1 max-w-md">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search participants..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                            </div>
                            <div class="flex space-x-2">
                                <select
                                    v-model="sortBy"
                                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="name">Sort by Name</option>
                                    <option value="email">Sort by Email</option>
                                    <option value="joined">Sort by Joined Date</option>
                                </select>
                                <button
                                    @click="sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
                                    class="px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    {{ sortOrder === 'asc' ? '↑' : '↓' }}
                                </button>
                            </div>
                        </div>

                        <!-- No Users Message -->
                        <div v-if="event.users.length === 0" class="text-center py-8">
                            <p class="text-gray-500">No participants yet.</p>
                            <p v-if="availableUsers.length === 0" class="text-sm text-gray-400 mt-2">
                                All users are already part of this event.
                            </p>
                        </div>

                        <!-- Filtered Results Message -->
                        <div v-else-if="filteredAndSortedUsers.length === 0" class="text-center py-8">
                            <p class="text-gray-500">No participants match your search.</p>
                            <button
                                @click="searchQuery = ''"
                                class="mt-2 text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                            >
                                Clear search
                            </button>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            @click="setSortBy('name')"
                                        >
                                            <div class="flex items-center space-x-1">
                                                <span>Name</span>
                                                <span v-if="sortBy === 'name'" class="text-indigo-600">
                                                    {{ sortOrder === 'asc' ? '↑' : '↓' }}
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            @click="setSortBy('email')"
                                        >
                                            <div class="flex items-center space-x-1">
                                                <span>Email</span>
                                                <span v-if="sortBy === 'email'" class="text-indigo-600">
                                                    {{ sortOrder === 'asc' ? '↑' : '↓' }}
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            @click="setSortBy('joined')"
                                        >
                                            <div class="flex items-center space-x-1">
                                                <span>Joined</span>
                                                <span v-if="sortBy === 'joined'" class="text-indigo-600">
                                                    {{ sortOrder === 'asc' ? '↑' : '↓' }}
                                                </span>
                                            </div>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Per-Event Attributes
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in filteredAndSortedUsers" :key="user.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.first_name }} {{ user.last_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ user.email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(user.pivot.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <!-- Editing mode -->
                                            <div v-if="editingUsers.has(user.id)" class="min-w-0 flex-1">
                                                <div class="max-w-md">
                                                    <KeyValueEditor v-model="userAttrsForms[user.id].attrs"
                                                        placeholder="Add per-event attributes..."
                                                        key-placeholder="Attribute name"
                                                        value-placeholder="Attribute value" />
                                                </div>
                                                <div class="mt-2 flex space-x-2">
                                                    <button @click="saveUserAttrs(user.id)"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Save
                                                    </button>
                                                    <button @click="cancelEditingUser(user.id)"
                                                        class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Display mode -->
                                            <div v-else>
                                                <div v-if="user.pivot.attrs && Object.keys(user.pivot.attrs).length > 0"
                                                    class="space-y-1">
                                                    <div v-for="(value, key) in user.pivot.attrs" :key="key"
                                                        class="flex items-center space-x-2">
                                                        <span
                                                            class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">{{
                                                            key }}</span>
                                                        <span class="text-xs text-gray-800">{{ value }}</span>
                                                    </div>
                                                </div>
                                                <div v-else class="text-gray-500">
                                                    No attributes
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex space-x-2">
                                                <button v-if="!editingUsers.has(user.id)"
                                                    @click="startEditingUser(user)"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                    Edit
                                                </button>
                                                <button @click="removeUser(user.id)"
                                                    class="text-red-600 hover:text-red-900 font-medium">
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- CSV Download Button -->
                        <div v-if="filteredAndSortedUsers.length > 0" class="mt-4">
                            <PrimaryButton @click="downloadParticipantsCSV">
                                Download Participants CSV
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
