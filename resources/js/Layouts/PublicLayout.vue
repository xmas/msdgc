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
        type: Boolean,
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
        <!-- Sponsors Header Bar -->
        <header v-if="false" class="w-full bg-zinc-800 py-2 px-4 flex items-center justify-center">
            <div class="flex flex-wrap items-center gap-6 justify-center w-full max-w-6xl">
                <span class="font-semibold text-white mr-4">Proudly sponsored by:</span>
                <a href="https://flexlinediscs.com" target="_blank" rel="noopener" class="flex items-center bg-white rounded-sm px-3 py-1">
                    <img src="https://flexlinediscs.com/cdn/shop/files/Logo_Design.svg?v=1741434274&width=480" class="h-8 w-auto" />
                </a>
                <a href="https://www.discmania.net/" target="_blank" rel="noopener" class="flex items-center">
                    <img src="https://discgolfresort.com/wp-content/uploads/2021/12/cropped-trans_background_kp-white-logo.png" alt="Discmania" class="h-8 w-auto" />
                </a>
                <a href="https://www.pdga.com/" target="_blank" rel="noopener" class="flex items-center">
                    <img src="https://f7124e02755f4e326e33.cdn6.editmysite.com/uploads/b/f7124e02755f4e326e3354e3b13f8f12161564fccaaddc748c2b11a5871081d0/MandosFlameLogo_1701889526.png?width=2400&optimize=medium" alt="PDGA" class="h-8 w-auto" />
                </a>
                <a href="https://www.pdga.com/" target="_blank" rel="noopener" class="flex items-center">
                    <img src="data:image/jpeg;base64,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" alt="PDGA" class="h-8 w-auto" />
                </a>



                <!-- Add more sponsors as needed -->
            </div>
        </header>

        <!-- Hero Section (conditional) -->
        <div v-if="showHero" class="bg-gray-100 dark:bg-zinc-900 ">
            <header :class="headerStyle"
                class="relative flex flex-col lg:flex-row items-center justify-between text-white"
                :style="`background: url('${heroBackground}') center center / cover no-repeat; opacity: 0.9;`">

                <div class="py-10 flex flex-row items-center justify-between text-shadow-lg m-0"
                    style="background: linear-gradient(to bottom, rgba(0,0,0,0.9) 0px, rgba(0,0,0,0));">
                    <img src="/images/msdgc_logo.png" alt="Needle"
                        class="hidden lg:flex max-h-30 w-auto relative mr-8 " />

                    <div>
                        <div class="club-name text-6xl font-bold text-white uppercase text-center">
                            {{ heroTitle }}
                        </div>
                        <div class="text-xl text-white/90 italic font-bold lg:font-normal text-center">
                            {{ heroSubtitle }}
                        </div>
                    </div>
                    <img src="/images/squatch_disc.png" alt="Squatch Disc" class="hidden lg:flex max-h-30 w-auto" />
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
                    <Link :href="route('board')" class="hidden lg:flex" :class="navStyle">Board</Link>
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
                        <li><Link :href="route('board')" class="hover:underline">Board</Link></li>
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
