<template>
    <admin-layout title="Manage Season">
        <template #header> Manage Season </template>
        <section class="text-gray-700 body-font">
            <div class="container mx-auto">
                <div class="w-full flex justify-start">
                    <div class="flex items-center">
                        <div class="relative rounded-l-md shadow-sm">
                            <input
                                v-model="season_number"
                                placeholder="Season number"
                                class="px-3 py-2 border border-gray-400 rounded"
                            />
                        </div>
                    </div>
                    <div>
                        <ButtonGenerate @click="generateSeason" />
                    </div>
                </div>
                <div class="my-5 flex sm:flex-row flex-col">
                    <div class="flex flex-row mb-1 sm:mb-0">
                        <div class="relative">
                            <select
                                v-model="perPage"
                                @change="getData"
                                class="h-full block w-full border border-gray-500 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600"
                                placeholder="Per Page"
                            >
                                <option value="5">05 Per Page</option>
                                <option value="10">10 Per Page</option>
                                <option value="15">15 Per Page</option>
                                <option value="30">30 Per Page</option>
                            </select>
                        </div>
                    </div>
                    <DivSearch>
                        <input
                            v-model="search"
                            class="block pl-8 pr-6 py-2 w-full bg-white text-gray-900 focus:bg-white focus:placeholder-gray-600 focus:text-gray-900 focus:outline-none focus:border-gray-600"
                            placeholder="Search here ..."
                            type="text"
                        />
                    </DivSearch>
                </div>
                <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md">
                    <Table>
                        <template #tableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Slug</TableHead>
                            <TableHead class="text-center">Poster</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </template>
                        <TableRow
                            v-for="season in seasons.data"
                            :key="season.id"
                        >
                            <TableData>{{ season.name }}</TableData>
                            <TableData>{{ season.slug }}</TableData>
                            <TableData
                                class="flex text-center justify-center items-center"
                            >
                                <img
                                    class="h-12 w-12 rounded"
                                    :src="`https://www.themoviedb.org/t/p/w220_and_h330_face/${season.poster_path}`"
                                />
                            </TableData>
                            <TableData class="text-center">
                                <ButtonOption
                                    :link="
                                        route('admin.episodes.index', [
                                            tvShow.id,
                                            season.id,
                                        ])
                                    "
                                />
                                <ButtonEdit
                                    :link="
                                        route('admin.seasons.edit', [
                                            tvShow.id,
                                            season.id,
                                        ])
                                    "
                                />
                                <ButtonDelete
                                    :link="
                                        route('admin.seasons.destroy', [
                                            tvShow.id,
                                            season.id,
                                        ])
                                    "
                                />
                            </TableData>
                        </TableRow>
                    </Table>
                    <div class="px-3 py-2 border" v-if="seasons">
                        <Pagination :links="seasons.links" />
                    </div>
                </div>
            </div>
        </section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { defineProps, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import TableData from "@/Components/TableData.vue";
import Table from "@/Components/Table.vue";
import TableHead from "@/Components/TableHead.vue";
import TableRow from "@/Components/TableRow.vue";
import ButtonEdit from "@/Components/ButtonEdit.vue";
import ButtonDelete from "@/Components/ButtonDelete.vue";
import ButtonGenerate from "@/Components/ButtonGenerate.vue";
import ButtonOption from "@/Components/ButtonOption.vue";
import InputGenerate from "@/Components/InputGenerate.vue";
import DivSearch from "@/Components/DivSearch.vue";

const props = defineProps({
    tvShow: Object,
    seasons: Object,
    filters: Object,
});
const search = ref(props.filters.search);
const perPage = ref(props.filters.perPage);
const season_number = ref("");
watch(search, (value) => {
    Inertia.get(
        `/admin/tv-shows/${props.tvShow.id}/seasons`,
        { search: value, perPage: perPage.value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

function getData() {
    Inertia.get(
        `/admin/tv-shows/${props.tvShow.id}/seasons`,
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function generateSeason() {
    Inertia.post(
        `/admin/tv-shows/${props.tvShow.id}/seasons`,
        { season_number: season_number.value },
        {
            onFinish: () => {
                season_number.value = "";
            },
        }
    );
}
</script>
<style></style>
