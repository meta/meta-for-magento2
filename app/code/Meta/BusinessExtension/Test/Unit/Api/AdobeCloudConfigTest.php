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

namespace Meta\BusinessExtension\Test\Unit\Api;

use Meta\BusinessExtension\Model\Api\AdobeCloudConfig;
use Meta\BusinessExtension\Api\AdobeCloudConfigInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class AdobeCloudConfigTest extends TestCase
{
    /**
     * @var AdobeCloudConfig
     */
    private $adobeCloudConfig;

    /**
     * Class setup function
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $objectManager = new ObjectManager($this);
        
        $this->adobeCloudConfig = $objectManager->getObject(
            AdobeCloudConfig::class
        );
    }

    /**
     * Validate if seller is on Cloud
     *
     * @return void
     */
    public function testIsSellerOnAdobeCloudReturnsTrueWhenEnvVarIsSet(): void
    {
        /** check if the envinorment is non-premise */
        $_ENV['MAGENTO_CLOUD_ENVIRONMENT'] = 'MAGENTO_CLOUD_ENVIRONMENT';

        $this->assertTrue($this->adobeCloudConfig->isSellerOnAdobeCloud());
        unset($_ENV['MAGENTO_CLOUD_ENVIRONMENT']);
    }

    /**
     * Validate if seller is on-premise
     *
     * @return void
     */
    public function testIsSellerOnAdobeCloudReturnsFalseWhenEnvVarIsNotSet(): void
    {
        /** check if the envinorment is on-premise */
        unset($_ENV['MAGENTO_CLOUD_ENVIRONMENT']);
        
        $this->assertFalse($this->adobeCloudConfig->isSellerOnAdobeCloud());
    }

    /**
     * Validate if seller is on-premise
     *
     * @return void
     */
    public function testGetCommercePartnerSellerPlatformType(): void
    {
        /** check if the envinorment is on-premise */
        $_ENV['MAGENTO_CLOUD_ENVIRONMENT'] = 'MAGENTO_CLOUD_ENVIRONMENT';
        
        $this->assertSame(AdobeCloudConfigInterface::ADOBE_COMMERCE_CLOUD, $this->adobeCloudConfig->getCommercePartnerSellerPlatformType());
    }

    /**
     * Validate if seller is on-premise
     *
     * @return void
     */
    public function testGetCommercePartnerSellerPlatformTypeIsNotOnCloud(): void
    {
        /** check if the envinorment is on-premise */
        unset($_ENV['MAGENTO_CLOUD_ENVIRONMENT']);
        
        $this->assertSame(AdobeCloudConfigInterface::MAGENTO_OPEN_SOURCE, $this->adobeCloudConfig->getCommercePartnerSellerPlatformType());
    }
}
