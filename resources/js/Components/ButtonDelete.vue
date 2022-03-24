<template>
    <Link
        :href="link"
        class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-700 focus:ring-red-500 text-white inline-flex justify-center py-1 px-1 mx-1 border border-transparent shadow-sm text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
        title="Delete"
        as="button"
        method="DELETE"
        type="button"
    >
        <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>
        <slot />
    </Link>
</template>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

defineProps({
    link: {
        type: String,
        required: true,
    },
});
</script>
