<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\File;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;
use Magento\MediaStorage\Model\File\UploaderFactory as FileUploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\UrlInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\Factory as DataObjectFactory;

class Uploader
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
     * @var FileUploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var DataObjectFactory
     */
    protected $resultFactory;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $fileId;

    /**
     * @var FileUploader
     */
    protected $_uploader;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param FileUploaderFactory $uploaderFactory
     * @param DataObjectFactory $resultFactory
     * @param string $dir
     * @param string $fileId
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        FileUploaderFactory $uploaderFactory,
        DataObjectFactory $resultFactory,
        $dir = '',
        $fileId = null
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->resultFactory = $resultFactory;
        $this->dir = $dir;
        $this->fileId = $fileId;
    }

    /**
     * Retrieve file uploader instance
     *
     * @return FileUploader
     */
    public function getUploader()
    {
        if (null === $this->_uploader) {
            $uploader = $this->uploaderFactory->create([
                'fileId' => $this->fileId
            ]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $this->_uploader = $uploader;
        }
        return $this->_uploader;
    }

    /**
     * @return DataObject
     * @return DataObject
     */
    public function execute()
    {
        try {
            $data = $this->getUploader()->save($this->getUploadPath());
            $result = $this->resultFactory->create($data);
            $this->prepareResult($result);
        } catch (\Exception $e) {
            $result = $this->resultFactory->create([
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ]);
        }
        return $result;
    }

    /**
     * Retrieve upload path
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($this->dir);
    }

    /**
     * Prepare result
     *
     * @param DataObject $result
     * @return void
     */
    protected function prepareResult(DataObject $result)
    {
        if ($file = $result->getFile()) {
            $result->setUrl($this->getMediaUrl($file));
        }
    }

    /**
     * Get media url
     *
     * @param string $file
     * @return string
     */
    protected function getMediaUrl($file)
    {
        $file = ltrim(str_replace('\\', '/', $file), '/');
        return $this->storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . ($this->dir ? $this->dir . '/' : '') . $file;
    }
}
