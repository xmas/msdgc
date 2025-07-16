<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, onMounted, computed } from 'vue';

const minutes = ref([]);
const loading = ref(true);
const error = ref(null);
const collapsedYears = ref(new Set());

// Board members state
const boardMembers = ref([]);
const membersLoading = ref(true);
const membersError = ref(null);

onMounted(async () => {
    try {
        // Fetch board minutes
        const response = await fetch('/api/collections/board_minutes');
        if (response.ok) {
            const data = await response.json();
            // Sort minutes newest to oldest by date_of_meeting
            minutes.value = (data.items || []).sort((a, b) => {
                const dateA = new Date(a.date_of_meeting || 0);
                const dateB = new Date(b.date_of_meeting || 0);
                return dateB - dateA;
            });

            // Initialize collapsed years - collapse all except current year
            const currentYear = new Date().getFullYear();
            const yearsToCollapse = new Set();

            minutes.value.forEach(minute => {
                if (minute.date_of_meeting) {
                    const year = new Date(minute.date_of_meeting).getFullYear();
                    if (year !== currentYear) {
                        yearsToCollapse.add(year.toString());
                    }
                }
            });

            collapsedYears.value = yearsToCollapse;
        } else {
            error.value = 'Failed to fetch board minutes';
        }

        // Fetch board members
        const membersResponse = await fetch('/api/collections/board_member');
        if (membersResponse.ok) {
            const membersData = await membersResponse.json();
            // Sort by order field if available, otherwise by name
            boardMembers.value = (membersData.items || []).sort((a, b) => {
                if (a.order && b.order) {
                    return a.order - b.order;
                }
                return (a.name || '').localeCompare(b.name || '');
            });
        } else {
            membersError.value = 'Failed to fetch board members';
        }
    } catch (err) {
        error.value = 'Error fetching board minutes: ' + err.message;
        membersError.value = 'Error fetching board members: ' + err.message;
    } finally {
        loading.value = false;
        membersLoading.value = false;
    }
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Group minutes by year
const minutesByYear = computed(() => {
    const grouped = {};
    minutes.value.forEach(minute => {
        if (minute.date_of_meeting) {
            const year = new Date(minute.date_of_meeting).getFullYear();
            if (!grouped[year]) {
                grouped[year] = [];
            }
            grouped[year].push(minute);
        }
    });

    // Sort years newest to oldest and return as array
    return Object.keys(grouped)
        .sort((a, b) => parseInt(b) - parseInt(a))
        .map(year => ({
            year,
            minutes: grouped[year]
        }));
});

const toggleYear = (year) => {
    if (collapsedYears.value.has(year)) {
        collapsedYears.value.delete(year);
    } else {
        collapsedYears.value.add(year);
    }
};

const isYearCollapsed = (year) => {
    return collapsedYears.value.has(year);
};

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black";
</script>

<template>
    <Head title="Board" />

    <PublicLayout
        title="Board"
        :show-hero="true"
        hero-title="Board of Directors"
        hero-subtitle="Leadership and Governance"
        hero-background="/images/north_park_hole_1.jpg">

        <div class="bg-gray-100 dark:bg-zinc-900 basket-tile">
            <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="grid grid-cols-1 gap-6">

                            <!-- About the Board Section -->
                            <div :class="cardStyle" class="bg-white">
                                <div class="box-page">
                                    <div class="card-header">
                                        <span class="card-header-icon">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </span>
                                        <h1 class="card-header-text">About the Board</h1>
                                    </div>

                                    <div class="space-y-4">
                                        <p>
                                            The Mineral Springs Disc Golf Club is governed by a dedicated Board of Directors who volunteer their time to ensure the club operates effectively and serves the disc golf community.
                                        </p>

                                        <p>
                                            Our board meets regularly to discuss club operations, course maintenance, events, and strategic planning. As a public benefit non-profit association, we are committed to transparency and community involvement.
                                        </p>

                                        <p>
                                            All board meetings are open to club members, and we encourage community participation in our decision-making process. Meeting minutes are published below to keep our community informed about club activities and decisions.
                                        </p>

                                        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                                            <h3 class="font-bold text-blue-800 mb-2">Want to Get Involved?</h3>
                                            <p class="text-blue-700">
                                                We welcome volunteers and are always looking for members who want to contribute to the club's mission.
                                                If you're interested in joining the board or volunteering, please reach out to us through our contact page.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Board Members Section -->
                            <div :class="cardStyle" class="bg-white">
                                <div class="box-page w-full">
                                    <div class="card-header">
                                        <span class="card-header-icon">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11V7a5 5 0 0110 0v4m-5 0v6m0 0h4a2 2 0 002-2v-4a2 2 0 00-2-2h-4m0 6H7a2 2 0 01-2-2v-4a2 2 0 012-2h4" />
                                            </svg>
                                        </span>
                                        <h1 class="card-header-text">Current Board Members</h1>
                                    </div>

                                    <div v-if="membersLoading" class="flex justify-center py-8">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                                    </div>

                                    <div v-else-if="membersError" class="text-red-500 text-center py-8">
                                        {{ membersError }}
                                    </div>

                                    <div v-else-if="boardMembers.length === 0" class="text-gray-500 text-center py-8">
                                        No board members information available yet.
                                    </div>

                                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-xl">
                                        <div
                                            v-for="member in boardMembers"
                                            :key="member.id"
                                            class="p-4 hover:bg-gray-50 transition-colors"
                                        >
                                            <div class="flex items-start space-x-4">
                                                <!-- Member Information -->
                                                <div class="flex-1">
                                                    <h3 class="text-md font-semibold text-gray-800">
                                                        {{ member.title }}
                                                    </h3>

                                                    <div v-if="member.club_title" class="text-gray-600 mb-2">
                                                        {{ member.club_title }}
                                                    </div>

                                                    <div v-if="member.roles">
                                                        <div class="flex flex-wrap gap-2">
                                                            <span
                                                                v-for="role in member.roles"
                                                                :key="role"
                                                                class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold"
                                                            >
                                                                {{ role }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Board Minutes Section -->
                            <div :class="cardStyle" class="bg-white">
                                <div class="box-page w-full">
                                    <div class="card-header">
                                        <span class="card-header-icon">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </span>
                                        <h1 class="card-header-text">Board Meeting Minutes</h1>
                                    </div>

                                    <div v-if="loading" class="flex justify-center py-8">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                                    </div>

                                    <div v-else-if="error" class="text-red-500 text-center py-8">
                                        {{ error }}
                                    </div>

                                    <div v-else-if="minutesByYear.length === 0" class="text-gray-500 text-center py-8">
                                        No board meeting minutes available yet.
                                    </div>

                                    <div v-else class="space-y-6">
                                        <!-- Year Sections -->
                                        <div
                                            v-for="yearGroup in minutesByYear"
                                            :key="yearGroup.year"
                                            class="border border-gray-200 rounded-lg overflow-hidden"
                                        >
                                            <!-- Year Header -->
                                            <button
                                                @click="toggleYear(yearGroup.year)"
                                                class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between"
                                            >
                                                <h2 class="text-xl font-semibold text-gray-800">
                                                    {{ yearGroup.year }} ({{ yearGroup.minutes.length }} meeting{{ yearGroup.minutes.length === 1 ? '' : 's' }})
                                                </h2>
                                                <svg
                                                    class="w-5 h-5 transform transition-transform duration-200"
                                                    :class="{ 'rotate-180': !isYearCollapsed(yearGroup.year) }"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>

                                            <!-- Year Content -->
                                            <div
                                                v-show="!isYearCollapsed(yearGroup.year)"
                                                class="border-t border-gray-200"
                                            >
                                                <div class="p-4 space-y-4">
                                                    <div
                                                        v-for="minute in yearGroup.minutes"
                                                        :key="minute.id"
                                                        class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
                                                    >
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                                                    {{ minute.title }}
                                                                </h3>

                                                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                                                    <div v-if="minute.date_of_meeting" class="flex items-center">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        <span>{{ formatDate(minute.date_of_meeting) }}</span>
                                                                    </div>

                                                                    <div v-if="minute.present" class="flex items-center">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                                        </svg>
                                                                        <span>{{ minute.present.split('\n').length }} attendees</span>
                                                                    </div>
                                                                </div>

                                                                <!-- <div v-if="minute.content" class="text-gray-700 text-sm line-clamp-3">
                                                                    {{ minute.content.substring(0, 200) }}{{ minute.content.length > 200 ? '...' : '' }}
                                                                </div> -->
                                                            </div>

                                                            <div class="ml-4 flex-shrink-0">
                                                                <Link
                                                                    :href="route('board.minutes.show', minute.id)"
                                                                    class="btn-primary bangers text-3xl"
                                                                >
                                                                    View Minutes
                                                                </Link>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </main>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
