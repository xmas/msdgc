<script setup>
import { Head, Link } from '@inertiajs/vue3';

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"
const headerStyle = "flex items-center justify-center"
const navStyle = ""
</script>

<template>
    <Head title="Contact - MSDGC" />

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
            style="background: url('/images/involved/happy_place.jpeg') center center / cover no-repeat; opacity: 0.9;">

            <div class="text-center py-10 px-5 lg:px-20 text-shadow-lg"
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0px, rgba(0,0,0,0));">
                <h1 class="text-5xl lg:text-6xl font-bold text-white uppercase mb-4">
                    Put your bag down,
                </h1>
                <h2 class="text-4xl lg:text-5xl font-bold text-white uppercase mb-4">
                    and get involved.
                </h2>
                <p class="text-xl text-white/90 italic font-bold lg:font-normal">
                    It takes a village to make our club great
                </p>
            </div>
        </header>

        <!-- Main Content -->
        <div class="relative min-h-screen flex flex-col items-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full px-6 lg:max-w-7xl py-10">
                <main class="mt-6">
                    <div class="grid gap-6">

                        <!-- Introduction -->
                        <div :class="cardStyle" class="bg-blue-50">
                            <div class="text-center">
                                <h2 class="text-3xl font-bold text-blue-800 bangers mb-4">Ways to Get Involved</h2>
                                <p class="text-lg text-gray-700 mb-4">
                                    If you want to learn more about any of the following volunteer opportunities, contact us using the information below.
                                </p>
                                <a href="http://eepurl.com/iF3r6w"
                                   target="_blank"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition-colors inline-block">
                                    Learn More & Sign Up
                                </a>
                            </div>
                        </div>

                        <!-- Telecommunications -->
                        <div :class="cardStyle" class="bg-green-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-green-800 bangers">Telecommunications</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Email Campaigns</li>
                                        <li>Website Editing & Maintenance</li>
                                        <li>Calendar updating</li>
                                    </ul>
                                </div>
                                <!-- Image in the 3rd column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/telecomm.jpeg" alt="Telecommunications" class="w-full h-full object-cover rounded-lg" />
                                </div>
                            </div>
                        </div>


                        <!-- Social Media -->
                        <div :class="cardStyle" class="bg-purple-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Image in the 1st column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/social_media.jpeg" alt="Social Media" class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h3a1 1 0 011 1v2h3a1 1 0 011 1v3a1 1 0 01-1 1h-3v3a1 1 0 01-1 1H8a1 1 0 01-1-1v-3H4a1 1 0 01-1-1V5a1 1 0 011-1h3z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-purple-800 bangers">Social Media</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Create/maintain Instagram</li>
                                        <li>Create/maintain Twitter</li>
                                        <li>Facebook management</li>
                                    </ul>
                                </div>
                            </div>
                        </div>                        <!-- Digital Media Design -->
                        <div :class="cardStyle" class="bg-pink-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-pink-800 bangers">Digital Media Design</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Flyers</li>
                                        <li>Course Signage</li>
                                        <li>YouTube content creation</li>
                                    </ul>
                                </div>
                                <!-- Image in the 3rd column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/media.jpeg" alt="Digital Media Design" class="w-full h-full object-cover rounded-lg" />
                                </div>
                            </div>
                        </div>

                        <!-- League and Event Organizers -->
                        <div :class="cardStyle" class="bg-orange-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Image in the 1st column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/organize.jpeg" alt="League and Event Organizers" class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-orange-800 bangers">League and Event Organizers</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Handicapped leagues/rounds</li>
                                        <li>Fun league</li>
                                        <li>Women's league</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Merchandise Creators -->
                        <div :class="cardStyle" class="bg-red-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-red-800 bangers">Merchandise Creators</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Hot stamp press</li>
                                        <li>Hats, beanies, shirts, hoodies, towels etc</li>
                                        <li>Discs/mini's</li>
                                        <li>Stickers & decals</li>
                                    </ul>
                                </div>
                                <!-- Image in the 3rd column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/merch.jpeg" alt="Merchandise Creators" class="w-full h-full object-cover rounded-lg" />
                                </div>
                            </div>
                        </div>                        <!-- Course Layout & Design -->
                        <div :class="cardStyle" class="bg-teal-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Image in the 1st column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/course.jpeg" alt="Course Layout & Design" class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-teal-800 bangers">Course Layout & Design</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Alternate Layouts</li>
                                        <li>Short baskets, ace runs, extreme OB</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Coaching & Community Outreach -->
                        <div :class="cardStyle" class="bg-indigo-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-indigo-800 bangers">Coaching & Community Outreach</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Kids/Teen Outreach</li>
                                        <li>New Player Outreach & Coaching</li>
                                        <li>Putting Clinic</li>
                                        <li>Forehand Clinic</li>
                                        <li>Distance Clinic</li>
                                    </ul>
                                </div>
                                <!-- Image in the 3rd column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/outreach.jpeg" alt="Coaching & Community Outreach" class="w-full h-full object-cover rounded-lg" />
                                </div>
                            </div>
                        </div>

                        <!-- Course Maintenance -->
                        <div :class="cardStyle" class="bg-yellow-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Image in the 1st column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/maintain.jpg" alt="Course Maintenance" class="w-full h-full object-cover rounded-lg" />
                                </div>
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-yellow-800 bangers">Course Maintenance</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Organize & Participate in Work Parties</li>
                                        <li>Help with Projects like tee pad replacement/repair</li>
                                        <li>Build Benches, Walls, Pathways</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory/Supplies -->
                        <div :class="cardStyle" class="bg-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                                <!-- Content spans 2 columns -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-3xl font-bold text-gray-800 bangers">Inventory/Supplies</h2>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-lg">
                                        <li>Glow Lights</li>
                                        <li>Batteries</li>
                                        <li>Flags</li>
                                    </ul>
                                </div>
                                <!-- Image in the 3rd column -->
                                <div class="flex items-center">
                                    <img src="/images/involved/inventory.jpg" alt="Inventory/Supplies" class="w-full h-full object-cover rounded-lg" />
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
                                <h2 class="text-3xl font-bold bangers">Contact Us</h2>
                            </div>
                            <p class="text-lg mb-6">Ready to get involved? Reach out to us and let us know how you'd like to contribute to the Mineral Springs Disc Golf community!</p>

                            <!-- Contact Methods -->
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div class="text-center">
                                    <h3 class="text-xl font-bold mb-4">Email Us</h3>
                                    <a href="mailto:info@mineralspringsdgc.com"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors inline-block">
                                        info@mineralspringsdgc.com
                                    </a>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold mb-4">Sign Up for Updates</h3>
                                    <a href="http://eepurl.com/iF3r6w"
                                       target="_blank"
                                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors inline-block">
                                        Newsletter Signup
                                    </a>
                                </div>
                            </div>

                            <!-- Social Media Links -->
                            <div class="border-t border-gray-600 pt-6">
                                <h3 class="text-xl font-bold mb-4 text-center">Follow Us</h3>
                                <div class="flex justify-center space-x-6">
                                    <a href="https://www.facebook.com/profile.php?id=61563080123772"
                                       target="_blank"
                                       class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="https://instagram.com/mineralspringsdgc"
                                       target="_blank"
                                       class="bg-pink-600 hover:bg-pink-700 text-white p-3 rounded-full transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988c6.62 0 11.988-5.367 11.988-11.988C24.005 5.367 18.637.001 12.017.001zM15.675 19.988H8.341c-2.186 0-3.997-1.811-3.997-3.997V8.657c0-2.186 1.811-3.997 3.997-3.997h7.334c2.186 0 3.997 1.811 3.997 3.997v7.334c0 2.186-1.811 3.997-3.997 3.997z"/>
                                        </svg>
                                    </a>
                                    <a href="https://www.youtube.com/channel/UC4uhAEj9crJ4jazU1Uv6ALA"
                                       target="_blank"
                                       class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div class="border-t border-gray-600 pt-6 mt-6">
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link href="/"
                                          class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors">
                                        Back to Home
                                    </Link>
                                    <Link :href="route('contact')"
                                          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold text-center transition-colors">
                                        Get Involved
                                    </Link>
                                </div>
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
