<?php

namespace Omnipay\DocdataPayments\Tests;

use Omnipay\DocdataPayments\Message\CreateRequest;
use Omnipay\DocdataPayments\Message\ExtendedStatusRequest;
use Omnipay\DocdataPayments\OnePageCheckoutGateway;
use PHPUnit\Framework\TestCase;

class OnePageCheckoutGatewayTest extends TestCase
{
    public function testPurchaseCreatesOnePageCreateRequest(): void
    {
        $gateway = new OnePageCheckoutGateway();

        $this->assertInstanceOf(CreateRequest::class, $gateway->purchase());
    }

    public function testExtendedStatusCreatesExtendedStatusRequest(): void
    {
        $gateway = new OnePageCheckoutGateway();

        $this->assertInstanceOf(ExtendedStatusRequest::class, $gateway->extendedStatus());
    }
}
