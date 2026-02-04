<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();
const showingNavigationDropdown = ref(false);
const page = usePage();

const isSuperAdmin = computed(() => page.props.auth.user.role === 'super_admin');
const dashboardRoute = computed(() => isSuperAdmin.value ? 'admin.dashboard' : 'dashboard');
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="border-b border-gray-100 bg-white"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route(dashboardRoute)">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links - Super Admin -->
                            <div
                                v-if="isSuperAdmin"
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                >
                                    {{ t('admin.dashboard') }}
                                </NavLink>
                                <NavLink
                                    :href="route('admin.schools.index')"
                                    :active="route().current('admin.schools.*')"
                                >
                                    {{ t('admin.schools') }}
                                </NavLink>
                                <NavLink
                                    :href="route('admin.teachers.index')"
                                    :active="route().current('admin.teachers.*')"
                                >
                                    {{ t('admin.teachers') }}
                                </NavLink>
                                <NavLink
                                    :href="route('admin.students.index')"
                                    :active="route().current('admin.students.*')"
                                >
                                    {{ t('nav.students') }}
                                </NavLink>
                                <NavLink
                                    :href="route('admin.audit-logs')"
                                    :active="route().current('admin.audit-logs')"
                                >
                                    {{ t('nav.activity_logs') }}
                                </NavLink>
                            </div>

                            <!-- Navigation Links - Teacher -->
                            <div
                                v-else
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    {{ t('nav.dashboard') }}
                                </NavLink>
                                <NavLink
                                    :href="route('classrooms.index')"
                                    :active="route().current('classrooms.*')"
                                >
                                    {{ t('nav.classrooms') }}
                                </NavLink>
                                <NavLink
                                    :href="route('students.index')"
                                    :active="route().current('students.*')"
                                >
                                    {{ t('nav.students') }}
                                </NavLink>
                                <NavLink
                                    :href="route('quiz-templates.index')"
                                    :active="route().current('quiz-templates.*')"
                                >
                                    {{ t('nav.quiz_templates') }}
                                </NavLink>
                                <NavLink
                                    :href="route('audit-logs.index')"
                                    :active="route().current('audit-logs.*')"
                                >
                                    {{ t('nav.activity_logs') }}
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center gap-2">
                            <!-- Language Switcher -->
                            <LanguageSwitcher />
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
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
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            {{ t('nav.profile') }}
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            {{ t('nav.logout') }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger - 44px touch target -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-3 min-w-[44px] min-h-[44px] text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none active:bg-gray-200"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <!-- Super Admin Mobile Nav -->
                    <div v-if="isSuperAdmin" class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('admin.dashboard')"
                            :active="route().current('admin.dashboard')"
                        >
                            {{ t('admin.dashboard') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('admin.schools.index')"
                            :active="route().current('admin.schools.*')"
                        >
                            {{ t('admin.schools') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('admin.teachers.index')"
                            :active="route().current('admin.teachers.*')"
                        >
                            {{ t('admin.teachers') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('admin.students.index')"
                            :active="route().current('admin.students.*')"
                        >
                            {{ t('nav.students') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('admin.audit-logs')"
                            :active="route().current('admin.audit-logs')"
                        >
                            {{ t('nav.activity_logs') }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- Teacher Mobile Nav -->
                    <div v-else class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            {{ t('nav.dashboard') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('classrooms.index')"
                            :active="route().current('classrooms.*')"
                        >
                            {{ t('nav.classrooms') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('students.index')"
                            :active="route().current('students.*')"
                        >
                            {{ t('nav.students') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('quiz-templates.index')"
                            :active="route().current('quiz-templates.*')"
                        >
                            {{ t('nav.quiz_templates') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('audit-logs.index')"
                            :active="route().current('audit-logs.*')"
                        >
                            {{ t('nav.activity_logs') }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                NIP: {{ $page.props.auth.user.nip }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Mobile Language Switcher -->
                            <div class="px-4 py-2">
                                <div class="flex gap-2">
                                    <a
                                        :href="route('language.switch', 'en')"
                                        class="flex-1 rounded-md px-3 py-2 text-center text-sm"
                                        :class="$page.props.locale === 'en' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'bg-gray-100 text-gray-600'"
                                    >
                                        English
                                    </a>
                                    <a
                                        :href="route('language.switch', 'id')"
                                        class="flex-1 rounded-md px-3 py-2 text-center text-sm"
                                        :class="$page.props.locale === 'id' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'bg-gray-100 text-gray-600'"
                                    >
                                        Indonesia
                                    </a>
                                </div>
                            </div>
                            <ResponsiveNavLink :href="route('profile.edit')">
                                {{ t('nav.profile') }}
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                {{ t('nav.logout') }}
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
