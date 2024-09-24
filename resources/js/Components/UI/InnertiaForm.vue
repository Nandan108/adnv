<template>
  <form @submit="handleSubmit" :action="action">
    <input type='hidden' :value="csrfToken" name="_token" />
    <input type='hidden' v-if="['GET', 'POST'].indexOf(method.toUpperCase()) === -1" :value="method" name="_method" />
    <input v-if="hiddenSubmit" type="submit" class="absolute invisible z-0">
    <slot />
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
  method: { type: String, default: 'POST' },
  action: { type: String, required: true },
  hiddenSubmit: { type: Boolean, default: false },
  mode: { type: String, default: 'normal' } // 'normal', 'prevent', or 'spa'
});

const csrfToken = ref('');
onMounted(() => {
  csrfToken.value = document.head.querySelector('meta[name="csrf-token"]').content;
});

const emit = defineEmits(['submit', 'response']);

// Initialize Inertia form handling using useForm
const form = useForm({});

const handleSubmit = async (event) => {
  emit('submit', event);

  if (props.mode === 'prevent') {
    event.preventDefault();
    console.log('Submission prevented by parent');
    return;
  }

  if (!event.defaultPrevented) {
    event.preventDefault();

    // Collect form data from the form controls inside the slot
    const formData = new FormData(event.target);

    if (props.mode === 'spa') {
      // Use `useForm` in SPA mode to prevent page reload and handle the response
      form[props.method.toLowerCase()](props.action, {
        data: formData,
        preserveScroll: true,
        onSuccess: (page) => {
          emit('response', page); // Emit response to parent
        },
        onError: (errors) => {
          console.error('SPA submission error', errors);
        }
      });
    } else {
      // Use the router for normal behavior with full page reload or location change
      router[props.method.toLowerCase()](props.action, formData, {
        preserveScroll: true
      });
    }
  }
};



</script>
