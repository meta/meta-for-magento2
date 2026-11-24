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

namespace Meta\BusinessExtension\Test\Unit\Helper;

use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Helper\GraphAPIConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class GraphAPIConfigTest extends TestCase
{
    /**
     * Setup function
     */
    protected function setUp(): void
    {
        $this->scopeConfigInterface = $this->createMock(ScopeConfigInterface::class);
        $this->context = $this->createMock(Context::class);
        $this->context->expects($this->once())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfigInterface);

        $objectManager = new ObjectManager($this);
        $this->graphAPIConfigMockObj = $objectManager->getObject(
            GraphAPIConfig::class,
            [
                'context' => $this->context
            ]
        );
    }

    /**
     * Test getGraphBaseURL function
     *
     * @return void
     */
    public function testGetGraphBaseURL(): void
    {
        $this->assertSame('https://graph.facebook.com/', $this->graphAPIConfigMockObj->getGraphBaseURL());
    }

    /**
     * Test getGraphBaseURL function
     *
     * @return void
     */
    public function testGetGraphBaseURLWithOverride(): void
    {
        $this->scopeConfigInterface->expects($this->once())
            ->method('getValue')
            ->willReturn('https://graph.facebook.com/v20.3/');
        $this->assertSame('https://graph.facebook.com/v20.3/', $this->graphAPIConfigMockObj->getGraphBaseURL());
    }
}
