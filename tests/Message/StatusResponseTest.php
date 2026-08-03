<?php

namespace Omnipay\DocdataPayments\Tests\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\DocdataPayments\Message\StatusResponse;
use PHPUnit\Framework\TestCase;

class StatusResponseTest extends TestCase
{
    public function testPendingUsesMostRecentPaymentAttempt(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $data = json_decode(json_encode([
            'statusSuccess' => [
                'success' => ['code' => 'SUCCESS'],
                'report' => [
                    'payment' => [
                        [
                            'paymentMethod' => 'CREDITCARD',
                            'authorization' => ['status' => 'CANCELED'],
                        ],
                        [
                            'paymentMethod' => 'BANK_TRANSFER',
                            'authorization' => ['status' => 'AUTHORIZED'],
                        ],
                    ],
                    'approximateTotals' => [
                        'totalRegistered' => 100,
                        'totalCaptured' => 0,
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        $response = new StatusResponse($request, $data);

        $this->assertTrue($response->isPending());
    }
}
