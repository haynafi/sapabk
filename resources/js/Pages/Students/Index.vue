<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    students: Object,
    classrooms: Array,
    filters: Object,
});

const selectedClassroom = ref(props.filters.classroom_id || '');

watch(selectedClassroom, (value) => {
    router.get(route('students.index'), { classroom_id: value || undefined }, {
        preserveState: true,
        preserveScroll: true,
    });
});
</script>

<template>
    <Head :title="t('students.title')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ t('students.title') }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <!-- Filters -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('students.filter_classroom') }}</label>
                            <select
                                v-model="selectedClassroom"
                                class="w-full md:w-64 min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">{{ t('students.all_classrooms') }}</option>
                                <option v-for="classroom in classrooms" :key="classroom.id" :value="classroom.id">
                                    {{ classroom.name }}
                                </option>
                            </select>
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
                                            {{ t('students.joined') }}
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
                                            <div class="text-sm text-gray-500">{{ student.classroom?.name }}</div>
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
                                                {{ t('common.view') }}
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="students.links && students.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex space-x-2">
                                <Link
                                    v-for="link in students.links"
                                    :key="link.label"
                                    :href="link.url"
                                    v-html="link.label"
                                    class="px-3 py-1 rounded"
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
