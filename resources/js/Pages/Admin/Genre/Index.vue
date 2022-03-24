<template>
    <admin-layout title="Manage Genres">
        <!-- <Head title="Manage Genres" /> -->
        <template #header> Manage Genres </template>
        <Section>
            <template #typeID>
                <div @click="generateGenre">
                    <button
                        class="group inline-flex items-center justify-center w-full h-full py-2 px-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:shadow-outline-indigo active:bg-indigo-800 transition duration-150 ease-in-out disabled:opacity-50"
                    >
                        <span> Generate </span>
                    </button>
                </div>
            </template>
            <template #filters>
                <DivPerPage>
                    <select
                        v-model="dataFilters.perPage"
                        @change="dataFilters.perPage === $event.value"
                        class="h-full block w-full border border-r-0 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600 rounded-l-md"
                    >
                        <OptionPerPage />
                    </select>
                </DivPerPage>
                <DivSearch>
                    <input
                        v-model="dataFilters.search"
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
                                        dataFilters.column == 'title' &&
                                        dataFilters.direction == 'asc'
                                    "
                                />
                                <ArrowUp
                                    v-if="
                                        dataFilters.column == 'title' &&
                                        dataFilters.direction == 'desc'
                                    "
                                />
                            </TableHeadSort>
                        </TableHead>
                        <TableHead>Slug</TableHead>
                        <TableHead>Action</TableHead>
                    </template>
                    <TableRow v-for="genre in genres.data" :key="genre.id">
                        <TableData> {{ genre.title }}</TableData>
                        <TableData>{{ genre.slug }}</TableData>
                        <TableData>
                            <ButtonEdit
                                :href="route('admin.genres.edit', genre.id)"
                            />
                            <ButtonDelete
                                :href="route('admin.genres.destroy', genre.id)"
                            />
                        </TableData>
                    </TableRow>
                </Table>
                <Pagination :links="genres.links" />
            </template>
        </Section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Section from "@/Components/Section.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { defineProps, ref, watch, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { throttle, pickBy } from "lodash";
import TableData from "@/Components/TableData.vue";
import Table from "@/Components/Table.vue";
import TableHead from "@/Components/TableHead.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeadSort from "@/Components/TableHeadSort.vue";
import TableHeadSortCenter from "@/Components/TableHeadSortCenter.vue";
import ButtonEdit from "@/Components/ButtonEdit.vue";
import ButtonDelete from "@/Components/ButtonDelete.vue";
import DivSearch from "@/Components/DivSearch.vue";
import DivInputId from "@/Components/DivInputId.vue";
import DivPerPage from "@/Components/DivPerPage.vue";
import OptionPerPage from "@/Components/OptionPerPage.vue";
import ArrowDown from "@/Components/ArrowDown.vue";
import ArrowUp from "@/Components/ArrowUp.vue";
import ButtonGenerate from "@/Components/ButtonGenerate.vue";

const props = defineProps({
    genres: Object,
    filters: Object,
});
const dataFilters = reactive({
    search: props.filters.search,
    perPage: props.filters.perPage ? props.filters.perPage : 10,
    column: props.filters.column,
    direction: props.filters.direction,
});
watch(
    dataFilters,
    throttle(() => { 
        let query = pickBy(dataFilters);
        let queryRoute = route(
            "admin.genres.index",
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
    dataFilters.column = column;
    dataFilters.direction = dataFilters.direction === "asc" ? "desc" : "asc";
}

function getGenres() {
    Inertia.get(
        "/admin/genres",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function generateGenre() {
    Inertia.post(
        "/admin/genres",
        {},
        {
            // onStart: () => (showSpinner.value = true),
            // onFinish: () => (showSpinner.value = false),
        }
    );
}
</script>
<style></style>
