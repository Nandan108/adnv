import { Model, useRepo } from 'pinia-orm';
import Commercialdoc from './Commercialdoc';

export default class CommercialdocEvent extends Model {
  static entity = 'commercialdocevent';

  static fields() {
    return {
      id: this.number(null),
      type: this.string(),
      data: this.attr({}),
      created_at: this.string(),

      commercialdoc_id: this.number(null),
      // doc: this.belongsTo(Commercialdoc, 'commercialdoc_id'),
    }
  }
}