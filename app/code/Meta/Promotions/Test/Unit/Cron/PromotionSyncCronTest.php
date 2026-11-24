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

namespace Meta\Promotions\Test\Unit\Cron;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Promotions\Cron\PromotionSyncCron;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\Promotions\Model\Promotion\Feed\Uploader;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Magento\Store\Api\Data\StoreInterface;

class PromotionSyncCronTest extends TestCase
{
    private $fbeHelperMock;
    private $systemConfigMock;
    private $uploaderMock;
    private $object;
    private $subject;

    public function setUp(): void
    {
        $this->fbeHelperMock = $this->getMockBuilder(FBEHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->systemConfigMock = $this->getMockBuilder(SystemConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->uploaderMock = $this->getMockBuilder(Uploader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new ObjectManager($this);
        $this->subject = $this->object->getObject(PromotionSyncCron::class, [
            'systemConfig' => $this->systemConfigMock,
            'uploader' => $this->uploaderMock,
            'fbeHelper' => $this->fbeHelperMock
        ]);
    }

    public function testExecute(): void
    {
        $storeId = 1;
        $uploadResponse = ['Success' => true];

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->systemConfigMock->expects($this->once())
            ->method('getAllOnsiteFBEInstalledStores')
            ->willReturn([$storeMock]);

        $storeMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($storeId);
        $this->systemConfigMock->expects($this->once())
            ->method('isPromotionsSyncEnabled')
            ->with($storeId)
            ->willReturn(true);
        $this->uploaderMock->expects($this->once())
            ->method('uploadPromotions')
            ->with($storeId)
            ->willReturn($uploadResponse);

        $this->subject->execute();
    }

    public function testExecuteException(): void
    {
        $storeId = 1;
        $uploadResponse = ['Success' => true];

        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->systemConfigMock->expects($this->once())
            ->method('getAllOnsiteFBEInstalledStores')
            ->willReturn([$storeMock]);
        $storeMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($storeId);
        $this->systemConfigMock->expects($this->once())
            ->method('isPromotionsSyncEnabled')
            ->with($storeId)
            ->willThrowException(new \Exception('Unable to fetch data'));

        $this->subject->execute();
    }
}
