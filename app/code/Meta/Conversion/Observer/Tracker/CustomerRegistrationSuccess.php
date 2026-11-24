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
use Magento\Store\Model\StoreManagerInterface;
use Meta\Conversion\Model\CapiTracker;
use Meta\Conversion\Model\Tracker\CustomerRegistrationSuccess as CustomerRegistrationSuccessTracker;

class CustomerRegistrationSuccess implements ObserverInterface
{

    const EVENT_NAME = 'facebook_businessextension_ssapi_customer_registration_success';

    public function __construct(
        private CustomerRegistrationSuccessTracker $customerRegistrationSuccessTracker,
        private StoreManagerInterface $storeManager,
        private readonly CapiTracker $capiTracker
    ) { }

    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if ($initialPayload = $this->getInitialPayload($customer)) {
            $payload = $this->customerRegistrationSuccessTracker->getPayload($initialPayload);
            $this->capiTracker->execute($payload, self::EVENT_NAME, $this->customerRegistrationSuccessTracker->getEventType(), true);
        }
    }

    private function getInitialPayload($customer)
    {
        if ($customer->getId()) {
            return  [
                'content_name' => $customer->getFirstname() . " " . $customer->getLastname(),
                'value' => $customer->getId(),
                'status' => "True",
                'currency' => $this->storeManager->getStore()->getCurrentCurrency()->getCode()
            ];
        }
        return false;
    }
}
