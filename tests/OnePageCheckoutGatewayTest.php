<?php

namespace Omnipay\DocdataPayments\Tests;

use Omnipay\DocdataPayments\Message\CreateRequest;
use Omnipay\DocdataPayments\OnePageCheckoutGateway;
use PHPUnit\Framework\TestCase;

class OnePageCheckoutGatewayTest extends TestCase
{
    public function testPurchaseCreatesOnePageCreateRequest(): void
    {
        $gateway = new OnePageCheckoutGateway();

        $this->assertInstanceOf(CreateRequest::class, $gateway->purchase());
    }
}
