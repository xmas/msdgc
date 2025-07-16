<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Member Events
            </h3>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ events.length }} {{ events.length === 1 ? 'event' : 'events' }}
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="flex items-center justify-center p-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <!-- Empty state -->
        <div v-else-if="!events.length" class="text-center py-8">
            <div class="text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2M8 7h8M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M9 17H7M17 17h2"></path>
                </svg>
                <p class="text-lg font-medium">No events found</p>
                <p class="text-sm">This member is not part of any events yet.</p>
            </div>
        </div>

        <!-- Events list -->
        <div v-else class="space-y-4">
            <div
                v-for="event in events"
                :key="event.id"
                class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4 hover:shadow-md transition-shadow"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ event.name }}
                        </h4>

                        <div class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            {{ event.event_group }}
                        </div>

                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Joined:</span>
                            {{ formatDate(event.pivot.created_at) }}
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Event attributes indicator -->
                        <div v-if="event.pivot.attrs && Object.keys(event.pivot.attrs).length > 0" class="relative">
                            <button
                                @click="toggleAttributes(event.id)"
                                class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                :title="showAttributes[event.id] ? 'Hide attributes' : 'Show attributes'"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Event attributes (expandable) -->
                <div v-if="showAttributes[event.id] && event.pivot.attrs" class="mt-4 p-3 bg-gray-50 dark:bg-gray-600 rounded-md">
                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Attributes</h5>
                    <div class="space-y-1">
                        <div
                            v-for="(value, key) in event.pivot.attrs"
                            :key="key"
                            class="flex justify-between text-sm"
                        >
                            <span class="font-medium text-gray-600 dark:text-gray-400">{{ key }}:</span>
                            <span class="text-gray-900 dark:text-white">{{ value }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refresh button -->
        <div class="flex justify-center pt-4">
            <button
                @click="fetchEvents"
                :disabled="loading"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ loading ? 'Refreshing...' : 'Refresh' }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
    userId: {
        type: [Number, String],
        required: true
    }
});

// Reactive data
const events = ref([]);
const loading = ref(false);
const error = ref(null);
const showAttributes = reactive({});

// Methods
const fetchEvents = async () => {
    if (!props.userId) return;

    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`/api/users/${props.userId}/events`);
        events.value = response.data;
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to fetch events';
        console.error('Error fetching user events:', err);
    } finally {
        loading.value = false;
    }
};

const toggleAttributes = (eventId) => {
    showAttributes[eventId] = !showAttributes[eventId];
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Lifecycle
onMounted(() => {
    fetchEvents();
});

// Expose methods for parent component
defineExpose({
    fetchEvents
});
</script>
