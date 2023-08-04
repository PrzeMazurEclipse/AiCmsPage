<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Controller\Adminhtml\Data;

use Eclipse\AiCmsPage\Service\AiService;
use Eclipse\AiCmsPage\Service\PageService;
use Eclipse\AiCmsPage\Model\SavePageData;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Generate extends Action implements HttpGetActionInterface
{
    /**
     * @param AiService $aiService
     * @param PageService $pageService
     * @param SavePageData $savePageData
     * @param Context $context
     */
    public function __construct(
        private readonly AiService      $aiService,
        private readonly PageService    $pageService,
        private readonly SavePageData   $savePageData,
        Context                         $context
    )
    {
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(): ResponseInterface
    {

        $id = (int)$this->getRequest()->getParam('id');
        $area = (string)$this->getRequest()->getParam('area');
        $language = $this->pageService->getPageLanguage($id);
        //send page to aiService in the future, to base on some information there generate additional content
        $page = $this->pageService->getPageById($id);
        $data = $this->aiService->generate($area, $language);
        $this->savePageData->save($page, $data, $area);

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
