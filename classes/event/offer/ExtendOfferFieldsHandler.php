<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Offer;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Controllers\Offers;

/**
 * Class ExtendOfferFieldsHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Offer
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendOfferFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Offer::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Offers::class;
    }

    /**
     * Extend fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $this->addField($obWidget);
    }

    /**
     * Remove fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function addField($obWidget)
    {
        $obWidget->addTabFields(
            [
                'section_yandex_market' => [
                    'label' => 'lovata.yandexmarketshopaholic::lang.field.section_yandex_market',
                    'type'  => 'section',
                    'span'  => 'full',
                    'tab'   => 'lovata.toolbox::lang.tab.images',
                ],
                'preview_image_yandex'  => [
                    'label'     => 'lovata.toolbox::lang.field.preview_image',
                    'type'      => 'fileupload',
                    'span'      => 'full',
                    'required'  => true,
                    'mode'      => 'image',
                    'tab'       => 'lovata.toolbox::lang.tab.images',
                    'fileTypes' => 'jpeg,png',
                ],
                'images_yandex'         => [
                    'label'     => 'lovata.toolbox::lang.field.images',
                    'type'      => 'fileupload',
                    'span'      => 'full',
                    'required'  => false,
                    'mode'      => 'image',
                    'tab'       => 'lovata.toolbox::lang.tab.images',
                    'fileTypes' => 'jpeg,png',
                ],
            ]
        );
    }
}
