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
use Meta\Conversion\Api\TrackerInterface;

class AddPaymentInfo implements TrackerInterface
{
    private const EVENT_TYPE = "AddPaymentInfo";

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
        $contents = [];
        if (!empty($params['contents'])) {
            foreach ($params['contents'] as $content) {
                $contents[] = [
                    'product_id' => $content['id'],
                    'quantity' => $content['quantity']
                ];
            }
        }
        return [
            'currency' => $params['currency'],
            'value' => $params['value'],
            'content_type' => $params['content_type'],
            'contents' => $contents,
            'content_ids' => $params['content_ids'],
            'content_category' => $params['content_category']
        ];
    }
}
