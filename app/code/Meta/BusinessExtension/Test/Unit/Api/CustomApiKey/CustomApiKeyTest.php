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

namespace Meta\BusinessExtension\Test\Unit\Api\CustomApiKey;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Meta\BusinessExtension\Model\Api\CustomApiKey\Authenticator;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Helper\FBEHelper;

class CustomApiKeyTest extends TestCase
{
    /**
     * @var MockObject
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var Http
     */
    private Http $httpRequest;

    /**
     * @var MockObject
     */
    private SystemConfig $systemConfig;

    /**
     * @var MockObject
     */
    private FBEHelper $fbeHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scopeConfig = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->httpRequest = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->systemConfig = $this->getMockBuilder(SystemConfig::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->fbeHelper = $this->getMockBuilder(FBEHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAuthenticateApiKeyFailed()
    {
        $this->expectException(LocalizedException::class);

        $apiKey = 'generated-api-key';
        $wrongApiKey = 'wrong-api-key';
        $this->scopeConfig->method('getValue')
            ->with('meta_extension/general/api_key')
            ->willReturn($apiKey);

        // Define the mock behavior
        $this->httpRequest->method('getHeader')
            ->with('Meta-extension-token') // Expect the getHeader method to be called with this argument.
            ->willReturn($wrongApiKey); // Return this value when the above is called.
        $authenticator = new Authenticator(
            $this->scopeConfig,
            $this->httpRequest,
            $this->systemConfig,
            $this->fbeHelper
        );
        $authenticator->authenticateRequestDangerouslySkipSignatureValidation();
    }

    public function testAuthenticateApiKeySuccess()
    {
        $this->expectNotToPerformAssertions();
        $apiKey = 'generated-api-key';
        $this->scopeConfig->method('getValue')
            ->with('meta_extension/general/api_key')
            ->willReturn($apiKey);
        // Define the mock behavior
        $this->httpRequest->method('getHeader')
            ->with('Meta-extension-token') // Expect the getHeader method to be called with this argument.
            ->willReturn($apiKey); // Return this value when the above is called.
        $authenticator = new Authenticator(
            $this->scopeConfig,
            $this->httpRequest,
            $this->systemConfig,
            $this->fbeHelper
        );
        $authenticator->authenticateRequestDangerouslySkipSignatureValidation();
    }
}
