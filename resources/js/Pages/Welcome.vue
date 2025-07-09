<script setup>
import { Head, Link } from '@inertiajs/vue3';
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
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const navStyle = ""
const ms_blue = '3477F4';
const ms_grey = 'A5A5A6';
const ms_green = '3F8E28';

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"

const headerStyle = "flex items-center justify-center"

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

    <Head title="Welcome" />
    <div class="bg-gray-100 dark:bg-zinc-900 basket-tile">
        <header class="flex justify-between items-center text-white pr-4 px-4 py-5 lg:py-2 font-bold"
            style="background-color: #3477F4;">
            <img src="/images/msdgc_logo.png" alt="MSDGC Logo" class="h-12 w-auto pr-4 self-start sm:self-center" />


            <nav v-if="canLogin" class="mx-3 flex justify-end">
                <a href="mailto:info@mineralspringsdgc.com" class="flex items-center bg-white inset text-blue-500 rounded-sm px-3 py-1 mr-8 bangers text-xl">Contact Us</a>


                <Link v-if="$page.props.auth.user" :href="route('dashboard')" :class="navStyle">
                Dashboard
                </Link>

                <template v-else>
                    <Link :href="route('login')" :class="navStyle">
                    Log in
                    </Link>
                    <div class="mx-4">|</div>
                    <Link v-if="canRegister" :href="route('register')" :class="navStyle">
                    Register
                    </Link>
                </template>
            </nav>
        </header>

        <header :class="headerStyle"
            class="pb-6 lg:pb-0 relative py-40 lg:py-20 flex flex-col lg:flex-row items-center justify-between text-white px-6 lg:px-12"
            style="background: url('/images/north_park_hole_1.jpg') center center / cover no-repeat; opacity: 0.9;">


            <div class="flex flex-row items-center justify-between py-10 px-5 lg:px-20 text-shadow-lg "
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.9) 0px, rgba(0,0,0,0));">
                <img src="/images/msdgc_logo.png" alt="Needle" class="hidden lg:flex max-h-45 w-auto relative mr-8 " />

                <div>
                    <div class="club-name text-6xl font-bold text-white uppercase text-center">
                        Mineral Springs Disc Golf Club
                    </div>
                    <div class="text-xl text-white/90 italic font-bold lg:font-normal text-center">
                        A Public Benefit Non-Profit Association
                    </div>
                </div>
                <img src="/images/squatch_disc.png" alt="Squatch Disc" class="hidden lg:flex max-h-45 w-auto" />
            </div>

        </header>

        <header :class="headerStyle" class="pt-6 text-white pr-4 px-4 py-5 lg:py-2 font-bold"
            style="background-color: #3F8E28;">
            <nav class="flex flex-wrap gap-4 lg:col-span-1 text-white justify-between items-center ">
                <a href="#course" class="hidden lg:flex" :class="navStyle">Course Map</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="https://calendar.google.com/calendar/u/0/embed?src=495b1ab477f0effe46de4b97a8ae37ab1229210525dba1f4b8a558828ae8387f@group.calendar.google.com&ctz=America/Los_Angeles"
                    :class="navStyle" target="_blank" rel="noopener">Calendar</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="#lost-found" class="hidden lg:flex" :class="navStyle">Lost and Found</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="#membership" class="hidden lg:flex" :class="navStyle">Club Membership</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <Link :href="route('contact')" class="hidden lg:flex" :class="navStyle">Get Involved</Link>
            </nav>
        </header>


        <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">

            <div class="relative w-full px-6 lg:max-w-7xl py-10">

                <main class="mt-6">
                    <div class="grid gap-6">

                        <div :class="cardStyle" class="bg-zinc-800 text-white">
                            <p class="uppercase font-bold text-6xl bangers">Monday Dubs!!</p>
                            <p>6pm in the summer, earlier as it gets darker. If you're there by 5 you're probably safe.
                                Check
                                the Facebook group for weekly updates.</p>

                            <p class="uppercase font-bold text-6xl bangers text-red-300">IMPORTANT</p>
                            <p class="text-red-100 font-bold"> This means the course is <span class="font-bold text-pink-400">VERY BUSY on Monday evenings</span>.
                                All are
                                welcome, but be prepared for a fun time.</p>
                        </div>


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
                                    We maintain the calendar to give notifications (both email and push) 1 hour before
                                    start
                                    times,
                                    which can be edited to fit your preference.
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

                <footer class="py-16 text-sm text-black dark:text-white/70">
                    &copy; {{ new Date().getFullYear() }} Mineral Springs Disc Golf Club. All rights reserved.
                </footer>
            </div>
        </div>
    </div>
</template>
