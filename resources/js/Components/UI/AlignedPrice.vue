<style scoped>
.whole {
  @apply min-w-[5ch] inline-flex text-right justify-end
}

.decimal {
  /* @apply mx-[0.1rem] */
}

.fractional {
  @apply inline-flex text-left min-w-[4ch] justify-start
}
</style>
<template>
  <div class="aligned-price flex justify-center text-nowrap" :class="{ invalid: !parts.valid }">
    <span class="whole" :style="styles.whole">{{ parts.whole }}</span>
    <span class="decimal">{{ parts.separator }}</span>
    <span class="fractional" :style="styles.frac">{{ parts.fraction }}</span>
  </div>
</template>

<script setup>
import { isNull } from 'lodash';
import { computed, inject } from 'vue'

const defaults = inject('AlignedPriceDefaults');
defaults.locale ??= 'de-CH';
defaults.style ??= 'currency';
defaults.currency ??= 'CHF';
defaults.currencyDisplay ??= 'code';
defaults.digits ??= 2;
defaults.alignSpaces ??= [5, defaults.digits];

const props = defineProps({
  price: [Number, String],
  style: { type: String },
  locale: { type: String },
  currency: { type: String },
  digits: { type: Number },
  decimalSymbol: String,
  alignSpaces: Array,
  noPriceText: String,
  zeroText: String,
})

const styles = computed(() => {
  const frac = props.alignSpaces?.[1] ?? defaults.alignSpaces[1];
  const whole = props.alignSpaces?.[0] ?? defaults.alignSpaces[0];
  return {
    frac: `min-width: ${frac}ch;`,
    whole: `min-width: ${whole}ch;`,
  }
})

const settings = computed(() => {
  const locale = props.locale ?? defaults.locale;
  const options = {
    style: props.style ?? defaults.style,
    currency: props.currency ?? defaults.currency,
    digits: props.digits ?? defaults.digits,
    minimumFractionDigits: props.digits ?? defaults.minimumFractionDigits ?? defaults.digits,
    maximumFractionDigits: props.digits ?? defaults.maximumFractionDigits ?? defaults.digits,
    currencyDisplay: props.currencyDisplay,
  };
  return { locale, options };
});

const parts = computed(() => {
  let price = Number(props.price);
  let validPrice = props.price !== null && !isNaN(price);

  if (!validPrice) return {
    valid: false,
    separator: props.noPriceText ?? (isNaN(props.price) ? 'NaN' : isNull(props.price) ? 'Null' : 'undefined')
  }
  if (!price && props.zeroText) return {
    valid: true,
    separator: props.zeroText
  }

  const formattedPrice = price.toLocaleString(settings.value.locale, settings.value.options);
  const decimalSymbol = (1.1).toLocaleString(settings.value.locale, settings.value.options).charAt(1);

  const [whole, fraction] = formattedPrice.split(decimalSymbol);

  const separator = fraction ? props.decimalSymbol || decimalSymbol : '';

  return { valid: true, whole, separator, fraction: fraction || '' };
});

</script>
