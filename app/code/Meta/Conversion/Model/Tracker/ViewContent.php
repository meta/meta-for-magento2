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

namespace Meta\Conversion\Model\Tracker;

use Magento\Framework\Exception\NoSuchEntityException;
use Meta\Conversion\Helper\MagentoDataHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Meta\Conversion\Api\TrackerInterface;
use Magento\Framework\Escaper;

class ViewContent implements TrackerInterface
{

    private const EVENT_TYPE = "ViewContent";

    /**
     * @var MagentoDataHelper
     */
    private $magentoDataHelper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param MagentoDataHelper $magentoDataHelper
     * @param ProductRepositoryInterface $productRepository
     * @param Escaper $escaper
     */
    public function __construct(
        MagentoDataHelper $magentoDataHelper,
        ProductRepositoryInterface $productRepository,
        Escaper $escaper
    ) {
        $this->magentoDataHelper = $magentoDataHelper;
        $this->productRepository = $productRepository;
        $this->escaper = $escaper;
    }

    /**
     * @inheritDoc
     */
    public function getEventType(): string
    {
        return self::EVENT_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getPayload(array $params): array
    {
        $productId = $params['productId'];
        try {
            $product = $this->productRepository->getById($productId);
            $contentId = $this->magentoDataHelper->getContentId($product);
            return [
                'currency' => $this->magentoDataHelper->getCurrency(),
                'value' => $this->magentoDataHelper->getValueForProduct($product),
                'content_ids' => [$contentId],
                'content_category' => $this->magentoDataHelper->getCategoriesForProduct($product),
                'content_name' => $this->escaper->escapeUrl($product->getName()),
                'contents' => [
                    [
                        'product_id' => $contentId,
                        'quantity' => 1,
                        'item_price' => $this->magentoDataHelper->getValueForProduct($product)
                    ]
                ],
                'content_type' => $this->magentoDataHelper->getContentType($product)
            ];
        } catch (NoSuchEntityException $e) {
            return [];
        }
    }
}
