<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    event_group: '',
    attrs: ''
});

const submit = () => {
    form.post(route('events.store'));
};

const cancel = () => {
    router.visit(route('events.index'));
};

const eventGroups = [
    'Conference',
    'Workshop',
    'Meetup',
    'Webinar',
    'Training',
    'Networking',
    'Social',
    'Competition',
    'Other'
];
</script>

<template>
    <AppLayout title="Create Event">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create New Event
                </h2>
                <SecondaryButton @click="cancel">
                    Back to Events
                </SecondaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <FormSection @submitted="submit">
                        <template #title>
                            Event Details
                        </template>

                        <template #description>
                            Create a new event with basic information and custom attributes.
                        </template>

                        <template #form>
                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="name" value="Event Name" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="event_group" value="Event Group" />
                                <select
                                    id="event_group"
                                    v-model="form.event_group"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Select an event group</option>
                                    <option v-for="group in eventGroups" :key="group" :value="group">
                                        {{ group }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.event_group" class="mt-2" />
                            </div>

                            <div class="col-span-6">
                                <InputLabel for="attrs" value="Event Attributes (JSON)" />
                                <textarea
                                    id="attrs"
                                    v-model="form.attrs"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="6"
                                    placeholder='{"location": "123 Main St", "capacity": 100, "description": "Event description", "tags": ["networking", "tech"]}'
                                />
                                <InputError :message="form.errors.attrs" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500">
                                    Optional: Enter custom attributes as JSON. Example: {"location": "123 Main St", "capacity": 100}
                                </p>
                            </div>
                        </template>

                        <template #actions>
                            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                                Event created successfully.
                            </ActionMessage>

                            <SecondaryButton @click="cancel" class="mr-3">
                                Cancel
                            </SecondaryButton>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Create Event
                            </PrimaryButton>
                        </template>
                    </FormSection>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
