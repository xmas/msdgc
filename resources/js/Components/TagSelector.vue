<script setup>
import { ref, computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    availableTags: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select tags',
    },
});

const selectedTags = computed({
    get() {
        return props.modelValue || [];
    },
    set(val) {
        emit('update:modelValue', val);
    },
});

const toggleTag = (tag) => {
    if (selectedTags.value.includes(tag)) {
        selectedTags.value = selectedTags.value.filter(t => t !== tag);
    } else {
        selectedTags.value = [...selectedTags.value, tag];
    }
};

const isSelected = (tag) => {
    return selectedTags.value.includes(tag);
};
</script>

<template>
    <div class="space-y-3">
        <!-- Selected tags display -->
        <div v-if="selectedTags.length > 0" class="flex flex-wrap gap-2">
            <span
                v-for="tag in selectedTags"
                :key="tag"
                class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
            >
                {{ tag }}
                <button
                    type="button"
                    @click="toggleTag(tag)"
                    class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200"
                >
                    ×
                </button>
            </span>
        </div>

        <!-- Available tags -->
        <div v-if="availableTags.length > 0" class="space-y-2">
            <p class="text-sm text-gray-600 dark:text-gray-400">Available tags:</p>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-48 overflow-y-auto">
                <label
                    v-for="tag in availableTags"
                    :key="tag"
                    class="flex items-center space-x-2 cursor-pointer p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                    <Checkbox
                        :checked="isSelected(tag)"
                        @update:checked="() => toggleTag(tag)"
                    />
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ tag }}</span>
                </label>
            </div>
        </div>

        <!-- No tags available message -->
        <div v-else class="text-sm text-gray-500 dark:text-gray-400 italic">
            No tags available
        </div>
    </div>
</template>
