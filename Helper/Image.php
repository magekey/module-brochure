<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Image\Adapter\AdapterInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;

class Image extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Media config node
     */
    const MEDIA_TYPE_CONFIG_NODE = 'images';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $imageFactory;

    /**
     * @var \Magento\Framework\View\ConfigInterface
     */
    protected $viewConfig;

    /**
     * @var \Magento\Framework\Config\View
     */
    protected $configView;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Image\AdapterFactory $imageFactory
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\View\ConfigInterface $viewConfig
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
        $this->viewConfig = $viewConfig;
        parent::__construct($context);
    }

    /**
     * Get image url
     *
     * @param string $path
     * @param string $imageId
     * @param array $attributes
     * @return string
     */
    public function getImage($path, $imageId = null, array $attributes = [])
    {
        $attributes = $this->mergeAttributes($imageId, $attributes);
        $sourcePath = $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($path);
        $targetPath = $this->getTargetPath($sourcePath, $attributes);

        if (!file_exists($targetPath)) {
            $image = $this->imageFactory->create();
            $image->open($sourcePath);
            $this->setImageAttributes($image, $attributes);
            $image->resize($attributes['width'], $attributes['height']);
            $image->save($targetPath);
        }

        $relativePath = $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getRelativePath($targetPath);

        return $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $relativePath;
    }

    /**
     * Merge attributes
     *
     * @param string $imageId
     * @param array $attributes
     * @return array
     */
    protected function mergeAttributes($imageId, array $attributes)
    {
        return array_merge(
            [
                'constrain' => true,
                'transparency' => true,
                'frame' => false,
                'aspect_ratio' => true,
                'width' => null,
                'height' => null
            ],
            $this->getConfigView()->getMediaAttributes('MageKey_Brochure', self::MEDIA_TYPE_CONFIG_NODE, $imageId),
            $attributes
        );
    }

    /**
     * Retrieve config view
     *
     * @return \Magento\Framework\Config\View
     */
    protected function getConfigView()
    {
        if (!$this->configView) {
            $this->configView = $this->viewConfig->getViewConfig();
        }
        return $this->configView;
    }

    /**
     * Set image attributes
     *
     * @param AdapterInterface $image
     * @param array $attributes
     * @return void
     */
    protected function setImageAttributes(AdapterInterface $image, array $attributes = [])
    {
        $image->keepFrame($attributes['frame']);
        $image->constrainOnly($attributes['constrain']);
        $image->keepAspectRatio($attributes['aspect_ratio']);
        $image->keepTransparency($attributes['transparency']);
        if (!empty($attributes['background'])) {
            $image->backgroundColor($attributes['background']);
        }
    }

    /**
     * Get target path
     *
     * @param string $sourcePath
     * @param array $attributes
     * @return string
     */
    public function getTargetPath($sourcePath, array $attributes)
    {
        $targetPath = dirname($sourcePath) . '/';
        switch ($attributes['type']) {
            default:
                $targetPath .= 'cache/'
                    . $attributes['width'] . 'x' . $attributes['height']
                    . '/' . basename($sourcePath);
                break;
        }
        return $targetPath;
    }
}
