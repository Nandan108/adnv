import { Model, useRepo } from 'pinia-orm';
import Insurance from './Insurance';
import Booking from './Booking';

import { addDays, format, subYears, parseISO, differenceInYears } from 'date-fns';
import { DateCast } from 'pinia-orm/casts'
import { fr } from 'date-fns/locale'
import Tour from './Tour';
import HotelService from './HotelService';
import { uniq } from 'lodash';

export default class Traveler extends Model {
  static entity = 'traveler';
  static primaryKey = ['adulte', 'idx'];

  static fields() {
    return {
      id: this.number(null),
      adulte: this.boolean(null),
      idx: this.number(null),

      booking_id: this.number(null),
      insurance: this.belongsTo(Insurance, 'id_assurance'),

      nom: this.string(null),
      prenom: this.string(null),
      titre: this.string(null),
      date_naissance: this.attr(null),
      code_pays_nationalite: this.string(null),

      // TODO: autocalculate these
      // age: this.number(null), // this.adulte ? null : $age = $t->getAgeAtDate($booking->date_depart),
      // minMaxAge: this.attr(null), // this.adulte ? null : this.age < 2 ? [0, 1] : [this.age, this.age],
      // minMaxBd: this.attr(null), // this.adulte ? null : this.BirthdateMinMax,

      booking_id: this.number(null),
      booking_type: this.string(null),
      booking: this.belongsTo(Booking, 'booking_id'),
      totals: this.attr({}),
      //'typePerson' => $totals[$idx]->typePerson['vol'] ?? $totals[$idx]->typePerson['chambre'],
    }
  }

  static casts() {
    return {
      date_naissance: DateCast,
    }
  }

  get birthdateISO() {
    return this.date_naissance ? format(this.date_naissance, 'yyyy-MM-dd') : null;
  }

  get typePerson() {
    return this.totals?.typePerson.vol ??
      this.totals?.typePerson.chambre;
  }

  get label() {
    return `${this.typePerson} ${this.idx + 1}`;
  }

  get fullIdx() { return `${this.adulte ^ 1}-${this.idx}` }

  get toursTotal() {
    return Object.values(this.totals.options.tours || {}).reduce((sum, tour) => sum + tour.total, 0);
  }

  get ageAtDate() {
    return function (tripStartDate) {
      if (!this.date_naissance) return null;

      return differenceInYears(tripStartDate, this.date_naissance);
    }
  }
  get tripStart() { return this.booking ? new Date(this.booking?.date_depart) : null; }
  get age() { return this.ageAtDate(this.tripStart); }
  set age(age) {}
  get minMaxAge() { return this.adulte ? null : this.age < 2 ? [0, 1] : [this.age, this.age]; }
  get collapsibleAgeRange() { return uniq(this.minMaxAge).join('-') }
  get minMaxBd() {
    const [minAge, maxAge] = this.minMaxAge;

    const minBirthdate = format(addDays(subYears(this.tripStart, maxAge + 1), 1), 'yyyy-MM-dd');
    const maxBirthdate = format(subYears(this.tripStart, minAge), 'yyyy-MM-dd');

    return [minBirthdate, maxBirthdate];
  }

  get totalTours() {
    const tours = useRepo(Tour).get();
    return function(id) {
      return id
        ? this.totals.options.tours[id]?.total || 0
        : tours.reduce((sum, tour) => sum + (this.totals.options.tours[tour.id]?.total || 0), 0)
    }
  }
  get totalService() {
    const services = useRepo(HotelService).get();
    return function(id) {
      return id
        ? this.totals.options.prests[id]
        : services.reduce((sum, service) => {
          return sum + (this.totals.options.prests[service.id] || 0)
        }, 0)
    }
  }

}
