import { Model } from 'pinia-orm';
import Location from './Location';

export default class Country extends Model {
  static entity = 'country';
  static primaryKey = 'code';

  static fields() {
    return {
      code: this.string(null),
      nom_fr_fr: this.string(null),

      locations: this.hasMany(Location, 'code_pays'),
    }

  }
  get name() {
    return this.nom_fr_fr;
  }
}