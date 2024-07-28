<template>
  <div :class="[ 'accordion', { 'is-open': model }]">
    <div @click="model = !model" class="accordion-header cursor-pointer" :class="headerClass">
      <slot name="title">{{ title }}</slot>
    </div>
    <div class="accordion-content overflow-hidden duration-300 ease transition-max-height" ref="content"
      :style="{ 'max-height': maxHeight }">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { debounce } from 'lodash';

const model = defineModel();

const props = defineProps({
  title: String,
  headerClass: [String, Array, Object],
});

const emit = defineEmits(['update:modelValue', 'contentHeightChanged']);

const maxHeight = ref('0px');
const content = ref(null);
let forceRecheck = false;
let resizeObserver = null;

const calculateHeight = async (expectChange = false) => {
  const newVal = model.value ? content.value.scrollHeight + 'px' : '0px';
  forceRecheck |= expectChange;

  if (maxHeight.value === newVal) {
    if (forceRecheck) {
      // console.log('Excpecting change, but non found. Recheck in 5ms');
      return await setTimeout(() => calculateHeight(forceRecheck), 5);
    }
  } else {
    forceRecheck = false;
  }
  return maxHeight.value = newVal
};

watch(() => maxHeight.value, (val) => emit('contentHeightChanged', val));
watch(() => model.value, () => calculateHeight(true));

onMounted(() => {
  calculateHeight();
  resizeObserver = new ResizeObserver(debounce(calculateHeight, 10));

  if (content.value) {
    resizeObserver.observe(content.value);
  } else {
    console.error('content ref is null');
  }
});

onBeforeUnmount(() => {
  if (resizeObserver && content.value) {
    resizeObserver.unobserve(content.value);
  }
});

defineExpose({
  calculateHeight,
});

</script>

<style scoped>
.transition-max-height {
  transition-property: max-height;
}
</style>
