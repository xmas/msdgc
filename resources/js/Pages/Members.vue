<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MemberList from '@/Functions/MemberList.vue';
import { ref } from 'vue';

defineProps({
    auth: Object,
});

const memberListRef = ref(null);

const handleAddToEvent = () => {
    if (memberListRef.value) {
        memberListRef.value.openEventModal();
    }
};
</script>

<template>
    <AppLayout title="Members">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-4xl text-msdgc-blue dark:text-gray-200 leading-tight bangers">
                    Member Management
                </h2>
                <button
                    @click="handleAddToEvent"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors duration-200"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h6a2 2 0 012 2v4m-6 0v1h6V7M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2"></path>
                    </svg>
                    Add to Event
                </button>
            </div>
        </template>

        <div class="w-full h-full">
            <MemberList ref="memberListRef" :user="auth.user" />
        </div>
    </AppLayout>
</template>
