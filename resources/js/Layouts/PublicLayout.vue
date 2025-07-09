<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'MSDGC'
    },
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    },
    showHero: {
        type: Boolean,
        default: false
    },
    heroTitle: {
        type: String,
        default: 'Mineral Springs Disc Golf Club'
    },
    heroSubtitle: {
        type: String,
        default: 'A Public Benefit Non-Profit Association'
    },
    heroBackground: {
        type: String,
        default: '/images/north_park_hole_1.jpg'
    },
    showNavigation: {
        type: Boolean,
        default: true
    },
    appDebug: {
        type: String,
        default: false
    },
});

const navStyle = ""
const headerStyle = "flex items-center justify-center"
</script>

<template>
    <div>

        <Head :title="title" />

        <!-- Public Header Navigation -->
        <header
            :class="['flex justify-between items-center text-white pr-4 px-4 py-5 lg:py-2 font-bold']"
            :style="appDebug
                ? 'background: repeating-linear-gradient(135deg, rgb(43 127 255)  0 20px, rgb(105 162 249) 20px 40px);'
                : 'background-color: #3477F4;'"
        >
            <Link href="/">
            <img src="/images/msdgc_logo.png" alt="MSDGC Logo" class="h-12 w-auto pr-4 self-start sm:self-center" />
            </Link>

            <nav v-if="canLogin" class="mx-3 flex justify-end">
                <a href="mailto:info@mineralspringsdgc.com"
                    class="flex items-center bg-white inset text-blue-500 rounded-sm px-3 py-1 mr-8 bangers text-xl">Contact
                    Us</a>

                <Link v-if="$page.props.auth?.user" :href="route('dashboard')" :class="navStyle">
                Member Area
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

        <!-- Hero Section (conditional) -->
        <div v-if="showHero" class="bg-gray-100 dark:bg-zinc-900 basket-tile">
            <header :class="headerStyle"
                class="pb-6 lg:pb-0 relative py-40 lg:py-20 flex flex-col lg:flex-row items-center justify-between text-white px-6 lg:px-12"
                :style="`background: url('${heroBackground}') center center / cover no-repeat; opacity: 0.9;`">

                <div class="flex flex-row items-center justify-between py-10 px-5 lg:px-20 text-shadow-lg "
                    style="background: linear-gradient(to bottom, rgba(0,0,0,0.9) 0px, rgba(0,0,0,0));">
                    <img src="/images/msdgc_logo.png" alt="Needle"
                        class="hidden lg:flex max-h-45 w-auto relative mr-8 " />

                    <div>
                        <div class="club-name text-6xl font-bold text-white uppercase text-center">
                            {{ heroTitle }}
                        </div>
                        <div class="text-xl text-white/90 italic font-bold lg:font-normal text-center">
                            {{ heroSubtitle }}
                        </div>
                    </div>
                    <img src="/images/squatch_disc.png" alt="Squatch Disc" class="hidden lg:flex max-h-45 w-auto" />
                </div>
            </header>

            <header v-if="showNavigation" :class="headerStyle" class="pt-6 text-white pr-4 px-4 py-5 lg:py-2 font-bold"
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
        </div>

        <!-- Page Content -->
        <main>
            <slot />

        </main>

        <footer class="py-16 text-sm text-white bg-sky-800">
            <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold mb-3">About</h3>
                    <p>
                        Mineral Springs Disc Golf Club is a public benefit non-profit association dedicated to promoting disc golf and community engagement.
                    </p>
                </div>
                <div>
                    <h3 class="font-bold mb-3">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#course" class="hover:underline">Course Map</a></li>
                        <li><a href="https://calendar.google.com/calendar/u/0/embed?src=495b1ab477f0effe46de4b97a8ae37ab1229210525dba1f4b8a558828ae8387f@group.calendar.google.com&ctz=America/Los_Angeles" target="_blank" rel="noopener" class="hover:underline">Calendar</a></li>
                        <li><a href="#lost-found" class="hover:underline">Lost and Found</a></li>
                        <li><a href="#membership" class="hover:underline">Club Membership</a></li>
                        <li><Link :href="route('contact')" class="hover:underline">Get Involved</Link></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-3">Contact</h3>
                    <p>Email: <a href="mailto:info@mineralspringsdgc.com" class="hover:underline">info@mineralspringsdgc.com</a></p>
                    <p class="mt-2">&copy; {{ new Date().getFullYear() }} Mineral Springs Disc Golf Club. All rights reserved.</p>
                </div>
            </div>
        </footer>
        </div>
</template>
