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

namespace Meta\Conversion\Plugin\Tracker;

use Magento\Checkout\Model\PaymentInformationManagement;
use Meta\Conversion\Helper\MagentoDataHelper;
use Meta\Conversion\Model\CapiTracker;
use Meta\Conversion\Model\Tracker\AddPaymentInfo as AddPaymentInfoTracker;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\AddressInterface;

class AddPaymentInfo
{
    const EVENT_NAME = 'facebook_businessextension_ssapi_add_payment_info';

    public function __construct(
        private readonly AddPaymentInfoTracker $addPaymentInfoTracker,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly MagentoDataHelper $magentoDataHelper,
        private readonly CapiTracker $capiTracker
    ) { }

    public function afterSavePaymentInformation(
        PaymentInformationManagement $paymentInformationManagement,
        bool $result,
        $cartId,
        PaymentInterface $paymentMethod,
        ?AddressInterface $billingAddress = null
    ) {
        if ($result) {
            $payload = $this->addPaymentInfoTracker->getPayload($this->getInitialPayload($cartId));
            $this->capiTracker->execute($payload, self::EVENT_NAME, $this->addPaymentInfoTracker->getEventType(), true);
        }
        return $result;
    }

    /**
     * @param $cartId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getInitialPayload($cartId): array
    {
        $quote = $this->cartRepository->getActive($cartId);
        $payload = $this->magentoDataHelper->getCartPayload($quote);
        $payload['content_type'] = 'product';
        return $payload;
    }
}
