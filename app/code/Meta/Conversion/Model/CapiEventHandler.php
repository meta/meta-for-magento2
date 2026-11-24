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

namespace Meta\Conversion\Model;

use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Meta\BusinessExtension\Helper\FBEHelper;
use Meta\Conversion\Helper\ServerEventFactory;
use Meta\Conversion\Helper\ServerSideHelper;

class CapiEventHandler
{

    /**
     * @param ServerSideHelper $serverSideHelper
     * @param FBEHelper $fbeHelper
     * @param ServerEventFactory $serverEventFactory
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(
        private readonly ServerSideHelper $serverSideHelper,
        private readonly FBEHelper $fbeHelper,
        private readonly ServerEventFactory $serverEventFactory,
        private readonly JsonSerializer $jsonSerializer
    ) { }

    public function process(string $message): void
    {
        try {
            $payload = $this->jsonSerializer->unserialize($message);
            $eventId = $payload['event_id'];
            $eventType = $payload['event_type'];
            // remove values from array
            unset($payload['event_id']);
            unset($payload['event_type']);

            $additionalPayloadKeys = ['request_uri', 'user_agent', 'fbp', 'fbc'];
            $additionalPayloadData = array_intersect_key($payload, array_flip($additionalPayloadKeys));
            $payload = array_diff_key($payload, array_flip($additionalPayloadKeys));

            // Add source and pluginVersion in the payload as custom properties
            $payload['custom_properties'] = [];
            $payload['custom_properties']['source'] = $this->fbeHelper->getSource();
            $payload['custom_properties']['pluginVersion'] = $this->fbeHelper->getPluginVersion();
            $event = $this->serverEventFactory->createEvent($eventType, array_filter($payload), $additionalPayloadData, $eventId);
            if (isset($payload['userDataFromOrder'])) {
                $this->serverSideHelper->sendEvent($event, $payload['userDataFromOrder']);
            } else {
                $this->serverSideHelper->sendEvent($event);
            }
        } catch (\Exception $e) {
            $this->fbeHelper->logException($e);
        }
    }
}
