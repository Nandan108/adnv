import { Model } from 'pinia-orm';
import Location from './Location';

export default class Tour extends Model {
  static entity = 'tour';

  static fields() {
    return {
      id: this.number(null),
      nom: this.string(null),
      //partner: { lk: 'id_partenaire', class: TourPartner},
      location: this.belongsTo(Location, 'id_lieu'),
    }
  }
}