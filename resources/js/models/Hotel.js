import { computed } from 'vue';

import { Model } from 'pinia-orm';
import HotelService from './HotelService';
import Room from './Room';
import Location from './Location';
import Transfert from './Transfert';

export default class Hotel extends Model {
  static entity = 'hotel';

  static fields () {
    return {
      id: this.number(null),
      nom: this.string(null),
      repas: this.string(null),

      rooms: this.hasMany(Room, 'id_hotel'),
      provider_id: this.number(null),
      services: this.hasMany(HotelService, 'provider_id'),
      arv_id_hotel: this.number(null),
      transfert: this.hasMany(Transfert, 'arv_id_hotel'),
      id_lieu: this.number(null),
      location: this.belongsTo(Location, 'id_lieu'),

    }
  }

  get mealServices() { return this.services.filter(p => p.type.is_meal); }
  get otherServices() { return this.services.filter(p => !p.type.is_meal); }
}

