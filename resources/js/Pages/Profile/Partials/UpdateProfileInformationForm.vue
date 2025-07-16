<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import TagInput from '@/Components/TagInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    first_name: props.user.first_name || '',
    last_name: props.user.last_name || '',
    email: props.user.email,
    sms: props.user.sms || '',
    provider: props.user.provider || '',
    sms_opt_in: props.user.sms_opt_in || false,
    email_opt_in: props.user.email_opt_in || false,
    how_did_you_hear: props.user.how_did_you_hear || '',
    paid_via: props.user.paid_via || '',
    tags: props.user.tags || [],
    comments: props.user.comments || '',
    topics: props.user.topics || '',
    region: props.user.region || '',
    photo: null,
});

const providerOptions = [
    'Verizon',
    'AT&T',
    'T-Mobile',
    'Sprint',
    'Other'
];

const howDidYouHearOptions = [
    'Google Search',
    'Facebook',
    'Instagram',
    'Friend Referral',
    'Golf Course',
    'Tournament',
    'Word of Mouth',
    'Newsletter',
    'Other'
];

const paidViaOptions = [
    'Cash',
    'Check',
    'Credit Card',
    'PayPal',
    'Venmo',
    'Zelle',
    'Bank Transfer',
    'Other'
];

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <InputLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.first_name + ' ' + user.last_name"
                        class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </SecondaryButton>

                <SecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto">
                    Remove Photo
                </SecondaryButton>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- First Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full" required
                    autocomplete="given-name" />
                <InputError :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="last_name" value="Last Name" />
                <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full" required
                    autocomplete="family-name" />
                <InputError :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required
                    autocomplete="username" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2 dark:text-white">
                        Your email address is unverified.

                        <Link :href="route('verification.send')" method="post" as="button"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            @click.prevent="sendEmailVerification">
                        Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent"
                        class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>

            <!-- SMS/Phone Number -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="sms" value="SMS/Phone Number" />
                <TextInput id="sms" v-model="form.sms" type="tel" class="mt-1 block w-full" autocomplete="tel"
                    placeholder="(555) 123-4567" />
                <InputError :message="form.errors.sms" class="mt-2" />
            </div>

            <!-- Provider -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="provider" value="Mobile Provider" />
                <SelectInput id="provider" v-model="form.provider" :options="providerOptions"
                    placeholder="Select your mobile provider..." />
                <InputError :message="form.errors.provider" class="mt-2" />
            </div>

            <!-- Communication Preferences -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel value="Communication Preferences" />
                <div class="mt-2 space-y-2">
                    <label class="flex items-center">
                        <Checkbox v-model:checked="form.sms_opt_in" />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            I agree to receive SMS notifications
                        </span>
                    </label>
                    <label class="flex items-center">
                        <Checkbox v-model:checked="form.email_opt_in" />
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            I agree to receive email notifications
                        </span>
                    </label>
                </div>
                <InputError :message="form.errors.sms_opt_in" class="mt-2" />
                <InputError :message="form.errors.email_opt_in" class="mt-2" />
            </div>

            <!-- How did you hear about our club? -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="how_did_you_hear" value="How did you hear about our club?" />
                <SelectInput id="how_did_you_hear" v-model="form.how_did_you_hear" :options="howDidYouHearOptions"
                    placeholder="Please select..." />
                <InputError :message="form.errors.how_did_you_hear" class="mt-2" />
            </div>

            <!-- Tags -->
            <div class="col-span-6 sm:col-span-4">
                <TagInput v-model="form.tags" label="Tags" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Add tags to categorize your profile (e.g., beginner, advanced, tournament player, volunteer)
                </p>
                <InputError :message="form.errors.tags" class="mt-2" />
            </div>

            <!-- Comments -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="comments" value="Comments" />
                <textarea id="comments" v-model="form.comments"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm px-3 py-2"
                    rows="3" placeholder="Any additional comments or notes..." />
                <InputError :message="form.errors.comments" class="mt-2" />
            </div>

            <!-- Topics -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="topics" value="Topics of Interest" />
                <textarea id="topics" v-model="form.topics"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm px-3 py-2"
                    rows="4"
                    placeholder="What topics or activities are you interested in? (tournaments, lessons, social events, etc.)" />
                <InputError :message="form.errors.topics" class="mt-2" />
            </div>

            <!-- Region -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="region" value="Region" />
                <TextInput id="region" v-model="form.region" type="text" class="mt-1 block w-full"
                    placeholder="Your region or area" />
                <InputError :message="form.errors.region" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>
