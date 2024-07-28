import { defineStore } from 'pinia';

export const useMainStore = defineStore('main', {
  state: () => ({
    booking: {},
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    assurances: [],
  })
});
