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

namespace Meta\Conversion\Test\Unit\Model\Tracker;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Model\Tracker\AddPaymentInfo;

class AddPaymentInfoTest extends TestCase
{
    private const EVENT_TYPE = "AddPaymentInfo";
    private $subject;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(AddPaymentInfo::class);
    }

    public function testGetEventType()
    {
        $this->assertEquals(self::EVENT_TYPE, $this->subject->getEventType());
    }

    public function testGetPayload()
    {
        $params = [
            'contents' => [
                [
                    'id' => 1,
                    'quantity' => 1
                ]
            ],
            'currency' => 'USD',
            'value' => 10,
            'content_type' => 'Product',
            'content_ids' => [1],
            'content_category' => 'test_category'
        ];

        $payload = [
            'currency' => 'USD',
            'value' => 10,
            'content_type' => 'Product',
            'contents' => [
                [
                    'product_id' => 1,
                    'quantity' => 1
                ]
            ],
            'content_ids' => [1],
            'content_category' => 'test_category'
        ];
        $this->assertEquals($payload, $this->subject->getPayload($params));
    }
}
