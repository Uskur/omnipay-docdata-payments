<?php

namespace Omnipay\DocdataPayments\Message;

/**
 * Extended status request response.
 */
class ExtendedStatusResponse extends StatusResponse
{
    /**
     * An authorized non-bank-transfer payment is successful immediately. A
     * bank transfer is successful only after the full amount is captured.
     */
    public function isSuccessful(): bool
    {
        if (parent::isSuccessful() === false) {
            return false;
        }

        if (!isset($this->data->statusSuccess->report->payment)) {
            return false;
        }

        $payments = $this->data->statusSuccess->report->payment;
        if (!is_array($payments)) {
            $payments = [$payments];
        }

        foreach ($payments as $payment) {
            if (($payment->authorization->status ?? null) !== 'AUTHORIZED') {
                continue;
            }

            if (($payment->paymentMethod ?? null) !== 'BANK_TRANSFER') {
                return true;
            }

            if ($this->isCaptured()) {
                return true;
            }
        }

        return false;
    }
}
