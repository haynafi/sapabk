<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 pb-6 sm:justify-center sm:pt-0 sm:pb-0"
    >
        <div class="flex-shrink-0">
            <Link href="/" class="block">
                <ApplicationLogo class="h-16 w-16 sm:h-20 sm:w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="mt-4 sm:mt-6 w-full overflow-hidden bg-white px-4 sm:px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>

        <!-- Safe area padding for iOS notch/home indicator -->
        <div class="h-safe-area-inset-bottom sm:hidden"></div>
    </div>
</template>
