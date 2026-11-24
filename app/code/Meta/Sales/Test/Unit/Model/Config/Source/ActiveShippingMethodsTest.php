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
use Meta\Sales\Model\Config\Source\ActiveShippingMethods;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Shipping\Model\Carrier\AbstractCarrierInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ActiveShippingMethodsTest extends TestCase
{
    private $scopeConfigMock;
    private $shippingConfigMock;
    private $object;
    private $subject;
    
    public function setUp(): void
    {

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->shippingConfigMock = $this->getMockBuilder(ShippingConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->object = new ObjectManager($this);
        $this->subject = $this->object->getObject(ActiveShippingMethods::class, [
            'scopeConfig' => $this->scopeConfigMock,
            'shippingConfig' => $this->shippingConfigMock
        ]);
    }

    public function testToOptionArray()
    {
        $carrierMock = $this->getMockBuilder(AbstractCarrierInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->shippingConfigMock->expects($this->once())
            ->method('getAllCarriers')
            ->willReturn([$carrierMock]);

        $this->subject->toOptionArray();
    }
}
