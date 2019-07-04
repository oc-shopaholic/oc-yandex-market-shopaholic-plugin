<?php namespace Lovata\YandexMarketShopaholic\Classes\Helper;

use File;
use XMLWriter;
use October\Rain\Argon\Argon;
use Lovata\YandexMarketShopaholic\Models\YandexMarketSettings as Config;

/**
 * Class GenerateXML
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class GenerateXML
{
    const FILE_NAME = 'yandex_market_yaml.xml';

    /**
     * @var array
     */
    protected $arShopData = [];

    /**
     * @var array
     */
    protected $arOffersData = [];

    /**
     * Generated content
     */
    protected $sContent;

    /**
     * @var XMLWriter
     */
    protected $obXMLWriter;

    /**
     * Generate
     *
     * @param array $arData
     */
    public function generate($arData)
    {
        $this->arShopData   = array_get($arData, 'shop', []);
        $this->arOffersData = array_get($arData, 'offers', []);

        if (empty($this->arShopData) || empty($this->arOffersData)) {
            return;
        }

        $this->start();
        $this->setContent();
        $this->stop();
        $this->save();
    }

    /**
     * Get file path
     *
     * @return string
     */
    public static function getFilePath()
    {
        $sFilePath = (string) Config::getValue('path_to_export_the_file' , '');

        if (empty($sFilePath)) {
            return '/';
        }

        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/^\//', '', $sFilePath);
        $sFilePath = preg_replace('/\/$/', '', $sFilePath);
        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/ +/', '', $sFilePath);
        $sFilePath .= '/';

        return $sFilePath;
    }

    /**
     * Start xml content generation
     */
    protected function start()
    {
        $this->obXMLWriter = new XMLWriter();
        $this->obXMLWriter->openMemory();
        $this->obXMLWriter->setIndent(1);
        $this->obXMLWriter->startDocument('1.0', 'UTF-8');
        $this->obXMLWriter->startElement('yml_catalog');
        $this->obXMLWriter->writeAttribute('date', Argon::now()->format('Y-m-d h:i'));
    }

    /**
     * Set content
     */
    protected function setContent()
    {
        // <shop>
        $this->obXMLWriter->startElement('shop');
        $this->setShopElement();
        $this->setOffersElement();
        // </shop>
        $this->obXMLWriter->endElement();
    }

    /**
     * Set shop element
     */
    protected function setShopElement()
    {
        if (empty($this->arShopData) || !is_array($this->arShopData) || empty($this->obXMLWriter)) {
            return;
        }

        $arCurrencyList = array_get($this->arShopData, 'currencies', []);
        $arCategoryList = array_get($this->arShopData, 'categories', []);

        if (empty($arCurrencyList) || empty($arCategoryList)) {
            return;
        }

        // <name>
        $this->obXMLWriter->writeElement('name', array_get($this->arShopData, 'name'));
        // </name>
        // <company>
        $this->obXMLWriter->writeElement('company', array_get($this->arShopData, 'company'));
        // </company>
        // <url>
        $this->obXMLWriter->writeElement('url', array_get($this->arShopData, 'url'));
        // </url>
        // <platform>
        $this->obXMLWriter->writeElement('platform', array_get($this->arShopData, 'platform'));
        // </platform>
        // <agency>
        $this->obXMLWriter->writeElement('agency', array_get($this->arShopData, 'agency'));
        // </agency>
        // <email_agency>
        $this->obXMLWriter->writeElement('email', array_get($this->arShopData, 'email_agency'));
        // </email_agency>
        // <currencies>
        $this->obXMLWriter->startElement('currencies');
        // </currencies>
        foreach ($arCurrencyList as $arCurrency) {
            // <currency id='' rate=''>
            $this->obXMLWriter->startElement('currency');
            $this->obXMLWriter->writeAttribute('id', array_get($arCurrency, 'id'));
            $this->obXMLWriter->writeAttribute('rate', array_get($arCurrency, 'rate'));
            $this->obXMLWriter->endElement();
            // </currency>
        }
        // </currencies>
        $this->obXMLWriter->endElement();
        // <categories>
        $this->obXMLWriter->startElement('categories');
        foreach ($arCategoryList as $arCategory) {
            $iParentId = array_get($arCategory, 'parent_id');
            // <category id='' parentId=''>
            $this->obXMLWriter->startElement('category');
            $this->obXMLWriter->writeAttribute('id', array_get($arCategory, 'id'));
            if (!empty($iParentId)) {
                $this->obXMLWriter->writeAttribute('parentId', array_get($arCategory, 'parent_id'));
            }
            $this->obXMLWriter->text(array_get($arCategory, 'name'));
            $this->obXMLWriter->endElement();
            // </category>
        }
        // </categories>
        $this->obXMLWriter->endElement();
    }

    /**
     * Set offers element
     */
    protected function setOffersElement()
    {
        if (empty($this->arOffersData) || !is_array($this->arOffersData) || empty($this->obXMLWriter)) {
            return;
        }

        // <offers>
        $this->obXMLWriter->startElement('offers');
        foreach ($this->arOffersData as $arOffer) {
            $this->setOfferElement($arOffer);
        }
        // </offers>
        $this->obXMLWriter->endElement();
    }

    /**
     * Set offer element
     *
     * @param array $arOffer
     */
    protected function setOfferElement($arOffer)
    {
        if (empty($arOffer) || !is_array($arOffer)) {
            return;
        }

        $fOldPrice      = array_get($arOffer, 'old_price');
        $sBrandName     = array_get($arOffer, 'brand_name');
        $arImageList    = array_get($arOffer, 'images', []);
        $arPropertyList = array_get($arOffer, 'properties', []);

        // <offer id='' bid=''>
        $this->obXMLWriter->startElement('offer');
        $this->obXMLWriter->writeAttribute('id', array_get($arOffer, 'id'));
        $this->obXMLWriter->writeAttribute('bid', array_get($arOffer, 'rate'));
        // <name>
        $this->obXMLWriter->writeElement('name', array_get($arOffer, 'name'));
        // </name>
        // <url>
        $this->obXMLWriter->writeElement('url', array_get($arOffer, 'url'));
        // </url>
        // <enable_auto_discounts>
        $this->obXMLWriter->writeElement('enable_auto_discounts', array_get($arOffer, 'auto_discounts'));
        // </enable_auto_discounts>
        if (!empty($sBrandName)) {
            // <vendor>
            $this->obXMLWriter->writeElement('vendor', $sBrandName);
            // </vendor>
        }
        // <price>
        $this->obXMLWriter->writeElement('price', array_get($arOffer, 'price'));
        // </price>
        if (!empty($fOldPrice)) {
            // <oldprice>
            $this->obXMLWriter->writeElement('oldprice', $fOldPrice);
            // </oldprice>
        }
        // <currencyId>
        $this->obXMLWriter->writeElement('currencyId', array_get($arOffer, 'currency_id'));
        // </currencyId>
        // <categoryId>
        $this->obXMLWriter->writeElement('categoryId', array_get($arOffer, 'category_id'));
        // </categoryId>
        if (!empty($arImageList)) {
            foreach ($arImageList as $sImageUrl) {
                // <picture>
                $this->obXMLWriter->writeElement('picture', $sImageUrl);
                // </picture>
            }
        }
        if (!empty($arPropertyList)) {
            foreach ($arPropertyList as $arProperty) {
                // <param name='' unit=''>
                $this->obXMLWriter->startElement('param');
                $this->obXMLWriter->writeAttribute('name', array_get($arProperty, 'name'));
                $this->obXMLWriter->writeAttribute('unit', array_get($arProperty, 'measure'));
                $this->obXMLWriter->text(array_get($arProperty, 'value'));
                $this->obXMLWriter->endElement();
                // </param>
            }
        }
        // </offer>
        $this->obXMLWriter->endElement();
    }

    /**
     * End xml content generation
     */
    protected function stop()
    {
        $this->obXMLWriter->endElement();
        $this->obXMLWriter->endDocument();
        $this->sContent = $this->obXMLWriter->outputMemory();
    }

    /**
     * Save generated content
     */
    protected function save()
    {
        $sFilePath = self::getFilePath();

        $sFilePath = base_path($sFilePath);

        if (!file_exists($sFilePath)) {
            mkdir($sFilePath, null, true);
        }

        $sFile = $sFilePath.self::FILE_NAME;

        File::put($sFile, $this->sContent);
    }
}
