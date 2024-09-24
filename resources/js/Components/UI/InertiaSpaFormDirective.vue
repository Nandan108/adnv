<template>
  <form @submit="handleSubmit" v-spa="spaHandler" :action="action">
    <input type='hidden' :value="csrfToken" name="_token" />
    <input type='hidden' v-if="['GET', 'POST'].indexOf(method.toUpperCase()) === -1" :value="method" name="_method" />
    <input v-if="hiddenSubmit" type="submit" class="absolute invisible z-0">
    <slot />
  </form>
</template>

<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  method: { type: String, default: 'POST' },
  action: { type: String, required: true },
  hiddenSubmit: { type: Boolean, default: false }
});

const csrfToken = ref('');
onMounted(() => {
  csrfToken.value = document.head.querySelector('meta[name="csrf-token"]').content;
});

const emit = defineEmits(['submit', 'response']);

const handleSubmit = async (event) => {
  emit('submit', event);

  if (!event.defaultPrevented) {
    event.preventDefault();
    const form = new FormData(event.target);
    router[props.method.toLowerCase()](props.action, form, { preserveScroll: true });
  }
};

// Custom directive for SPA behavior
const spaHandler = (callback) => {
  const spaDirective = {
    beforeMount(el, binding) {
      el.addEventListener('submit', async (event) => {
        event.preventDefault(); // Always prevent default in SPA mode
        const form = new FormData(event.target);
        try {
          const response = await router[props.method.toLowerCase()](props.action, form, {
            preserveScroll: true
          });
          binding.value(response); // Call the provided handler with the response
        } catch (error) {
          console.error('SPA submission failed', error);
        }
      });
    },
    unmounted(el) {
      el.removeEventListener('submit', callback);
    }
  };

  return spaDirective;
};
</script>
