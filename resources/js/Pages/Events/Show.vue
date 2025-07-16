<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps({
    event: Object,
    availableUsers: Array
});

const showAddUserForm = ref(false);

const addUserForm = useForm({
    user_id: '',
    attrs: ''
});

// Transform users for SearchableSelect component
const userOptions = computed(() => {
    return props.availableUsers.map(user => ({
        value: user.id,
        label: `${user.name} (${user.email})`
    }));
});

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
            showAddUserForm.value = false;
        }
    });
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
                <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                </div>

                <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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

                        <!-- Event Attributes -->
                        <div v-if="formatAttrs(event.attrs)" class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Event Attributes</dt>
                            <dd class="mt-1">
                                <pre class="bg-gray-50 p-4 rounded-md text-sm overflow-x-auto">{{ JSON.stringify(event.attrs, null, 2) }}</pre>
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Participants ({{ event.users.length }})
                            </h3>
                            <PrimaryButton v-if="availableUsers.length > 0" @click="showAddUserForm = !showAddUserForm">
                                {{ showAddUserForm ? 'Cancel' : 'Add User' }}
                            </PrimaryButton>
                        </div>

                        <!-- Add User Form -->
                        <div v-if="showAddUserForm" class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <form @submit.prevent="submitAddUser">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="user_id" value="Select User" />
                                        <SearchableSelect
                                            v-model="addUserForm.user_id"
                                            :options="userOptions"
                                            placeholder="Choose a user..."
                                            search-placeholder="Search users..."
                                            :required="true"
                                        />
                                        <InputError :message="addUserForm.errors.user_id" class="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel for="attrs" value="Per-Event Attributes (JSON)" />
                                        <textarea
                                            id="attrs"
                                            v-model="addUserForm.attrs"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            rows="3"
                                            placeholder='{"role": "participant", "notes": "Special requirements"}'
                                        />
                                        <InputError :message="addUserForm.errors.attrs" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end space-x-2">
                                    <SecondaryButton type="button" @click="showAddUserForm = false">
                                        Cancel
                                    </SecondaryButton>
                                    <PrimaryButton :class="{ 'opacity-25': addUserForm.processing }" :disabled="addUserForm.processing">
                                        Add User
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>

                        <!-- No Users Message -->
                        <div v-if="event.users.length === 0" class="text-center py-8">
                            <p class="text-gray-500">No participants yet.</p>
                            <p v-if="availableUsers.length === 0" class="text-sm text-gray-400 mt-2">
                                All users are already part of this event.
                            </p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Joined
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Per-Event Attributes
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in event.users" :key="user.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.name }}
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
                                            <div v-if="user.pivot.attrs && Object.keys(user.pivot.attrs).length > 0">
                                                <pre class="bg-gray-50 p-2 rounded text-xs overflow-x-auto max-w-xs">{{ formatPivotAttrs(user.pivot.attrs) }}</pre>
                                            </div>
                                            <div v-else class="text-gray-500">
                                                No attributes
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <button
                                                @click="removeUser(user.id)"
                                                class="text-red-600 hover:text-red-900 font-medium"
                                            >
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
