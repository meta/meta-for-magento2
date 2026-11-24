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

namespace Meta\Catalog\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Meta\Catalog\Setup\MetaCatalogAttributes;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddCategoryAttributes implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var MetaCatalogAttributes
     */
    private $metaCatalogAttributes;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param MetaCatalogAttributes $metaCatalogAttributes
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        MetaCatalogAttributes $metaCatalogAttributes
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->metaCatalogAttributes = $metaCatalogAttributes;
    }

    /**
     * Get dependencies for the data patch
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return  [];
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
     * Create category attributes
     *
     * @return void
     */
    public function apply(): void
    {
        $categoryAttributes = $this->metaCatalogAttributes->getCategoryAttributes();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        foreach ($categoryAttributes as $attributeCode => $attributeData) {
            if (!$eavSetup->getAttributeId(Category::ENTITY, $attributeCode)) {
                $eavSetup->addAttribute(Category::ENTITY, $attributeCode, $attributeData);
            }
        }
    }

    /**
     * Revert the created product attributes
     *
     * @return void
     */
    public function revert(): void
    {
        $categoryAttributes = $this->metaCatalogAttributes->getCategoryAttributes();
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        foreach (array_keys($categoryAttributes) as $attributeCode) {
            $eavSetup->removeAttribute(Category::ENTITY, $attributeCode);
        }
        //delete the patch entry from patch_list table
        $this->moduleDataSetup->deleteTableRow('patch_list', 'patch_name', __CLASS__);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
