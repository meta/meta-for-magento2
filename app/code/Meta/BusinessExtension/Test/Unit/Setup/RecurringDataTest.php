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

namespace Meta\BusinessExtension\Test\Unit\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Meta\BusinessExtension\Model\System\Message\UnsupportedMagentoVersionNotification;
use Meta\BusinessExtension\Setup\RecurringData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class RecurringDataTest extends TestCase
{
    /**
     * Test the install method prints the notice on an unsupported Magento version
     *
     * @return void
     */
    public function testInstallPrintsNoticeWhenDisplayed(): void
    {
        $output = new BufferedOutput();
        $this->createRecurringData(true, $output)->install(
            $this->createStub(ModuleDataSetupInterface::class),
            $this->createStub(ModuleContextInterface::class)
        );

        $this->assertEquals(PHP_EOL . 'Unsupported version notice', $output->fetch());
    }

    /**
     * Test the install method prints nothing on a supported Magento version
     *
     * @return void
     */
    public function testInstallPrintsNothingWhenNotDisplayed(): void
    {
        $output = new BufferedOutput();
        $this->createRecurringData(false, $output)->install(
            $this->createStub(ModuleDataSetupInterface::class),
            $this->createStub(ModuleContextInterface::class)
        );

        $this->assertEquals('', $output->fetch());
    }

    /**
     * Create the recurring data script with a stubbed notification
     *
     * @param bool $isDisplayed
     * @param BufferedOutput $output
     * @return RecurringData
     */
    private function createRecurringData(bool $isDisplayed, BufferedOutput $output): RecurringData
    {
        $notification = $this->createStub(UnsupportedMagentoVersionNotification::class);
        $notification->method('isDisplayed')->willReturn($isDisplayed);
        $notification->method('getText')->willReturn('Unsupported version notice');

        return new RecurringData($notification, $output);
    }
}
