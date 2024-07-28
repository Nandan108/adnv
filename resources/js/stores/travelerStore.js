import { defineStore } from 'pinia';
import Traveler from '@/models/Traveler';
import { computed } from 'vue';

export const useTravelerStore = defineStore('traveler', {
  state: () => ({
    travelers: [],
  }),

  actions: {
    setTravelers(travelerDataArray) {
      this.travelers = travelerDataArray.map(data => new Traveler(data));
    },

    setTraveler(travelerData) {
      const traveler = travelerData instanceof Traveler ? travelerData : new Traveler(travelerData);
      const index = this.travelers.findIndex(u => u.fullIdx === traveler.fullIdx);
      if (index !== -1) {
        this.travelers[index] = traveler;
      } else {
        this.travelers.push(traveler);
      }
      return traveler;
    },

    async update(traveler, reservationHash, updatedData) {
      try {
        const updatedTraveler = await traveler.value.update(reservationHash, updatedData);
        if (updatedTraveler) {
          return this.setTraveler(updatedTraveler);
        }
      } catch (error) {
        console.error('Error updating traveler:', error);
        // Handle the error as needed
      }
    },

    hasOptions(type) {
      return this.travelers.some(t => Object.keys(t.totals.options[type] || {}).length);
    },
  },

  getters: {
    hasTours: (state) => state.travelers.some(t => Object.keys(t.totals.options.tours || {}).length),
    hasPrests: (state) => state.travelers.some(t => Object.keys(t.totals.options.prests || {}).length),
    //byFullIdx: (state) => state.travelers.reduce((map, t) => Object.assign(map, { [t.fullIdx]: t }), {}),
    byFullIdx: (state) => Object.fromEntries(state.travelers.map(t => [t.fullIdx, t])),
    totalSejour: (state) => state.travelers.reduce((sum, t) => sum + t.totals.sousTotalSejour, 0),
    finalTotal: (state) => state.travelers.reduce((sum, p) => sum + p.totals.totalFinal, 0),
    allSaved: (state) => !state.travelers.some(p => !p.id),
  }
});
