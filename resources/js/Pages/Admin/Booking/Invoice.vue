<template>
  <div>
    <Invoice :data="data" :user="user" />
  </div>

</template>

<script setup>
import Invoice from '../../Booking/Invoice.vue';
import { onMounted } from 'vue';
import { useRepo } from 'pinia-orm';
import { loadData, models } from 'models';


const props = defineProps({
  user: Object,
  data: Object,
});

// Insert the data into the ORM on component mount
// TODO: move this to the layout, or to a service
onMounted(() => {
  loadData(props.data)
});

</script>