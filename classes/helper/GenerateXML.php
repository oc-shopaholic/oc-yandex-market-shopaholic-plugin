<?php namespace Lovata\YandexMarketShopaholic\Classes\Helper;

use File;
use XMLWriter;
use October\Rain\Argon\Argon;

/**
 * Class GenerateXML
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class GenerateXML
{
    const FILE_NAME = 'yandex_market.yml';
    const DEFAULT_DIRECTORY = 'app/media/';

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
     * Get path to file relative to storage folder
     * @return string
     */
    public static function getFilePath()
    {
        $sResult = self::DEFAULT_DIRECTORY.self::FILE_NAME;

        return $sResult;
    }

    /**
     * Generate
     *
     * @param array $arData
     */
    public function generate($arData)
    {
        $this->arShopData   = (array) array_get($arData, 'shop', []);
        $this->arOffersData = (array) array_get($arData, 'offers', []);
        if (empty($this->arShopData) || empty($this->arOffersData)) {
            return;
        }

        $this->start();
        $this->setContent();
        $this->stop();

        $this->save();
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
        $arCurrencyList = array_get($this->arShopData, 'currencies', []);
        $arCategoryList = array_get($this->arShopData, 'categories', []);

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

        if (!empty($arCurrencyList)) {
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
        }

        if (!empty($arCategoryList)) {
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
    }

    /**
     * Set offers element
     */
    protected function setOffersElement()
    {
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
        // <description>
        $this->obXMLWriter->writeElement('description', array_get($arOffer, 'description'));
        // </description>
        // <url>
        $this->obXMLWriter->writeElement('url', array_get($arOffer, 'url'));
        // </url>
        // <enable_auto_discounts>
        $this->obXMLWriter->writeElement('enable_auto_discounts', (int) array_get($arOffer, 'auto_discounts'));
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
        $sMediaPath = self::getFilePath();
        $sFilePath = storage_path($sMediaPath);

        if (file_exists($sFilePath)) {
            unlink($sFilePath);
        }

        File::put($sFilePath, $this->sContent);
    }
}
