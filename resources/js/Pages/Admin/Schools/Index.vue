<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    schools: Object,
    filters: Object,
});

const page = usePage();
const search = ref(props.filters.search || '');

function applySearch() {
    router.get(route('admin.schools.index'), { search: search.value || undefined }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function deleteSchool(id, name) {
    if (confirm(t('admin.confirm_delete_school', { name }))) {
        router.delete(route('admin.schools.destroy', id));
    }
}
</script>

<template>
    <Head :title="t('admin.schools')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
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
                        {{ t('admin.schools') }}
                    </h2>
                </div>
                <Link
                    :href="route('admin.schools.create')"
                    class="inline-flex items-center px-4 py-2 min-h-[44px] bg-indigo-600 text-white rounded-md font-semibold text-sm hover:bg-indigo-700"
                >
                    {{ t('admin.create_school') }}
                </Link>
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
                        <!-- Search -->
                        <div class="mb-6">
                            <div class="flex gap-2">
                                <input
                                    v-model="search"
                                    type="text"
                                    :placeholder="t('common.search') + '...'"
                                    class="flex-1 min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    @keyup.enter="applySearch"
                                />
                                <button
                                    @click="applySearch"
                                    class="px-4 py-2 min-h-[44px] bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                >
                                    {{ t('common.search') }}
                                </button>
                            </div>
                        </div>

                        <div v-if="schools.data.length === 0" class="text-center py-8 text-gray-500">
                            {{ t('admin.no_schools') }}
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('admin.school_name') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('admin.slug') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('admin.teachers') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('nav.classrooms') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('nav.students') }}
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('common.actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="school in schools.data" :key="school.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ school.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <code class="text-sm text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ school.slug }}</code>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm text-gray-500">{{ school.users_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm text-gray-500">{{ school.classrooms_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm text-gray-500">{{ school.students_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('admin.schools.edit', school.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                {{ t('common.edit') }}
                                            </Link>
                                            <button
                                                @click="deleteSchool(school.id, school.name)"
                                                class="text-red-600 hover:text-red-900"
                                                :disabled="school.users_count > 0"
                                                :class="{ 'opacity-50 cursor-not-allowed': school.users_count > 0 }"
                                                :title="school.users_count > 0 ? t('admin.cannot_delete_school_with_teachers') : ''"
                                            >
                                                {{ t('common.delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="schools.links && schools.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex flex-wrap justify-center gap-2">
                                <Link
                                    v-for="link in schools.links"
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
