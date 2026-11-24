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

namespace Meta\Conversion\Test\Unit\Plugin\Contact\Controller\Index;

use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Meta\Conversion\Plugin\Contact\Controller\Index\Post;
use Magento\Contact\Controller\Index\Post as PostController;
use Magento\Framework\Controller\Result\Redirect;
use Meta\Conversion\Observer\Common;

class PostTest extends TestCase
{
    private $commonMock;
    private $subject;

    public function setUp(): void
    {
        $this->commonMock = $this->getMockBuilder(Common::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->subject = $objectManager->getObject(Post::class, ['common' => $this->commonMock]);
    }

    public function testAfterExecute()
    {
        $contactData = [
            'content_type' => "contact"
        ];

        $postControllerMock = $this->getMockBuilder(PostController::class)
            ->disableOriginalConstructor()
            ->getMock();
        $redirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->commonMock->expects($this->once())
            ->method('setCookieForMetaPixel')
            ->with('event_contact', $contactData);

        $this->assertEquals($redirectMock, $this->subject->afterExecute($postControllerMock, $redirectMock));
    }
}
