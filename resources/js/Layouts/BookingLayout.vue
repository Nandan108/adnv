<style>

.navbar-toggler-icon {
    display: inline-block;
    width: 1.5em;
    height: 1.5em;
    vertical-align: middle;
    content: "";
    background: no-repeat center center;
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(238, 80, 87, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
    background-size: 100% 100%;
}

#mainNav {
    @apply block text-black
        border border-slate-300 rounded-sm shadow-md
        absolute top-[100%] right-0 w-fit -translate-y-3 max-h-52 overflow-hidden
        transition-[max-height] duration-300 ease-in-out
        lg:flex lg:flex-auto lg:items-center lg:top-0 lg:translate-y-0 lg:h-20
        lg:border-x-2 lg:border-slate-300
}
#mainNav.collapsed {
    @apply max-h-0 border-0 lg:max-h-fit
}
.nav-item {
    @apply hover:bg-slate-200 transition-all duration-500
}

</style>

<template>
    <div class="relative z-10" id="top-container">
        <div class="h-20 w-full p-0 bg-white">
            <!-- Top Navbar -->
            <div class="container max-w-[75%] mx-auto items-align relative">
                <div class="flex flex-row ">
                    <nav class="flex w-full px-10 items-center justify-between
                        lg:flex-row lg:flex-nowrap gap-3">
                        <Link class="navbar-brand mr-auto py-2" href="/">
                            <img :src="'/images/logo.png'" alt="Site logo" style="width: 100px;">
                        </Link>
                        <div id="mainNav" class="tm-bg-white" :class="{collapsed: menu_collapsed}">
                            <ul class="navbar-nav">
                                <li class="nav-item p-4">
                                    <a class="font-bold" href="">
                                        Formulaire de réservation <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                                <li class="nav-item p-4">
                                    <a class="" href="https://adnvoyage.com/">Nos destinations</a>
                                </li>
                                <li class="nav-item p-4">
                                    <a class="" href="https://adnvoyage.com/contact/">Contact</a>
                                </li>
                            </ul>
                        </div>
                        <button type="button" id="nav-toggle"
                            class="navbar-toggler" :class="{ collapsed: menu_collapsed }"
                            @click="menu_collapsed = !menu_collapsed"
                            data-toggle="collapse" data-target="#mainNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>


        <slot />

        <footer class="">
            <div class="container">
                <div class="flex ">
                    <p style="width: 100%;text-align: center;color: #FFF;line-height: 18px;margin-top: 20px;">
                        ADN voyage SARL <br>
                        Rue Le-Corbusier 8, 1208 Genève - Suisse, info@adnvoyage.com
                    </p>
                    <p
                        style="width: 100%;text-align: center;color: #FFF;line-height: 18px;margin-top: 6px;font-weight: bold;margin-bottom: 16px;">
                        Copyright © ADN voyage SARL 2024
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const menu_collapsed = ref(true);

const counter = ref(0)
setInterval(() => counter.value++, 1000)

</script>
