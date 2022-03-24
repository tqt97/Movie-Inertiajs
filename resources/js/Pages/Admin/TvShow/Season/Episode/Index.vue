<template>
    <admin-layout title="Dashboard">
        <template #header> Episode </template>
        <section class="text-gray-700 body-font">
            <div class="container mx-auto">
                <div class="w-full flex justify-start">
                    <div class="flex items-center">
                        <div class="relative rounded-l-md shadow-sm">
                            <input
                                v-model="episode_number"
                                placeholder="Episode number"
                                class="px-3 py-2 border border-gray-300 rounded"
                            />
                        </div>
                    </div>
                    <div>
                        <ButtonGenerate @click="generateEpisode" />
                    </div>
                </div>
                <div class="my-5 flex sm:flex-row flex-col">
                    <div class="flex flex-row mb-1 sm:mb-0">
                        <div class="relative">
                            <select
                                v-model="perPage"
                                @change="getData"
                                class="h-full block w-full border border-gray-500 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600"
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
                    <ButtonReset />
                </div>
                <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md">
                    <Table>
                        <template #tableHead>
                            <TableHead>Name</TableHead>
                            <TableHead class="text-center"
                                >Episode Number</TableHead
                            >
                            <TableHead class="text-center">Public</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </template>
                        <TableRow
                            v-for="episode in episodes.data"
                            :key="episode.id"
                        >
                            <TableData>{{ episode.name }}</TableData>
                            <TableData class="text-center">{{
                                episode.episode_number
                            }}</TableData>
                            <TableData class="text-center">
                                <Publish v-if="episode.is_public" />
                                <UnPublish v-else />
                            </TableData>
                            <TableData class="text-center">
                                <ButtonEdit
                                    :link="
                                        route('admin.episodes.edit', [
                                            tvShow.id,
                                            season.id,
                                            episode.id,
                                        ])
                                    "
                                />
                                <ButtonDelete
                                    :link="
                                        route('admin.episodes.destroy', [
                                            tvShow.id,
                                            season.id,
                                            episode.id,
                                        ])
                                    "
                                />
                            </TableData>
                        </TableRow>
                    </Table>
                    <div class="px-3 py-2 border" v-if="episodes">
                        <Pagination :links="episodes.links" />
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
import OrderSort from "@/Components/OrderSort.vue";
import DivSearch from "@/Components/DivSearch.vue";
import Publish from "@/Components/Publish.vue";
import UnPublish from "@/Components/UnPublish.vue";

const props = defineProps({
    tvShow: Object,
    season: Object,
    episodes: Object,
    filters: Object,
});
const search = ref(props.filters.search);
const perPage = ref(props.filters.perPage);
const episode_number = ref("");
watch(search, (value) => {
    Inertia.get(
        `/admin/tv-shows/${props.tvShow.id}/seasons/${props.season.id}/episodes`,
        { search: value, perPage: perPage.value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

function getData() {
    Inertia.get(
        `/admin/tv-shows/${props.tvShow.id}/seasons/${props.season.id}/episodes`,
        { perPage: perPage.value, search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function generateEpisode() {
    Inertia.post(
        `/admin/tv-shows/${props.tvShow.id}/seasons/${props.season.id}/episodes`,
        { episode_number: episode_number.value },
        {
            onFinish: () => {
                episode_number.value = "";
            },
        }
    );
}
</script>
<style></style>
