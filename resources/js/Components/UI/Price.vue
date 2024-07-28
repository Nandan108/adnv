<template>
  <span>{{ formattedPrice }}</span>
</template>

<script setup>
import { computed, defineProps, withDefaults } from 'vue'

const props = defineProps({
  price: [Number | String],
  style: {type: String, default: 'currency'},
  locale: {type: String, default: 'fr-CH'},
  currency: {type: String, default: 'CHF'},
  digits: {type: Number, default: 2},
})

const formattedPrice = computed(() => {
  const priceNumber = Number(props.price);
  return priceNumber.toLocaleString(props.locale, {
    style: props.style,
    currency: props.currency,
    maximumFractionDigits: props.digits,
  });
});

</script>
