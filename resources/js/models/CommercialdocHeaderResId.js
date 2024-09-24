import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocHeaderResId extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdocheaderresid';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'header_res_id';

  // Custom attributes (getters and setters)
  get name() { return this.data?.name || null; }
  set name(value) { this.data.name = value; }

  get resId() { return this.data?.id || null; }
  set resId(value) { (this.data ??= {}).id = value; }
}
