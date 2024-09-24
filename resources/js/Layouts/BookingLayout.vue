<style scoped>
.navbar-toggler {
    @apply lg:hidden p-2 border border-gray-300 rounded-md;
    @apply border-[var(--adn-orange)] max-h-fit m-auto mr-0;
}

.navbar-toggler-icon {
    @apply inline-block w-6 h-6 align-middle bg-no-repeat bg-center;
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(238, 80, 87, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
}

#mainNav.collapsed {
    @apply max-h-0 border-0 lg:max-h-fit
}

.navbar {
    @apply flex items-center justify-between w-full;
}

.navbar-brand {
    @apply flex items-center mr-auto;
}

.navbar-collapse {
    @apply hidden lg:flex bg-white w-full lg:w-auto lg:flex-grow lg:items-center;
}

ul.nav-list {
    @apply flex flex-col lg:flex-row lg:ml-auto bg-white uppercase text-[80%] text-slate-500;
}

ul.nav-list>li {
    @apply cursor-pointer font-normal text-black hover:bg-slate-200 transition-all duration-500 lg:border-l-slate-500 p-5 flex items-center border-l border-slate-300
}

.nav-link {
    @apply px-4 py-2 transition duration-300 ease-in-out;
}

.nav-link:hover,
.nav-link:focus {
    @apply text-white bg-orange-500;
}

.bg-img {
    @apply bg-fixed bg-center bg-cover;
    background-image: url('/public/images/bg-img-1.jpg');
}
@media print {
    .bg-img {
        background-image: none !important;
    }
}
</style>

<template>
    <div class="relative z-10 bg-img" id="top-container">

        <div v-if="successMessages.length || errorMessages.length" class="fixed w-screen top-24">
            <div class="mx-auto w-[600px] max-w-full">
                <div v-for="(message, idx) in successMessages"
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ message }}</span>
                    <button @click="dismissMessage('success', idx)" class="absolute top-0 bottom-0 right-0 px-2">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z" />
                        </svg>
                    </button>
                </div>
                <div v-for="(message, idx) in errorMessages"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ message }}</span>
                    <button @click="dismissMessage('error', idx)" class="absolute top-0 bottom-0 right-0 px-2">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 011.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>


        <div class="h-17 w-full p-0 bg-white print:hidden">
            <!-- Top Navbar -->
            <div class="container max-w-[75%] mx-auto items-align relative">
                <nav class="flex w-full px-10 justify-between lg:flex-row lg:flex-nowrap gap-3 items-stretch">
                    <Link class="navbar-brand mr-auto py-2" href="/">
                    <img :src="'/images/logo.png'" alt="Site logo" style="width: 100px;">
                    </Link>
                    <div id="mainNav" class="block text-black z-10
                            absolute top-[100%] lg:relative
                            border rounded-sm shadow-md lg:shadow-none
                            right-0 w-fit -translate-y-3 max-h-52 overflow-hidden
                            transition-[max-height] duration-300 ease-in-out
                            lg:flex lg:flex-auto lg:items-stretch lg:top-0 lg:translate-y-0
                            lg:border-x-2" :class="{ collapsed: menu_collapsed }">
                        <ul class="nav-list">
                            <li>
                                <a class="font-bold" href="">
                                    Formulaire de réservation <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="https://adnvoyage.com/">Nos destinations</a>
                            </li>
                            <li>
                                <a class="" href="https://adnvoyage.com/contact/">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <button type="button" class="navbar-toggler" :class="{ collapsed: menu_collapsed }"
                        @click="menu_collapsed = !menu_collapsed" data-toggle="collapse" data-target="#mainNav"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </nav>
            </div>
        </div>

        <slot />

        <footer class="bg-black bg-opacity-40 print:hidden">
            <div class="flex flex-col justify-center items-center text-neutral-300 py-2 text-sm">
                <p class="text-center">
                    <b>ADN voyage SARL</b><br>
                    Rue Le-Corbusier 8, 1208 Genève, Suisse &mdash; info@adnvoyage.com
                </p>
                <p>Copyright © ADN voyage SARL 2024</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { successMessages, errorMessages, loadMessages, dismissMessage } from 'services/layoutService';

import { Link } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { loadData } from 'models';

const props = defineProps({})

// When a success or error message is flashed, display it!
watch(() => props.flash || {}, loadMessages, { immediate: true });
watch(() => props.data || {}, loadData, { immediate: true });

const menu_collapsed = ref(true);

const counter = ref(0)
setInterval(() => counter.value++, 1000)

</script>
