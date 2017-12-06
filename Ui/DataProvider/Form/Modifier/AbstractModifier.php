<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\DataProvider\Form\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class AbstractModifier
 */
abstract class AbstractModifier implements ModifierInterface
{
    /**
     * Container fieldset prefix
     */
    const CONTAINER_PREFIX = 'container_';
}
