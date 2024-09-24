<template>
  <input type="hidden" name="_token" :value="csrf_token" autocomplete="off">
</template>

<script setup>
import { computed } from 'vue'
const csrf_token = computed(() =>
  document.querySelector('meta[name="csrf-token"]').getAttribute('content')
);
</script>