<template>
    <Link
        :href="link"
        class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-700 focus:ring-blue-500 text-white inline-flex justify-center py-1 px-1 mx-1 border border-transparent shadow-sm text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
        title="Edit"
    >
        <svg
            class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <slot/>
    </Link>
</template>
<script setup>
import {Link} from "@inertiajs/inertia-vue3";
import {defineProps} from "vue";

defineProps({
    link: {
        type: String,
        required: true,
    },
});
</script>
