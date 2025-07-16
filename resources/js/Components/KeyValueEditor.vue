<script setup>
import { ref, computed, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: [String, Object],
        default: () => ({})
    },
    placeholder: {
        type: String,
        default: 'Add attributes...'
    },
    keyPlaceholder: {
        type: String,
        default: 'Key'
    },
    valuePlaceholder: {
        type: String,
        default: 'Value'
    },
    readonly: {
        type: Boolean,
        default: false
    }
});

// Parse initial value
const parseValue = (value) => {
    if (!value) return {};
    if (typeof value === 'string') {
        try {
            return JSON.parse(value);
        } catch {
            return {};
        }
    }
    return value;
};

const pairs = ref([]);
const isInternalUpdate = ref(false);

const addPair = () => {
    pairs.value.push({
        key: '',
        value: '',
        id: Math.random().toString(36).substr(2, 9)
    });
};

const removePair = (index) => {
    if (pairs.value.length > 1) {
        pairs.value.splice(index, 1);
        emitUpdate();
    }
};

const emitUpdate = () => {
    const result = {};
    pairs.value.forEach(pair => {
        if (pair.key.trim()) {
            result[pair.key.trim()] = pair.value;
        }
    });

    isInternalUpdate.value = true;
    // Only emit JSON string if there are actual pairs, otherwise emit empty string
    const jsonString = Object.keys(result).length > 0 ? JSON.stringify(result) : '';
    emit('update:modelValue', jsonString);
    // Reset flag after nextTick to ensure watch has processed
    setTimeout(() => {
        isInternalUpdate.value = false;
    }, 0);
};

// Initialize pairs from modelValue
const initializePairs = () => {
    const parsed = parseValue(props.modelValue);
    pairs.value = Object.entries(parsed).map(([key, value]) => ({
        key,
        value: String(value),
        id: Math.random().toString(36).substr(2, 9)
    }));

    // Add empty pair if none exist
    if (pairs.value.length === 0) {
        addPair();
    }
};

// Watch for external changes only
watch(() => props.modelValue, () => {
    // Only reinitialize if the change is not from internal input
    if (!isInternalUpdate.value) {
        initializePairs();
    }
}, { immediate: true });

// Auto-add new pair when last pair is filled
const handleInput = (index) => {
    const pair = pairs.value[index];
    const isLastPair = index === pairs.value.length - 1;

    if (isLastPair && pair.key.trim() && pair.value.trim()) {
        addPair();
    }

    emitUpdate();
};

const validPairs = computed(() => {
    return pairs.value.filter(pair => pair.key.trim());
});

const isEmpty = computed(() => {
    return validPairs.value.length === 0;
});
</script>

<template>
    <div class="space-y-3">
        <div v-if="!readonly" class="space-y-2">
            <div
                v-for="(pair, index) in pairs"
                :key="pair.id"
                class="flex items-center space-x-2"
            >
                <div class="flex-1">
                    <input
                        v-model="pair.key"
                        type="text"
                        :placeholder="keyPlaceholder"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                        @input="handleInput(index)"
                    />
                </div>
                <div class="flex-1">
                    <input
                        v-model="pair.value"
                        type="text"
                        :placeholder="valuePlaceholder"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                        @input="handleInput(index)"
                    />
                </div>
                <button
                    v-if="pairs.length > 1"
                    type="button"
                    @click="removePair(index)"
                    class="text-red-600 hover:text-red-800 p-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                <div v-else class="w-6"></div>
            </div>
        </div>

        <!-- Read-only display -->
        <div v-if="readonly && !isEmpty" class="space-y-2">
            <div
                v-for="pair in validPairs"
                :key="pair.id"
                class="flex items-center space-x-2 p-2 bg-gray-50 rounded-md"
            >
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-700">{{ pair.key }}</span>
                </div>
                <div class="flex-1">
                    <span class="text-sm text-gray-600">{{ pair.value }}</span>
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="isEmpty" class="text-center py-4 text-gray-500 text-sm">
            {{ readonly ? 'No attributes set' : placeholder }}
        </div>

        <!-- Manual add button (optional) -->
        <div v-if="!readonly" class="flex justify-start">
            <SecondaryButton type="button" @click="addPair" class="text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Attribute
            </SecondaryButton>
        </div>
    </div>
</template>
