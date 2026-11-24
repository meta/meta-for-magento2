<?php

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

namespace Meta\Catalog\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class UpdateMetaCatalogSourceAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Get dependencies for the data patch
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get alias for the data patch
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * Update the attribute source model value from Facebook to Meta
     *
     * @return void
     */
    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $this->updateAttribute();

        $connection->endSetup();
    }

    /**
     * Update the attribute source model value from Facebook to Meta
     */
    private function updateAttribute()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $eavAttributeTable = $this->moduleDataSetup->getTable('eav_attribute');

        // phpcs:disable
        $oldSourceModel = 'Facebook\BusinessExtension\Model\Config\Source\Product\GoogleProductCategory';
        $newSourceModel = \Meta\Catalog\Model\Config\Source\Product\GoogleProductCategory::class;

        $connection->update(
            $eavAttributeTable,
            ['source_model' => $newSourceModel],
            $connection->quoteInto('source_model = ?', $oldSourceModel)
        );
    }

    /**
     * Revert the attribute source model value from Facebook to Meta
     *
     * @return void
     */
    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->revertAttributeUpdate();

        //delete the patch entry from patch_list table
        $this->moduleDataSetup->deleteTableRow('patch_list', 'patch_name', __CLASS__);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Revert the attribute source model value from Facebook to Meta
     */
    private function revertAttributeUpdate()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $eavAttributeTable = $this->moduleDataSetup->getTable('eav_attribute');

        // phpcs:disable
        $oldSourceModel = 'Facebook\BusinessExtension\Model\Config\Source\Product\GoogleProductCategory';
        $newSourceModel = \Meta\Catalog\Model\Config\Source\Product\GoogleProductCategory::class;
        $connection->update(
            $eavAttributeTable,
            ['source_model' => $oldSourceModel],
            $connection->quoteInto('source_model = ?', $newSourceModel)
        );
    }
}
