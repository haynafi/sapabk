<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

defineProps({
    student: Object,
});
</script>

<template>
    <Head :title="student.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('students.index')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('students.student_profile') }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Profile Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                <img
                                    v-if="student.photo_path"
                                    :src="'/storage/' + student.photo_path"
                                    class="h-32 w-32 rounded-full object-cover"
                                />
                                <div
                                    v-else
                                    class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center"
                                >
                                    <span class="text-4xl text-gray-500 font-medium">
                                        {{ student.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ student.name }}</h3>
                                <p class="text-gray-500">{{ student.nisn ? 'NISN: ' + student.nisn : 'No NISN' }}</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ student.classroom?.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biodata -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('students.biodata') }}</h3>

                        <div v-if="student.biodata && Object.keys(student.biodata).length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="(value, key) in student.biodata" :key="key" class="border-b border-gray-100 pb-2">
                                <dt class="text-sm font-medium text-gray-500 capitalize">
                                    {{ key.replace(/_/g, ' ') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ value || '-' }}</dd>
                            </div>
                        </div>
                        <div v-else class="text-gray-500 text-sm">{{ t('students.no_biodata') }}</div>
                    </div>
                </div>

                <!-- Quiz Responses -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('students.quiz_responses') }}</h3>

                        <div v-if="student.quiz_responses && student.quiz_responses.length > 0" class="space-y-6">
                            <div
                                v-for="response in student.quiz_responses"
                                :key="response.id"
                                class="border border-gray-200 rounded-lg p-4"
                            >
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 gap-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ response.quiz_template?.title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ t('students.submitted') }}: {{ new Date(response.created_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>

                                <div v-if="response.answers" class="space-y-4">
                                    <div
                                        v-for="question in response.quiz_template?.questions"
                                        :key="question.id"
                                        class="border-l-4 border-indigo-200 pl-4"
                                    >
                                        <p class="text-sm font-medium text-gray-700">{{ question.question }}</p>
                                        <p class="text-sm text-gray-900 mt-1">
                                            <template v-if="response.answers[question.id] === true">{{ t('common.yes') }}</template>
                                            <template v-else-if="response.answers[question.id] === false">{{ t('common.no') }}</template>
                                            <template v-else>{{ response.answers[question.id] || '-' }}</template>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-gray-500 text-sm">{{ t('students.no_quiz_responses') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
