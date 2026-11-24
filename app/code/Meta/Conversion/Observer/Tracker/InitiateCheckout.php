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

namespace Meta\Conversion\Observer\Tracker;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Meta\Conversion\Model\Tracker\InitiateCheckout as InitiateCheckoutTracker;
use Magento\Checkout\Model\Session as CheckoutSession;
use Meta\Conversion\Model\CapiTracker;

class InitiateCheckout implements ObserverInterface
{

    const EVENT_NAME = 'facebook_businessextension_ssapi_initiate_checkout';

    public function __construct(
        private readonly InitiateCheckoutTracker $initiateCheckoutTracker,
        private readonly CheckoutSession $checkoutSession,
        private readonly CapiTracker $capiTracker
    ) { }

    public function execute(Observer $observer): void
    {
        $quoteId = $this->checkoutSession->getQuoteId();
        $payload = $this->initiateCheckoutTracker->getPayload(['quoteId' => $quoteId]);
        $this->capiTracker->execute($payload, self::EVENT_NAME, $this->initiateCheckoutTracker->getEventType());
    }
}
