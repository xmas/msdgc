<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Course from './Sections/Course.vue';
import LostFound from './Sections/LostFound.vue';
import Membership from './Sections/Membership.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    appDebug: {
        type: Boolean,
        default: false,
    },
});

import { ref, onMounted } from 'vue';

const sponsors = ref([]);
const minutes = ref([]);

const non_pro_shop_sponsors = ref([]);


onMounted(async () => {
    try {
        const response = await fetch('/api/collections/sponsors');
        if (response.ok) {
            sponsors.value = await response.json();
            //   remove all blueprint = "type"
            sponsors.value.items = sponsors.value.items.filter(sponsor => sponsor.blueprint !== "type");

            // Filter out non-pro shop sponsors
            non_pro_shop_sponsors.value = sponsors.value.items.filter(sponsor => !sponsor.is_pro_shop);
            // remove the non pro shop sponsors from the main list
            sponsors.value.items = sponsors.value.items.filter(sponsor => sponsor.is_pro_shop);
            // Sort sponsors by title
        } else {
            console.error('Failed to fetch sponsors');
        }
    } catch (error) {
        console.error('Error fetching sponsors:', error);
    }
    try {
        const response = await fetch('/api/collections/board_minutes');
        if (response.ok) {
            minutes.value = await response.json();
        } else {
            console.error('Failed to fetch board minutes');
        }
    } catch (error) {
        console.error('Error fetching board minutes:', error);
    }


});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"

</script>

