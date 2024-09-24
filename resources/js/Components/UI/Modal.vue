<template>
  <div v-if="visible" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div ref="modalContent" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
      <h2 class="text-2xl mb-4">{{ title }}</h2>
      <slot></slot>
      <div class="flex justify-end mt-4 gap-2">
        <DangerButton @click="$emit('close')">Cancel</DangerButton>
        <PrimaryButton @click="$emit('submit')">Save</PrimaryButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';
import DangerButton from './DangerButton.vue';
import PrimaryButton from './PrimaryButton.vue';


const props = defineProps({
  visible: Boolean,
  title: String,
  focus: {
    type: Function,
    default: () => true,
  },
});

const modalContent = ref(null);
const emit = defineEmits(['submit', 'close']);

watch(() => props.visible, (visible) => {
  if (!visible) return;
  nextTick(() => {
    const focusableElements = modalContent.value.querySelectorAll('input, select, textarea, [tabindex]:not([tabindex="-1"])');
    for (const element of focusableElements) {
      if (props.focus(element)) return element.focus();
    }
  });
});


</script>

<style scoped>
/* You can customize the modal styles here */
</style>
