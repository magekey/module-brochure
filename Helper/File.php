<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime as FileMime;

class File extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var FileMime
     */
    protected $fileMime;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param FileMime $fileMime
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        FileMime $fileMime
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->fileMime = $fileMime;
        parent::__construct($context);
    }

    /**
     * Get media url
     *
     * @param string $path
     * @return string
     */
    public function getMediaUrl($path)
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        return $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $path;
    }

    /**
     * Get file path
     *
     * @param string $path
     * @return string
     */
    public function getMediaFile($path)
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        return $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($path);
    }

    /**
     * Get file size
     *
     * @param string $path
     * @return int
     */
    public function getFileSize($path)
    {
        $file = $this->getMediaFile($path);
        if (file_exists($file)) {
            return filesize($file);
        }
        return 0;
    }

    /**
     * Get mime type
     *
     * @param string $path
     * @return string|null
     */
    public function getMimeType($path)
    {
        $file = $this->getMediaFile($path);
        if (file_exists($file)) {
            return $this->fileMime->getMimeType($file);
        }
        return null;
    }
}
