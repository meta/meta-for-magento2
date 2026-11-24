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
use Meta\Conversion\Model\Tracker\Purchase as PurchaseTracker;
use Meta\Conversion\Model\CapiTracker;

class Purchase implements ObserverInterface
{

    const EVENT_NAME = 'facebook_businessextension_ssapi_purchase';

    public function __construct(
        private readonly PurchaseTracker $purchaseTracker,
        private readonly CapiTracker $capiTracker
    ) { }

    public function execute(Observer $observer): void
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $lastOrderId = !empty($orderIds) ? $orderIds[0] : null;
        if ($lastOrderId) {
            $payload = $this->purchaseTracker->getPayload(['lastOrder' => $lastOrderId]);
            $this->capiTracker->execute($payload, self::EVENT_NAME, $this->purchaseTracker->getEventType());
        }
    }
}
