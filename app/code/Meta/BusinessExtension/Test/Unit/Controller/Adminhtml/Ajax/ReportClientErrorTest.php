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
use Magento\Backend\App\Action\Context;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\BusinessExtension\Model\MBEInstalls;
use Magento\Framework\Exception\LocalizedException;
use Meta\BusinessExtension\Controller\Adminhtml\Ajax\ReportClientError;

class ReportClientErrorTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->request = $this->createMock(RequestInterface::class);
        $this->context = $this->createMock(Context::class);
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->resultJsonFactory = $this->createMock(JsonFactory::class);
        $this->fbeHelper = $this->createMock(FBEHelper::class);

        $objectManager = new ObjectManager($this);
        $this->reportClientErrorMockObj = $objectManager->getObject(
            ReportClientError::class,
            [
                'context' => $this->context,
                'resultJsonFactory' => $this->resultJsonFactory,
                'fbeHelper' => $this->fbeHelper
            ]
        );
    }

    public function testExecuteForJson(): void
    {
        $storeId = 1;
        $message = 'Test error message';
        $stackTrace = 'Test stack trace';
        $filename = 'test.js';
        $lineNumber = 10;
        $columnNumber = 5;

        $this->request->expects($this->exactly(6))
            ->method('getParam')
            ->willReturnMap([
                ['storeID', null, $storeId],
                ['message', null, $message],
                ['stackTrace', null, $stackTrace],
                ['filename', null, $filename],
                ['line', null, $lineNumber],
                ['column', null, $columnNumber]
            ]);

        $this->fbeHelper->expects($this->once())
            ->method('logExceptionDetailsImmediatelyToMeta')
            ->with(
                100, // JS_EXCEPTION_CODE
                $message,
                $stackTrace,
                [
                    'event' => 'js_exception',
                    'event_type' => 'error',
                    'extra_data' => [
                        'filename' => $filename,
                        'line_number' => $lineNumber,
                        'column_number' => $columnNumber,
                    ],
                    'store_id' => $storeId,
                ]
            );

        $result = $this->reportClientErrorMockObj->executeForJson();
        $this->assertIsArray($result);
    }
}
