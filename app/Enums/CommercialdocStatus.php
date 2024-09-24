<?php

namespace App\Enums;

enum CommercialdocStatus: string
{
    case INITIAL_QUOTE_CREATED = 'initial_quote_created';
    case INITIAL_QUOTE_SENT = 'initial_quote_sent';
    case FINAL_QUOTE_SENT = 'final_quote_sent';
    case QUOTE_VALIDATED = 'quote_validated';
    case AWAITING_DEPOSIT_PAYMENT = 'invoice_sent';
    case AWAITING_BALANCE_PAYMENT = 'deposit_received';
    case FULLY_PAID = 'fully_paid';
    case PRODUCT_DELIVERED = 'product_delivered';
    case CANCELED_BY_CLIENT = 'canceled_by_client';
    case DEADLINE_EXCEEDED = 'quote_expired';
    case CANCELED_BY_ADMIN = 'canceled_by_admin';

    // Method that returns the ID of the stage
    public function stage(): int {
        return array_search($this, array_values(self::cases()));
    }

    public function finalQuoteWasSent() {
        return $thisStage = $this->stage() >= $sentStage = CommercialdocStatus::FINAL_QUOTE_SENT->stage();
    }

    public static function deadValues(): array {
        return [
            self::CANCELED_BY_CLIENT,
            self::DEADLINE_EXCEEDED,
            self::CANCELED_BY_ADMIN,
        ];
    }
    public function isDead(): int {
        return in_array($this, self::deadValues());
    }

    public static function awaitingPaymentValues(): array {
        return [
            self::AWAITING_DEPOSIT_PAYMENT,
            self::AWAITING_BALANCE_PAYMENT,
        ];
    }
    public function isAwaitingPayment(): int {
        return in_array($this, self::awaitingPaymentValues());
    }

    public function isAwaitingAdminAction(): int {
        return in_array($this, [
            self::INITIAL_QUOTE_CREATED,
            self::INITIAL_QUOTE_SENT,
            self::QUOTE_VALIDATED,
            self::AWAITING_DEPOSIT_PAYMENT,
            self::AWAITING_BALANCE_PAYMENT,
            self::FULLY_PAID,
        ]);
    }
}
