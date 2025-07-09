<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    backgroundColor: {
        type: String,
        default: 'bg-gray-100'
    },
    iconColor: {
        type: String,
        default: 'bg-gray-600'
    },
    titleColor: {
        type: String,
        default: 'text-gray-800'
    },
    imageAlt: {
        type: String,
        required: true
    },
    imageSrc: {
        type: String,
        required: true
    },
    imageOnLeft: {
        type: Boolean,
        default: false
    }
});

const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black";
</script>

<template>
    <div :class="[cardStyle, backgroundColor]">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
            <!-- Image column - conditionally positioned -->
            <div v-if="imageOnLeft" class="flex items-center">
                <img :src="imageSrc" :alt="imageAlt" class="w-full h-full object-cover rounded-lg" />
            </div>

            <!-- Content spans 2 columns -->
            <div :class="imageOnLeft ? 'md:col-span-2' : 'md:col-span-2'">
                <div class="flex items-center gap-4 mb-4">
                    <div :class="[iconColor, 'w-12 h-12 rounded-full flex items-center justify-center']">
                        <slot name="icon">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </slot>
                    </div>
                    <h2 :class="[titleColor, 'text-3xl font-bold bangers']">{{ title }}</h2>
                </div>
                <div class="text-gray-700 text-lg">
                    <slot name="content"></slot>
                </div>
            </div>

            <!-- Image column - conditionally positioned -->
            <div v-if="!imageOnLeft" class="flex items-center">
                <img :src="imageSrc" :alt="imageAlt" class="w-full h-full object-cover rounded-lg" />
            </div>
        </div>
    </div>
</template>
