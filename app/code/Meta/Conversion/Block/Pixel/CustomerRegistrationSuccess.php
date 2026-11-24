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

namespace Meta\Conversion\Block\Pixel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Template\Context;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Meta\Conversion\Helper\MagentoDataHelper;

/**
 * @api
 */
class CustomerRegistrationSuccess extends Common
{

    private $customerSession;


    public function __construct(
        Context $context,
        FBEHelper $fbeHelper,
        MagentoDataHelper $magentoDataHelper,
        SystemConfig $systemConfig,
        Escaper $escaper,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $fbeHelper, $magentoDataHelper, $systemConfig, $escaper, $checkoutSession, $data);
        $this->customerSession = $customerSession;
    }

    /**
     * Returns content type
     *
     * @return string
     */
    public function getContentType()
    {
        return "customer_registration";
    }

    /**
     * Returns event name
     *
     * @return string
     */
    public function getEventToObserveName()
    {
        return 'facebook_businessextension_ssapi_customer_registration_success';
    }

    public function getEventId(): ?string
    {
        $eventIds = $this->customerSession->getMetaEventIds();
        if (is_array($eventIds) && array_key_exists($this->getEventToObserveName(), $eventIds)) {
            return $eventIds[$this->getEventToObserveName()];
        }

        return null;
    }
}
