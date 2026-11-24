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
use Meta\Conversion\Model\Tracker\CustomizeProduct;
use Meta\Conversion\Helper\MagentoDataHelper;

class CustomizeProductTest extends TestCase
{
    private $magentoDataHelperMock;
    private $subject;

    public function setUp(): void
    {
        $this->magentoDataHelperMock = $this->getMockBuilder(MagentoDataHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(CustomizeProduct::class, ['magentoDataHelper' => $this->magentoDataHelperMock]);
    }

    public function testGetEventType()
    {
        $this->assertEquals('CustomizeProduct', $this->subject->getEventType());
    }

    public function testGetPayload()
    {
        $currency = 'USD';
        $payload = [
            'content_type' => 'product',
            'content_name' => 'test-sku',
            'content_ids' => [123],
            'currency' => $currency,
            'value' => 10.00
        ];

        $this->magentoDataHelperMock->expects($this->once())
            ->method('getCurrency')
            ->willReturn($currency);

        $this->assertEquals($payload, $this->subject->getPayload($payload));
    }
}
