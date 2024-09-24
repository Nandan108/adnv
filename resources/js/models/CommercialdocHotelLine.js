import CommercialdocInfo from './CommercialdocInfo';
import { format, parseISO } from 'date-fns';

export default class CommercialdocHotelLine extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdochotelline';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'hotel_line';

  // Custom attributes (getters and setters)
  get checkin() { return this.data?.checkin || null; }
  set checkin(value) { this.data.checkin = value; }

  get checkout() { return this.data?.checkout || null; }
  set checkout(value) { this.data.checkout = value; }

  get hotel() { return this.data?.hotel || null; }
  set hotel(value) { this.data.hotel = value; }

  get roomType() { return this.data?.room_type || null; }
  set roomType(value) { this.data.room_type = value; }

  get mealType() { return this.data?.meal_type || null; }
  set mealType(value) { this.data.meal_type = value; }

  // Checkin date getter/setter
  get checkinDate() {
    return this.checkin ? format(parseISO(this.checkin), 'yyyy-MM-dd') : null;
  }
  set checkinDate(value) {
    const time = this.checkinTime || '00:00'; // Fallback to midnight if no time set
    this.checkin = `${value}T${time}`;
  }

  // Checkin time getter/setter
  get checkinTime() {
    return this.checkin ? format(parseISO(this.checkin), 'HH:mm') : null;
  }
  set checkinTime(value) {
    const date = this.checkinDate || format(new Date(), 'yyyy-MM-dd'); // Fallback to today if no date set
    this.checkin = `${date}T${value}`;
  }

  // Checkout date getter/setter
  get checkoutDate() {
    return this.checkout ? format(parseISO(this.checkout), 'yyyy-MM-dd') : null;
  }
  set checkoutDate(value) {
    const time = this.checkoutTime || '00:00'; // Fallback to midnight if no time set
    this.checkout = `${value}T${time}`;
  }

  // Checkout time getter/setter
  get checkoutTime() {
    return this.checkout ? format(parseISO(this.checkout), 'HH:mm') : null;
  }
  set checkoutTime(value) {
    const date = this.checkoutDate || format(new Date(), 'yyyy-MM-dd'); // Fallback to today if no date set
    this.checkout = `${date}T${value}`;
  }
}
