export default class Traveler {
  constructor(data) {
    Object.assign(this, data);
  }

  get fullIdx() {
    return `${this.adulte ^ 1}-${this.idx}`;
  }

  get toursTotal() {
    return Object.values(this.totals.options.tours || {}).reduce((sum, tour) => sum + tour.total, 0);
  }

  async update(reservationHash, updatedData) {
    try {
      this.updating = true;
      const response = await fetch(`/reservation/${reservationHash}/updateTraveler/${this.fullIdx}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', },
        body: JSON.stringify(updatedData),
      });
      this.updating = false;
      if (!response.ok) {
        throw new Error('Failed to update traveler');
      }
      const updatedTraveler = await response.json();
      return new Traveler(Object.assign({}, this, updatedTraveler));
    } catch (error) {
      console.error('Update failed:', error);
      this.updating = false;
      throw error; // Propagate the error
    }
  }
}
