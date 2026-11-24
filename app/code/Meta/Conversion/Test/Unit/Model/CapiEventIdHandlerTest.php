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
use Meta\Conversion\Model\CapiEventIdHandler;

class CapiEventIdHandlerTest extends TestCase
{
    private $subject;

    public function setUp(): void
    {
        $object = new ObjectManager($this);
        $this->subject = $object->getObject(CapiEventIdHandler::class);
    }

    public function testMetaEventId()
    {
        $eventName = 'test_event_name';
        $eventId = 'test_event_id';
        $this->subject->setMetaEventId($eventName, $eventId);
        $this->assertEquals($eventId, $this->subject->getMetaEventId($eventName));
    }

    public function testMissingEventId()
    {
        $eventName = 'test_event_name';
        $eventId = 'test_event_id';
        $this->subject->setMetaEventId($eventName, $eventId);
        $this->assertNull($this->subject->getMetaEventId('new-event'));
    }
}
