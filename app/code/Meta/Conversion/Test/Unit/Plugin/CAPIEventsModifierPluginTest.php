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

namespace Meta\Conversion\Test\Unit\Plugin;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Plugin\CAPIEventsModifierPlugin;
use FacebookAds\Object\ServerSide\Event;
use Meta\Conversion\Helper\ServerSideHelper;

class CAPIEventsModifierPluginTest extends TestCase
{
    private $eventMock;
    private $serverSideHelperMock;
    private $subject;

    public function setUp(): void
    {
        $this->eventMock = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->serverSideHelperMock = $this->getMockBuilder(ServerSideHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(CAPIEventsModifierPlugin::class);
    }

    public function testBeforeSendEventWithNullData()
    {
        $this->assertEquals([$this->eventMock, null], $this->subject->beforeSendEvent($this->serverSideHelperMock, $this->eventMock));
    }

    public function testBeforeSendEvent()
    {
        $userData = [
            'name' => 'John Doe'
        ];
        $this->assertEquals([$this->eventMock, $userData], $this->subject->beforeSendEvent($this->serverSideHelperMock, $this->eventMock, $userData));
    }

}
