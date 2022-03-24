<template>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                    <slot/>
                </div>
            </div>
            <div class="px-4 py-2 bg-gray-50 text-right sm:px-6">
                <jet-button> Update </jet-button>
            </div>
        </div>
    </div>
</template>
<script setup>
import JetButton from "@/Jetstream/Button.vue";
</script>
