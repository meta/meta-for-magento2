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

namespace Meta\BusinessExtension\Test\Unit\Controller\Adminhtml\Setup;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Meta\BusinessExtension\Controller\Adminhtml\Setup\Index;

class IndexTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $page = $this->getMockBuilder(Page::class)
                ->disableOriginalConstructor()
                ->addMethods(['setActiveMenu'])
                ->getMock();

        $page->expects($this->once())
            ->method('setActiveMenu')
            ->with('Meta_BusinessExtension::facebook_business_extension');
        $this->resultPageFactory = $this->createMock(PageFactory::class);
        $this->resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($page);

        $objectManager = new ObjectManager($this);
        $this->indexMockObj = $objectManager->getObject(
            Index::class,
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory
            ]
        );
    }

    /**
     * Test the execute method of the Index controller
     *
     * @return void
     */
    public function testExecute(): void
    {
        $result = $this->indexMockObj->execute();
        $this->assertInstanceOf(Page::class, $result);
    }
}
