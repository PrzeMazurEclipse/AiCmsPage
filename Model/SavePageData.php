<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Model;

use Exception;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Psr\Log\LoggerInterface;

class SavePageData
{
    /**
     * @param PageRepositoryInterface $pageRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @param PageInterface $page
     * @param string $data
     * @param string $area
     * @return bool
     */
    public function save(pageInterface $page, string $data, string $area): bool
    {
        match ($area) {
            'content heading' => $page->setContentHeading($data),
            'content' => $page->setContent($data),
            'url key' => $page->setIdentifier($data),
            'meta title' => $page->setMetaTitle($data),
            'meta keywords' => $page->setMetaKeywords($data),
            'meta description' => $page->setMetaDescription($data),
        };

        try{
            $this->pageRepository->save($page);

            return true;
        } catch (Exception $e) {
            $this->logger->error($e);

            return false;
        }
    }
}
