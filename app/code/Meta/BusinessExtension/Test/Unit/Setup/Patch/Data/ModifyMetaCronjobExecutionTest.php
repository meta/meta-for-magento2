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

namespace Meta\BusinessExtension\Test\Unit\Setup\Patch\Data;

use Meta\BusinessExtension\Setup\Patch\Data\ModifyMetaCronjobExecution;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ModifyMetaCronjobExecutionTest extends TestCase
{
    /**
     * @var ModifyMetaCronjobExecution|MockObject
     */
    private $modifyMetaCronjobExecution;

    /** @var ModuleDataSetupInterface|MockObject */
    private $moduleDataSetupMock;

    /** @var WriterInterface|MockObject */
    private $configWriterMock;

    /**
     * Class setUp method
     *
     * return void
     */
    protected function setUp(): void
    {
        $this->moduleDataSetupMock = $this->createMock(ModuleDataSetupInterface::class);
        $this->configWriterMock = $this->createMock(WriterInterface::class);
        $objectManager = new ObjectManager($this);
        $this->modifyMetaCronjobExecution = $objectManager->getObject(
            ModifyMetaCronjobExecution::class,
            [
                'moduleDataSetup' => $this->moduleDataSetupMock,
                'configWriter' => $this->configWriterMock
            ]
        );
    }

    /**
     * Test getDependencies function
     *
     * @return void
     */
    public function testGetDependencies(): void
    {
        $this->assertEquals([], ModifyMetaCronjobExecution::getDependencies());
    }

    /**
     * Test getAliases function
     *
     * @return void
     */
    public function testGetAliases(): void
    {
        $this->assertEquals([], $this->modifyMetaCronjobExecution->getAliases());
    }

    /**
     * Test apply function
     *
     * @return void
     */
    public function testApply(): void
    {
        $this->moduleDataSetupMock->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturn($this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class));
        
        $this->configWriterMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $result = $this->modifyMetaCronjobExecution->apply();
        $this->assertInstanceOf(DataPatchInterface::class, $result);
        $this->assertInstanceOf(PatchRevertableInterface::class, $result);
    }

    /**
     * Test revert function
     *
     * @return void
     */
    public function testRevert(): void
    {
        $adapterInterface = $this->createMock(\Magento\Framework\DB\Adapter\AdapterInterface::class);
        $this->moduleDataSetupMock->expects($this->exactly(2))
            ->method('getConnection')
            ->willReturn($adapterInterface);
        $adapterInterface->expects($this->once())
            ->method('startSetup');
        $adapterInterface->expects($this->once())
            ->method('endSetup');
       

        $result = $this->modifyMetaCronjobExecution->revert();
    }
}
