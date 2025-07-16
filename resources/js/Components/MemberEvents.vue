<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                You are part of {{ events.length }} {{ events.length === 1 ? 'event' : 'events' }}
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2M8 7h8M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M9 17H7M17 17h2">
                    </path>
                </svg>
                <p class="text-lg font-medium">No events found</p>
                <p class="text-sm">This member is not part of any events yet.</p>
            </div>
        </div>

        <!-- Events list grouped by event_group -->
        <div v-else class="space-y-4">
            <div v-for="(groupEvents, groupName) in groupedEvents" :key="groupName" class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                <!-- Group header -->
                <button @click="toggleGroup(groupName)"
                    class="w-full flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ groupName || 'Ungrouped' }}
                        </h3>
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ groupEvents.length }}
                        </span>
                    </div>
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transform transition-transform"
                         :class="{ 'rotate-180': expandedGroups[groupName] }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Group content -->
                <div v-show="expandedGroups[groupName]" class="border-t border-gray-200 dark:border-gray-600">
                    <div class="divide-y divide-gray-200 dark:divide-gray-600">
                        <div v-for="event in groupEvents" :key="event.id"
                            class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ event.name }}
                                </h4>

                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Joined:</span>
                                    {{ formatDate(event.pivot.created_at) }}
                                </div>
                            </div>

                            <div v-if="event.pivot.attrs" class="mt-4 p-3 bg-gray-50 dark:bg-gray-600 rounded-md grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2">
                                <div v-for="(value, key) in event.pivot.attrs" :key="key" class="col-span-1 justify-between text-sm pb-4">
                                    <div class="font-bold text-gray-600 dark:text-gray-400 uppercase text-sm pb-2">{{ key }}</div>
                                    <div v-if="value" class="rounded-sm bg-blue-500 text-white font-bold text-lg text-center mr-6 py-1">{{ value }}</div>
                                    <div v-else class="text-gray-400 italic">No value</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refresh button -->
        <div class="flex justify-center pt-4">
            <button @click="fetchEvents" :disabled="loading"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                {{ loading ? 'Refreshing...' : 'Refresh' }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue';
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
const expandedGroups = reactive({});

// Computed properties
const groupedEvents = computed(() => {
    const groups = {};

    events.value.forEach(event => {
        const groupName = event.event_group || 'Ungrouped';
        if (!groups[groupName]) {
            groups[groupName] = [];
        }
        groups[groupName].push(event);
    });

    // Sort groups alphabetically, but put 'Ungrouped' last
    const sortedGroups = {};
    Object.keys(groups).sort((a, b) => {
        if (a === 'Ungrouped') return 1;
        if (b === 'Ungrouped') return -1;
        return a.localeCompare(b);
    }).forEach(key => {
        sortedGroups[key] = groups[key];
    });

    return sortedGroups;
});

// Methods
const fetchEvents = async () => {
    if (!props.userId) return;

    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`/api/users/${props.userId}/events`);
        events.value = response.data;

        // Auto-expand all groups on first load
        Object.keys(groupedEvents.value).forEach(groupName => {
            if (expandedGroups[groupName] === undefined) {
                expandedGroups[groupName] = true;
            }
        });
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to fetch events';
        console.error('Error fetching user events:', err);
    } finally {
        loading.value = false;
    }
};

const toggleGroup = (groupName) => {
    expandedGroups[groupName] = !expandedGroups[groupName];
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
