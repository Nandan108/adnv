import { Model } from 'pinia-orm';
import { BooleanCast } from 'pinia-orm/casts'

export default class HotelServiceType extends Model {
  static entity = 'hotelservicetype';

  static fields () {
    return {
      id: this.number(null),
      name: this.string(null),
      is_meal: this.number(null),
    }
  }
}