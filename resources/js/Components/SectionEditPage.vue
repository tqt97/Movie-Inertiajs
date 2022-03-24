<template>
    <section class="text-gray-700 body-font">
        <div class="container mx-auto">
            <div class="flex flex-row mb-1 sm:mb-3">
                <slot name="nav" />
            </div>
            <section class="w-full">
                    <slot />
            </section>
        </div>
    </section>
</template>
