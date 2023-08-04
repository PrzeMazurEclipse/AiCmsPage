<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Generate implements ButtonProviderInterface
{
    /**
     * @param Context $context
     */
    public function __construct(
        private readonly Context $context
    )
    {
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $url = $this->getUrl('eclipse_aicmspage/data/generate', ['id'=>$this->getCurrentId(), 'area'=>'meta title']);
        return [
            'label' => __('Generate Meta Title'),
            'class' => 'generate-metatitle-button',
//            'on_click' => "$('#css-selector').modal('openModal')",
            'on_click' => sprintf("location.href = '%s';", $url),
            'sort_order' => 30,
        ];
    }
    /**
     * Get current id of a page
     *
     * @return int|bool
     */
    public function getCurrentId()
    {
        $params = $this->context->getRequestParams();
        if (isset($params['page_id'])) {
            return (int)$params['page_id'];
        } else {
            return false;
        }
    }


    /**
     * @param $route
     * @param $params
     * @return string
     */
    private function getUrl($route = '', $params = [])
    {
        return $this->context->getUrl($route, $params);
    }
}
