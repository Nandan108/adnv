import { Model } from 'pinia-orm';
import { DateCast } from 'pinia-orm/casts'
import Currency from './Currency';
import CommercialdocItem from './CommercialdocItem';
import CommercialdocEvents from './CommercialdocEvent';
import CommercialdocInfo from './CommercialdocInfo';
import Booking from './Booking';
import Country from './Country';
import CommercialdocFlightLine from './CommercialdocFlightLine';
import CommercialdocHotelLine from './CommercialdocHotelLine';
import CommercialdocTransferLine from './CommercialdocTransferLine';
import CommercialdocTransferComments from './CommercialdocTransferComments';
import CommercialdocHeaderResId from './CommercialdocHeaderResId';
import CommercialdocTravelerLine from './CommercialdocTravelerLine';
import CommercialdocTripInfo from './CommercialdocTripInfo';

export default class Commercialdoc extends Model {
  static entity = 'commercialdoc';

  static fields() {
    return {
      id: this.number(null),

      doc_id: this.attr(null),
      type: this.string(null), // quote, invoice
      deadline: this.string(null),
      object_type: this.string(null), // trip, circuit, cruise
      status: this.string(1),
      client_remarques: this.string(null),
      title: this.string(null),
      lastname: this.string(null),
      firstname: this.string(null),
      email: this.string(null),
      phone: this.string(null),
      street: this.string(null),
      street_num: this.string(null),
      zip: this.string(null),
      city: this.string(null),

      header_address_lines: this.attr(null),
      header_specific_lines: this.attr(null),

      created_at: this.attr(null),

      hashId: this.string(null),

      reservation_id: this.number(null),
      booking: this.belongsTo(Booking, 'reservation_id'),

      currency_code: this.string(null),
      currency: this.belongsTo(Currency, 'currency_code'),

      country_code: this.string(null),
      country: this.belongsTo(Country, 'country_code'),

      events: this.hasMany(CommercialdocEvents, 'commercialdoc_id'),
      items: this.hasMany(CommercialdocItem, 'commercialdoc_id'),
      infos: this.hasMany(CommercialdocInfo, 'commercialdoc_id'),
      flightLines: this.hasMany(CommercialdocFlightLine, 'commercialdoc_id'),
      hotelLines: this.hasMany(CommercialdocHotelLine, 'commercialdoc_id'),
      transferLines: this.hasMany(CommercialdocTransferLine, 'commercialdoc_id'),
      transferComments: this.hasMany(CommercialdocTransferComments, 'commercialdoc_id'),
      headerResIds: this.hasMany(CommercialdocHeaderResId, 'commercialdoc_id'),
      travelerLines: this.hasMany(CommercialdocTravelerLine, 'commercialdoc_id'),
      tripInfo: this.hasMany(CommercialdocTripInfo, 'commercialdoc_id'),
    }
  }

  get initialItems() {
    const items = this.items.filter(i => i.stage === 'initial');
    return items;
  }
  get finalItems() {
    const items = this.items.filter(i => i.stage === 'final');
    return items;
  }

  static casts() {
    return {
      created_at: DateCast,
    }
  }

  get sortedTravelers() {
    return this.travelerLines.sort((a, b) => a.adulte - b.adulte || a.idx - b.idx)
  }

  get statusValues() {
    return {
      initial_quote_created: { text: "Devis initial créé", color: "lightgray", },
      initial_quote_sent: { text: "Devis initial envoyé", color: "#f9b3b3", },
      final_quote_sent: { text: "Devis final envoyé", color: "#e1cd62", },
      quote_validated: { text: "Devis validé par client", color: "#7eca49", },
      invoice_sent: { text: "Attente payement acompte", color: "#ffda6c", },
      deposit_received: { text: "Attente payement solde", color: "#00dff7", },
      fully_paid: { text: "Facture payée", color: "#2abd5d" },
      canceled_by_client: { text: "canceled by client", color: "gray", borderColor: "red" },
      quote_expired: { text: "canceled by system", color: "gray", borderColor: "red" },
      canceled_by_admin: { text: "canceled by admin", color: "gray", borderColor: "red" },
    }
  }

  get statusText() { return this.statusValues[this.status].text }
  get statusColor() { return this.statusValues[this.status].color }

  get depositAmount() {
    return this.events.filter(e => e.type==='invoice_sent')[0]?.data?.depositAmount ?? 0;
  }
  // get status() {
  //     switch (this.status) {
  //       case 1: return { label: 'Nouveau', color: "#f9b3b3" }; // devis initial envoyé
  //       case 2: return { label: 'En cours', color: "#e1cd62" }; // devis finial envoyé (TODO: message de rappel après 2j)
  //       case 3: return { label: 'Validé', color: "#7eca49" };
  // devis final validé par client
  // quote becomes invoice before next step
  // case 3B: mail sent with amount of deposit and choice of payment method:
  //    bank transfer, cache, CC (with link to pay online)
  //       case 4: return { label: 'Attente paiement', color: "#ffda6c" };
  // deposit received
  //       case 5: return { label: 'Accompte payé', color: "#00dff7" };
  //        // send finial invoice indicating payment already done
  //       case 6: return { label: 'Annulé', color: "#f00" };
  //         case 7: annulé par client
  //         case 8: annulé par admin
  //   };
  // }
}
/*
status 1: "Initial quote sent" - awaiting creation of final quote by admin
status 2: "Final quote sent" - awaiting client validation
status 3: "Quote Validated" - awaiting invoice generation
status 4: Awaiting deposit payment
status 5: Awaiting payment of balance.

action 1: Client imputs data, system create and send initial quote
action 2: admin sends final quote to client
action 3: client validates
action 4: Quote becomes Invoice, admin inputs deposit amount and sends invoice
action 5: Deposit payment confirmed (by manager)

initialQuoteSent
finalQuoteSent
validatedByClient
invoiceSent
paymentReceived
canceledByClient
quoteExpired
canceledByAdmin

*/