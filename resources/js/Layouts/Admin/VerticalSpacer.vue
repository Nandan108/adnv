<template>
  <div class="vertical-spacer" :style="{ height: height + 'px' }"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';

const props = defineProps({
  trackedRef: {
    type: Object,
    default: null, // Allows null as a valid prop value
  }
});

const height = ref(0);

onMounted(() => {
  const updateHeight = () => {
    if (props.trackedRef && props.trackedRef.value) {
      height.value = props.trackedRef.value.$el.offsetHeight;
    }
  };

  let interval = setInterval(() => console.log('trackedRef', props.trackedRef?.value), 1000)

  // Watch the ref and update height when it becomes available
  watch(
    () => props.trackedRef?.value,
    (newVal) => {

      console.log('new ref value: ', newVal)

      if (newVal) {
        updateHeight();
        // Observe size changes of the tracked component
        const resizeObserver = new ResizeObserver(updateHeight);
        resizeObserver.observe(newVal.$el);

        // Cleanup
        onBeforeUnmount(() => {
          resizeObserver.disconnect();
        });
      }
    },
    { immediate: true }
  );
});
</script>
