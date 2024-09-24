<script setup>

const props = defineProps({
  url: String
});

// use replace to simulate a redirect
window.location.replace(props.url);

</script>

<template></template>
