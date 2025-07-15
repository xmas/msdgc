<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, onMounted, nextTick } from 'vue';

const props = defineProps({
    minuteId: {
        type: String,
        required: true,
    },
});

const minute = ref(null);
const loading = ref(true);
const error = ref(null);
const tableOfContents = ref([]);

const generateTableOfContents = () => {
    if (!minute.value?.content) return;

    nextTick(() => {
        const container = document.getElementById('meeting-minutes');
        if (!container) return;

        const headers = container.querySelectorAll('h1, h2, h3, h4, h5, h6');
        const toc = [];

        headers.forEach((header, index) => {
            const id = `header-${index}`;
            header.id = id;

            toc.push({
                id,
                text: header.textContent.trim(),
                level: parseInt(header.tagName.charAt(1)),
                tagName: header.tagName.toLowerCase()
            });
        });

        tableOfContents.value = toc;
    });
};

const scrollToHeader = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

onMounted(async () => {
    try {
        const response = await fetch(`/api/collections/board_minutes/${props.minuteId}`);
        if (response.ok) {
            const data = await response.json();
            minute.value = data.item || null;
            generateTableOfContents();
        } else {
            error.value = 'Failed to fetch meeting minutes';
        }
    } catch (err) {
        error.value = 'Error fetching meeting minutes: ' + err.message;
    } finally {
        loading.value = false;
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

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black my-4";
</script>

<template>
    <Head :title="minute?.title || 'Board Meeting Minutes'" />

    <PublicLayout
        :title="minute?.title || 'Board Meeting Minutes'"
        :show-hero="true"
        :hero-title="minute?.title || 'Board Meeting Minutes'"
        hero-subtitle="Mineral Springs Disc Golf Club"
        hero-background="/images/north_park_hole_1.jpg">

        <div class="bg-gray-100 dark:bg-zinc-900 basket-tile">
            <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="grid grid-cols-1 gap-6">

                            <!-- Navigation -->
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <Link :href="route('board')" class="hover:text-blue-600 transition-colors btn-primary bangers text-3xl">
                                    ← Back to Board
                                </Link>
                            </div>

                            <!-- Loading State -->
                            <div v-if="loading" :class="cardStyle" class="bg-white">
                                <div class="flex justify-center py-8 w-full">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                                </div>
                            </div>

                            <!-- Error State -->
                            <div v-else-if="error" :class="cardStyle" class="bg-red-50 border-l-4 border-red-400">
                                <div class="text-red-700 text-center py-8">
                                    {{ error }}
                                </div>
                            </div>

                            <!-- Not Found State -->
                            <div v-else-if="!minute" :class="cardStyle" class="bg-yellow-50 border-l-4 border-yellow-400">
                                <div class="text-yellow-700 text-center py-8">
                                    Meeting minutes not found.
                                </div>
                            </div>

                            <!-- Meeting Minutes Content -->
                            <div v-else>

                                <!-- Meeting Header -->
                                <div :class="cardStyle" class="bg-white">
                                    <div class="w-full">
                                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ minute.title }}</h1>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <!-- Meeting Date -->
                                            <div v-if="minute.date_of_meeting" class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-700">
                                                    <strong>Meeting Date:</strong> {{ formatDate(minute.date_of_meeting) }}
                                                </span>
                                            </div>

                                            <!-- Next Meeting Date -->
                                            <div v-if="minute.date_of_next_meeting" class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-700">
                                                    <strong>Next Meeting:</strong> {{ formatDate(minute.date_of_next_meeting) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attendees -->
                                <div v-if="minute.present" :class="cardStyle" class="bg-blue-50">
                                    <div class="w-full">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Present at Meeting
                                        </h2>

                                        <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-200">
                                            <div class="prose max-w-none" v-html="minute.present"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Report -->
                                <div v-if="minute.financial_report" :class="cardStyle" class="bg-green-50">
                                    <div class="w-full">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            Financial Report
                                        </h2>

                                        <div class="bg-white p-4 rounded-lg shadow-sm border border-green-200">
                                            <div class="prose max-w-none" v-html="minute.financial_report"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table of Contents -->
                                <div v-if="minute.content && tableOfContents.length > 0" :class="cardStyle" class="bg-slate-50">
                                    <div class="w-full">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                            </svg>
                                            Table of Contents
                                        </h2>

                                        <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200">
                                            <nav class="toc">
                                                <ul class="space-y-1">
                                                    <li v-for="item in tableOfContents" :key="item.id"
                                                        :class="['toc-item', `toc-${item.tagName}`]">
                                                        <a @click="scrollToHeader(item.id)"
                                                           class="toc-link cursor-pointer hover:text-blue-600 transition-colors"
                                                           :title="item.text">
                                                            {{ item.text }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Content -->
                                <div v-if="minute.content" :class="cardStyle" class="bg-white">
                                    <div class="w-full">
                                        <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Meeting Minutes
                                        </h2>

                                        <div class="prose max-w-none">
                                            <div class="whitespace-pre-wrap text-gray-700 leading-relaxed meeting-content" id="meeting-minutes" v-html="minute.content"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Fields -->
                                <div v-if="minute.old_business || minute.new_business || minute.announcements" :class="cardStyle" class="bg-gray-50">
                                    <div class="w-full space-y-6">
                                        <div v-if="minute.old_business">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2">Old Business</h3>
                                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                <div class="prose max-w-none" v-html="minute.old_business"></div>
                                            </div>
                                        </div>

                                        <div v-if="minute.new_business">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2">New Business</h3>
                                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                <div class="prose max-w-none" v-html="minute.new_business"></div>
                                            </div>
                                        </div>

                                        <div v-if="minute.announcements">
                                            <h3 class="text-xl font-bold text-gray-800 mb-2">Announcements</h3>
                                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                <div class="prose max-w-none" v-html="minute.announcements"></div>
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
.prose {
    color: #374151;
    max-width: none;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #1f2937;
    font-weight: 600;
}

.prose p {
    margin-bottom: 1rem;
}

.prose ul, .prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6b7280;
}

.prose strong {
    font-weight: 600;
}

.prose em {
    font-style: italic;
}

/* Styles for rendered HTML content in meeting minutes */
.meeting-content :deep(h1) {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.2;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

.meeting-content :deep(h2) {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.meeting-content :deep(h3) {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.meeting-content :deep(h4) {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.meeting-content :deep(h5) {
    font-size: 1.125rem;
    font-weight: 600;
    color: #4b5563;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.meeting-content :deep(h6) {
    font-size: 1rem;
    font-weight: 600;
    color: #4b5563;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.meeting-content :deep(p) {
    margin-bottom: 0.1rem;
    line-height: 1;
}

.meeting-content :deep(ul), .meeting-content :deep(ol) {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.meeting-content :deep(li) {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.meeting-content :deep(blockquote) {
    border-left: 4px solid #3b82f6;
    background-color: #f8fafc;
    padding: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #475569;
    border-radius: 0.375rem;
}

.meeting-content :deep(strong) {
    font-weight: 600;
    color: #1f2937;
}

.meeting-content :deep(em) {
    font-style: italic;
}

.meeting-content :deep(code) {
    background-color: #f1f5f9;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 0.875rem;
    color: #dc2626;
}

.meeting-content :deep(pre) {
    background-color: #f8fafc;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1rem 0;
    border: 1px solid #e2e8f0;
}

.meeting-content :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.meeting-content :deep(th), .meeting-content :deep(td) {
    border: 1px solid #e2e8f0;
    padding: 0.5rem;
    text-align: left;
}

.meeting-content :deep(th) {
    background-color: #f8fafc;
    font-weight: 600;
}

.meeting-content :deep(hr) {
    border: none;
    border-top: 2px solid #e5e7eb;
    margin: 2rem 0;
}

/* Table of Contents Styles */
.toc {
    font-size: 0.875rem;
}

.toc-item {
    position: relative;
}

.toc-link {
    display: block;
    padding: 0.25rem 0;
    color: #4b5563;
    text-decoration: none;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.toc-link:hover {
    color: #2563eb;
    background-color: #f1f5f9;
    padding-left: 0.5rem;
}

/* Indentation for different heading levels */
.toc-h1 {
    margin-left: 0;
    font-weight: 600;
}

.toc-h2 {
    margin-left: 1rem;
    font-weight: 500;
}

.toc-h3 {
    margin-left: 2rem;
    font-weight: 500;
}

.toc-h4 {
    margin-left: 3rem;
    font-weight: 400;
}

.toc-h5 {
    margin-left: 4rem;
    font-weight: 400;
}

.toc-h6 {
    margin-left: 5rem;
    font-weight: 400;
}

/* Scroll margin for headers to account for any fixed elements */
.meeting-content :deep(h1),
.meeting-content :deep(h2),
.meeting-content :deep(h3),
.meeting-content :deep(h4),
.meeting-content :deep(h5),
.meeting-content :deep(h6) {
    scroll-margin-top: 2rem;
}
</style>
