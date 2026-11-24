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

namespace Meta\Conversion\Test\Unit\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Meta\Conversion\Model\CapiEventIdHandler;
use Meta\Conversion\Model\CapiTracker;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CapiTrackerTest extends TestCase
{
    private $customerSessionMock;
    private $capiEventIdHandlerMock;
    private $jsonSerializerMock;
    private $publisherMock;
    private $subject;

    public function setUp(): void
    {
        $this->customerSessionMock = $this->getMockBuilder(Session::class)
            ->addMethods(['setMetaEventIds'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->capiEventIdHandlerMock = $this->getMockBuilder(CapiEventIdHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jsonSerializerMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->publisherMock = $this->getMockBuilder(PublisherInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(CapiTracker::class, [
            'customerSession' => $this->customerSessionMock,
            'capiEventIdHandler' => $this->capiEventIdHandlerMock,
            'jsonSerializer' => $this->jsonSerializerMock,
            'publisher' => $this->publisherMock
        ]);
    }

    public function testExecuteWithSessionStorage()
    {
        $eventName = 'test_event';
        $eventType = 'event_type';
        $payload = ['sku' => 'ABC123'];

        $savedEventIds = [];

        $this->customerSessionMock->expects($this->once())
            ->method('setMetaEventIds')
            ->willReturnCallback(function ($value) use (&$savedEventIds) {
                $savedEventIds = $value;
            });

        $this->jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->willReturnCallback(function ($data) use (&$serializedData) {
                $serializedData = $data;
                return json_encode($data);
            });

        $this->publisherMock->expects($this->once())
            ->method('publish');

        $this->subject->execute($payload, $eventName, $eventType, true);

        $this->assertArrayHasKey($eventName, $savedEventIds);
        $this->assertEquals($savedEventIds[$eventName], $serializedData['event_id']);
    }

    public function testExecuteWithHandlerStorage()
    {
        $eventName = 'event_name';
        $eventType = 'event_type';
        $payload = ['sku' => 'XYZ789'];

        $capturedEventId = null;
        $serializedPayload = null;

        $this->capiEventIdHandlerMock->expects($this->once())
            ->method('setMetaEventId')
            ->willReturnCallback(function ($passedEventName, $eventId) use ($eventName, &$capturedEventId) {
                // Capture the event ID for assertion
                $this->assertEquals($eventName, $passedEventName);
                $capturedEventId = $eventId;
            });

        $this->jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->willReturnCallback(function ($data) use (&$serializedPayload) {
                $serializedPayload = $data;
                return json_encode($data);
            });

        $this->publisherMock->expects($this->once())
            ->method('publish')
            ->with('send.conversion.event.to.meta', $this->isType('string'));

        $this->subject->execute($payload, $eventName, $eventType, false);

        $this->assertArrayHasKey('event_id', $serializedPayload);
        $this->assertEquals($capturedEventId, $serializedPayload['event_id']);
    }
}
