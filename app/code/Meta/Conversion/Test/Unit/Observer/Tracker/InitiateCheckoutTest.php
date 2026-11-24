<?php

declare(strict_types=1);

/**
 * Copyright (c) Meta Platforms, Inc. and affiliates.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Meta\Conversion\Test\Unit\Observer\Tracker;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Model\CapiTracker;
use Meta\Conversion\Model\Tracker\InitiateCheckout as InitiateCheckoutTracker;
use Meta\Conversion\Observer\Tracker\InitiateCheckout;
use PHPUnit\Framework\TestCase;

class InitiateCheckoutTest extends TestCase
{
    private $initiateCheckoutTrackerMock;
    private $checkoutSessionMock;
    private $capiTrackerMock;
    private $subject;

    public function setUp(): void
    {
        $this->initiateCheckoutTrackerMock = $this->getMockBuilder(InitiateCheckoutTracker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->checkoutSessionMock = $this->getMockBuilder(CheckoutSession::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->capiTrackerMock = $this->getMockBuilder(CapiTracker::class)
            ->disableOriginalConstructor()
            ->getMock();

        $object = new ObjectManager($this);
        $this->subject = $object->getObject(InitiateCheckout::class,[
            'initiateCheckoutTracker' => $this->initiateCheckoutTrackerMock,
            'checkoutSession' => $this->checkoutSessionMock,
            'capiTracker' => $this->capiTrackerMock
        ]);
    }

    public function testExecute()
    {
        $quoteId = 1;
        $eventType = 'InitiateCheckout';
        $payload = [
            'event_id' => 'akfba-afbakb-q4eqr',
            'event_name' => 'initiate checkout',
            'event_type' => $eventType
        ];

        $observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->checkoutSessionMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($quoteId);
        $this->initiateCheckoutTrackerMock->expects($this->once())
            ->method('getPayload')
            ->with(['quoteId' => $quoteId])
            ->willReturn($payload);
        $this->initiateCheckoutTrackerMock->expects($this->once())
            ->method('getEventType')
            ->willReturn($eventType);
        $this->capiTrackerMock->expects($this->once())
            ->method('execute')
            ->with($payload, InitiateCheckout::EVENT_NAME, $eventType);

        $this->subject->execute($observerMock);
    }
}
