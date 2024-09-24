import { Model } from 'pinia-orm';
import Booking from './Booking';
import HotelService from './HotelService';

// In French: Prestation!
export default class BookingHotelService extends Model {
  static entity = 'bookingHotelService';

  static primaryKey = ['reservation_id', 'prestation_id']
  static fields () {
    return {
      reservation_id: this.attr(null),
      booking: this.belongsTo(Booking, 'reservation_id'),

      prestation_id: this.attr(null),
      HotelService: this.belongsTo(Booking, 'prestation_id'),

      adulte: this.attr(null),
      enfant: this.attr(null),
      bebe: this.attr(null),
    }
  }
}