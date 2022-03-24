<template>
    <div class="px-3 py-2">
        <div class="flex mt-1 justify-end">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    v-html="link.label"
                    class="mb-1 mr-1 px-3 py-2 text-gray-400 text-sm leading-4 border rounded"
                ></div>
                <Link
                    :key="key"
                    v-else
                    :href="link.url"
                    v-html="link.label"
                    class="mb-1 mr-1 px-3 py-2 text-sm leading-4 border rounded-md focus:border-indigo-500 hover:bg-gray-700 hover:text-white"
                    :class="{ 'bg-gray-700 text-white': link.active }"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

defineProps({
    links: Array,
});
</script>

<style></style>
