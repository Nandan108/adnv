<style scope>
</style>

<template>
  <header class='fixed w-full pb-4 flex flex-col items-center' ref='headerRef'>
    <div class="w-full shadow-md h-12 bg-black">
      <div class="container flex flex-row justify-between items-center">
        <a href="/" class="float-left block p-1 w-20"><img src="/public/images/logo2.png"></a>
        <div id="app-nav-top-bar" class="nav-collapse">
          <ul class="nav pull-right flex flex-row">
            <li class="dropdown flex flex-row gap-3">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i> Votre
                compte
                <b class="caret hidden-phone"></b>
              </a>
              <ul class="dropdown-menu flex flex-row gap-3">
                <li>
                  <a href="profil.php">Gérer profil</a>
                </li>
                <li>
                  <a href="logout.php">Se deconnecter</a>
                </li>
              </ul>
            </li>
          </ul>

          <ul class="nav pull-right">
            <li>
              <a href="accueil.php"><i class="icon-user"></i>
                {{ user?.name }}
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

    <div class="w-full bg-[#6b8c2d] shadow-md shadow-gray-400">
      <div class="container m-0 z-40">
        <!-- <div class="container"> container -->
        <ul class="body-nav w-full flex flex-row flex-wrap justify-between">
          <li v-for="link in navLinks"
            class="h-20 m-1 shadow-lg w-20 text-xs pt-2 bg-white bg-opacity-5 hover:bg-opacity-15 transition-all duration-300">
            <a :href="link.url" class="w-full h-full flex flex-col justify-center text-center gap-1 text-white">
              <i :class="['fa text-4xl', link.icon]"></i>
              {{ link.title }}
            </a>
          </li>
        </ul>
        <!-- </div> -->
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';


const props = defineProps({
  user: Object,
  navLinks: Array,
})

const headerRef = ref(null);
const emit = defineEmits(['heightChanged']);

const updateHeaderHeight = () => {
    if (headerRef.value) {
        const height = headerRef.value.offsetHeight;
        emit('heightChanged', height);
    }
};

onMounted(() => {
    // Ensure the height is updated after the component is rendered
    nextTick(() => {
        updateHeaderHeight();
    });

    // Listen to window resize
    window.addEventListener('resize', updateHeaderHeight);

    // Clean up
    onUnmounted(() => {
        window.removeEventListener('resize', updateHeaderHeight);
    });
});

</script>