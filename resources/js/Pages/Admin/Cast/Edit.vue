<template>
    <admin-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit cast
            </h2>
        </template>
        <section class="text-gray-700 body-font">
            <div class="container mx-auto">
                <div class="flex flex-row mb-1 sm:mb-3">
                    <Link
                        :href="route('admin.casts.index')"
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
                        &nbsp;Cast index
                    </Link>
                </div>
                <section class="w-full mb-8 overflow-hidden rounded-lg shadow-md p-5">
                    <form @submit.prevent="updateCast">
                        <div>
                            <jet-label for="name" value="Name" />
                            <jet-input
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autofocus
                                autocomplete="name"
                            />
                            <div
                                class="text-sm text-red-400"
                                v-if="form.errors.name"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="mt-4">
                            <jet-label for="poster_path" value="Poster" />
                            <jet-input
                                id="poster_path"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.poster_path"
                            />
                            <div
                                class="text-sm text-red-400"
                                v-if="form.errors.poster_path"
                            >
                                {{ form.errors.poster_path }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <jet-button
                                class="ml-4"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Update
                            </jet-button>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";

const props = defineProps({
    cast: Object,
});

const form = useForm({
    name: props.cast.name,
    poster_path: props.cast.poster_path,
});
function updateCast() {
    form.put("/admin/casts/" + props.cast.id);
}
</script>

<style></style>
