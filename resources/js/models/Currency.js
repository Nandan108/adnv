import { Model } from 'pinia-orm';

export default class Currency extends Model {
  static entity = 'currency';
  static primaryKey = 'code';

  static fields() {
    return {
      code: this.string(null),
      nom_monnaie: this.string(null),
      taux: this.number(null),
    }
  }

  get name() { return this.nom_monnaie }
}