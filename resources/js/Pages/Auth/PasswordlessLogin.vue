<template>
    <Head title="Sign In" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Enter your email address or phone number and we'll send you a secure login link.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="type" value="Login Method" />
                <select
                    id="type"
                    v-model="form.type"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    required
                >
                    <option value="email">Email</option>
                    <option value="sms">SMS/Text</option>
                </select>
                <InputError :message="form.errors.type" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="identifier"
                    :value="form.type === 'email' ? 'Email Address' : 'Phone Number'"
                />
                <TextInput
                    id="identifier"
                    v-model="form.identifier"
                    :type="form.type === 'email' ? 'email' : 'tel'"
                    class="mt-1 block w-full"
                    :placeholder="form.type === 'email' ? 'you@example.com' : '+1 (555) 123-4567'"
                    required
                    autofocus
                />
                <InputError :message="form.errors.identifier" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Back to traditional login
                </Link>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Send Login Link
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
    status: {
        type: String,
        default: null,
    },
})

const form = useForm({
    type: 'email',
    identifier: '',
})

const submit = () => {
    form.post(route('passwordless.send-link'), {
        onFinish: () => form.reset('identifier'),
    })
}
</script>
