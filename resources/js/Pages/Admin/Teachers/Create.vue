<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    schools: Array,
});

const form = useForm({
    name: '',
    nip: '',
    email: '',
    password: '',
    school_id: '',
    must_change_password: true,
});

const submit = () => {
    form.post(route('admin.teachers.store'));
};
</script>

<template>
    <Head :title="t('admin.create_teacher')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <Link
                    :href="route('admin.teachers.index')"
                    class="text-gray-500 hover:text-gray-700 mr-4 p-2 -ml-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ t('admin.create_teacher') }}
                </h2>
            </div>
        </template>

        <div class="py-6 sm:py-12">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <form @submit.prevent="submit" class="p-4 sm:p-6 space-y-6">
                        <div>
                            <InputLabel for="school_id" :value="t('admin.school')" />
                            <select
                                id="school_id"
                                v-model="form.school_id"
                                class="mt-1 block w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="">{{ t('admin.select_school') }}</option>
                                <option v-for="school in schools" :key="school.id" :value="school.id">
                                    {{ school.name }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.school_id" />
                        </div>

                        <div>
                            <InputLabel for="name" :value="t('auth.name')" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="nip" :value="t('auth.nip')" />
                            <TextInput
                                id="nip"
                                type="text"
                                class="mt-1 block w-full font-mono"
                                v-model="form.nip"
                                required
                                :placeholder="t('admin.nip_placeholder')"
                            />
                            <InputError class="mt-2" :message="form.errors.nip" />
                        </div>

                        <div>
                            <InputLabel for="email" :value="t('auth.email_optional')" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" :value="t('auth.password')" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="block">
                            <label class="flex items-center min-h-[44px]">
                                <Checkbox v-model:checked="form.must_change_password" />
                                <span class="ms-2 text-sm text-gray-600">{{ t('admin.force_password_change') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link
                                :href="route('admin.teachers.index')"
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
