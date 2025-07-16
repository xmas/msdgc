<template>
    <section id="local_sponsors" :class="cardStyle" class="bg-white">
        <div v-if="sponsors.length > 0" class="w-full">
            <h2 class="text-4xl font-bold text-center mb-6 bangers text-gray-700">Local Sponsors</h2>
            <div class="flex flex-wrap justify-center items-stretch gap-4 md:gap-6 w-full">
                <div v-for="sponsor in sponsors" :key="sponsor.id"
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
</template>

<script setup>
defineProps({
    sponsors: {
        type: Array,
        required: true
    }
});

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"
</script>
