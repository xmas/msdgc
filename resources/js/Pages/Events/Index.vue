<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    events: Object
});

const deleteEvent = (eventId) => {
    if (confirm('Are you sure you want to delete this event?')) {
        router.delete(route('events.destroy', eventId));
    }
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatAttrs = (attrs) => {
    if (!attrs || Object.keys(attrs).length === 0) return 'None';
    return Object.entries(attrs)
        .map(([key, value]) => `${key}: ${JSON.stringify(value)}`)
        .join(', ');
};
</script>

<template>
    <AppLayout title="Events Management">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Events Management
                </h2>
                <PrimaryButton @click="router.visit(route('events.create'))">
                    Create New Event
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="events.data.length === 0" class="text-center py-8">
                            <p class="text-gray-500 text-lg">No events found.</p>
                            <PrimaryButton @click="router.visit(route('events.create'))" class="mt-4">
                                Create Your First Event
                            </PrimaryButton>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Group
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Participants
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Attributes
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="event in events.data" :key="event.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ event.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ event.event_group }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <span class="mr-2">{{ event.users.length }}</span>
                                                <span class="text-gray-500">participants</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                            <div class="truncate" :title="formatAttrs(event.attrs)">
                                                {{ formatAttrs(event.attrs) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(event.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <SecondaryButton @click="router.visit(route('events.show', event.id))">
                                                    View
                                                </SecondaryButton>
                                                <SecondaryButton @click="router.visit(route('events.edit', event.id))">
                                                    Edit
                                                </SecondaryButton>
                                                <DangerButton @click="deleteEvent(event.id)">
                                                    Delete
                                                </DangerButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="events.links && events.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <template v-for="link in events.links" :key="link.label">
                                    <button
                                        v-if="link.url"
                                        @click="router.visit(link.url)"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                        ]"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            'bg-white border-gray-300 text-gray-300 cursor-not-allowed'
                                        ]"
                                        v-html="link.label"
                                    />
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
