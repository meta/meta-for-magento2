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
use Magento\Framework\Event\ManagerInterface as EventManager;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Meta\BusinessExtension\Helper\CatalogConfigUpdateHelper;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\BusinessExtension\Helper\FBEHelper;

class CatalogConfigUpdateHelperTest extends TestCase
{
    /**
     * @var FBEHelper
     */
    private $fbeHelper;

    /**
     * @var SystemConfig
     */
    private $systemConfig;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->fbeHelper = $this->createMock(FBEHelper::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $this->eventManager = $this->createMock(EventManager::class);

        $objectManager = new ObjectManager($this);
        $this->catalogConfigUpdateHelperMockObj = $objectManager->getObject(
            CatalogConfigUpdateHelper::class,
            [
                'fbeHelper' => $this->fbeHelper,
                'systemConfig' => $this->systemConfig,
                'eventManager' => $this->eventManager
            ]
        );
    }

    /**
     * Test updateCatalogConfiguration function
     *
     * @return void
     */
    public function testUpdateCatalogConfiguration(): void
    {
        $storeId = 99;
        $oldCatalogId = 1;
        $catalogId = '10';
        $pixelId = '234';
        $triggerFullSync = false;
        $commercePartnerIntegrationId = 'meta_id';

        $this->systemConfig->expects($this->once())
            ->method('getCatalogId')
            ->with($this->equalTo($storeId))
            ->willReturn($oldCatalogId);

        $this->systemConfig->expects($this->exactly(3))
            ->method('saveConfig');

        $this->systemConfig->expects($this->once())
            ->method('cleanCache');

        $this->catalogConfigUpdateHelperMockObj->updateCatalogConfiguration(
            $storeId,
            $catalogId,
            $commercePartnerIntegrationId,
            $pixelId,
            $triggerFullSync
        );
    }

    /**
     * Test updateCatalogConfiguration function
     *
     * @return void
     */
    public function testUpdateCatalogConfigurationException(): void
    {
        $storeId = 99;
        $oldCatalogId = 1;
        $catalogId = '10';
        $pixelId = '234';
        $triggerFullSync = false;
        $commercePartnerIntegrationId = 'meta_id';

        $this->systemConfig->expects($this->once())
            ->method('getCatalogId')
            ->with($this->equalTo($storeId))
            ->willReturn($oldCatalogId);

        $this->systemConfig->expects($this->exactly(3))
            ->method('saveConfig');

        $exception = new \Exception('Unable to clean cache');
        $this->systemConfig->expects($this->once())
            ->method('cleanCache')
            ->willThrowException($exception);

        $this->fbeHelper->expects($this->once())
            ->method('logExceptionImmediatelyToMeta')
            ->with(
                $exception,
                [
                    'store_id' => $storeId,
                    'event' => 'update_catalog_config',
                    'event_type' => 'update_catalog_config',
                    'catalog_id' => $catalogId,
                    'old_catalog_id' => $oldCatalogId,
                    'commerce_partner_integration_id' => $commercePartnerIntegrationId,
                    'pixel_id' => $pixelId
                ]
            );

        $this->catalogConfigUpdateHelperMockObj->updateCatalogConfiguration(
            $storeId,
            $catalogId,
            $commercePartnerIntegrationId,
            $pixelId,
            $triggerFullSync
        );
    }
}
