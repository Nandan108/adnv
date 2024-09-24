import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocTravelerLine extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdocTravelerLine';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'traveler_line';

  // Custom attributes (getters and setters)
  get name() { return this.data?.name || null; }
  set name(value) { this.data.name = value; }

  get ticketNum() { return this.data?.ticket_num || null; }
  set ticketNum(value) { (this.data ??= {}).ticket_num = value; }
}
