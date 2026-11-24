<?php

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

namespace Meta\Promotions\Test\Unit\Model\Promotion\Feed;

use PHPUnit\Framework\TestCase;
use Meta\Promotions\Model\Promotion\Feed\Method\FeedApi as MethodFeedApi;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Promotions\Model\Promotion\Feed\Uploader;

class UploaderTest extends TestCase
{

    /**
     * @var SystemConfig
     */
    private $systemConfigMock;

    /**
     * @var MethodFeedApi
     */
    private $feedMethodApiMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var object
     */
    private $subject;


    public function setUp():void
    {
        $this->systemConfigMock = $this->getMockBuilder(SystemConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->feedMethodApiMock = $this->getMockBuilder(MethodFeedApi::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);
        $this->subject = $this->objectManager->getObject(Uploader::class, [
            'systemConfig' => $this->systemConfigMock,
            'methodFeedApi' => $this->feedMethodApiMock,
        ]);
    }

    public function testUploadPromotions()
    {
        $storeId = 1;
        $this->feedMethodApiMock->expects($this->once())
            ->method('execute')
            ->with($storeId)
            ->willReturn(true);
        $this->subject->uploadPromotions($storeId);
    }
}
