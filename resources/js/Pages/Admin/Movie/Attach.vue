<template>
    <admin-layout title="Dashboard">
        <Head title="Attach Movie" />
        <template #header> Movie Attach </template>
        <SectionAttachPage>
            <template #nav>
                <LinkHome :href="route('admin.movies.index')">
                    <IconHome />Movie index
                </LinkHome>
            </template>

            <template #trailer>
                <div class="flex space-x-2">
                    <div v-for="trailer in trailers" :key="trailer.id">
                        <Link
                            class="px-4 py-2 bg-red-500 hover:bg-red-700 rounded"
                            :href="route('admin.trailers.destroy', trailer.id)"
                            method="delete"
                            as="button"
                            type="button"
                        >
                            {{ trailer.name }}
                        </Link>
                    </div>
                </div>
                <form @submit.prevent="submitTrailer">
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
                        <jet-label for="embed_html" value="Embed HTML" />
                        <textarea
                            id="embed_html"
                            type="text"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            v-model="form.embed_html"
                        ></textarea>

                        <div
                            class="text-sm text-red-400"
                            v-if="form.errors.embed_html"
                        >
                            {{ form.errors.embed_html }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <jet-button
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            add trailer
                        </jet-button>
                    </div>
                </form>
            </template>
            <template #download>
                <div class="flex space-x-2">
                    <div v-for="download in downloads" :key="download.id">
                        <Link
                            class="px-4 py-2 bg-red-500 hover:bg-red-700 rounded"
                            :href="
                                route('admin.downloads.destroy', download.id)
                            "
                            method="delete"
                            as="button"
                            type="button"
                        >
                            {{ download.name }}
                        </Link>
                    </div>
                </div>
                <form @submit.prevent="submitDownload">
                    <div>
                        <jet-label for="name" value="Name" />
                        <jet-input
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formDownload.name"
                            autofocus
                            autocomplete="name"
                        />
                        <div
                            class="text-sm text-red-400"
                            v-if="formDownload.errors.name"
                        >
                            {{ formDownload.errors.name }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <jet-label for="web_url" value="Url" />
                        <jet-input
                            id="web_url"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="formDownload.web_url"
                        />

                        <div
                            class="text-sm text-red-400"
                            v-if="formDownload.errors.web_url"
                        >
                            {{ formDownload.errors.web_url }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <jet-button
                            class="ml-4"
                            :class="{
                                'opacity-25': formDownload.processing,
                            }"
                            :disabled="formDownload.processing"
                        >
                            add download
                        </jet-button>
                    </div>
                </form>
            </template>
            <template #cast>
                <div class="flex space-x-2">
                    <div
                        class="m-2 p-1 text-sm"
                        v-for="mc in movieCasts"
                        :key="mc.id"
                    >
                        {{ mc.name }}
                    </div>
                </div>
                <form @submit.prevent="addCast">
                    <VueMultiselect
                        v-model="castForm.casts"
                        :options="casts"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Add Casts"
                        label="name"
                        track-by="name"
                    ></VueMultiselect>
                    <div class="flex mt-4 justify-end">
                        <JetButton>add casts</JetButton>
                    </div>
                </form>
            </template>
            <template #tag>
                <div class="flex space-x-2">
                    <div
                        class="m-2 p-1 text-sm"
                        v-for="mt in movieTags"
                        :key="mt.id"
                    >
                        {{ mt.tag_name }}
                    </div>
                </div>
                <form @submit.prevent="addTag">
                    <VueMultiselect
                        v-model="tagForm.tags"
                        :options="tags"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Add tags"
                        label="tag_name"
                        track-by="tag_name"
                    >
                    </VueMultiselect>
                    <div class="mt-2 flex justify-end">
                        <JetButton>add tags</JetButton>
                    </div>
                </form>
            </template>
        </SectionAttachPage>
    </admin-layout>
</template>

<script setup>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import VueMultiselect from "vue-multiselect";
import LinkHome from "@/components/LinkHome.vue";
import SectionAttachPage from "@/components/SectionAttachPage.vue";

const props = defineProps({
    movie: Object,
    trailers: Array,
    downloads: Array,
    casts: Array,
    tags: Array,
    movieTags: Array,
    movieCasts: Array,
});
const value = ref("");
const form = useForm({
    name: "",
    embed_html: "",
});
const formDownload = useForm({
    name: "",
    web_url: "",
});
const castForm = useForm({
    casts: props.movieCasts,
});
const tagForm = useForm({
    tags: props.movieTags,
});
function submitTrailer() {
    form.post(`/admin/movies/${props.movie.id}/add-trailer`, {
        onSuccess: () => form.reset(),
    });
}
function submitDownload() {
    formDownload.post(`/admin/movies/${props.movie.id}/add-download`, {
        onSuccess: () => formDownload.reset(),
    });
}
function addCast() {
    castForm.post(`/admin/movies/${props.movie.id}/add-casts`, {
        preserveState: true,
        preserveScroll: true,
    });
}
function addTag() {
    tagForm.post(`/admin/movies/${props.movie.id}/add-tags`, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style></style>
