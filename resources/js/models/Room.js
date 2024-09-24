import { Model } from 'pinia-orm'

import Hotel from './Hotel';
import Currency from './Currency';

export default class Room extends Model {
  static entity = 'room';
  static primaryKey = 'id_chambre';

  static fields() {
    return {
      id_chambre: this.number(null),
      id_hotel: this.number(null),
      nom_chambre: this.string(),

      // _villa: this.number(null),
      // _nb_min: this.number(null),
      // _nb_max: this.number(null),
      // _nb_max_adulte: this.number(null),
      // _nb_max_enfant: this.number(null),
      // _nb_max_bebe: this.number(null),
      // _age_max_bebe: this.number(null),
      // _age_max_petit_enfant: this.number(null),
      // _age_max_enfant: this.number(null),
      // _adulte_1_net_a: this.number(null),
      // _adulte_1_net_b: this.number(null),
      // _enfant_2_net: this.number(null),
      // _enfant_1_net: this.number(null),
      // _adulte_1_net: this.number(null),
      // _adulte_2_net: this.number(null),
      // _adulte_3_net: this.number(null),
      // _adulte_4_net: this.number(null),
      // _bebe_1_net: this.number(null),

      currency: this.belongsTo(Currency, 'monnaie'),
      hotel: this.belongsTo(Hotel, 'id_hotel'),
    }
  }

  get name() {
    return this.nom_chambre;
  }

}
