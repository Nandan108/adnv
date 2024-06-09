<template>
  <main>
    <header>
      <h1>MAIN LAYOUT HEADER</h1>
      <Link href="/">Home</Link> &nbsp;
      <Link href="/hello">hello</Link>
      <!-- <Link href="/contact">Contact</Link> -->
    </header>
    <article>
      <slot />
    </article>
  </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
// import { ref } from 'vue'

// const counter = ref(0)
// setInterval(() => counter.value++, 1000)

</script>
