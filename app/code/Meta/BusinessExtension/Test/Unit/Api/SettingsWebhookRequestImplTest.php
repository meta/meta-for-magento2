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

namespace Meta\BusinessExtension\Test\Unit\Api;

use PHPUnit\Framework\TestCase;
use Meta\BusinessExtension\Model\Api\SettingsWebhookRequestImpl;
use Meta\BusinessExtension\Api\Data\MetaIssueNotificationInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class SettingsWebhookRequestImplTest extends TestCase
{
    /**
     * @var SettingsWebhookRequestImpl
     */
    private $settingsWebhookRequestImpl;

    /**
     * Class setup function
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $objectManager = new ObjectManager($this);
        $this->settingsWebhookRequestImpl =  $objectManager->getObject(
            SettingsWebhookRequestImpl::class
        );
    }

    /**
     * Validate if the settings external business ID is working
     *
     * @return void
     */
    public function testSetExternalBusinessId(): void
    {
        $externalBusinessId = '12345';
        $this->settingsWebhookRequestImpl->setExternalBusinessId($externalBusinessId);
        
        $this->assertEquals($externalBusinessId, $this->settingsWebhookRequestImpl->getExternalBusinessId());
    }

    /**
     * Validate if the settings notification is working
     *
     * @return void
     */
    public function testSetNotification(): void
    {
        $notification = $this->createMock(MetaIssueNotificationInterface::class);
        $notification->setSeverity(1);
        $notification->setMessage('Test message');
        $notification->setNotificationId('12345');

        $this->settingsWebhookRequestImpl->setNotification($notification);
        
        $this->assertEquals($notification, $this->settingsWebhookRequestImpl->getNotification());
        $this->assertSame($notification->getMessage(), $this->settingsWebhookRequestImpl->getNotification()->getMessage());
        $this->assertSame($notification->getSeverity(), $this->settingsWebhookRequestImpl->getNotification()->getSeverity());
        $this->assertSame($notification->getNotificationId(), $this->settingsWebhookRequestImpl->getNotification()->getNotificationId());
    }

    /**
     * Validate if the graphql version is set correctly
     *
     * @return void
     */
    public function testSetGraphAPIVersion(): void
    {
        $graphqlVersion = 'v22.0';
        $this->settingsWebhookRequestImpl->setGraphAPIVersion($graphqlVersion);
        
        $this->assertEquals($graphqlVersion, $this->settingsWebhookRequestImpl->getGraphAPIVersion());
    }
}
