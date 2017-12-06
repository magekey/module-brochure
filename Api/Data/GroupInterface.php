<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Api\Data;

/**
 * @api
 */
interface GroupInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const GROUP_ID          = 'group_id';
    const LABEL             = 'label';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label);
}
