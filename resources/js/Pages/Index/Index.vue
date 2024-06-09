<template>

  <div class="">
    Hola - The message is {{ message }} from the app
  </div>
  <Link :href="'some-page'">Go to some page...</Link>
</template>

<script setup>

import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  message: String,
  // someProp: {
  //   type: String,
  //   default: 'De Fault',
  // }
});
</script>

<script>
// import MainLayout from '@/Layouts/MainLayout.vue'
export default {
  // layout: MainLayout,
}
</script>
