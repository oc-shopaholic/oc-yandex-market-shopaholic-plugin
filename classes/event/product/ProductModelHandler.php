<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;

/**
 * Class ProductModelHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ProductModelHandler extends ModelHandler
{
    /** @var Product */
    protected $obElement;

    /**
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        parent::subscribe($obEvent);

        $this->extendModel();
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Product::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return ProductItem::class;
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
