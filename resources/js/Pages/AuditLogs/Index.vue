<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    logs: Object,
    filters: Object,
});

const localFilters = ref({
    action: props.filters.action || '',
    subject_type: props.filters.subject_type || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
});

const showFilters = ref(false);

function applyFilters() {
    const params = {};
    if (localFilters.value.action) params.action = localFilters.value.action;
    if (localFilters.value.subject_type) params.subject_type = localFilters.value.subject_type;
    if (localFilters.value.date_from) params.date_from = localFilters.value.date_from;
    if (localFilters.value.date_to) params.date_to = localFilters.value.date_to;

    router.get(route('audit-logs.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
}

function clearFilters() {
    localFilters.value = {
        action: '',
        subject_type: '',
        date_from: '',
        date_to: '',
    };
    router.get(route('audit-logs.index'));
}

const actionColors = {
    viewed: 'bg-blue-100 text-blue-800',
    created: 'bg-green-100 text-green-800',
    updated: 'bg-yellow-100 text-yellow-800',
    deleted: 'bg-red-100 text-red-800',
    submitted: 'bg-purple-100 text-purple-800',
};
</script>

<template>
    <Head :title="t('nav.activity_logs')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ t('nav.activity_logs') }}
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <!-- Mobile Filter Toggle -->
                        <button
                            @click="showFilters = !showFilters"
                            class="sm:hidden w-full flex items-center justify-between px-4 py-3 min-h-[44px] bg-gray-100 rounded-md mb-4"
                        >
                            <span class="font-medium text-gray-700">{{ t('common.filters') }}</span>
                            <svg
                                class="w-5 h-5 text-gray-500 transition-transform"
                                :class="{ 'rotate-180': showFilters }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Filters - Collapsible on mobile -->
                        <div
                            class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4"
                            :class="{ 'hidden sm:grid': !showFilters }"
                        >
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('audit.action') }}</label>
                                <select
                                    v-model="localFilters.action"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">{{ t('audit.all_actions') }}</option>
                                    <option value="viewed">{{ t('audit.viewed') }}</option>
                                    <option value="created">{{ t('audit.created') }}</option>
                                    <option value="updated">{{ t('audit.updated') }}</option>
                                    <option value="deleted">{{ t('audit.deleted') }}</option>
                                    <option value="submitted">{{ t('audit.submitted') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('audit.subject_type') }}</label>
                                <select
                                    v-model="localFilters.subject_type"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">{{ t('audit.all_types') }}</option>
                                    <option value="Student">{{ t('nav.students') }}</option>
                                    <option value="Classroom">{{ t('nav.classrooms') }}</option>
                                    <option value="QuizTemplate">{{ t('nav.quiz_templates') }}</option>
                                    <option value="QuizResponse">{{ t('audit.quiz_response') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('audit.from_date') }}</label>
                                <input
                                    type="date"
                                    v-model="localFilters.date_from"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('audit.to_date') }}</label>
                                <input
                                    type="date"
                                    v-model="localFilters.date_to"
                                    class="w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <div class="flex items-end gap-2">
                                <button
                                    @click="applyFilters"
                                    class="flex-1 px-4 py-3 min-h-[44px] bg-indigo-600 text-white rounded-md hover:bg-indigo-700 active:bg-indigo-800"
                                >
                                    {{ t('common.filter') }}
                                </button>
                                <button
                                    @click="clearFilters"
                                    class="flex-1 px-4 py-3 min-h-[44px] bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 active:bg-gray-300"
                                >
                                    {{ t('common.clear') }}
                                </button>
                            </div>
                        </div>

                        <div v-if="logs.data.length === 0" class="text-center py-8 text-gray-500">
                            {{ t('audit.no_logs') }}
                        </div>

                        <!-- Desktop Table View -->
                        <div v-else class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('audit.timestamp') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('audit.user') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('audit.action') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('audit.subject') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ t('audit.description') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="log in logs.data" :key="log.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(log.created_at).toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ log.user_name }}</div>
                                            <div v-if="log.metadata?.ip_address" class="text-xs text-gray-400">
                                                {{ log.metadata.ip_address }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                                :class="actionColors[log.action] || 'bg-gray-100 text-gray-800'"
                                            >
                                                {{ log.action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ log.subject_type }}</div>
                                            <div v-if="log.subject_name" class="text-xs text-gray-500">
                                                {{ log.subject_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-md truncate">
                                            {{ log.description }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div v-if="logs.data.length > 0" class="md:hidden space-y-4">
                            <div
                                v-for="log in logs.data"
                                :key="log.id"
                                class="bg-gray-50 rounded-lg p-4 space-y-3"
                            >
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                                        :class="actionColors[log.action] || 'bg-gray-100 text-gray-800'"
                                    >
                                        {{ log.action }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ new Date(log.created_at).toLocaleString() }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ log.subject_type }}</div>
                                    <div v-if="log.subject_name" class="text-sm text-gray-600">{{ log.subject_name }}</div>
                                </div>
                                <div class="text-sm text-gray-500">{{ log.description }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ t('audit.by') }}: {{ log.user_name }}
                                </div>
                            </div>
                        </div>

                        <!-- Pagination - Touch friendly -->
                        <div v-if="logs.links && logs.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex flex-wrap justify-center gap-2">
                                <Link
                                    v-for="link in logs.links"
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
