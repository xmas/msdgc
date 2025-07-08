<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Course from './Sections/Course.vue';
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

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"

const headerStyle = "flex items-center justify-center"

</script>

<template>

    <Head title="Welcome" />
    <div class="bg-gray-100 dark:bg-zinc-900">

        <header class="flex justify-between items-center text-white pr-4 px-4 py-5 lg:py-2 font-bold"
            style="background-color: #3477F4;">
            <img src="/images/msdgc_logo.png" alt="MSDGC Logo" class="h-12 w-auto pr-4 self-start sm:self-center" />
            <nav v-if="canLogin" class="mx-3 flex justify-end">
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
                <img src="/images/msdgc_logo.png" alt="Needle" class="hidden lg:flex max-h-45 w-auto" />

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
                <a href="#calendar" :class="navStyle">Calendar</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="#lost-found" class="hidden lg:flex" :class="navStyle">Lost and Found</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="#club-membership" class="hidden lg:flex" :class="navStyle">Club Membership</a>
                <span class="hidden lg:flex text-white/70">|</span>
                <a href="#get-involved" class="hidden lg:flex" :class="navStyle">Get Involved</a>
            </nav>
        </header>


        <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">

            <div class="relative w-full px-6 lg:max-w-7xl py-10">

                <main class="mt-6">
                    <div class="grid gap-6">

                        <div :class="cardStyle" class="bg-zinc-800 text-white">
                            <p class="uppercase font-bold text-3xl">Monday Dubs!!</p>
                            <p>6pm in the summer, earlier as it gets darker. If you're there by 5 you're probably safe.
                                Check
                                the Facebook group for weekly updates.</p>

                            <p class="uppercase font-bold text-3xl">IMPORTANT:</p>
                            <p> This means the course is <span class="font-bold">VERY BUSY</span> on Monday evenings.
                                All are
                                welcome, but be prepared for a fun time.</p>
                        </div>


                        <section id="course" :class="cardStyle">
                            <Course />
                        </section>

                    </div>
                </main>

                <footer class="py-16 text-sm text-black dark:text-white/70">
                </footer>
            </div>
        </div>
    </div>
</template>
