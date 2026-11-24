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

use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;

class MetaAttributeColumn extends Select
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * Constructor meta attribute column
     *
     * @param Context $context
     * @param array $options
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $options = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->options = $options;
    }

    /**
     * Set meta attribute input name
     *
     * @param string $value
     * @return mixed
     */
    public function setInputName(string $value)
    {
        return $this->setName($value);
    }

    /**
     * Set meta attribute input id
     *
     * @param string $value
     * @return MetaAttributeColumn
     */
    public function setInputId(string $value)
    {
        return $this->setId($value);
    }

    /**
     * Render meta attribute
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            asort($this->options);
            $this->setOptions($this->options);
        }
        return parent::_toHtml();
    }

    /**
     * Get all meta attribute options
     *
     * @return array
     */
    public function getMetaOptions()
    {
        return $this->options;
    }
}
