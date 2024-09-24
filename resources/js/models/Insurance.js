import { Model } from 'pinia-orm';

export default class Insurance extends Model {
  static entity = 'insurance';

  static fields() {
    return {
      id: this.number(null),
      titre_assurance: this.string(null),  // varchar(50)
      prix_assurance: this.number(null),  // decimal(10,2) unsigned [0.00]
      prestation_assurance: this.string(null),  // text
      couverture: this.string(null),  // enum('par famille','par personne')
      duree: this.string(null),  // enum('annuelle','voyage')
      pourcentage: this.number(null),  // varchar(20) NULL [0]
      prix_minimum: this.number(null),  // decimal(5,2) unsigned NULL
      frais_annulation: this.string(null),  // text
      assistance: this.string(null),  // text
      fraisderecherche: this.string(null),  // text
      volretarde: this.string(null),  // text
    }
  }
}