<template>
    <admin-layout title="Manage Casts">
        <!-- <Head title="Manage Casts" /> -->
        <template #header> Manage Casts </template>
        <Section>
            <template #typeID>
                <DivInputId>
                    <input
                        v-model="cast_id"
                        placeholder="Cast ID"
                        class="px-3 py-2 border border-gray-600 rounded-l-md"
                    />
                </DivInputId>
                <ButtonGenerate @click="generateCast" />
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
                        <TableHead @click="sort('name')">
                            <TableHeadSort>
                                <template #title> Name </template>
                                <ArrowDown
                                    v-if="
                                        dataFilters.column == 'name' &&
                                        dataFilters.direction == 'asc'
                                    "
                                />
                                <ArrowUp
                                    v-if="
                                        dataFilters.column == 'name' &&
                                        dataFilters.direction == 'desc'
                                    "
                                />
                            </TableHeadSort>
                        </TableHead>
                        <TableHead>Slug</TableHead>
                        <TableHead class="text-center">Poster</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </template>
                    <TableRow v-for="cast in casts.data" :key="cast.id">
                        <TableData> {{ cast.name }}</TableData>
                        <TableData>{{ cast.slug }}</TableData>
                        <TableData
                            class="flex text-center justify-center items-center"
                        >
                            <img
                                class="h-12 w-12 rounded"
                                :src="`https://www.themoviedb.org/t/p/w220_and_h330_face/${cast.poster_path}`"
                            />
                        </TableData>
                        <TableData class="text-center">
                            <ButtonEdit
                                :href="route('admin.casts.edit', cast.id)"
                            />
                            <ButtonDelete
                                :href="route('admin.casts.destroy', cast.id)"
                            />
                        </TableData>
                    </TableRow>
                </Table>
                <Pagination :links="casts.links" />
            </template>
        </Section>
    </admin-layout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Section from "@/Components/Section.vue";
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
    casts: Object,
    filters: Object,
});

const cast_id = ref("");

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
            "admin.casts.index",
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

function getCasts() {
    Inertia.get(
        "/admin/casts",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function generateCast() {
    Inertia.post(
        "/admin/casts",
        { cast_id: cast_id.value },
        {
            onFinish: () => {
                cast_id.value = "";
            },
        }
    );
}
</script>
<style></style>
