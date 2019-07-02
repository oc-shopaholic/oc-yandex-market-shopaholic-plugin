<?php namespace Lovata\YandexMarketShopaholic\Widgets;

use Flash;
use Artisan;
use Backend\Classes\ReportWidgetBase;

/**
 * Class ExportToXML
 * @package Lovata\YandexMarketShopaholic\Widgets
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExportToXML extends ReportWidgetBase
{
    /**
     * Render method
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        return $this->makePartial('widget');
    }

    /**
     * Generate xml for yandex market
     */
    public function onGenerateXMLForYandexMarket()
    {
        Artisan::call('shopaholic:catalog_export_to_yandex');
        Flash::info(trans('lovata.yandexmarketshopaholic::lang.message.export_is_complete'));
    }
}
