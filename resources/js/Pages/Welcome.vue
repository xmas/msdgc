<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Course from './Sections/Course.vue';
import LostFound from './Sections/LostFound.vue';
import Membership from './Sections/Membership.vue';
import DiscGolfSponsors from './Sections/DiscGolfSponsors.vue';
import LocalSponsors from './Sections/LocalSponsors.vue';

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
                            <div class="grid grid-cols-3 gap-6">
                                <!-- Monday Dubs Card - Full Width -->
                                <div :class="cardStyle" class="bg-zinc-800 text-white col-span-1">
                                    <p class="uppercase font-bold text-6xl bangers">Monday Dubs!!</p>
                                    <p>6pm in the summer, earlier as it gets darker. If you're there by 5 you're
                                        probably
                                        safe.
                                        Check the Facebook group for weekly updates.</p>

                                    <p class="uppercase font-bold text-6xl bangers text-red-300">IMPORTANT</p>
                                    <p class="text-red-100 font-bold"> This means the course is <span
                                            class="font-bold text-pink-400">VERY BUSY on Monday evenings</span>.
                                        All are welcome, but be prepared for a fun time.</p>
                                </div>

                                <!-- Sponsors Section - 2 Column Layout -->
                                <DiscGolfSponsors class="col-span-2" :sponsors="sponsors.items || []" />
                            </div>

                            <section id="course" :class="cardStyle" class="bg-white">
                                <Course />
                            </section>

                            <LocalSponsors :sponsors="non_pro_shop_sponsors" />


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
