<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

defineProps({
    stats: {
        type: Object,
        default: () => ({
            classrooms: 0,
            students: 0,
            quizTemplates: 0,
        }),
    },
});
</script>

<template>
    <Head :title="t('nav.dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ t('nav.dashboard') }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Stats Grid - Mobile optimized -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-6 sm:mb-8">
                    <!-- Classrooms Card -->
                    <Link
                        :href="route('classrooms.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow active:bg-gray-50"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="ml-4 sm:ml-5 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.classrooms') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.classrooms }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Students Card -->
                    <Link
                        :href="route('students.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow active:bg-gray-50"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4 sm:ml-5 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.students') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.students }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Quiz Templates Card -->
                    <Link
                        :href="route('quiz-templates.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow active:bg-gray-50"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4 sm:ml-5 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.quiz_templates') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.quizTemplates }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Quick Actions - Mobile optimized with 44px touch targets -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('dashboard.quick_actions') }}</h3>
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                            <Link
                                :href="route('classrooms.create')"
                                class="inline-flex items-center justify-center px-4 py-3 min-h-[44px] bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ t('dashboard.create_classroom') }}
                            </Link>
                            <Link
                                :href="route('quiz-templates.create')"
                                class="inline-flex items-center justify-center px-4 py-3 min-h-[44px] bg-purple-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ t('dashboard.create_quiz') }}
                            </Link>
                            <Link
                                :href="route('audit-logs.index')"
                                class="inline-flex items-center justify-center px-4 py-3 min-h-[44px] bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                {{ t('dashboard.view_logs') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
