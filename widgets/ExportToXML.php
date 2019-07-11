<?php namespace Lovata\YandexMarketShopaholic\Widgets;

use Flash;
use Backend\Classes\ReportWidgetBase;
use Lovata\YandexMarketShopaholic\Classes\Helper\DataCollection;
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
    public function onGenerateXMLForYandexMarket()
    {
        $obDataCollection = new DataCollection();
        $obDataCollection->generate();

        Flash::info(trans('lovata.yandexmarketshopaholic::lang.message.export_is_complete'));

        $this->vars['sFileUrl'] = $this->getFileUrl();
    }

    /**
     * Get fie url
     *
     * @return string
     */
    protected function getFileUrl()
    {
        $sAppUrl = config('app.url');
        $sMediaFilePath = GenerateXML::getMediaPath().GenerateXML::FILE_NAME;
        $sStorageMediaFilePath = storage_path($sMediaFilePath);

        if (!file_exists($sStorageMediaFilePath)) {
            return '';
        }

        $sStorageFilePath = \Storage::url($sMediaFilePath);

        return $sAppUrl.$sStorageFilePath;
    }
}
