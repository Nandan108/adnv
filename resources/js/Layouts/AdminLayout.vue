<style scoped>
:root {
  --header-height: 134px;
}

#top-header {}

main {
  --admin-header-bgcolor: #6b8c2d;
}

a.nav-link {
  @apply w-full flex flex-row sm:flex-col text-center items-center justify-center gap-2 sm:gap-1
}
</style>

<template>
  <main>
    <div id='top-header' class="fixed top-0 w-full z-30 text-neutral-200">
      <header class='fixed w-full pb-4 flex flex-col items-center' ref='headerRef'>
        <div class="w-full shadow-md h-12 bg-black">
          <div class="container flex flex-row justify-between items-center">
            <a href="/" class="float-left block p-1 w-20">
              <img src="/public/images/logo2.png">
            </a>
            <div id="app-nav-top-bar" class="nav-collapse text-sm">
              <ul class="nav pull-right flex flex-row">
                <li class="dropdown flex flex-row gap-3">
                  <a href="#" class="dropdown-toggle text-white" data-toggle="dropdown">
                    <i class="fa fa-cogs mr-2"></i> Votre compte <b class="caret hidden-phone"></b>
                  </a>
                  <ul class="dropdown-menu flex flex-row gap-3">
                    <li>
                      <a href="profil.php" class="text-white">Gérer profil</a>
                    </li>
                    <li>
                      <form method="post" :action="route('logout')">
                        <CsrfToken />
                        <button type="submit">Se deconnecter</button>
                      </form>
                    </li>
                    <li>
                      <button label="Toggle Dark Mode" @click="toggleDarkMode()">
                        <i :class="['fa', inDarkMode ? 'fa-moon' : 'fa-sun']"></i>
                      </button>
                    </li>
                  </ul>
                </li>
              </ul>

              <ul class="nav pull-right">
                <li>
                  <a href="/" class="text-white">
                    <i class="fas fa-user mr-2"></i>{{ user?.firstname }} {{ user?.lastname }}
                  </a>
                </li>
              </ul>
            </div>
            <button class="hidden btn btn-navbar" data-toggle="collapse" data-target="#app-nav-top-bar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
        </div>

        <div v-if="successMessages.length || errorMessages.length" class="fixed my-auto top-8 w-[600px] max-w-full">
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

        <div class="w-full bg-[var(--admin-header-bgcolor)] shadow-md shadow-gray-400">
          <div class="container m-0 z-40">
            <ul class="body-nav w-full grid grid-cols-3 sm:flex sm:flex-row sm:flex-wrap justify-between gap-0.5 pb-0.5">
              <li v-for="link in adminPages" class="py-1 shadow-lg col-span-1 sm:w-[4.5rem] text-xs
                    bg-white bg-opacity-10 hover:bg-opacity-25 transition-all duration-300">
                <Link v-if="link.route" class="nav-link text-white"
                  :href="typeof link.route === 'Array' ? route(...(link.route)) : route(link.route)">
                <i :class="['fa text-base sm:text-4xl', link.icon]"></i>
                {{ link.title }}
                </Link>
                <a v-else :href="'/' + link.url" class="nav-link text-white">
                  <i :class="['fa text-base sm:text-4xl', link.icon]"></i>
                  {{ link.title }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>
    </div>

    <div id="body-container" class='mt-[var(--header-height)]'>
      <div id="body-content" class="flex justify-center">

        <article class='container'>
          <slot />

        </article>

      </div>
    </div>

    <footer class="application-footer bg-[var(--admin-header-bgcolor)] text-white text-xs py-2 -mb-2">
      <div class="container">
        <div class="flex flex-row justify-around bg-color">
          <div>
            <p>Ramseb & Urssy - All right reserved</p>
            <p>Copyright © ADN voyage Sarl 2022</p>
          </div>
          <p>ADN voyage Sarl - info@adnvoyage.com<br>
            Rue Le-Corbusier 8, 1208 Genève - Suisse</p>
        </div>
      </div>
    </footer>
  </main>
</template>

<script setup>
import { CsrfToken } from 'Components/UI';
import '@/../css/fontawesome/all.css';
import { Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted, nextTick, ref, watch, computed } from 'vue'

const headerRef = ref(null);
const updateHeaderHeight = () => {
  if (headerRef.value) {
    const height = headerRef.value.offsetHeight;
    document.documentElement.style.setProperty('--header-height', `${height}px`);
  }
};

onMounted(() => {
  // Ensure the height is updated after the component is rendered
  nextTick(updateHeaderHeight);
  // Listen to window resize
  window.addEventListener('resize', updateHeaderHeight);
  // Clean up
  onUnmounted(() => window.removeEventListener('resize', updateHeaderHeight));
  console.log('Mounted', new Date());
});

const adminPages = [
  { title: 'Accueil', url: "", icon: 'fa-home' },
  { title: 'Devis', route: "admin.reservation.index", icon: 'fa-file-invoice' },
  //{ title: 'Devis', route: "admin.reservation.index", icon: 'fa-bell' },
  { title: 'Lieux', url: "lieu.php", icon: 'fa-map-marker-alt' },
  { title: 'Hôtels', url: "hotels.php", icon: 'fa-hotel' },
  { title: 'Vols', url: "vols.php", icon: 'fa-plane' },
  { title: 'Circuits', url: "circuits.php", icon: 'fa-retweet' },
  { title: 'Croisières', url: "croisieres.php", icon: 'fa-ship' },
  { title: 'Transferts', url: "transferts.php", icon: 'fa-arrows-alt-h' },
  { title: 'Excursions', url: "excursions.php", icon: 'fa-hiking' },
  { title: 'Séjours', url: "package.php?order&page=1", icon: 'fa-calendar-week' },
  { title: 'Partenaires', url: "liste_partenaires.php", icon: 'fa-handshake' },
  { title: 'Assurances', url: "assurances.php", icon: 'fa-user-shield' },
  { title: 'Config', url: "config_taux_change.php", icon: 'fa-cogs' },
  { title: 'App', url: "app.php", icon: 'fa-mobile-alt' },
]

import { usePage } from '@inertiajs/vue3'
import { successMessages, errorMessages, loadMessages, dismissMessage } from 'services/layoutService';

const props = defineProps({
  user: Object,
})

const page = usePage()

// When a success or error message is flashed, display it!
watch(() => props.flash || {}, loadMessages, { immediate: true });

const user = computed(() => props.user)


const inDarkMode = ref(false);
function toggleDarkMode() {
  const element = document.querySelector('html');
  inDarkMode.value = element.classList.toggle('dark-mode');
}

</script>
