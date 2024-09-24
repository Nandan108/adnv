import { Model } from 'pinia-orm';

export default class Airline extends Model {
  static entity = 'airline';

  static fields() {
    return {
      id: this.attr(null),
      company: this.string(''), // varchar(50) NOT NULL,
      commentaire: this.string(''), // text DEFAULT NULL,
      photo: this.string(''), // text NOT NULL,
    }
  }
}