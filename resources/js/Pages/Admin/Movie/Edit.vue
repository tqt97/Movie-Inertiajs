<template>
    <admin-layout title="Dashboard">
        <Head title="Edit Movie" /> 
        <template #header> Edit Movie </template>
        <SectionEditPage>
            <template #nav>
                <LinkHome :href="route('admin.movies.index')">
                    <IconHome />Movie index
                </LinkHome>
            </template>
            <form @submit.prevent="updateMovie">
                <DivFormEdit>
                    <DivCol12>
                        <jet-label for="title" value="Title" />
                        <jet-input
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.title"
                        >
                            {{ form.errors.title }}
                        </div>
                    </DivCol12>
                    <DivCol4>
                        <jet-label for="runtime" value="Runtime" />
                        <jet-input
                            id="runtime"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.runtime"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.runtime"
                        >
                            {{ form.errors.runtime }}
                        </div>
                    </DivCol4>
                    <DivCol4>
                        <jet-label for="lang" value="Language" />
                        <jet-input
                            id="lang"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.lang"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.lang"
                        >
                            {{ form.errors.lang }}
                        </div>
                    </DivCol4>
                    <DivCol4>
                        <jet-label for="video_format" value="Video format" />
                        <jet-input
                            id="video_format"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.video_format"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.video_format"
                        >
                            {{ form.errors.video_format }}
                        </div>
                    </DivCol4>
                    <DivCol6>
                        <jet-label for="poster_path" value="Poster path" />
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
                    </DivCol6>
                    <DivCol6>
                        <jet-label for="backdrop_path" value="Backdrop path" />
                        <jet-input
                            id="backdrop_path"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.backdrop_path"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.backdrop_path"
                        >
                            {{ form.errors.backdrop_path }}
                        </div>
                    </DivCol6>
                    <DivCol12>
                        <jet-label for="overview" value="Overview" />
                        <textarea
                            id="overview"
                            type="text"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            v-model="form.overview"
                        ></textarea>
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.overview"
                        >
                            {{ form.errors.overview }}
                        </div>
                    </DivCol12>
                    <DivCol12>
                        <label class="flex items-center">
                            <jet-checkbox
                                name="is_public"
                                v-model:checked="form.is_public"
                            />
                            <span class="ml-2 text-md font-medium text-gray-600"
                                >Public</span
                            >
                        </label>
                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.is_public"
                        >
                            {{ form.errors.is_public }}
                        </div>
                    </DivCol12>
                </DivFormEdit>
            </form>
        </SectionEditPage>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import SectionEditPage from "@/components/SectionEditPage.vue";
import DivFormEdit from "@/components/DivFormEdit.vue";
import DivCol12 from "@/components/DivCol12.vue";
import DivCol6 from "@/components/DivCol6.vue";
import DivCol4 from "@/components/DivCol4.vue";
import IconHome from "@/components/IconHome.vue";
import LinkHome from "@/components/LinkHome.vue";

const props = defineProps({
    movie: Object,
});

const form = useForm({
    title: props.movie.title,
    poster_path: props.movie.poster_path,
    video_format: props.movie.video_format,
    runtime: props.movie.runtime,
    rating: props.movie.rating,
    backdrop_path: props.movie.backdrop_path,
    overview: props.movie.overview,
    is_public: props.movie.is_public ? true : false,
    lang: props.movie.lang,
});
function updateMovie() {
    form.put("/admin/movies/" + props.movie.id);
}
</script>

<style></style>
