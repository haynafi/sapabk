<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import QuizBuilder from '@/Components/QuizBuilder.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    quizTemplate: Object,
});

const form = useForm({
    title: props.quizTemplate.title,
    questions: props.quizTemplate.questions || [],
});

const submit = () => {
    form.put(route('quiz-templates.update', props.quizTemplate.id));
};
</script>

<template>
    <Head :title="t('quiz_templates.edit')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('quiz-templates.index')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('quiz_templates.edit') }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form @submit.prevent="submit" class="p-4 sm:p-6 space-y-6">
                        <div>
                            <InputLabel for="title" :value="t('quiz_templates.template_title')" />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div>
                            <InputLabel :value="t('quiz_templates.questions')" class="mb-4" />
                            <QuizBuilder v-model="form.questions" />
                            <InputError class="mt-2" :message="form.errors.questions" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link
                                :href="route('quiz-templates.index')"
                                class="text-gray-600 hover:text-gray-900 py-2"
                            >
                                {{ t('common.cancel') }}
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ t('quiz_templates.update') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
