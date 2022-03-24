<template>
    <admin-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit genre
            </h2>
        </template>
        <section class="text-gray-700 body-font">
            <div class="container mx-auto">
                <div class="flex flex-row mb-1 sm:mb-3">
                    <Link
                        :href="route('admin.genres.index')"
                        class="inline-flex items-center px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        &nbsp;Genre index
                    </Link>
                </div>
                <section class="w-full">
                    <form @submit.prevent="updateGenre">
                        <div
                            class="shadow overflow-hidden sm:rounded-md w-full"
                        >
                            <div class="px-2 py-2 bg-white sm:p-6 space-y-5">
                                <div class="mb-3">
                                    <label
                                        class="block text-lg font-medium text-black"
                                    >Title
                                    </label>
                                    <input
                                        v-model="form.title"
                                        type="text"
                                        class="mt-1 focus:ring-indigo-200 focus:border-indigo-300 block w-full shadow-sm sm:text-md border-gray-300 rounded-md"
                                    />
                                </div>
                                <div class="mb-3">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center px-2 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition"
                                        title="Update"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"
                                            />
                                        </svg>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {Link, useForm} from "@inertiajs/inertia-vue3";
import {defineProps} from "vue";

const props = defineProps({
    genre: Object,
});

const form = useForm({
    title: props.genre.title,
});

function updateGenre() {
    form.put("/admin/genres/" + props.genre.id);
}
</script>

<style></style>
