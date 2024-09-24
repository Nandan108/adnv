import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocFlightLine extends CommercialdocInfo {
  // Define the entity name (can be the same or different based on your preference)
  static entity = 'commercialdocflightline';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'flight_line';

  // Custom attributes (getters and setters)
  get date() { return this.data.date || null; }
  set date(value) { this.data.date = value; }

  get airline() { return this.data.airline || null; }
  set airline(value) { this.data.airline = value; }

  get flightNum() { return this.data.flight_num || null; }
  set flightNum(value) { this.data.flight_num = value; }

  get origin() { return this.data.origin || null; }
  set origin(value) { this.data.origin = value; }

  get destination() { return this.data.dest || null; }
  set destination(value) { this.data.dest = value; }

  get departureTime() { return this.data.dep_time || null; }
  set departureTime(value) { this.data.dep_time = value; }

  get arrivalTime() { return this.data.arr_time || null; }
  set arrivalTime(value) { this.data.arr_time = value; }

  get arrivalNextDay() { return this.data.arr_next_day || false; }
  set arrivalNextDay(value) { this.data.arr_next_day = value; }
}
