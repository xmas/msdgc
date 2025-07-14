<template>
    <Head title="Complete Sign Up" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            <p class="font-semibold">Welcome!</p>
            <p class="mt-2">
                We found that you're signing in with
                <span class="font-medium">{{ identifier }}</span>
                for the first time. Please tell us your name to complete your account setup.
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Your Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Enter your full name"
                    required
                    autofocus
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-md">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p class="font-medium">Account Details:</p>
                    <p class="mt-1">
                        {{ type === 'email' ? 'Email' : 'Phone' }}:
                        <span class="font-medium">{{ identifier }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Complete Sign Up
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
    token: {
        type: String,
        required: true,
    },
    identifier: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
})

const form = useForm({
    name: '',
})

const submit = () => {
    form.post(route('passwordless.complete-name', { token: props.token }))
}
</script>
