<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const locale = computed(() => page.props.locale);
const t = computed(() => page.props.translations);

const languages = [
    { code: 'en', name: 'English', flag: '🇬🇧' },
    { code: 'id', name: 'Bahasa Indonesia', flag: '🇮🇩' },
];

const currentLanguage = computed(() =>
    languages.find(lang => lang.code === locale.value) || languages[0]
);
</script>

<template>
    <Dropdown align="right" width="48">
        <template #trigger>
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
            >
                <span class="text-lg">{{ currentLanguage.flag }}</span>
                <span class="hidden sm:inline">{{ currentLanguage.name }}</span>
                <svg
                    class="ms-1 -me-0.5 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </template>

        <template #content>
            <div class="py-1">
                <a
                    v-for="lang in languages"
                    :key="lang.code"
                    :href="route('language.switch', lang.code)"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    :class="{ 'bg-gray-50 font-semibold': lang.code === locale }"
                >
                    <span class="text-lg">{{ lang.flag }}</span>
                    <span>{{ lang.name }}</span>
                    <svg
                        v-if="lang.code === locale"
                        class="ml-auto h-4 w-4 text-indigo-600"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </a>
            </div>
        </template>
    </Dropdown>
</template>
