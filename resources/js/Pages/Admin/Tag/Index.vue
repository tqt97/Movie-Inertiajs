<template>
    <admin-layout title="Dashboard">
        <Head title="Manage Tags" />
        <template #header> Manage Tags </template>
        <Section>
            <div>
                <LinkCreate :href="route('admin.tags.create')"> </LinkCreate>
            </div>
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
                        <TableHead >Slug</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </template>
                    <TableRow v-for="tag in tags.data" :key="tag.id">
                        <TableData > {{ tag.name }}</TableData>
                        <TableData> {{ tag.slug }}</TableData>
                        <TableData class="text-center">
                            <ButtonEdit
                                :href="route('admin.tags.edit', tag.id)"
                            />
                            <ButtonDelete
                                :href="route('admin.tags.destroy', tag.id)"
                            />
                        </TableData>
                    </TableRow>
                </Table>
                <Pagination :links="tags.links" v-if="tags"/>
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
import DivSearch from "@/Components/DivSearch.vue";
import DivInputId from "@/Components/DivInputId.vue";
import DivPerPage from "@/Components/DivPerPage.vue";
import OptionPerPage from "@/Components/OptionPerPage.vue";
import ArrowDown from "@/Components/ArrowDown.vue";
import ArrowUp from "@/Components/ArrowUp.vue";
import LinkCreate from "@/Components/LinkCreate.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { defineProps, ref, watch, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { throttle, pickBy } from "lodash";

const props = defineProps({
    tags: Object,
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
            "admin.tags.index",
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

function getTags() {
    Inertia.get(
        "/admin/tags",
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}
</script>
<style></style>
