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

use Magento\Framework\Escaper;
use Meta\Conversion\Helper\MagentoDataHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Meta\Conversion\Observer\Common;
use Magento\Catalog\Model\Product;
use Meta\Conversion\Model\CapiTracker;
use Meta\Conversion\Model\Tracker\AddToWishlist as AddToWishlistTracker;

class AddToWishlist implements ObserverInterface
{
    public const EVENT_NAME = 'facebook_businessextension_ssapi_add_to_wishlist';

    /**
     * @var MagentoDataHelper
     */
    private $magentoDataHelper;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var Common
     */
    private Common $common;

    private CapiTracker $capiTracker;

    private AddToWishlistTracker $addToWishlistTracker;

    /**
     * Constructor Observer
     *
     * @param MagentoDataHelper $magentoDataHelper
     * @param Escaper $escaper
     * @param Common $common
     */
    public function __construct(
        MagentoDataHelper $magentoDataHelper,
        Escaper $escaper,
        Common $common,
        CapiTracker $capiTracker,
        AddToWishlistTracker $addToWishlistTracker
    ) {
        $this->magentoDataHelper = $magentoDataHelper;
        $this->escaper = $escaper;
        $this->common = $common;
        $this->capiTracker = $capiTracker;
        $this->addToWishlistTracker = $addToWishlistTracker;
    }

    /**
     * Execute action method for the add to wishlist Observer
     *
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        // For cart page - "Move To Wishlist"
        $items = $observer->getEvent()->getItems();
        $payload = [];
        foreach ($items as $item) {
            $product = $item->getProduct();
            $contents = [
                [
                    'id' => $this->magentoDataHelper->getContentId($product),
                    'quantity' => 1
                ]
            ];
            $payload = [
                'content_name'     => $this->escaper->escapeUrl($product->getName()),
                'content_category' => $this->magentoDataHelper->getCategoriesNameById($product->getCategoryIds()),
                'content_ids'      => [$this->magentoDataHelper->getContentId($product)],
                'contents'         => $contents,
                'value'            => (float) $this->getPrice($product),
                'currency'         => $this->magentoDataHelper->getCurrency(),
            ];
            break;
        }

        $payload = $this->addToWishlistTracker->getPayload($payload);
        $this->capiTracker->execute($payload, self::EVENT_NAME, $this->addToWishlistTracker->getEventType(), true);

    }

    /**
     * Get Price for Grouped product
     *
     * @param Product $product
     * @return float|int
     */
    private function getPrice($product)
    {
        $value = $this->magentoDataHelper->getValueForProduct($product);
        $lowestprice = 0;
        $count = 0;
        if ($value <= 0 && $product->getTypeId() == 'grouped') {
            $usedProds = $product->getTypeInstance()->getAssociatedProducts($product);
            foreach ($usedProds as $child) {
                if ($child->getId() != $product->getId()) {
                    if ($count == 0) {
                        $lowestprice = $child->getPrice();
                    }

                    if ($child->getPrice() < $lowestprice) {
                        $lowestprice = $child->getPrice();
                    }
                }
                $count++;
            }
            $value = $lowestprice;
        }
        return $value;
    }
}
