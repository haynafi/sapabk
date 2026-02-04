<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

defineProps({
    classrooms: Object,
});

function deleteClassroom(id) {
    if (confirm(t('classrooms.confirm_delete'))) {
        router.delete(route('classrooms.destroy', id));
    }
}
</script>

<template>
    <Head :title="t('classrooms.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('classrooms.title') }}
                </h2>
                <Link
                    :href="route('classrooms.create')"
                    class="inline-flex items-center px-4 py-2 min-h-[44px] bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                >
                    {{ t('classrooms.create_new') }}
                </Link>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <div v-if="classrooms.data.length === 0" class="text-center py-8 text-gray-500">
                            {{ t('classrooms.no_classrooms') }}
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            <div
                                v-for="classroom in classrooms.data"
                                :key="classroom.id"
                                class="border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow"
                            >
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ classroom.name }}</h3>
                                        <p class="text-sm text-gray-500">{{ t('classrooms.code_label') }}: {{ classroom.unit_code }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ classroom.students_count }} {{ t('classrooms.students_count') }}
                                    </span>
                                </div>

                                <div v-if="classroom.quiz_template" class="mb-4">
                                    <span class="text-xs text-gray-500">{{ t('classrooms.quiz_label') }}:</span>
                                    <p class="text-sm text-gray-700">{{ classroom.quiz_template.title }}</p>
                                </div>
                                <div v-else class="mb-4">
                                    <span class="text-xs text-gray-400 italic">{{ t('classrooms.no_quiz') }}</span>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <Link
                                        :href="route('classrooms.show', classroom.id)"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium py-2"
                                    >
                                        {{ t('classrooms.view_details') }}
                                    </Link>
                                    <div class="space-x-2">
                                        <Link
                                            :href="route('classrooms.edit', classroom.id)"
                                            class="text-gray-600 hover:text-gray-900 text-sm py-2"
                                        >
                                            {{ t('common.edit') }}
                                        </Link>
                                        <button
                                            @click="deleteClassroom(classroom.id)"
                                            class="text-red-600 hover:text-red-900 text-sm py-2"
                                        >
                                            {{ t('common.delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="classrooms.links && classrooms.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex space-x-2">
                                <Link
                                    v-for="link in classrooms.links"
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
