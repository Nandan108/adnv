<?php

namespace App\Enums;

enum CommercialdocEventType: string
{
    case INITIAL_QUOTE_SENT = 'initial_quote_sent';
    case FINAL_QUOTE_SENT = 'final_quote_sent';
    case QUOTE_VALIDATED = 'quote_validated';
    case INVOICE_SENT = 'invoice_sent';
    case PAYMENT_RECIEVED = 'payment_recieved';
    case CLIENT_CANCELED = 'client_canceled';
    case QUOTE_EXPIRED = 'quote_expired';
    case ADMIN_CANCELED = 'admin_canceled';
}
