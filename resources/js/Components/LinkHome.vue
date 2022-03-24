<template>
    <Link
        class="inline-flex items-center px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
    >
        <slot />
    </Link>
</template>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
</script>
