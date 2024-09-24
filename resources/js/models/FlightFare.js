import { Model } from 'pinia-orm';
import Flight from './Flight';

export default class FlightFare extends Model {
  static entity = 'flightfare';
  static primaryKey = 'id_prix_vol';

  static fields() {
    return {
      id_prix_vol: this.number(null),

      id_vol: this.number(null),
      flight: this.belongsTo(Flight, 'id_vol'),

      adulte_net: this.number(null),
      adulte_comm: this.number(null),
      adulte_taxe: this.number(null),

      enfant_net: this.number(null),
      enfant_comm: this.number(null),
      enfant_taxe: this.number(null),

      bebe_net: this.number(null),
      bebe_comm: this.number(null),
      bebe_taxe: this.number(null),
    }
  }
}