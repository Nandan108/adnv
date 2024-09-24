import { Model } from 'pinia-orm';
import Flight from './Flight';
import Location from './Location';

export default class Airport extends Model {
  static entity = 'airport';
  static primaryKey = 'code_aeroport';

  static fields() {
    return {
      code_aeroport: this.string(null),
      aeroport: this.string(null),

      id_lieu: this.number(null),
      lieu: this.belongsTo(Location, 'id_lieu'),
      code_apt_depart: this.string(null),
      departingFlights: this.hasMany(Flight, 'code_apt_depart'),
      code_apt_transit: this.string(null),
      transitingFlights: this.hasMany(Flight, 'code_apt_transit'),
      code_apt_arrive: this.string(null),
      arrivingFlights: this.hasMany(Flight, 'code_apt_arrive'),
    }
  }
}