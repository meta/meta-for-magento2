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

namespace Meta\Catalog\Block\Adminhtml\Form\Field;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;
use Magento\Framework\DB\Select as DBSelect;

class ProductAttributeColumn extends Select
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * Constructor product attribute column
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Set product attribute input name
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName(string $value)
    {
        return $this->setName($value);
    }

    /**
     * Set product attribute input id
     *
     * @param string $value
     * @return ProductAttributeColumn
     */
    public function setInputId(string $value)
    {
        return $this->setId($value);
    }

    /**
     * Render product attribute
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getCatalogAttributeOptions());
        }
        return parent::_toHtml();
    }

    /**
     * Get all product eav attributes
     *
     * @return array[]
     */
    private function getCatalogAttributeOptions()
    {
        $option = [];
        $collection = $this->collectionFactory->create();
        $select = $collection->getSelect();
        $select->reset(DBSelect::COLUMNS)
            ->columns(['frontend_label', 'attribute_code'])
            ->order('frontend_label');
        $select->limit(10000);

        foreach ($collection as $items) {
            $option[] = ['label' => $items->getFrontendLabel(), 'value' => $items->getAttributeCode()];
        }
        return $option;
    }

    /**
     * Get all product eav attributes
     *
     * @return array
     */
    public function getProductOptions()
    {
        $option = [];
        $collection = $this->collectionFactory->create();
        $select = $collection->getSelect();
        $select->reset(DBSelect::COLUMNS)
            ->columns(['frontend_label', 'attribute_code'])
            ->order('frontend_label');

        foreach ($collection as $items) {
            if ($items->getFrontendLabel() != '') {
                $option[$items->getAttributeCode()] = $items->getFrontendLabel();
            }
        }
        return $option;
    }
}
