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

namespace Meta\BusinessExtension\Test\Unit\Block\Adminhtml\System\Config;

use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Block\Adminhtml\System\Config\ManualDataSyncEmpty;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Block\Template\Context;

class ManualDataSyncEmptyTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(RequestInterface::class);
        $context = $this->createMock(Context::class);
        $context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->requestMock);
        $objectManager = new ObjectManager($this);
        $this->manualDataSyncEmptyMockObj = $objectManager->getObject(
            ManualDataSyncEmpty::class,
            [
                'context' => $context
            ]
        );
    }

    /**
     * Test getStoreId function
     *
     * @return void
     */
    public function testGetStoreId(): void
    {
        $storeId = 123;
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('store')
            ->willReturn($storeId);

        $this->assertEquals($this->manualDataSyncEmptyMockObj->getStoreId(), $storeId);
    }
}
