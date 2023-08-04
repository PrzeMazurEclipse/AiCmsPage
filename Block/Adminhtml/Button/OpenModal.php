<?php

namespace Eclipse\AiCmsPage\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Catalog\Block\Adminhtml\Category\AbstractCategory;

class OpenModal extends AbstractCategory implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Open Model'),
            'class' => 'action-secondary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'cms_page_form.cms_page_form.test_model',
                                'actionName' => 'toggleModal'
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 20
        ];
    }
}
