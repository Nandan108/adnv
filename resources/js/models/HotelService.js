import { Model } from 'pinia-orm';
import HotelServiceType from './HotelServiceType';
import Hotel from './Hotel';

export default class HotelService extends Model {
  static entity = 'hotelservice';

  static fields() {
    return {
      id: this.number(null),

      id_type: this.number(null),
      name: this.string(null),
      type: this.belongsTo(HotelServiceType, 'id_type'),

      id_hotel: this.number(null),
      hotel: this.belongsTo(Hotel, 'id_hotel'),

      provider_id: this.attr(null), // int(10) unsigned
      provider_type: this.attr(null), // enum('App\\Models\\Hotel','App\\Models\\Circuit','App\\Models\\Croisiere')

      description: this.string(null), // mediumtext NULlL
      obligatoire: this.boolean(null), // boolean,
      debut_validite: this.string(null), // date
      fin_validite: this.string(null), // date
      code_monnaie: this.string(null), // char(3)
      taux_commission: this.number(null), // tinyint(3) unsigned [0]

      adulte_net: this.number(null), // decimal(6,2) unsigned
      enfant_net: this.number(null), // decimal(6,2) unsigned
      bebe_net: this.number(null), // decimal(6,2) unsigned

      photo: this.attr(null), // text COLLATE latin1_general_ci NOT NULL,    }
    }
  }

  get isMeal() { return this.type?.is_meal; }
}
