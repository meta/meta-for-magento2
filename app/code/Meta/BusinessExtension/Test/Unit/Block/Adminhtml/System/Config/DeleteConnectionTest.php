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
use Meta\BusinessExtension\Block\Adminhtml\System\Config\DeleteConnection;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Backend\Block\Widget\Button;

class DeleteConnectionTest extends TestCase
{
    /**
     * @var UrlInterface
     */
    private $urlBuilderMock;

    /**
     * @var RequestInterface
     */
    private $requestMock;

    /**
     * @var LayoutInterface
     */
    private $layoutMock;

    /**
     * @var Button
     */
    private $buttonBlockMock;

    /**
     * @var DeleteConnection
     */
    private $deleteConnectionMockObj;

    /**
     * @var SystemConfig
     */
    private $systemConfig;

    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->layoutMock = $this->createMock(LayoutInterface::class);
        $this->buttonBlockMock = $this->createMock(Button::class);

        $context = $this->createMock(Context::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);
        $context->expects($this->once())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);
        $context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->requestMock);
        $context->expects($this->once())
            ->method('getLayout')
            ->willReturn($this->layoutMock);

        $objectManager = new ObjectManager($this);
        $this->deleteConnectionMockObj = $objectManager->getObject(
            DeleteConnection::class,
            [
                'context' => $context,
                'systemConfig' => $this->systemConfig,
                'data' => []
            ]
        );
    }

    /**
     * Test getAjaxUrl function
     *
     * @return void
     */
    public function testGetAjaxUrl(): void
    {
        $storeId = 123;
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('store')
            ->willReturn($storeId);
        $returnValue = 'https://meta.com/fbeadmin/ajax/fbdeleteasset';
        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('fbeadmin/ajax/fbdeleteasset', ['storeId' => $storeId])
            ->willReturn($returnValue);

        $actualReturnValue = $this->deleteConnectionMockObj->getAjaxUrl();

        $this->assertEquals($returnValue, $actualReturnValue);
    }

    /**
     * Test getAjaxUrl function
     *
     * @return void
     */
    public function testGetAjaxUrlWithNullStoreId(): void
    {
        $storeId = null;
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('store')
            ->willReturn($storeId);
        $returnValue = 'https://meta.com/fbeadmin/ajax/fbdeleteasset';
        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('fbeadmin/ajax/fbdeleteasset', ['storeId' => $storeId])
            ->willReturn($returnValue);

        $actualReturnValue = $this->deleteConnectionMockObj->getAjaxUrl();

        $this->assertEquals($returnValue, $actualReturnValue);
    }

    /**
     * Test getCleanCacheAjaxUrl function
     *
     * @return void
     */
    public function testGetCleanCacheAjaxUrl(): void
    {
        $returnValue = 'https://meta.com/fbeadmin/ajax/cleanCache';
        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('fbeadmin/ajax/cleanCache')
            ->willReturn($returnValue);

        $actualReturnValue = $this->deleteConnectionMockObj->getCleanCacheAjaxUrl();

        $this->assertEquals($returnValue, $actualReturnValue);
    }

    /**
     * Test getButtonHtml function
     *
     * @return void
     */
    public function testGetButtonHtml(): void
    {
        $buttonData = [
            'id' => 'fb_delete_connection_btn',
            'label' => __('Delete Connection'),
        ];
        $buttonHtml = '<button id="fb_delete_connection_btn"
            type="button"
            class="scalable">
            <span>' . __('Delete Connection') . '</span>
            </button>';

        $this->layoutMock->expects($this->once())
            ->method('createBlock')
            ->with(Button::class)
            ->willReturn($this->buttonBlockMock);

        $this->buttonBlockMock->expects($this->once())
            ->method('setData')
            ->with($buttonData)
            ->willReturnSelf();

        $this->buttonBlockMock->expects($this->once())
            ->method('toHtml')
            ->willReturn($buttonHtml);

        $this->assertEquals($buttonHtml, $this->deleteConnectionMockObj->getButtonHtml());
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
            ->willReturn(null);

        $this->systemConfig->expects($this->once())
            ->method('isSingleStoreMode')
            ->willReturn(true);

        $this->systemConfig->expects($this->once())
            ->method('getDefaultStoreId')
            ->willReturn($storeId);

        $this->assertEquals($this->deleteConnectionMockObj->getStoreId(), $storeId);
    }

    /**
     * Test getStoreId function
     *
     * @return void
     */
    public function testGetStoreIdWithStoreParameter(): void
    {
        $storeId = 123;
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('store')
            ->willReturn($storeId);

        $this->assertEquals($this->deleteConnectionMockObj->getStoreId(), $storeId);
    }
}
