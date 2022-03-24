<template>
    <div>
        <Head :title="title" />

        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <Navbar />
            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
        <Footer />
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

export default defineComponent({
    props: {
        title: String,
    },

    components: {
        Head,
        Link,
        Navbar,
        Footer,
    },
});
</script>
