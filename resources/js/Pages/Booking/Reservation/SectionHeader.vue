<template>
  <div >
    <h4 class="col-sm-12 w-full text-white font-bold uppercase text-xl py-4 mt-3 flex flex-row items-center">
      <i v-if="icon" class="text-4xl pl-4 pr-7 py-3 bg-black fa rounded-r-xl" :class="[icon]"></i>
      <slot name="icon" />
      <div class="bg-adn-orange py-[5px] pr-7 pl-5 flex">
        <slot />
      </div>
    </h4>
  </div>
</template>

<script setup>

const props = defineProps({
  title: String,
  icon: String
});

</script>
