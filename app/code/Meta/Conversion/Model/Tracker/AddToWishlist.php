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

use Meta\Conversion\Api\TrackerInterface;

class AddToWishlist implements TrackerInterface
{
    private const EVENT_TYPE = "AddToWishlist";

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
        return [
            'value' => $params['value'],
            'currency' => $params['currency'],
            'content_ids' => $params['content_ids'],
            'content_category' => $params['content_category'],
            'content_name' => $params['content_name'],
            'contents' => [
                [
                    'product_id' => $params['contents'][0]['id'],
                    'quantity' => $params['contents'][0]['quantity'],
                ]
            ]
        ];
    }
}
