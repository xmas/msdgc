<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MemberList from '@/Functions/MemberList.vue';

defineProps({
    auth: Object,
});
</script>

<template>
    <AppLayout title="Members">
        <template #header>
            <h2 class="font-semibold text-4xl text-msdgc-blue dark:text-gray-200 leading-tight bangers">
                Member Management
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <MemberList :user="auth.user" />
            </div>
        </div>
    </AppLayout>
</template>
