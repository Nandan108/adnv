import { Model } from 'pinia-orm';
import Hotel from './Hotel';
import Airport from './Airport';
import Currency from './Currency';

export default class Transfert extends Model {
  static entity = 'transfert';

  static fields() {
    return {
      id: this.number(null),

      currency: this.belongsTo(Currency, 'monnaie'),
      airport: this.belongsTo(Airport, 'dpt_code_aeroport'),
      hotel: this.belongsTo(Hotel, 'arv_id_hotel'),
    }
  }
}

