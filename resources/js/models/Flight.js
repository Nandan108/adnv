import { Model } from 'pinia-orm';
import FlightFare from './FlightFare';
import Airport from './Airport';
import Airline from './Airline';
import Currency from './Currency';

export default class Flight extends Model {
  static entity = 'flight';

  static fields() {
    return {
      id: this.number(null),

      arrive_next_day: this.number(null),
      debut_vente: this.string(null),
      fin_vente: this.string(null),
      debut_voyage: this.string(null),
      fin_voyage: this.string(null),

      fares: this.hasMany(FlightFare, 'id_vol'),

      monnaie: this.string(null),
      currency: this.belongsTo(Currency, 'monnaie'),

      id: this.number(null),
      airline: this.belongsTo(Airline, 'id_company'),

      code_apt_depart: this.string(null),
      apt_depart: this.belongsTo(Airport, 'code_apt_depart'),

      code_apt_transit: this.string(null),
      apt_transit: this.belongsTo(Airport, 'code_apt_transit'),

      code_apt_arrive: this.string(null),
      apt_arrive: this.belongsTo(Airport, 'code_apt_arrive'),
    }
  };

  get titre() { return this.airline.company + ' ' + this.code_apt_arrive; };
  set titre(value) {};
}