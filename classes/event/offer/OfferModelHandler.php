<?php namespace Lovata\YandexMarketShopaholic\Classes\Event\Offer;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Item\OfferItem;

/**
 * Class OfferModelHandler
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Event\Offer
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class OfferModelHandler extends ModelHandler
{
    /** @var Offer */
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
        return Offer::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return OfferItem::class;
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
