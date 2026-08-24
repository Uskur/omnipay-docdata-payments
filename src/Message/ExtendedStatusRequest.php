<?php

namespace Omnipay\DocdataPayments\Message;

use SoapClient;
use stdClass;

/**
 * Request the detailed status of a Docdata payment order.
 */
class ExtendedStatusRequest extends StatusRequest
{
    /**
     * {@inheritdoc}
     */
    protected function runTransaction(SoapClient $soapClient, array $data): stdClass
    {
        return $soapClient->__soapCall('statusExtended', [$data]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getResponseName(): string
    {
        return ExtendedStatusResponse::class;
    }
}
