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
    /** @var Offer */
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
        Offer::extend(function ($obOffer) {
            /** @var Offer $obOffer */
            $obOffer->attachOne['preview_image_yandex'] = 'System\Models\File';
            $obOffer->attachMany['images_yandex']       = 'System\Models\File';
        });
    }
}
