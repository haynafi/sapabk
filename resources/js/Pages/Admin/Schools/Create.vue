<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const form = useForm({
    name: '',
    slug: '',
});

// Auto-generate slug from name
watch(() => form.name, (name) => {
    form.slug = name
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .substring(0, 100);
});

const submit = () => {
    form.post(route('admin.schools.store'));
};
</script>

<template>
    <Head :title="t('admin.create_school')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('admin.schools.index')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('admin.create_school') }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form @submit.prevent="submit" class="p-4 sm:p-6 space-y-6">
                        <div>
                            <InputLabel for="name" :value="t('admin.school_name')" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                :placeholder="t('admin.school_name_placeholder')"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="slug" :value="t('admin.slug')" />
                            <TextInput
                                id="slug"
                                type="text"
                                class="mt-1 block w-full font-mono"
                                v-model="form.slug"
                                required
                                :placeholder="t('admin.slug_placeholder')"
                            />
                            <p class="mt-1 text-sm text-gray-500">{{ t('admin.slug_hint') }}</p>
                            <InputError class="mt-2" :message="form.errors.slug" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link
                                :href="route('admin.schools.index')"
                                class="text-gray-600 hover:text-gray-900 py-2"
                            >
                                {{ t('common.cancel') }}
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ t('common.create') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
