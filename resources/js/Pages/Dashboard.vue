<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import MemberList from '@/Functions/MemberList.vue';
import MemberEvents from '@/Components/MemberEvents.vue';


defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-4xl text-msdgc-blue dark:text-gray-200 leading-tight bangers">
                <span class="text-blue-400">Welcome back,</span> {{ $attrs.auth.user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <h1 class="text-2xl font-medium text-gray-900 dark:text-white mb-6">
                            Member Dashboard
                        </h1>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2M8 7h8M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M9 17H7M17 17h2"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            My Events
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Events you're participating in
                                        </p>
                                    </div>
                                </div>
                                <MemberEvents :userId="$attrs.auth.user.id" />
                            </div>

                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Send Messages Card -->
                            <!-- <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                                Send Messages
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                                Email & SMS
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Send messages to all users or specific groups based on tags.
                                    </p>
                                    <div class="mt-3">
                                        <Link :href="route('messages.index')">
                                            <PrimaryButton class="bangers">
                                                Send Messages
                                            </PrimaryButton>
                                        </Link>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Quick Stats Card -->
                            <!-- <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                                Profile
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                                Settings
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Update your profile information and preferences.
                                    </p>
                                    <div class="mt-3">
                                        <Link :href="route('profile.show')">
                                            <PrimaryButton>
                                                View Profile
                                            </PrimaryButton>
                                        </Link>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Public Site Card -->
                            <!-- <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 truncate">
                                                Public Site
                                            </dt>
                                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                                MSDGC
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Visit the public facing website and information.
                                    </p>
                                    <div class="mt-3">
                                        <Link :href="route('home')">
                                            <PrimaryButton>
                                                View Site
                                            </PrimaryButton>
                                        </Link>
                                    </div>
                                </div>
                            </div> -->

                            <!-- My Events Card -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12" v-if="$attrs.auth.user.is_admin">
            <MemberList />
        </div>
    </AppLayout>
</template>
