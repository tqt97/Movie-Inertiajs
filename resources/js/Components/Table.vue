<template>
    <table class="w-full table-auto border border-b-0">
        <thead>
            <tr class="text-white bg-indigo-700 uppercase rounded-full">
                <slot name="tableHead"></slot>
            </tr>
        </thead>
        <tbody>
            <slot />
        </tbody>
    </table>
</template>
