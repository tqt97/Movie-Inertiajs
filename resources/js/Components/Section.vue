<template>
    <section class="text-black body-font">
        <div class="container mx-auto">
            <slot/>
            <div class="w-full flex justify-start" v-if="$slots.typeID">
                <slot name="typeID" />
            </div>
            <div class="my-5 flex sm:flex-row flex-col" v-if="$slots.filters">
                <slot name="filters" />
            </div>
            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-md" v-if="$slots.table">
                <slot name="table" />
            </div>
        </div>
    </section>
</template>
