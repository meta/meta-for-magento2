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

namespace Meta\BusinessExtension\Test\Unit\Model;

use PHPunit\Framework\TestCase;
use Meta\BusinessExtension\Model\PersistMetaTelemetryLogsHandler;
use Meta\BusinessExtension\Helper\GraphAPIAdapter;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;
use Meta\BusinessExtension\Helper\FBEHelper;

class PersistMetaTelemetryLogsHandlerTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->graphAPIAdapter = $this->createMock(GraphAPIAdapter::class);
        $this->systemConfig = $this->createMock(SystemConfig::class);

        $this->persistMetaTelemetryLogsHandlerMockObj = new PersistMetaTelemetryLogsHandler(
            $this->graphAPIAdapter,
            $this->systemConfig
        );
    }

    /**
     * Test persistMetaTelemetryLogs function
     *
     * @return void
     */
    public function testPersistMetaTelemetryLogs(): void
    {
        $messages = '[{"store_id":"99999","extra_data":{"meta":"Object"}}]';
        $storeId = 99999;
        $accessToken = 'ACCESS_TOKEN';

        $telemetryContext = [];
        $telemetryContext['event'] = FBEHelper::PERSIST_META_TELEMETRY_LOGS;
        $telemetryContext['extra_data'] = [];
        $telemetryContext['extra_data']['telemetry_logs'] = $messages;

        $this->systemConfig
            ->method('getAccessToken')
            ->with(
                $this->equalTo($storeId)
            )
            ->willReturn($accessToken);

        $this->graphAPIAdapter
            ->method('persistLogToMeta')
            ->with(
                $this->equalTo($telemetryContext),
                $this->equalTo($accessToken)
            )
            ->willReturn('');

        $this->persistMetaTelemetryLogsHandlerMockObj->persistMetaTelemetryLogs($messages);
    }

    /**
     * Test persistMetaTelemetryLogs function
     *
     * @return void
     */
    public function testPersistMetaTelemetryLogsWithNullAccessToken(): void
    {
        $messages = '[{"extra_data":{"meta":"Object"}}]';
        $storeId = 99999;
        $accessToken = null;

        $telemetryContext = [];
        $telemetryContext['event'] = FBEHelper::PERSIST_META_TELEMETRY_LOGS;
        $telemetryContext['extra_data'] = [];
        $telemetryContext['extra_data']['telemetry_logs'] = $messages;

        $this->systemConfig
            ->method('getAccessToken')
            ->with(
                $this->equalTo($storeId)
            )
            ->willReturn($accessToken);

        $this->graphAPIAdapter
            ->method('persistLogToMeta')
            ->with(
                $this->equalTo($telemetryContext),
                $this->equalTo($accessToken)
            )
            ->willReturn('');

        $this->persistMetaTelemetryLogsHandlerMockObj->persistMetaTelemetryLogs($messages);
    }
}
