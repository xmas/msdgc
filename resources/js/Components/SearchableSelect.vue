<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Search and select...',
    },
    searchPlaceholder: {
        type: String,
        default: 'Type to search...',
    },
    displayKey: {
        type: String,
        default: 'label',
    },
    valueKey: {
        type: String,
        default: 'value',
    },
    required: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(false);
const searchTerm = ref('');
const searchInput = ref(null);
const dropdown = ref(null);

const selectedOption = computed(() => {
    if (!props.modelValue) return null;
    return props.options.find(option => {
        const value = typeof option === 'object' ? option[props.valueKey] : option;
        return value == props.modelValue;
    });
});

const filteredOptions = computed(() => {
    if (!searchTerm.value) return props.options;

    return props.options.filter(option => {
        const label = typeof option === 'object' ? option[props.displayKey] : option;
        return label.toLowerCase().includes(searchTerm.value.toLowerCase());
    });
});

const displayText = computed(() => {
    if (!selectedOption.value) return props.placeholder;
    return typeof selectedOption.value === 'object'
        ? selectedOption.value[props.displayKey]
        : selectedOption.value;
});

const openDropdown = () => {
    isOpen.value = true;
    searchTerm.value = '';
    setTimeout(() => {
        searchInput.value?.focus();
    }, 10);
};

const closeDropdown = () => {
    isOpen.value = false;
    searchTerm.value = '';
};

const selectOption = (option) => {
    const value = typeof option === 'object' ? option[props.valueKey] : option;
    emit('update:modelValue', value);
    closeDropdown();
};

const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        closeDropdown();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="dropdown" class="relative">
        <!-- Selected value display -->
        <button
            type="button"
            @click="openDropdown"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2 text-left bg-white"
            :class="{ 'text-gray-500': !selectedOption, 'text-gray-900': selectedOption }"
        >
            <span class="block truncate">{{ displayText }}</span>
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>
        </button>

        <!-- Dropdown -->
        <div v-if="isOpen" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
            <!-- Search input -->
            <div class="sticky top-0 z-10 bg-white p-2">
                <input
                    ref="searchInput"
                    v-model="searchTerm"
                    type="text"
                    :placeholder="searchPlaceholder"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            <!-- Options -->
            <div v-if="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                No options found
            </div>

            <div v-else>
                <button
                    v-for="option in filteredOptions"
                    :key="typeof option === 'object' ? option[valueKey] : option"
                    type="button"
                    @click="selectOption(option)"
                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                    :class="{
                        'bg-indigo-50 text-indigo-600': (typeof option === 'object' ? option[valueKey] : option) == modelValue,
                        'text-gray-900': (typeof option === 'object' ? option[valueKey] : option) != modelValue
                    }"
                >
                    {{ typeof option === 'object' ? option[displayKey] : option }}
                </button>
            </div>
        </div>
    </div>
</template>
