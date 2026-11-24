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

namespace Meta\Catalog\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Meta\BusinessExtension\Model\System\Config as SystemConfig;

class MetaCatalogAttributes
{

    /**
     * Returns array of product attributes to be created
     *
     * @return array[]
     */
    public function getProductAttributes(): array
    {
        return [
            'google_product_category' => [
                'type' => 'varchar',
                'label' => 'Google Product Category',
                'input' => 'select',
                'source' => \Meta\Catalog\Model\Config\Source\Product\GoogleProductCategory::class,
                'required' => false,
                'sort_order' => 10,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'visible' => true,
                'is_html_allowed_on_front' => false,
                'visible_on_front' => false
            ],
            'send_to_facebook' => [
                'type' => 'int',
                'label' => 'Sync to Meta',
                'default' => 1,
                'source' => Boolean::class,
                'input' => 'select',
                'visible' => true,
                'sort_order' => 45,
                'required' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_html_allowed_on_front' => false,
                'visible_on_front' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE
            ]
        ];
    }

    /**
     * Returns array of category attributes to be created
     *
     * @return array[]
     */
    public function getCategoryAttributes(): array
    {
        return [
            SystemConfig::CATEGORY_SYNC_TO_FACEBOOK => [
                'type' => 'int',
                'label' => 'Sync to Meta',
                'input' => 'boolean',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'visible' => true,
                'default' => "1",
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Display Settings'
            ]
        ];
    }

    /**
     * Returns array of meta product set id as category attribute to be created
     *
     * @return array[]
     */
    public function getCategoryProductSetIdAttribute(): array
    {
        return [
            SystemConfig::META_PRODUCT_SET_ID => [
                'type' => 'varchar',
                'label' => 'Meta Product Set Id',
                'input' => 'text',
                'source' => '',
                'required' => false,
                'default' => null,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => false,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false,
                'frontend' => '',
                'backend' => ''
            ]
        ];
    }
}
