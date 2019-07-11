<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Product;

use Lovata\Shopaholic\Models\Product;

/**
 * Class ProductModelHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ProductModelHandler
{
    /** @var Product */
    protected $obElement;

    /**
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $this->extendModel();
    }

    /**
     * Extend Model object
     */
    protected function extendModel()
    {
        Product::extend(function ($obProduct) {
            /** @var Product $obProduct */
            $obProduct->attachOne['preview_image_yandex'] = 'System\Models\File';
            $obProduct->attachMany['images_yandex']       = 'System\Models\File';
        });
    }
}
