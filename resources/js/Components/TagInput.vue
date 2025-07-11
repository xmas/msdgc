<script setup>
import { ref, computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    label: {
        type: String,
        default: 'Tags',
    },
});

const newTag = ref('');

const tags = computed({
    get() {
        return props.modelValue || [];
    },
    set(val) {
        emit('update:modelValue', val);
    },
});

const addTag = () => {
    if (newTag.value.trim() && !tags.value.includes(newTag.value.trim())) {
        tags.value = [...tags.value, newTag.value.trim()];
        newTag.value = '';
    }
};

const removeTag = (index) => {
    tags.value = tags.value.filter((_, i) => i !== index);
};

const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        addTag();
    }
};
</script>

<template>
    <div>
        <InputLabel :value="label" />

        <!-- Display existing tags -->
        <div v-if="tags.length > 0" class="flex flex-wrap gap-2 mt-2 mb-3">
            <span
                v-for="(tag, index) in tags"
                :key="index"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200"
            >
                {{ tag }}
                <button
                    type="button"
                    @click="removeTag(index)"
                    class="ml-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200"
                >
                    ×
                </button>
            </span>
        </div>

        <!-- Add new tag input -->
        <div class="flex gap-2">
            <TextInput
                v-model="newTag"
                type="text"
                placeholder="Add a tag..."
                class="flex-1"
                @keydown="handleKeyDown"
            />
            <SecondaryButton type="button" @click="addTag" :disabled="!newTag.trim()">
                Add
            </SecondaryButton>
        </div>
    </div>
</template>
