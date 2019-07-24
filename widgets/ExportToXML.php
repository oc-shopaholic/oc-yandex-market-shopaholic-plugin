<?php namespace Lovata\YandexMarketShopaholic\Widgets;

use Flash;
use Storage;
use Backend\Classes\ReportWidgetBase;
use Lovata\YandexMarketShopaholic\Classes\Helper\ExportCatalogHelper;
use Lovata\YandexMarketShopaholic\Classes\Helper\GenerateXML;

/**
 * Class ExportToXML
 *
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
        $this->vars['sFileUrl'] = $this->getFileUrl();

        return $this->makePartial('widget');
    }

    /**
     * Generate xml for yandex market
     */
    public function onGenerateXMLFileYandexMarket()
    {
        $obDataCollection = new ExportCatalogHelper();
        $obDataCollection->run();

        Flash::info(trans('lovata.yandexmarketshopaholic::lang.message.export_is_completed'));

        $this->vars['sFileUrl'] = $this->getFileUrl();
    }

    /**
     * Get fie url
     *
     * @return string
     */
    protected function getFileUrl()
    {
        $sFilePath = GenerateXML::getFilePath();
        $sFullFilePath = storage_path($sFilePath);
        if (!file_exists($sFullFilePath)) {
            return null;
        }

        $sStorageFilePath = Storage::url($sFilePath);

        return $sStorageFilePath;
    }
}
