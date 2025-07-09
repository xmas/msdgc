<script setup>
import { Head, Link } from '@inertiajs/vue3';

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"
const headerStyle = "flex items-center justify-center"
const navStyle = ""
</script>

<template>
    <Head title="Get Involved - MSDGC" />

    <div class="bg-gray-100 dark:bg-zinc-900 basket-tile">
        <!-- Header Navigation -->
        <header class="flex justify-between items-center text-white pr-4 px-4 py-5 lg:py-2 font-bold"
            style="background-color: #3477F4;">
            <Link href="/">
                <img src="/images/msdgc_logo.png" alt="MSDGC Logo" class="h-12 w-auto pr-4 self-start sm:self-center" />
            </Link>

            <nav class="mx-3 flex justify-end">
                <a href="mailto:info@mineralspringsdgc.com" class="flex items-center bg-white inset text-blue-500 rounded-sm px-3 py-1 mr-8 bangers text-xl">Contact Us</a>
                <Link :href="route('dashboard')" v-if="$page.props.auth?.user" :class="navStyle">
                    Dashboard
                </Link>
                <Link href="/" :class="navStyle">
                    Back to Home
                </Link>
            </nav>
        </header>

        <!-- Page Header -->
        <header :class="headerStyle"
            class="pb-6 lg:pb-0 relative py-20 lg:py-16 flex flex-col items-center justify-center text-white px-6 lg:px-12"
            style="background: url('/images/north_park_hole_1.jpg') center center / cover no-repeat; opacity: 0.9;">

            <div class="text-center py-10 px-5 lg:px-20 text-shadow-lg"
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0px, rgba(0,0,0,0));">
                <h1 class="text-5xl lg:text-6xl font-bold text-white uppercase mb-4">
                    Get Involved
                </h1>
                <p class="text-xl text-white/90 italic font-bold lg:font-normal">
                    Join the Mineral Springs Disc Golf Community
                </p>
            </div>
        </header>

        <!-- Main Content -->
        <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full px-6 lg:max-w-7xl py-10">
                <main class="mt-6">
                    <div class="grid gap-6">

                        <!-- Volunteer Opportunities -->
                        <div :class="cardStyle" class="bg-green-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold text-green-800 bangers">Volunteer Opportunities</h2>
                            </div>
                            <p class="text-lg mb-4">Help make our club the best it can be! We're always looking for volunteers to help with:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700">
                                <li>Course maintenance and improvements</li>
                                <li>Event organization and tournament directing</li>
                                <li>Social media and marketing</li>
                                <li>Fundraising activities</li>
                                <li>New player mentoring</li>
                                <li>Equipment maintenance</li>
                            </ul>
                        </div>

                        <!-- Board Positions -->
                        <div :class="cardStyle" class="bg-blue-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold text-blue-800 bangers">Join the Board</h2>
                            </div>
                            <p class="text-lg mb-4">Consider running for a board position to help guide the future of MSDGC:</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <h3 class="font-bold text-blue-700">President</h3>
                                    <p class="text-sm text-gray-600">Lead the club and coordinate with park services</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <h3 class="font-bold text-blue-700">Vice President</h3>
                                    <p class="text-sm text-gray-600">Support club operations and events</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <h3 class="font-bold text-blue-700">Treasurer</h3>
                                    <p class="text-sm text-gray-600">Manage club finances and memberships</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <h3 class="font-bold text-blue-700">Secretary</h3>
                                    <p class="text-sm text-gray-600">Keep meeting minutes and communications</p>
                                </div>
                            </div>
                        </div>

                        <!-- Event Hosting -->
                        <div :class="cardStyle" class="bg-amber-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold text-amber-800 bangers">Host an Event</h2>
                            </div>
                            <p class="text-lg mb-4">Want to organize something fun for the club? We support various types of events:</p>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="bg-amber-200 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2">
                                        🏆
                                    </div>
                                    <h3 class="font-bold">Tournaments</h3>
                                    <p class="text-sm text-gray-600">Competitive events</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-amber-200 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2">
                                        🎉
                                    </div>
                                    <h3 class="font-bold">Fun Rounds</h3>
                                    <p class="text-sm text-gray-600">Casual social play</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-amber-200 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-2">
                                        🎓
                                    </div>
                                    <h3 class="font-bold">Clinics</h3>
                                    <p class="text-sm text-gray-600">Skills development</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Section -->
                        <div :class="cardStyle" class="bg-gray-800 text-white">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-3xl font-bold bangers">Ready to Get Started?</h2>
                            </div>
                            <p class="text-lg mb-6">Reach out to us and let us know how you'd like to contribute to the Mineral Springs Disc Golf community!</p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="mailto:info@mineralspringsdgc.com"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors">
                                    Email Us
                                </a>
                                <a href="https://www.facebook.com/groups/mineralspringsdgc"
                                   target="_blank"
                                   class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors">
                                    Join Facebook Group
                                </a>
                                <Link href="/"
                                      class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors">
                                    Back to Home
                                </Link>
                            </div>
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
