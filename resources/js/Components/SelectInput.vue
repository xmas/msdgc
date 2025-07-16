<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select an option...',
    },
});

const proxyValue = computed({
    get() {
        return props.modelValue;
    },
    set(val) {
        emit('update:modelValue', val);
    },
});
</script>

<!-- class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full py-2 px-3" -->
<template>
    <select v-model="proxyValue"
        class="bg-gray-100 border border-gray-300 rounded-md shadow-sm mt-1 block w-full py-2 px-3 text-sm">
        <option value="" disabled>{{ placeholder }}</option>
        <option v-for="option in options" :key="option.value || option" :value="option.value || option">
            {{ option.label || option }}
        </option>
    </select>
</template>
