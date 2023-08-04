<?php declare(strict_types=1);

namespace Eclipse\AiCmsPage\Ui\DataProvider\CmsPage\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Modal;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class AiGenerate implements ModifierInterface
{
    protected array $meta;

    const CODE = 'meta_title'; /** example: short_description */
    const CODE_DASHED = 'meta-title'; /** example: short-description */

    const TITLE = 'Meta Title'; /** example: Short Description */

    const CONTAINER_PREFIX = 'container_';


    public function __construct(
        protected LocatorInterface $locator,
        protected ArrayManager $arrayManager
    ) {}

    public function modifyData(array $data): array
    {
        return $data;
    }

    protected function addLinkToModal(): void
    {
        $codePath = $this->arrayManager->findPath(
            static::CODE,
            $this->meta,
            null,
            'children'
        );


        $codeButton['arguments']['data']['config'] = [
            'dataScope' => 'ai_'.static::CODE.'_button',
            'displayAsLink' => true,
            'formElement' => Container::NAME,
            'componentType' => Container::NAME,
            'component' => 'Magento_Ui/js/form/components/button',
            'template' => 'ui/form/components/button/container',
            'imports' => [
                'childError' =>  'cms_page_form.cms_page_form.ai_' . static::CODE . '_modal.ai-' . static::CODE_DASHED . ':error',
            ],
            'actions' => [
                [
                    'targetName' => 'cms_page_form.cms_page_form.ai_' . static::CODE . '_modal',
                    'actionName' => 'toggleModal',
                ]
            ],
            'title' => __('Generate ' . static::TITLE ),
            'additionalForGroup' => true,
            'provider' => false,
            'source' => 'product_details',
            'sortOrder' =>
                $this->arrayManager->get($codePath . '/arguments/data/config/sortOrder', $this->meta) + 1,
        ];

//        $this->meta = $this->arrayManager->set(
//            $this->arrayManager->slicePath($codePath, 0, -1) . '/ai_' . static::CODE . '_button',
//            $this->meta,
//            $codeButton
        // oryginał, nei działa w tym przypadku
//        );

//        $this->meta['ai_' . static::CODE . '_button'] = $codeButton;
        $this->meta['ai_' . static::CODE . '_button'] = $codeButton;
    }

    protected function addModal(): void
    {
        $this->meta['ai-' . static::CODE_DASHED]['arguments']['data']['config'] = [
            'componentType' => Container::NAME,
            'opened' => true,
            'collapsible' => false,
            'label' => '',
        ];

        $this->meta['ai_' . static::CODE . '_modal']['arguments']['data']['config'] = [
            'isTemplate' => false,
            'componentType' => Modal::NAME,
            'dataScope' => '',
            'provider' => 'cms_page_form.cms_page_form_data_source',
            'onCancel' => 'actionDone',
            'options' => [
                'title' => __('Generate Content for ' . static::TITLE ),
                'buttons' => [
                    [
                        'text' => __('Done'),
                        'class' => 'action-primary',
                        'actions' => [
                            [
                                'targetName' => '${ $.name }',
                                '__disableTmpl' => ['targetName' => false],
                                'actionName' => 'actionDone'
                            ]
                        ]
                    ],
                    [
                        'text' => __('Generate'),
                        'class' => 'action-primary',
                        'actions' => [
                            [
                                'targetName' => '${ $.name }',
                                '__disableTmpl' => ['targetName' => false],
                                'actionName' => 'actionGenerate'
                            ]
                        ]
                    ],
                ],

            ],
        ];

        $this->meta = $this->arrayManager->merge(
            $this->arrayManager->findPath(
                static::CONTAINER_PREFIX . static::CODE,
                $this->meta,
                null,
                'children'
            ),
            $this->meta,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'component' => 'Magento_Ui/js/form/components/group',
                        ],
                    ],
                ],
            ]
        );

        $this->meta['ai_' . static::CODE . '_modal']['children']['ai-' . static::CODE_DASHED ] = $this->meta['ai-' . static::CODE_DASHED ];

        unset($this->meta['ai-' . static::CODE_DASHED]);

    }
    public function modifyMeta(array $meta): array
    {
        $this->meta = $meta;
        $this->addLinkToModal();
        $this->addModal();
        //$this->addFieldsToModal();
        return $this->meta;
    }
}
