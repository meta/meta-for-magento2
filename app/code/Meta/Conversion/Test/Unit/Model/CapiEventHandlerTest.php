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

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Model\CapiEventHandler;
use Meta\Conversion\Helper\ServerSideHelper;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\Conversion\Helper\ServerEventFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class CapiEventHandlerTest extends TestCase
{
    private $serverSideHelperMock;
    private $fbeHelperMock;
    private $serverEventFactoryMock;
    private $jsonSerializerMock;
    private $subject;

    public function setUp(): void
    {
        $this->serverSideHelperMock = $this->getMockBuilder(ServerSideHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->fbeHelperMock = $this->getMockBuilder(FBEHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->serverEventFactoryMock = $this->getMockBuilder(ServerEventFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->jsonSerializerMock = $this->getMockBuilder(JsonSerializer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $object = new ObjectManager($this);
        $this->subject = $object->getObject(CapiEventHandler::class, [
            'serverSideHelper' => $this->serverSideHelperMock,
            'fbeHelper' => $this->fbeHelperMock,
            'serverEventFactory' => $this->serverEventFactoryMock,
            'jsonSerializer' => $this->jsonSerializerMock
        ]);
    }

    public function testProcess()
    {
        $message = '{"event_id":"kjfabfkhba-afkbahb","event_type":"addtocart","event":"addtocart","sku":"test_sku","qty":1}';
        $payload = [
            'event_id' => 'kjfabfkhba-afkbahb',
            'event_type' => 'addtocart',
            'sku' => 'test_sku',
            'qty' => 1,
            'event' => 'addtocart',
        ];

        $this->jsonSerializerMock->expects($this->once())
            ->method('unserialize')
            ->with($message)
            ->willReturn($payload);

        $this->fbeHelperMock->expects($this->once())
            ->method('getSource')
            ->willReturn('adobe_commerce');

        $this->fbeHelperMock->expects($this->once())
            ->method('getPluginVersion')
            ->willReturn('1.0.0');


        $this->subject->process($message);
    }

    public function testProcessUsingCustomData()
    {
        $message = '{"event_id":"kjfabfkhba-afkbahb","event_type":"addtocart","event":"addtocart","sku":"test_sku","qty":1,"userDataFromOrder":"{"name":"john doe"}"}';
        $payload = [
            'event_id' => 'kjfabfkhba-afkbahb',
            'event_type' => 'addtocart',
            'sku' => 'test_sku',
            'qty' => 1,
            'event' => 'addtocart',
            'userDataFromOrder' => ['name' => 'john doe']
        ];

        $this->jsonSerializerMock->expects($this->once())
            ->method('unserialize')
            ->with($message)
            ->willReturn($payload);

        $this->fbeHelperMock->expects($this->once())
            ->method('getSource')
            ->willReturn('adobe_commerce');

        $this->fbeHelperMock->expects($this->once())
            ->method('getPluginVersion')
            ->willReturn('1.0.0');


        $this->subject->process($message);
    }

    public function testProcessWithException()
    {
        $message = '{"event_type":"addtocart","event":"addtocart","sku":"test_sku","qty":1,"userDataFromOrder":"{"name":"john doe"}"}';
        $payload = [
            'event_type' => 'addtocart',
            'sku' => 'test_sku',
            'qty' => 1,
            'event' => 'addtocart',
            'userDataFromOrder' => ['name' => 'john doe']
        ];

        $this->jsonSerializerMock->expects($this->once())
            ->method('unserialize')
            ->with($message)
            ->willReturn($payload);

        $this->fbeHelperMock->expects($this->once())
            ->method('logException')
            ->with($this->callback(function ($exception) {
                return $exception instanceof \Exception &&
                    str_contains($exception->getMessage(), 'event_id');
            }));

        $this->subject->process($message);
    }
}
