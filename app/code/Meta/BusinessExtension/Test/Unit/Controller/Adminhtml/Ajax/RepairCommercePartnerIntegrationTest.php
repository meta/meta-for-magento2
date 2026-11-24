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

namespace Meta\BusinessExtension\Test\Unit\Controller\Adminhtml\Ajax;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Meta\BusinessExtension\Controller\Adminhtml\Ajax\RepairCommercePartnerIntegration;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\BusinessExtension\Model\MBEInstalls;
use Magento\Framework\Exception\LocalizedException;

class RepairCommercePartnerIntegrationTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp():void
    {
        $this->resultJsonFactory = $this->createMock(JsonFactory::class);
        $this->fbeHelper = $this->createMock(FBEHelper::class);
        $this->mbeInstalls = $this->createMock(MBEInstalls::class);
        $this->request = $this->createMock(RequestInterface::class);
        
        $objectManager = new ObjectManager($this);
        $this->repairCommercePartnerIntegration = $objectManager->getObject(
            RepairCommercePartnerIntegration::class,
            [
                'resultJsonFactory' => $this->resultJsonFactory,
                'fbeHelper' => $this->fbeHelper,
                'mbeInstalls' => $this->mbeInstalls,
                'request' => $this->request,
            ]
        );
    }

    /**
     * Test execute function
     *
     * @return void
     */
    public function testExecute():void
    {
        $storeId = 1;
        $json = $this->createMock(Json::class);
        $this->resultJsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($json);
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('storeId')
            ->willReturn($storeId);
        $this->mbeInstalls->expects($this->once())
            ->method('repairCommercePartnerIntegration')
            ->with($storeId)
            ->willReturn(true);

        $result = $this->repairCommercePartnerIntegration->execute();
    }

    /**
     * Test execute function
     *
     * @return void
     */
    public function testExecuteWithEmptyStoreId():void
    {
        $storeId = "";
        $json = $this->createMock(Json::class);
        $this->resultJsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($json);
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('storeId')
            ->willReturn($storeId);

        $result = $this->repairCommercePartnerIntegration->execute();
    }

    /**
     * Test execute function
     *
     * @return void
     */
    public function testExecuteWithException():void
    {
        $storeId = 1;
        $json = $this->createMock(Json::class);
        $this->resultJsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($json);
        $this->request->expects($this->exactly(2))
            ->method('getParam')
            ->with('storeId')
            ->willReturn($storeId);

        $this->mbeInstalls->expects($this->once())
            ->method('repairCommercePartnerIntegration')
            ->with($storeId)
            ->willThrowException(new \Exception("Error Occured"));

        $this->fbeHelper->expects($this->once())
            ->method('logExceptionImmediatelyToMeta')
            ->with(
                $this->isInstanceOf(\Exception::class),
                [
                    'store_id' => $storeId,
                    'event' => 'repair_cpi_fail',
                    'event_type' => 'save_config'
                ]
            );
        $this->expectException(LocalizedException::class);
        $result = $this->repairCommercePartnerIntegration->execute();
    }
}
