<?php

namespace Omnipay\DocdataPayments\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Status Request Response
 */
class StatusResponse extends AbstractResponse
{

    public function getTransactionReference(){
        return $this->request->getTransactionReference();
    }

    public function isSuccessful()
    {
        if(isset($this->data->statusSuccess) && $this->data->statusSuccess->success->code === 'SUCCESS'){
            return $this->isCaptured();
        }
        return false;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isPending()
    {
        if(!isset($this->data->statusSuccess->report->payment)){
            return true;
        }

        $payment = $this->getMostRecentPayment();

        $authorizationStatus = $payment->authorization->status;
        if ($authorizationStatus === 'CANCELED') {
            return false;
        }

        //TODO This is probably right, but different from the original implementation
        if ($authorizationStatus === 'AUTHORIZED') {
            if ($payment->paymentMethod === 'BANK_TRANSFER') {
                if ($this->isCaptured() === false) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        if (!isset($this->data->statusSuccess->report->payment)) {
            return false;
        }

        $payment = $this->getMostRecentPayment();

        if (\is_array($payment)) {
            $payment = $payment[0];
        }


        return $payment->authorization->status === 'CANCELED';
    }

    public function isCaptured()
    {
        $approximateTotals = $this->data->statusSuccess->report->approximateTotals;

        $totalRegistered = $approximateTotals->totalRegistered;
        $totalCaptured = $approximateTotals->totalCaptured - $approximateTotals->totalRefunded - $approximateTotals->totalChargedback - $approximateTotals->totalReversed;

        return $totalRegistered <= $totalCaptured;
    }

    /**
     * Docdata returns an array of payments when you do several attempts. It returns 1 object if there was 1 attempt.
     * Get the most recent payment, as all previous ones should be unsuccessful.
     * When there is a successful attempt the user is returned to payment service.
     * The only issue could be bank transfers. No clue how that is handled.
     *
     * @return \stdClass payment information
     */
    protected function getMostRecentPayment()
    {
        $oneOrSeveralPayments = $this->data->statusSuccess->report->payment;

        if (is_array($oneOrSeveralPayments)) {
            return end($oneOrSeveralPayments);
        }

        return $oneOrSeveralPayments;
    }

}
