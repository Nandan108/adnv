import { Model } from 'pinia-orm';
import { DateCast } from 'pinia-orm/casts'
import Commercialdoc from './Commercialdoc'
import FlightFare from './FlightFare';
import Room from './Room';
import Traveler from './Traveler';
import Transfert from './Transfert';
import HotelService from './HotelService';
import BookingHotelService from './BookingHotelService';
import Tour from './Tour';
import BookingTour from './BookingTour';
import { differenceInDays } from 'date-fns';

export default class Booking extends Model {
  static entity = 'booking';
  static primaryKey = 'id';

  static fields() {
    return {
      id: this.number(null),
      hashId: this.string(null),
      code_pays: this.string(null),
      date_depart: this.attr(null),
      date_retour: this.attr(null),
      nb_adulte: this.number(null),
      ages_enfants: this.attr([]),
      nb_bebe: this.number(null),
      id_prix_vol: this.number(null),
      id_transfert: this.number(null),
      id_chambre: this.number(null),
      nb_chambres: this.number(null),
      nom_chambre: this.string(null),
      personCounts: this.attr({}),
      created_at: this.attr(null),

      url: this.string(null),

      flightFare: this.belongsTo(FlightFare, 'id_prix_vol'),
      transfert: this.belongsTo(Transfert, 'id_transfert'),

      room: this.belongsTo(Room, 'id_chambre'),

      travelers: this.morphMany(Traveler, 'booking_id', 'booking_type'),
      quote: this.hasMany(Commercialdoc, 'reservation_id'),

      hotelServices: this.belongsToMany(
        HotelService, // The Related model
        BookingHotelService, // Intermediate pivot model
        'reservation_id', // Pivot's FK for parent
        'prestation_id', // Pivot's FK for related model
      ),

      tours: this.belongsToMany(
        Tour, // The Related model
        BookingTour, // Intermediate pivot model
        'reservation_id', // Pivot's FK for parent
        'tour_id', // Pivot's FK for related model
      ),
    }
  }

  static casts() {
    return {
      date_depart: DateCast,
      date_retour: DateCast,
      created_at: DateCast,
    }
  }


  get hotel() { return this.room?.hotel; }
  get flight() { return this.flightFare?.flight; }

  get location() {
    const location = this.hotel?.location ?? this.flight?.apt_arrive?.location;
    console.debug('Getting location from booking '+this.id, location)
    return location;
  }

  get boardPlan() {
    return this.hotelServices.filter(p => p.isMeal)[0]
  }
  get services() {
    return this.hotelServices.filter(p => !p.isMeal)
  }

  get travelerCounts() {
    const p = { adulte: 'adulte', enfant: 'enfant', bebe: 'bébé'};
    return Object.entries(this.personCounts ?? [])
      //.filter(([, count]) => count)
      .map(([person, count]) => `${count} ${p[person]}${count > 1 ? 's' : ''}` // +
        // (person === 'enfant' ? ' (' + this.ages_enfants.map(a => `${a} ans`).join(', ') + ')' : '')
      )
      .join(', ')
  }

  get shortTravlerCounts() {
    const p = { adulte: 'ad.', enfant: 'enf.', bebe: 'bb.'};
    return Object.entries(this.personCounts ?? [])
      .filter(([, count]) => count)
      .map(([person, count]) => `${count} ${p[person]}` +
        (person === 'enfant' ? ' (' + this.ages_enfants.map(a => `${a} ans`).join(', ') + ')' : ''))
      .join(', ')
  }

  get tripNights() {
    return differenceInDays(this.date_retour, this.date_depart);
  }
  get nextDayArrival() {
    return this.flight?.arrive_next_day
  }
  get hotelNights() {
    let jplus1 = this.nextDayArrival;
    return this.tripNights - this.nextDayArrival;
  }

  get hasTours() { return this.travelers.some(t => Object.keys(t.totals.options.tours || {}).length) }
  get hasPrests() { return this.travelers.some(t => Object.keys(t.totals.options.prests || {}).length) }
  get totalSejour() { return this.travelers.reduce((sum, t) => sum + t.totals.sousTotalSejour, 0) }
  get finalTotal() { return this.travelers.reduce((sum, p) => sum + p.totals.totalFinal, 0) }
  get allTravelersSaved() { return this.travelers.every(p => p.id) }

}
