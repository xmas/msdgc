<template>
    <div :class="cardStyle" class="bg-zinc-800 text-white col-span-1">
        <p class="uppercase font-bold text-6xl bangers">{{ mondayDubsData?.item?.header1 || 'Monday Dubs!!' }}</p>
        <p>{{ mondayDubsData?.item?.paragraph_1 || '6pm in the summer, earlier as it gets darker. If you\'re there by 5 you\'re probably safe. Check the Facebook group for weekly updates.' }}</p>

        <p class="uppercase font-bold text-6xl bangers text-red-300">{{ mondayDubsData?.item?.header2 || 'IMPORTANT' }}</p>
        <div class="text-red-100 font-bold" v-html="mondayDubsData?.item?.paragraph2 || defaultParagraph2"></div>
    </div>
</template>

<script setup>
const cardStyle = "flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] text-black"
import { ref, onMounted } from 'vue'

const mondayDubsData = ref(null)
const defaultParagraph2 = 'This means the course is <span class="font-bold text-pink-400">VERY BUSY on Monday evenings</span>. All are welcome, but be prepared for a fun time.'

onMounted(async () => {
    try {
        const response = await fetch('/api/collections/content/monday-dubs')
        if (response.ok) {
            mondayDubsData.value = await response.json()
        }
    } catch (error) {
        console.error('Failed to fetch Monday Dubs data:', error)
    }
})
</script>
