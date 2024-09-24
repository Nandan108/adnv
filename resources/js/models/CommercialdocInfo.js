import { Model, useRepo } from 'pinia-orm';
import { ArrayCast } from 'pinia-orm/casts';
import Commercialdoc from './Commercialdoc';
import {
  CommercialdocHeaderResId,
  CommercialdocFlightLine,
  CommercialdocTransferLine,
  CommercialdocHotelLine,
  CommercialdocTransferComments,
  CommercialdocTravelerLine,
  CommercialdocTripInfo,
} from './models'

export default class CommercialdocInfo extends Model {
  static entity = 'commercialdocinfo';

  static types() {
    return {
      header_res_id: CommercialdocHeaderResId,
      flight_line: CommercialdocFlightLine,
      transfert_line: CommercialdocTransferLine,
      hotel_line: CommercialdocHotelLine,
      transfert_comments: CommercialdocTransferComments,
      traveler_line: CommercialdocTravelerLine,
      trip_info: CommercialdocTripInfo
    }
  }

  static fields() {
    return {
      id: this.number(null),
      type: this.string(this.info_type || ''),
      ord: this.number(0),

      data: this.attr({}),

      commercialdoc_id: this.attr(null),
      doc: this.belongsTo(Commercialdoc, 'commercialdoc_id'),
    }
  }

  static casts() {
    return {
      data: ArrayCast
    }
  }

  /** Returns a clone of this object */
  clone() {
    const clone = useRepo(this.constructor).make(this);
    clone.data = {...this.data};

    return clone;
  }

}