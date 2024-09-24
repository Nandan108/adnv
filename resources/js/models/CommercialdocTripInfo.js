import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocTripInfo extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdoctripinfo';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'trip_info';

  // Custom attributes (getters and setters)
  get info() { return this.data.info || null; }
  set info(value) { this.data.info = value; }
}
