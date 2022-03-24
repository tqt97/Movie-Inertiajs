<template>
    <section class="container mx-auto p-6">
        <div class="flex flex-row mb-1 sm:mb-3">
            <slot name="nav" />
        </div>
        <div class="grid grid-cols-6 gap-6">
            <div
                class="w-full col-span-6 sm:col-span-3 mb-8 p-6 overflow-hidden bg-white rounded-lg shadow-lg"
            >
                <!-- <div class="mb-5">
                    <jet-button> Attach Trailer </jet-button>
                </div> -->
                <slot name="trailer" />
            </div>
            <div
                class="w-full col-span-6 sm:col-span-3 mb-8 p-6 overflow-hidden bg-white rounded-lg shadow-lg"
            >
                <!-- <div class="mb-5">
                    <jet-button> Attach Download </jet-button>
                </div> -->
                <slot name="download" />
            </div>
            <div
                class="w-full col-span-6 sm:col-span-3 mb-8 p-6 bg-white rounded-lg shadow-lg"
            >
                <!-- <div class="mb-5">
                    <jet-button> Attach Cast </jet-button>
                </div> -->
                <slot name="cast" />
            </div>
            <div
                class="w-full col-span-6 sm:col-span-3 mb-8 p-6 bg-white rounded-lg shadow-lg"
            >
                <!-- <div class="mb-5">
                    <jet-button> Attach Tag </jet-button>
                </div> -->
                <slot name="tag" />
            </div>
        </div>
        <slot />
    </section>
</template>
<script setup>
import JetButton from "@/Jetstream/Button.vue";
</script>
