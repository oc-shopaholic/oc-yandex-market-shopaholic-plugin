<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\YandexMarketShopaholic\Models\YandexMarketSettings;

/**
 * Class ExtendProductFieldsHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendProductFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $sCodeModelForImages = YandexMarketSettings::getValue('code_model_for_images', '');
        if ($sCodeModelForImages != YandexMarketSettings::CODE_PRODUCT) {
            return;
        }

        $arFields = [
            'section_yandex_market' => [
                'label' => 'lovata.yandexmarketshopaholic::lang.field.section_yandex_market',
                'tab'   => 'lovata.toolbox::lang.tab.images',
                'type'  => 'section',
                'span'  => 'full',
            ],
            'preview_image_yandex' => [
                'label'     => 'lovata.toolbox::lang.field.preview_image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'type'      => 'fileupload',
                'span'      => 'left',
                'mode'      => 'image',
                'fileTypes' => 'jpeg,png',
            ],
            'images_yandex' => [
                'label'     => 'lovata.toolbox::lang.field.images',
                'type'      => 'fileupload',
                'span'      => 'left',
                'mode'      => 'image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'fileTypes' => 'jpeg,png',
            ],
        ];

        $obWidget->addTabFields($arFields);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Product::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Products::class;
    }
}
