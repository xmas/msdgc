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

            </div>
        </template>

        <div class="w-full h-full">
            <MemberList ref="memberListRef" :user="auth.user" />
        </div>
    </AppLayout>
</template>
