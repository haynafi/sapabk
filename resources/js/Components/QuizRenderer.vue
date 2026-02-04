<script setup>
import { ref, watch } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    questions: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['update:modelValue']);

const answers = ref(JSON.parse(JSON.stringify(props.modelValue)));

// Flag to prevent circular updates
let isUpdatingFromProps = false;

// Emit changes to parent only when not updating from props
watch(answers, (newValue) => {
    if (isUpdatingFromProps) return;
    emit('update:modelValue', JSON.parse(JSON.stringify(newValue)));
}, { deep: true });

// Sync from parent props only when values actually differ
watch(() => props.modelValue, (newValue) => {
    const newJson = JSON.stringify(newValue);
    const currentJson = JSON.stringify(answers.value);
    if (newJson !== currentJson) {
        isUpdatingFromProps = true;
        answers.value = JSON.parse(newJson);
        // Reset flag after Vue's next tick
        setTimeout(() => { isUpdatingFromProps = false; }, 0);
    }
}, { deep: true });
</script>

<template>
    <div class="space-y-6">
        <div
            v-for="(question, index) in questions"
            :key="question.id"
            class="bg-white border border-gray-200 rounded-lg p-4"
        >
            <label class="block text-sm font-medium text-gray-900 mb-2">
                {{ index + 1 }}. {{ question.question }}
                <span v-if="question.required" class="text-red-500">*</span>
            </label>

            <!-- Text Input -->
            <input
                v-if="question.type === 'text'"
                v-model="answers[question.id]"
                type="text"
                :required="question.required"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Your answer..."
            />

            <!-- Essay (Textarea) -->
            <textarea
                v-else-if="question.type === 'essay'"
                v-model="answers[question.id]"
                :required="question.required"
                rows="4"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Your answer..."
            ></textarea>

            <!-- Multiple Choice -->
            <div v-else-if="question.type === 'multiple_choice'" class="space-y-2">
                <label
                    v-for="(option, optionIndex) in question.options"
                    :key="optionIndex"
                    class="flex items-center space-x-3 cursor-pointer"
                >
                    <input
                        v-model="answers[question.id]"
                        type="radio"
                        :name="'question-' + question.id"
                        :value="option"
                        :required="question.required"
                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <span class="text-gray-700">{{ option }}</span>
                </label>
            </div>

            <!-- Boolean (Yes/No) -->
            <div v-else-if="question.type === 'boolean'" class="flex space-x-4">
                <label class="flex items-center space-x-2 cursor-pointer min-h-[44px]">
                    <input
                        v-model="answers[question.id]"
                        type="radio"
                        :name="'question-' + question.id"
                        :value="true"
                        :required="question.required"
                        class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <span class="text-gray-700">{{ t('common.yes') }}</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer min-h-[44px]">
                    <input
                        v-model="answers[question.id]"
                        type="radio"
                        :name="'question-' + question.id"
                        :value="false"
                        :required="question.required"
                        class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <span class="text-gray-700">{{ t('common.no') }}</span>
                </label>
            </div>
        </div>
    </div>
</template>
