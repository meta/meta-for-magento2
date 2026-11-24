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

use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Model\PersistMetaTelemetryLogsMerger;

class PersistMetaTelemetryLogsMergerTest extends TestCase
{
    /**
     * Class setUp function
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->persistMetaTelemetryLogsHandlerMockObj = new PersistMetaTelemetryLogsMerger();
    }

    /**
     * Test merge function
     *
     * @return void
     */
    public function testMerge(): void
    {
        $messages = [
            'persist.meta.telemetry.logs' => [
                '{"log_type": "system", "message": "System started", "timestamp": 1678886400, "extra_data": {"version": "1.0.0"}}',
            ],
        ];

        $expectedMergedLogs = '[{"message":"System started","timestamp":1678886400,"extra_data":{"version":"1.0.0"}}]';
        $expectedResult = [
            'persist.meta.telemetry.logs' => [$expectedMergedLogs],
        ];

        $actualResult = $this->persistMetaTelemetryLogsHandlerMockObj->merge($messages);
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * Test merge function
     *
     * @return void
     */
    public function testMergeWithKeyIsNotPresent(): void
    {
        $messages = [
            '' => [
                '{"log_type": "system", "message": "System started", "timestamp": 1678886400, "extra_data": {"version": "1.0.0"}}',
            ],
        ];

        $actualResult = $this->persistMetaTelemetryLogsHandlerMockObj->merge($messages);
        $this->assertEquals($messages, $actualResult);
    }
}
