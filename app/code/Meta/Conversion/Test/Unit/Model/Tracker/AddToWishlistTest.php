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
use Meta\Conversion\Model\Tracker\AddToWishlist;

class AddToWishlistTest extends TestCase
{
    private $subject;

    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(AddToWishlist::class);
    }

    public function testGetEventType()
    {
        $this->assertEquals('AddToWishlist', $this->subject->getEventType());
    }

    public function testGetPayload()
    {
        $param = [
            'value' => 10,
            'currency' => 'USD',
            'content_ids' => '123',
            'content_category' => 'category-1',
            'content_name' => 'product-name',
            'contents' => [
                [
                    'id' => 123,
                    'quantity' => 1
                ]
            ]
        ];
        $payload = [
            'value' => 10,
            'currency' => 'USD',
            'content_ids' => '123',
            'content_category' => 'category-1',
            'content_name' => 'product-name',
            'contents' => [
                [
                    'product_id' => 123,
                    'quantity' => 1
                ]
            ]
        ];
        $this->assertEquals($payload, $this->subject->getPayload($param));
    }
}
