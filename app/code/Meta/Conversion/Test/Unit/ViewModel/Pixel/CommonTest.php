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

namespace Meta\Conversion\Test\Unit\ViewModel\Pixel;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\ViewModel\Pixel\Common;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;

class CommonTest extends TestCase
{
    private $systemConfigMock;
    private $subject;

    public function setUp(): void
    {
        $this->systemConfigMock = $this->getMockBuilder(SystemConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(Common::class, ['systemConfig' => $this->systemConfigMock]);
    }

    public function testGetFacebookPixelID()
    {
        $pixelId = '12345-234-sdfgt-234';
        $this->systemConfigMock->expects($this->once())
            ->method('getPixelId')
            ->willReturn($pixelId);
        $this->assertEquals($pixelId, $this->subject->getFacebookPixelID());
    }
}
