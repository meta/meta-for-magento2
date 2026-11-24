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

namespace Meta\BusinessExtension\Test\Unit\Block\Adminhtml\System\Config\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Block\Adminhtml\System\Config\Field\Hidden;

class HiddenTest extends TestCase
{
    /**
     * @var Hidden
     */
    private $hiddenBlock;

    /**
     * @var AbstractElement|\PHPUnit\Framework\MockObject\MockObject
     */
    private $elementMock;

    /**
     * @var SecureHtmlRenderer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $secureRendererMock;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * Function setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->contextMock = $this->createMock(Context::class);
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->secureRendererMock = $this->createMock(SecureHtmlRenderer::class);

        $objectManager = new ObjectManager($this);
        $this->hiddenMockObj = $objectManager->getObject(
            Hidden::class,
            [
                'context' => $this->contextMock,
                'secureRenderer' => $this->secureRendererMock,
            ]
        );
    }

    /**
     * Test _decorateRowHtml function
     *
     * @return void
     */
    public function testDecorateRowHtml(): void
    {
        $elementHtmlId = 'test_field';
        $originalHtml = '<input type="text" name="test">';
        $expectedHtml = '<tr id="row_' . $elementHtmlId . '" >' . $originalHtml . '</tr>' .
            '<style type="text/css">tr#row_' . $elementHtmlId . ' { display: none; }</style>';

        $this->elementMock->expects($this->exactly(2))
            ->method('getHtmlId')
            ->willReturn($elementHtmlId);

        $this->secureRendererMock->expects($this->once())
            ->method('renderStyleAsTag')
            ->with('display: none;', 'tr#row_' . $elementHtmlId)
            ->willReturn('<style type="text/css">tr#row_' . $elementHtmlId . ' { display: none; }</style>');
        
        $method = new \ReflectionMethod(Hidden::class, '_decorateRowHtml');
        $method->setAccessible(true);
        $actualHtml = $method->invokeArgs($this->hiddenMockObj, [$this->elementMock, $originalHtml]);

        $this->assertEquals($expectedHtml, $actualHtml);
    }
}
