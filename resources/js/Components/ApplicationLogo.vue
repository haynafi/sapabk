<script setup>
defineProps({
    showText: {
        type: Boolean,
        default: true,
    },
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
});

const sizeClasses = {
    sm: { icon: 'h-6 w-6', text: 'text-base' },
    md: { icon: 'h-8 w-8', text: 'text-xl' },
    lg: { icon: 'h-10 w-10', text: 'text-2xl' },
};
</script>

<template>
    <div class="flex items-center gap-2">
        <img
            src="/icons/icon-72x72.png"
            alt="SAPA BK Logo"
            :class="[sizeClasses[size].icon, 'rounded-lg']"
        />
        <span
            v-if="showText"
            :class="[sizeClasses[size].text, 'font-bold text-indigo-600 tracking-tight']"
        >
            SAPA BK
        </span>
    </div>
</template>
