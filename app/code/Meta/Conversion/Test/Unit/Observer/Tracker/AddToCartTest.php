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

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Observer\Tracker\AddToCart;
use Meta\Conversion\Model\CapiTracker;
use Meta\Conversion\Model\Tracker\AddToCart as AddToCartTracker;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event;
use Magento\Catalog\Model\Product;

class AddToCartTest extends TestCase
{
    private $addToCartTrackerMock;
    private $capiTrackerMock;
    private $subject;

    public function setUp(): void
    {
        $this->addToCartTrackerMock = $this->getMockBuilder(AddToCartTracker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->capiTrackerMock = $this->getMockBuilder(CapiTracker::class)
            ->disableOriginalConstructor()
            ->getMock();

        $object = new ObjectManager($this);
        $this->subject = $object->getObject(AddToCart::class, [
            'addToCartTracker' => $this->addToCartTrackerMock,
            'capiTracker' => $this->capiTrackerMock
        ]);
    }

    public function testExecute()
    {
        $eventType = 'addToCart';
        $productId = 1;
        $payload = [
            'product_id' => $productId,
            'qty' => 1,
            'event_type' => $eventType
        ];
        $observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock = $this->getMockBuilder(Event::class)
            ->addMethods(['getProduct'])
            ->disableOriginalConstructor()
            ->getMock();
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $observerMock->expects($this->once())->method('getEvent')->willReturn($eventMock);
        $eventMock->expects($this->once())->method('getProduct')->willReturn($productMock);
        $productMock->expects($this->once())->method('getId')->willReturn($productId);
        $this->addToCartTrackerMock->expects($this->once())->method('getPayload')->with(['productId' => $productId])->willReturn($payload);
        $this->addToCartTrackerMock->expects($this->once())->method('getEventType')->willReturn($eventType);
        $this->capiTrackerMock->expects($this->once())->method('execute')->with($payload, AddToCart::EVENT_NAME, $eventType, true);

        $this->subject->execute($observerMock);
    }
}
