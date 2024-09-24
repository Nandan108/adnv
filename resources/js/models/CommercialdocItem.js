import { Model, useRepo } from 'pinia-orm';
import Commercialdoc from './Commercialdoc';

export default class CommercialdocItem extends Model {
  static entity = 'commercialdocitem';

  static fields() {
    return {
      id: this.number(null),

      description: this.string(''), // string(255)
      unitprice: this.number(0), // decimal(7,2)
      qtty: this.number(1), // tinyint
      discount_pct: this.number(0), // tinyint
      section: this.string('primary'), // enum ['primary', 'options']
      ord: this.number(0),
      stage: this.string('final'),

      commercialdoc_id: this.number(null),
      doc: this.belongsTo(Commercialdoc, 'commercialdoc_id'),
    }
  }

  get total() { return this.unitprice * this.qtty; }

  /** Returns a clone of this object */
  clone() {
    const clone = useRepo(this.constructor).make(this);
    clone.data = {...this.data};

    return clone;
  }
}