<template>
  <form @submit.prevent="submit">
    <input-hidden :value="csrfToken" name="_token" />

    <input-hidden v-if="['GET', 'POST'].indexOf(method.toUpperCase()) === -1" :value="method" name="_method" />
    <input v-if="hiddenSubmit" type="submit" class="absolute invisible z-0">

    <slot />
  </form>
</template>

<script setup>
/**
Usage:
  <vue-form method="PUT">
    <input type="text" name="email">
    <input type="submit">
  </vue-form>

The hidden submit button (when <VueForm hiddenSubmit>) accomplishes 2 things:
    1: Allows the user to hit "enter" while an input field is focused to submit the form.
    2: Allows a mobile user to hit "Go" in the on-screen keyboard to submit the form.
 */
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps({
  method: { type: String, default: 'POST' },
  action: { type: String, required: true },
  hiddenSubmit: { type: Boolean, default: false }
});

const csrfToken = ref('');

onMounted(() => {
  csrfToken.value = document.head.querySelector('meta[name="csrf-token"]').content;
});

const submit = (event) => {
  const form = new FormData(event.target);
  router.post()
  router[method.toLowerCase()](action, form);  // Handles POST, PUT, DELETE, etc.
};
</script>
