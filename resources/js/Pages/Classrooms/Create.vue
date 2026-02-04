<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

defineProps({
    quizTemplates: Array,
});

const form = useForm({
    name: '',
    quiz_template_id: '',
});

const submit = () => {
    form.post(route('classrooms.store'));
};
</script>

<template>
    <Head :title="t('classrooms.create')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('classrooms.index')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('classrooms.create') }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form @submit.prevent="submit" class="p-4 sm:p-6 space-y-6">
                        <div>
                            <InputLabel for="name" :value="t('classrooms.name')" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="e.g., Class 10A"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="quiz_template_id" :value="t('classrooms.quiz_template_optional')" />
                            <select
                                id="quiz_template_id"
                                v-model="form.quiz_template_id"
                                class="mt-1 block w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">{{ t('classrooms.no_quiz') }}</option>
                                <option v-for="template in quizTemplates" :key="template.id" :value="template.id">
                                    {{ template.title }}
                                </option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ t('classrooms.quiz_hint') }}
                            </p>
                            <InputError class="mt-2" :message="form.errors.quiz_template_id" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link
                                :href="route('classrooms.index')"
                                class="text-gray-600 hover:text-gray-900 py-2"
                            >
                                {{ t('common.cancel') }}
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ t('classrooms.create') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
