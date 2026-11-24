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
use Meta\Conversion\Helper\MagentoDataHelper;

class CustomizeProduct implements TrackerInterface
{
    private const EVENT_TYPE = 'CustomizeProduct';

    /**
     * @var MagentoDataHelper
     */
    private $magentoDataHelper;

    /**
     * @param MagentoDataHelper $magentoDataHelper
     */
    public function __construct(
        MagentoDataHelper $magentoDataHelper
    ) {
        $this->magentoDataHelper = $magentoDataHelper;
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
        $payload = [
            'content_type' => $params['content_type'],
            'content_name' => $params['content_name'],
            'content_ids' => $params['content_ids'],
            'currency' => $this->magentoDataHelper->getCurrency()
        ];
        // include value only if it is present in the parameters as this is optional in the payload for CustomizeProduct event
        if (isset($params['value'])) {
            $payload['value'] = $params['value'];
        }
        return $payload;
    }
}