<style scoped>
.masked-element-image {
    width: 200px;
    height: 400px;
    background: linear-gradient(90deg, #3F8E28, #A5A5A6, #3477F4);
    background-size: 300% 100%;
    animation: colorSlide 4s ease-in-out infinite;

    /* Replace 'your-image.png' with your actual white image */
    mask: url('images/needle.png') no-repeat center;
    mask-size: contain;
    -webkit-mask: url('images/needle.png') no-repeat center;
    -webkit-mask-size: contain;
}
</style>

<template>
    <PublicLayout title="Welcome" :can-login="canLogin" :can-register="canRegister" :show-hero="true"
        :app-debug="appDebug" hero-title="Mineral Springs Disc Golf Club"
        hero-subtitle="A Public Benefit Non-Profit Association" hero-background="/images/north_park_hole_1.jpg">

        <div class="bg-gray-100 dark:bg-zinc-900 basket-tile">
            <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full px-6 lg:max-w-7xl">
                    <main class="mt-6">
                        <div class="grid grid-cols-1 gap-6">

                            <!-- Monday Dubs Card - Full Width -->
                            <div :class="cardStyle" class="bg-zinc-800 text-white col-span-full">
                                <p class="uppercase font-bold text-6xl bangers">Monday Dubs!!</p>
                                <p>6pm in the summer, earlier as it gets darker. If you're there by 5 you're probably
                                    safe.
                                    Check the Facebook group for weekly updates.</p>

                                <p class="uppercase font-bold text-6xl bangers text-red-300">IMPORTANT</p>
                                <p class="text-red-100 font-bold"> This means the course is <span
                                        class="font-bold text-pink-400">VERY BUSY on Monday evenings</span>.
                                    All are welcome, but be prepared for a fun time.</p>
                            </div>

                            <!-- Sponsors Section - 2 Column Layout -->
                            <div v-if="sponsors.items && sponsors.items.length > 0" class="col-span-full">
                                <h2 class="text-4xl font-bold text-center mb-6 bangers text-gray-800">Disc golf Sponsors
                                </h2>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div v-for="sponsor in sponsors.items" :key="sponsor.id" :class="cardStyle"
                                        class="bg-white text-black">
                                        <div class="flex flex-col md:flex-row gap-4 items-start">
                                            <!-- Logo on the left -->
                                            <div class="flex-shrink-0 w-24 h-24 md:w-32 md:h-32">
                                                <img v-if="sponsor.logo && sponsor.logo[0]" :src="sponsor.logo[0]"
                                                    :alt="sponsor.title + ' logo'"
                                                    class="w-full h-full object-contain rounded-lg" />
                                                <div v-else
                                                    class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <span class="text-gray-500 text-sm">No Logo</span>
                                                </div>
                                            </div>

                                            <!-- Content on the right -->
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-2xl font-bold text-zinc-800">{{
                                                    sponsor.title }}</h3>

                                                <div class="space-y-2 text-sm">
                                                    <!-- City and North/South indicator -->
                                                    <p v-if="sponsor.city_state" class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-zinc-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-zinc-500">{{ sponsor.city_state }} - {{ sponsor.north_of_msdgc ?
                                                            'North' : 'South' }} of Mineral Springs</span>
                                                    </p>

                                                    <!-- Nearby description -->
                                                    <p v-if="sponsor.nearby" class="text-gray-800 italic">{{ sponsor.nearby }}
                                                    </p>

                                                    <!-- Links -->
                                                    <div class="flex flex-wrap gap-2 mt-3">
                                                        <a v-if="sponsor.web_site" :href="sponsor.web_site"
                                                            target="_blank" rel="noopener"
                                                            class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                                </path>
                                                            </svg>
                                                            Website
                                                        </a>

                                                        <a v-if="sponsor.udisc_link" :href="sponsor.udisc_link"
                                                            target="_blank" rel="noopener"
                                                            class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            UDisc
                                                        </a>

                                                        <a v-if="sponsor.driving_directions"
                                                            :href="sponsor.driving_directions" target="_blank"
                                                            rel="noopener"
                                                            class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                                                </path>
                                                            </svg>
                                                            Directions
                                                        </a>

                                                        <span v-if="sponsor.is_pro_shop"
                                                            class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                                                </path>
                                                            </svg>
                                                            Pro Shop
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <section id="local_sponsors" :class="cardStyle" class="bg-white">
                                <div v-if="non_pro_shop_sponsors.length > 0" class="w-full">
                                    <h2 class="text-4xl font-bold text-center mb-6 bangers text-gray-700">Local Sponsors</h2>
                                    <div class="flex flex-wrap justify-center items-stretch gap-4 md:gap-6 w-full">
                                        <div v-for="sponsor in non_pro_shop_sponsors" :key="sponsor.id"
                                            class="flex flex-col items-center justify-center text-center w-36 md:w-44 flex-shrink-0">
                                            <a :href="sponsor.web_site || '#'"
                                               :target="sponsor.web_site ? '_blank' : '_self'"
                                               :rel="sponsor.web_site ? 'noopener' : ''"
                                               class="hover:opacity-80 transition-opacity flex flex-col items-center justify-center text-center w-full group">
                                                <!-- Logo container with consistent styling -->
                                                <div class="w-36 h-36 md:w-44 md:h-44 mb-3 flex items-center justify-center p-4 rounded-lg transition-transform group-hover:scale-105"
                                                     :style="sponsor.logo_background ? { background: sponsor.logo_background } : {}">
                                                    <img v-if="sponsor.logo && sponsor.logo[0]"
                                                         :src="sponsor.logo[0]"
                                                         :alt="sponsor.title + ' logo'"
                                                         class="w-full h-full object-contain rounded" />
                                                    <div v-else class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-500 text-xs">No Logo</span>
                                                    </div>
                                                </div>
                                                <!-- Title with consistent height for 2 lines -->
                                                <h3 class=" text-zinc-800 text-center w-full break-words leading-tight h-12 md:h-14 flex items-center justify-center">
                                                    {{ sponsor.title }}
                                                </h3>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="course" :class="cardStyle" class="bg-white">
                                <Course />
                            </section>

                            <section id="calendar" :class="cardStyle" class="bg-blue-200 squatch-tile">
                                <div class="box-page">
                                    <div class="card-header">
                                        <span class="card-header-icon">
                                            <img src="/images/calendar.svg" />
                                        </span>
                                        <h1 class="card-header-text">Calendar</h1>
                                    </div>

                                    <p>Download our Calendar so you can be notified of events without needing Facebook!
                                        We maintain the calendar to give notifications (both email and push) 1 hour
                                        before
                                        start times, which can be edited to fit your preference.
                                    </p>
                                    <button class="btn-primary mt-8">
                                        Check out the calendar!
                                    </button>
                                </div>
                            </section>

                            <div id="lost-found" :class="cardStyle" class="bg-amber-300 squatch-tile">
                                <LostFound />
                            </div>

                            <div id="membership" :class="cardStyle" class="bg-lime-300 squatch-tile">
                                <Membership />
                            </div>

                        </div>
                    </main>


                </div>
            </div>
        </div>
    </PublicLayout>
</template>
