<template>
    <div v-if="sponsors.length > 0" class="col-span-2">
        <h2 class="text-4xl font-bold text-center mb-6 bangers text-gray-800">Disc golf Sponsors</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div v-for="sponsor in sponsors" :key="sponsor.id" :class="cardStyle" class="bg-white text-black">

                <!-- Logo -->
                <div class="flex-shrink-0 h-24 md:h-32 flex items-center justify-center px-4  mx-auto">
                    <img v-if="sponsor.logo && sponsor.logo[0]" :src="sponsor.logo[0]" :alt="sponsor.title + ' logo'"
                        class="w-full h-full object-contain rounded-lg" />
                    <div v-else class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500 text-sm">No Logo</span>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 items-start">

                    <!-- Content on the right -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-bold text-zinc-800">{{ sponsor.title }}</h3>

                        <div class="space-y-2 text-sm">
                            <!-- City and North/South indicator -->
                            <p v-if="sponsor.city_state" class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-zinc-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <span class="text-zinc-500">{{ sponsor.city_state }} - {{ sponsor.north_of_msdgc ?
                                    'North' : 'South' }} of Mineral Springs</span>
                            </p>

                            <!-- Nearby description -->
                            <p v-if="sponsor.nearby" class="text-gray-800 italic">{{ sponsor.nearby }}</p>

                            <!-- Links -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a v-if="sponsor.web_site" :href="sponsor.web_site" target="_blank" rel="noopener"
                                    class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                    Website
                                </a>

                                <a v-if="sponsor.udisc_link" :href="sponsor.udisc_link" target="_blank" rel="noopener"
                                    class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    UDisc
                                </a>

                                <a v-if="sponsor.driving_directions" :href="sponsor.driving_directions" target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                        </path>
                                    </svg>
                                    Directions
                                </a>

                                <!-- <span v-if="sponsor.is_pro_shop"
                                    class="inline-flex items-center px-3 py-1 text-gray-600 text-xs rounded-full hover:text-gray-800 transition-colors outline-none focus:outline-none">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                        </path>
                                    </svg>
                                    Pro Shop
                                </span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    sponsors: {
        type: Array,
        required: true,
        default: () => []
    }
});

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"
</script>
