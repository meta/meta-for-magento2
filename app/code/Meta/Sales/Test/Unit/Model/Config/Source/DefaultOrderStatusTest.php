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

namespace Meta\Sales\Test\Unit\Model\Config\Source;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Sales\Model\Config\Source\DefaultOrderStatus;

class DefaultOrderStatusTest extends TestCase
{
    private $object;
    private $subject;
    public function setUp(): void
    {
        $this->object = new ObjectManager($this);
        $this->subject = $this->object->getObject(DefaultOrderStatus::class);
    }

    public function testToOptionArray(): void
    {
        $options = [
            ['value' => DefaultOrderStatus::ORDER_STATUS_PENDING, 'label' => __('Pending')],
            ['value' => DefaultOrderStatus::ORDER_STATUS_PROCESSING, 'label' => __('Processing')],
        ];
        $this->assertEquals($options, $this->subject->toOptionArray());
    }
}
