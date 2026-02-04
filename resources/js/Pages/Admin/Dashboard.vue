<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

defineProps({
    stats: Object,
    recentSchools: Array,
    recentTeachers: Array,
});
</script>

<template>
    <Head :title="t('admin.dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ t('admin.dashboard') }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-5 mb-6 sm:mb-8">
                    <!-- Schools Card -->
                    <Link
                        :href="route('admin.schools.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('admin.schools') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.schools }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Teachers Card -->
                    <Link
                        :href="route('admin.teachers.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('admin.teachers') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.teachers }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Classrooms Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.classrooms') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.classrooms }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Card -->
                    <Link
                        :href="route('admin.students.index')"
                        class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow"
                    >
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.students') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.students }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Quiz Templates Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">{{ t('nav.quiz_templates') }}</dt>
                                        <dd class="text-2xl sm:text-3xl font-semibold text-gray-900">{{ stats.quizTemplates }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Schools -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-4 sm:p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ t('admin.recent_schools') }}</h3>
                                <Link :href="route('admin.schools.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    {{ t('common.view') }} →
                                </Link>
                            </div>
                            <div v-if="recentSchools.length === 0" class="text-gray-500 text-sm">
                                {{ t('admin.no_schools') }}
                            </div>
                            <ul v-else class="divide-y divide-gray-200">
                                <li v-for="school in recentSchools" :key="school.id" class="py-3">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ school.name }}</p>
                                            <p class="text-xs text-gray-500">{{ school.slug }}</p>
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            <p>{{ school.users_count }} {{ t('admin.teachers') }}</p>
                                            <p>{{ school.students_count }} {{ t('nav.students') }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Teachers -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-4 sm:p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ t('admin.recent_teachers') }}</h3>
                                <Link :href="route('admin.teachers.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    {{ t('common.view') }} →
                                </Link>
                            </div>
                            <div v-if="recentTeachers.length === 0" class="text-gray-500 text-sm">
                                {{ t('admin.no_teachers') }}
                            </div>
                            <ul v-else class="divide-y divide-gray-200">
                                <li v-for="teacher in recentTeachers" :key="teacher.id" class="py-3">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ teacher.name }}</p>
                                            <p class="text-xs text-gray-500">NIP: {{ teacher.nip }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">{{ teacher.school?.name || '-' }}</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('dashboard.quick_actions') }}</h3>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                :href="route('admin.schools.index')"
                                class="inline-flex items-center px-4 py-3 min-h-[44px] bg-blue-600 text-white rounded-md font-semibold text-sm hover:bg-blue-700"
                            >
                                {{ t('admin.manage_schools') }}
                            </Link>
                            <Link
                                :href="route('admin.teachers.index')"
                                class="inline-flex items-center px-4 py-3 min-h-[44px] bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700"
                            >
                                {{ t('admin.manage_teachers') }}
                            </Link>
                            <Link
                                :href="route('admin.students.index')"
                                class="inline-flex items-center px-4 py-3 min-h-[44px] bg-yellow-600 text-white rounded-md font-semibold text-sm hover:bg-yellow-700"
                            >
                                {{ t('admin.all_students') }}
                            </Link>
                            <Link
                                :href="route('admin.audit-logs')"
                                class="inline-flex items-center px-4 py-3 min-h-[44px] bg-gray-600 text-white rounded-md font-semibold text-sm hover:bg-gray-700"
                            >
                                {{ t('admin.view_all_logs') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
