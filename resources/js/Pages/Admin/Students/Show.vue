<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    student: Object,
});

function deleteStudent() {
    if (confirm(t('admin.confirm_delete_student', { name: props.student.name }))) {
        router.delete(route('admin.students.destroy', props.student.id));
    }
}
</script>

<template>
    <Head :title="student.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <Link
                        :href="route('admin.students.index')"
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
                <button
                    @click="deleteStudent"
                    class="inline-flex items-center px-4 py-2 min-h-[44px] bg-red-600 text-white rounded-md font-semibold text-sm hover:bg-red-700"
                >
                    {{ t('common.delete') }}
                </button>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <!-- Student Header -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6 pb-6 border-b">
                            <div v-if="student.photo_url" class="flex-shrink-0">
                                <img class="h-24 w-24 rounded-full object-cover" :src="student.photo_url" :alt="student.name" />
                            </div>
                            <div v-else class="flex-shrink-0 h-24 w-24 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-500 font-bold text-2xl">{{ student.name.charAt(0).toUpperCase() }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900">{{ student.name }}</h3>
                                <p v-if="student.nisn" class="text-gray-500 font-mono">NISN: {{ student.nisn }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ student.classroom?.name || '-' }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ student.classroom?.school?.name || '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Biodata Section -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ t('students.biodata') }}</h4>
                            <div v-if="student.biodata && Object.keys(student.biodata).length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div v-for="(value, key) in student.biodata" :key="key" class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 capitalize">{{ key.replace(/_/g, ' ') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ value || '-' }}</dd>
                                </div>
                            </div>
                            <p v-else class="text-gray-500">{{ t('students.no_biodata') }}</p>
                        </div>

                        <!-- Quiz Responses Section -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ t('students.quiz_responses') }}</h4>
                            <div v-if="student.quiz_responses && student.quiz_responses.length > 0" class="space-y-4">
                                <div v-for="response in student.quiz_responses" :key="response.id" class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-medium text-gray-900">{{ response.quiz_template?.title || 'Quiz Response' }}</h5>
                                        <span class="text-xs text-gray-500">{{ new Date(response.created_at).toLocaleString() }}</span>
                                    </div>
                                    <div v-if="response.responses" class="space-y-2">
                                        <div v-for="(answer, question) in response.responses" :key="question" class="text-sm">
                                            <span class="font-medium text-gray-700">{{ question }}:</span>
                                            <span class="text-gray-600 ml-2">{{ answer }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-gray-500">{{ t('students.no_quiz_responses') }}</p>
                        </div>

                        <!-- Meta Information -->
                        <div class="mt-6 pt-6 border-t text-sm text-gray-500">
                            <p>{{ t('students.registered_at') }}: {{ new Date(student.created_at).toLocaleString() }}</p>
                            <p v-if="student.classroom?.teacher">{{ t('admin.teacher_name') }}: {{ student.classroom.teacher.name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
