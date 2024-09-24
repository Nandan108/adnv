import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocTransferLine extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdoctransferline';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'transfert_line';

  // Custom attributes (getters and setters)
  get pickup() { return this.data.pickup || null; }
  set pickup(value) { this.data.pickup = value; }

  get dropoff() { return this.data.dropoff || null; }
  set dropoff(value) { this.data.dropoff = value; }

  get duration() { return this.data.duration || null; }
  set duration(value) { this.data.duration = value; }

  get route() { return this.data.route || null; }
  set route(value) { this.data.route = value; }

  get vehicle() { return this.data.vehicle || null; }
  set vehicle(value) { this.data.vehicle = value; }
}
