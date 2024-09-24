import CommercialdocInfo from './CommercialdocInfo';

export default class CommercialdocTransferComments extends CommercialdocInfo {
  // Define the entity name
  static entity = 'commercialdoctransfercomments';
  static baseEntity = 'commercialdocinfo';
  static info_type = 'transfert_comments';

  // Custom attributes (getters and setters)
  get comments() { return this.data.comments || null; }
  set comments(value) { this.data.comments = value; }
}
