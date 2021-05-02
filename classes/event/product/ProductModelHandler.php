<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Product;

use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Models\Product;

/**
 * Class ProductModelHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ProductModelHandler
{
    /**
     * Extend Product model
     */
    public function subscribe()
    {
        Product::extend(function ($obProduct) {
            /** @var Product $obProduct */
            $obProduct->fillable[] = 'preview_image_yandex';
            $obProduct->fillable[] = 'images_yandex';

            $obProduct->attachOne['preview_image_yandex'] = 'System\Models\File';
            $obProduct->attachMany['images_yandex'] = 'System\Models\File';

            $obProduct->addCachedField(['preview_image_yandex', 'images_yandex']);
        });

        ProductItem::$arQueryWith[] = 'preview_image_yandex';
        ProductItem::$arQueryWith[] = 'images_yandex';
        ProductItem::$arQueryWith[] = 'offer.preview_image_yandex';
        ProductItem::$arQueryWith[] = 'offer.images_yandex';
    }
}
