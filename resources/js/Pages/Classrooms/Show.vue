<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    classroom: Object,
    students: Object,
    joinUrl: String,
});

const copied = ref(false);

function copyJoinUrl() {
    navigator.clipboard.writeText(props.joinUrl);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
}
</script>

<template>
    <Head :title="classroom.name" />

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
                <h2 class="text-xl font-semibold leading-tight text-gray-800 truncate">
                    {{ classroom.name }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Classroom Info -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">{{ t('classrooms.unit_code') }}</h3>
                                <p class="mt-1 text-xl sm:text-2xl font-mono font-bold text-indigo-600">{{ classroom.unit_code }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">{{ t('classrooms.quiz_template') }}</h3>
                                <p class="mt-1 text-base sm:text-lg text-gray-900">
                                    {{ classroom.quiz_template?.title || t('classrooms.no_quiz') }}
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">{{ t('students.name') }}</h3>
                                <p class="mt-1 text-base sm:text-lg text-gray-900">{{ classroom.teacher?.name }}</p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">{{ t('classrooms.public_link') }}</h3>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <input
                                    type="text"
                                    :value="joinUrl"
                                    readonly
                                    class="flex-1 border-gray-300 rounded-md bg-white text-sm min-h-[44px]"
                                />
                                <button
                                    @click="copyJoinUrl"
                                    class="px-4 py-3 min-h-[44px] bg-indigo-600 text-white rounded-md hover:bg-indigo-700 active:bg-indigo-800 text-sm font-medium whitespace-nowrap"
                                >
                                    {{ copied ? t('common.success') + '!' : t('classrooms.copy_link') }}
                                </button>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ t('join.fill_form') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Students List -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('nav.students') }}</h3>

                        <div v-if="students.data.length === 0" class="text-center py-8 text-gray-500">
                            {{ t('students.no_students') }}
                        </div>

                        <!-- Desktop Table View -->
                        <div v-else class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('students.name') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('students.nisn') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('students.registered_at') }}
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('classrooms.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in students.data" :key="student.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img
                                                        v-if="student.photo_path"
                                                        :src="'/storage/' + student.photo_path"
                                                        class="h-10 w-10 rounded-full object-cover"
                                                    />
                                                    <div
                                                        v-else
                                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center"
                                                    >
                                                        <span class="text-gray-500 font-medium">
                                                            {{ student.name.charAt(0).toUpperCase() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ student.nisn || '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ new Date(student.created_at).toLocaleDateString() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('students.show', student.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                {{ t('classrooms.view') }}
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div v-if="students.data.length > 0" class="md:hidden space-y-4">
                            <Link
                                v-for="student in students.data"
                                :key="student.id"
                                :href="route('students.show', student.id)"
                                class="block bg-gray-50 rounded-lg p-4 active:bg-gray-100"
                            >
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <img
                                            v-if="student.photo_path"
                                            :src="'/storage/' + student.photo_path"
                                            class="h-12 w-12 rounded-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center"
                                        >
                                            <span class="text-gray-500 font-medium text-lg">
                                                {{ student.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ student.name }}</p>
                                        <p class="text-sm text-gray-500">{{ t('students.nisn') }}: {{ student.nisn || '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ new Date(student.created_at).toLocaleDateString() }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </Link>
                        </div>

                        <!-- Pagination - Touch friendly -->
                        <div v-if="students.links && students.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex flex-wrap justify-center gap-2">
                                <Link
                                    v-for="link in students.links"
                                    :key="link.label"
                                    :href="link.url"
                                    v-html="link.label"
                                    class="px-4 py-2 min-h-[44px] min-w-[44px] flex items-center justify-center rounded"
                                    :class="{
                                        'bg-indigo-600 text-white': link.active,
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200 active:bg-gray-300': !link.active && link.url,
                                        'text-gray-400 cursor-not-allowed': !link.url,
                                    }"
                                />
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
