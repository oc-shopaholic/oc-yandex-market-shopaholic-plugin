<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Offer;

use Lovata\Shopaholic\Models\Offer;

/**
 * Class OfferModelHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Offer
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class OfferModelHandler
{
    /**
     * Extend Offer model
     */
    public function subscribe()
    {
        Offer::extend(function ($obOffer) {
            /** @var Offer $obOffer */
            $obOffer->fillable[] = 'preview_image_yandex';
            $obOffer->fillable[] = 'images_yandex';

            $obOffer->attachOne['preview_image_yandex'] = 'System\Models\File';
            $obOffer->attachMany['images_yandex']       = 'System\Models\File';

            $obOffer->addCachedField(['preview_image_yandex', 'images_yandex']);
        });
    }
}
