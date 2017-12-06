<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Api\Data;

/**
 * @api
 */
interface ItemInterface
{
    /**#@+
     * Constants for keys of data array.
     */
    const ITEM_ID           = 'item_id';
    const TITLE             = 'title';
    const IMAGE             = 'image';
    const FILE              = 'file';
    const POSITION          = 'position';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Get file
     *
     * @return string|null
     */
    public function getFile();

    /**
     * Get position
     *
     * @return int|null
     */
    public function getPosition();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Set file
     *
     * @param string $file
     * @return $this
     */
    public function setFile($file);

    /**
     * Set position
     *
     * @param string $position
     * @return $this
     */
    public function setPosition($position);
}
