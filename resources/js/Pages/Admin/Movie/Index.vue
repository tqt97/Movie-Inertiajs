<template>
    <admin-layout title="Dashboard">
        <Head title="Manage Movie" />
        <template #header> Manage Movies </template>
        <Section>
            <template #typeID>
                <DivInputId>
                    <input
                        v-model="movie_id"
                        placeholder="Movie ID"
                        class="px-3 py-2 border border-gray-600 rounded-l-md"
                    />
                </DivInputId>
                <ButtonGenerate @click="generateMovie" />
            </template>
            <template #filters>
                <DivPerPage>
                    <select
                        v-model="movieFilters.perPage"
                        @change="movieFilters.perPage === $event.value"
                        class="h-full block w-full border border-r-0 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600 rounded-l-md"
                    >
                        <OptionPerPage />
                    </select>
                </DivPerPage>
                <DivSearch>
                    <input
                        v-model="movieFilters.search"
                        class="block pl-8 pr-6 py-2 w-full bg-white text-gray-900 focus:bg-white focus:placeholder-gray-600 focus:text-gray-900 focus:outline-none focus:border-gray-600 rounded-r-md"
                        placeholder="Search by title ..."
                        type="text"
                    />
                </DivSearch>
            </template>
            <template #table>
                <Table>
                    <template #tableHead>
                        <TableHead @click="sort('title')">
                            <TableHeadSort>
                                <template #title> Title </template>
                                <ArrowDown
                                    v-if="
                                        movieFilters.column == 'title' &&
                                        movieFilters.direction == 'asc'
                                    "
                                />
                                <ArrowUp
                                    v-if="
                                        movieFilters.column == 'title' &&
                                        movieFilters.direction == 'desc'
                                    "
                                />
                            </TableHeadSort>
                        </TableHead>
                        <TableHead class="text-center" @click="sort('rating')">
                            <TableHeadSortCenter>
                                <template #title> Rating </template>
                                <ArrowDown
                                    v-if="
                                        movieFilters.column == 'rating' &&
                                        movieFilters.direction == 'asc'
                                    "
                                />
                                <ArrowUp
                                    v-if="
                                        movieFilters.column == 'rating' &&
                                        movieFilters.direction == 'desc'
                                    "
                                />
                            </TableHeadSortCenter>
                        </TableHead>
                        <TableHead class="text-center">Visit</TableHead>
                        <TableHead class="text-center" @click="sort('runtime')">
                            <TableHeadSortCenter>
                                <template #title> Runtime </template>
                                <ArrowDown
                                    v-if="
                                        movieFilters.column == 'runtime' &&
                                        movieFilters.direction == 'asc'
                                    "
                                />
                                <ArrowUp
                                    v-if="
                                        movieFilters.column == 'runtime' &&
                                        movieFilters.direction == 'desc'
                                    "
                                />
                            </TableHeadSortCenter>
                        </TableHead>
                        <TableHead class="text-center">Poster</TableHead>
                        <TableHead class="text-center">Published</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </template>
                    <TableRow v-for="movie in movies.data" :key="movie.id">
                        <TableData>{{ movie.title }}</TableData>
                        <TableData class="text-center">
                            {{ movie.rating }}
                        </TableData>
                        <TableData class="text-center">
                            {{ movie.visits }}
                        </TableData>
                        <TableData class="text-center">
                            {{ movie.runtime }}
                        </TableData>
                        <TableData
                            class="flex text-center justify-center items-center"
                        >
                            <img
                                class="h-12 w-12 rounded"
                                :src="`https://www.themoviedb.org/t/p/w220_and_h330_face/${movie.poster_path}`"
                            />
                        </TableData>
                        <TableData class="text-center">
                            <Publish v-if="movie.is_public" />
                            <UnPublish v-else />
                        </TableData>
                        <TableData class="text-center">
                            <ButtonOption
                                :link="route('admin.movies.attach', movie.id)"
                            />
                            <ButtonEdit
                                :link="route('admin.movies.edit', movie.id)"
                            />
                            <ButtonDelete
                                :link="route('admin.movies.destroy', movie.id)"
                            />
                        </TableData>
                    </TableRow>
                </Table>
                <Pagination :links="movies.links" />
            </template>
        </Section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue"; 
import Section from "@/Components/Section.vue";
import Pagination from "@/Components/Pagination.vue";
import Table from "@/Components/Table.vue";
import TableData from "@/Components/TableData.vue";
import TableHead from "@/Components/TableHead.vue";
import TableHeadSort from "@/Components/TableHeadSort.vue";
import TableHeadSortCenter from "@/Components/TableHeadSortCenter.vue";
import TableRow from "@/Components/TableRow.vue";
import ButtonEdit from "@/Components/ButtonEdit.vue";
import ButtonDelete from "@/Components/ButtonDelete.vue";
import ButtonGenerate from "@/Components/ButtonGenerate.vue";
import ButtonOption from "@/Components/ButtonOption.vue";
import DivSearch from "@/Components/DivSearch.vue";
import DivInputId from "@/Components/DivInputId.vue";
import DivPerPage from "@/Components/DivPerPage.vue";
import OptionPerPage from "@/Components/OptionPerPage.vue";
import Publish from "@/Components/Publish.vue";
import UnPublish from "@/Components/UnPublish.vue";
import ArrowDown from "@/Components/ArrowDown.vue";
import ArrowUp from "@/Components/ArrowUp.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { defineProps, ref, watch, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { throttle, pickBy } from "lodash";

const props = defineProps({
    movies: Object,
    filters: Object,
});
const movie_id = ref("");

const movieFilters = reactive({
    search: props.filters.search,
    perPage: props.filters.perPage ? props.filters.perPage : 10,
    column: props.filters.column,
    direction: props.filters.direction,
});
watch(
    movieFilters,
    throttle(() => {
        let query = pickBy(movieFilters);
        let queryRoute = route(
            "admin.movies.index",
            Object.keys(query).length
                ? query
                : {
                      remember: "forget",
                  }
        );
        Inertia.replace(
            queryRoute,
            {},
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            }
        );
    }, 500),
    {
        deep: true,
    }
);

function sort(column) {
    movieFilters.column = column;
    movieFilters.direction = movieFilters.direction === "asc" ? "desc" : "asc";
}

function generateMovie() {
    Inertia.post(
        "/admin/movies",
        { movie_id: movie_id.value },
        {
            onFinish: () => {
                movie_id.value = "";
            },
        }
    );
}
</script>
<style></style>
