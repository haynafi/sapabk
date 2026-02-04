<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    students: Object,
    schools: Array,
    classrooms: Array,
    filters: Object,
});

const page = usePage();
const search = ref(props.filters.search || '');
const selectedSchool = ref(props.filters.school_id || '');
const selectedClassroom = ref(props.filters.classroom_id || '');

function applyFilters() {
    router.get(route('admin.students.index'), {
        search: search.value || undefined,
        school_id: selectedSchool.value || undefined,
        classroom_id: selectedClassroom.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function clearFilters() {
    search.value = '';
    selectedSchool.value = '';
    selectedClassroom.value = '';
    router.get(route('admin.students.index'));
}

function deleteStudent(id, name) {
    if (confirm(t('admin.confirm_delete_student', { name }))) {
        router.delete(route('admin.students.destroy', id));
    }
}
</script>

<template>
    <Head :title="t('admin.all_students')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('admin.dashboard')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('admin.all_students') }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="page.props.flash?.success" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ page.props.flash.success }}
                </div>
                <div v-if="page.props.flash?.error" class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ page.props.flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <!-- Filters -->
                        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <input
                                    v-model="search"
                                    type="text"
                                    :placeholder="t('admin.search_name_nisn')"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                            <div>
                                <select
                                    v-model="selectedSchool"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">{{ t('admin.all_schools') }}</option>
                                    <option v-for="school in schools" :key="school.id" :value="school.id">
                                        {{ school.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <select
                                    v-model="selectedClassroom"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">{{ t('students.all_classrooms') }}</option>
                                    <option v-for="classroom in classrooms" :key="classroom.id" :value="classroom.id">
                                        {{ classroom.name }} ({{ classroom.school?.name || '-' }})
                                    </option>
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    @click="applyFilters"
                                    class="flex-1 px-4 py-2 min-h-[44px] bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                >
                                    {{ t('common.filter') }}
                                </button>
                                <button
                                    @click="clearFilters"
                                    class="flex-1 px-4 py-2 min-h-[44px] bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                                >
                                    {{ t('common.clear') }}
                                </button>
                            </div>
                        </div>

                        <div v-if="students.data.length === 0" class="text-center py-8 text-gray-500">
                            {{ t('students.no_students') }}
                        </div>

                        <div v-else class="overflow-x-auto">
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
                                            {{ t('students.classroom') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('admin.school') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('students.registered_at') }}
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('common.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="student in students.data" :key="student.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div v-if="student.photo_url" class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" :src="student.photo_url" :alt="student.name" />
                                                </div>
                                                <div v-else class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-500 font-medium text-sm">{{ student.name.charAt(0).toUpperCase() }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="text-sm text-gray-600">{{ student.nisn || '-' }}</code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-500">{{ student.classroom?.name || '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-500">{{ student.classroom?.school?.name || '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-500">{{ new Date(student.created_at).toLocaleDateString() }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('admin.students.show', student.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                {{ t('common.view') }}
                                            </Link>
                                            <button
                                                @click="deleteStudent(student.id, student.name)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                {{ t('common.delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
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
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active && link.url,
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
