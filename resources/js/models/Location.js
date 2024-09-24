import { Model } from 'pinia-orm';
import Country from './Country';

export default class Location extends Model {
  static entity = 'location';
  static primaryKey = 'id_lieu';

  static fields() {
    return {
      id_lieu: this.number(null),
      code_pays: this.attr(null),
      lieu: this.attr(null),
      pays: this.attr(null),
      photo_lieu: this.attr(null),
      region: this.attr(null),
      region_key: this.attr(null),
      ville: this.attr(null),
      ville_key: this.attr(null),

      country: this.belongsTo(Country, 'code_pays'),
    }
  }
}