<script setup>
import { ref, watch, computed } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['update:modelValue']);

const questions = ref(JSON.parse(JSON.stringify(props.modelValue)));

const questionTypes = computed(() => [
    { value: 'text', label: t('quiz_templates.text') },
    { value: 'essay', label: t('quiz_templates.essay') },
    { value: 'multiple_choice', label: t('quiz_templates.multiple_choice') },
    { value: 'boolean', label: t('quiz_templates.boolean') },
]);

// Flag to prevent circular updates
let isUpdatingFromProps = false;

// Emit changes to parent only when not updating from props
watch(questions, (newValue) => {
    if (isUpdatingFromProps) return;
    emit('update:modelValue', JSON.parse(JSON.stringify(newValue)));
}, { deep: true });

// Sync from parent props only when values actually differ
watch(() => props.modelValue, (newValue) => {
    const newJson = JSON.stringify(newValue);
    const currentJson = JSON.stringify(questions.value);
    if (newJson !== currentJson) {
        isUpdatingFromProps = true;
        questions.value = JSON.parse(newJson);
        // Reset flag after Vue's next tick
        setTimeout(() => { isUpdatingFromProps = false; }, 0);
    }
}, { deep: true });

function generateId() {
    return 'q-' + Math.random().toString(36).substr(2, 9);
}

function addQuestion() {
    questions.value.push({
        id: generateId(),
        type: 'text',
        question: '',
        required: true,
        options: [],
    });
}

function removeQuestion(index) {
    questions.value.splice(index, 1);
}

function addOption(questionIndex) {
    questions.value[questionIndex].options.push('');
}

function removeOption(questionIndex, optionIndex) {
    questions.value[questionIndex].options.splice(optionIndex, 1);
}

function moveQuestion(index, direction) {
    const newIndex = index + direction;
    if (newIndex >= 0 && newIndex < questions.value.length) {
        const temp = questions.value[index];
        questions.value[index] = questions.value[newIndex];
        questions.value[newIndex] = temp;
    }
}
</script>

<template>
    <div class="space-y-6">
        <div
            v-for="(question, qIndex) in questions"
            :key="question.id"
            class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm"
        >
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-gray-500">Question {{ qIndex + 1 }}</span>
                <div class="flex space-x-2">
                    <button
                        type="button"
                        @click="moveQuestion(qIndex, -1)"
                        :disabled="qIndex === 0"
                        class="text-gray-400 hover:text-gray-600 disabled:opacity-30"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="moveQuestion(qIndex, 1)"
                        :disabled="qIndex === questions.length - 1"
                        class="text-gray-400 hover:text-gray-600 disabled:opacity-30"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="removeQuestion(qIndex)"
                        class="text-red-400 hover:text-red-600"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('quiz_templates.question_text') }}</label>
                    <input
                        v-model="question.question"
                        type="text"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        :placeholder="t('quiz_templates.question_placeholder')"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('quiz_templates.question_type') }}</label>
                        <select
                            v-model="question.type"
                            class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option v-for="type in questionTypes" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center min-h-[44px]">
                            <input
                                v-model="question.required"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5"
                            />
                            <span class="ml-2 text-sm text-gray-600">{{ t('quiz_templates.required') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Multiple Choice Options -->
                <div v-if="question.type === 'multiple_choice'" class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">{{ t('quiz_templates.options') }}</label>
                    <div
                        v-for="(option, oIndex) in question.options"
                        :key="oIndex"
                        class="flex items-center space-x-2"
                    >
                        <input
                            v-model="question.options[oIndex]"
                            type="text"
                            class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            :placeholder="t('quiz_templates.option') + ' ' + (oIndex + 1)"
                        />
                        <button
                            type="button"
                            @click="removeOption(qIndex, oIndex)"
                            class="text-red-400 hover:text-red-600 p-2 min-w-[44px] min-h-[44px] flex items-center justify-center"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="addOption(qIndex)"
                        class="text-sm text-indigo-600 hover:text-indigo-800 py-2"
                    >
                        {{ t('quiz_templates.add_option') }}
                    </button>
                </div>
            </div>
        </div>

        <button
            type="button"
            @click="addQuestion"
            class="w-full py-4 min-h-[48px] border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition-colors"
        >
            {{ t('quiz_templates.add_question') }}
        </button>
    </div>
</template>
