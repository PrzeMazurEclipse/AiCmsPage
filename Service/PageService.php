<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Service;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class PageService
{
    /**
     * @param PageRepositoryInterface $pageRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly StoreManagerInterface $storeManager
    )
    {
    }

    /**
     * @param int $id
     * @return PageInterface
     * @throws LocalizedException
     */
    public function getPageById(int $id): PageInterface
    {
        return $this->pageRepository->getById($id);
    }

    /**
     * @param int $pageId
     * @return string|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getPageLanguage(int $pageId): ?string
    {
        $page = $this->getPageById($pageId);
        $store = $this->storeManager->getStore((int)$page->getStoreId());
        $language = $store->getConfig('general/locale/code');
        return $language;
    }
}
