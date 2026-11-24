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

namespace Meta\Conversion\Plugin\Contact\Controller\Index;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Meta\Conversion\Observer\Common;
use Magento\Contact\Controller\Index\Post as PostController;

class Post
{
    /**
     * @var Common
     */
    private Common $common;

    /**
     * Constructor after Contact Post
     *
     * @param Common $common
     */
    public function __construct(
        Common $common
    ) {
        $this->common = $common;
    }

    /**
     * Plugin for execute for adding payload in cookie
     *
     * @param \Magento\Contact\Controller\Index\Post $subject
     * @param Redirect $result
     * @return Redirect
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(PostController $subject, Redirect $result)
    {
        $contactData = [
            'content_type' => "contact"
        ];
        $this->common->setCookieForMetaPixel('event_contact', $contactData);
        return $result;
    }
}
