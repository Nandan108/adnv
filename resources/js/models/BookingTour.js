import { Model } from 'pinia-orm';

export default class BookingTour extends Model {
  static entity = 'bookingtour';

  static primaryKey = ['reservation_id', 'tour_id']
  static fields () {
    return {
      reservation_id: this.attr(null),
      tour_id: this.attr(null),

      adulte: this.attr(null),
      enfant: this.attr(null),
      bebe: this.attr(null),
    }
  }
}