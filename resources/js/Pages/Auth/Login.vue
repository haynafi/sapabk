<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t, locale } = useTranslations();

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    nip: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.login')" />

        <!-- Language Switcher for Guest -->
        <div class="mb-4 flex justify-center gap-2">
            <a
                :href="route('language.switch', 'en')"
                class="rounded px-3 py-1 text-sm"
                :class="locale === 'en' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
            >
                English
            </a>
            <span class="text-gray-300">|</span>
            <a
                :href="route('language.switch', 'id')"
                class="rounded px-3 py-1 text-sm"
                :class="locale === 'id' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
            >
                Indonesia
            </a>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="nip" :value="t('auth.nip')" />

                <TextInput
                    id="nip"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.nip"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.nip" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="t('auth.password')" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ t('auth.remember_me') }}</span>
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.login') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
