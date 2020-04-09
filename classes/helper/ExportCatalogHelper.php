<?php namespace Lovata\YandexMarketShopaholic\Classes\Helper;

use Event;
use System\Classes\PluginManager;

use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;

use Lovata\PropertiesShopaholic\Classes\Item\PropertyItem;
use Lovata\PropertiesShopaholic\Classes\Item\PropertyValueItem;
use Lovata\YandexMarketShopaholic\Models\YandexMarketSettings;

/**
 * Class ExportCatalogHelper
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExportCatalogHelper
{
    const EVENT_YANDEX_MARKET_SHOP_DATA = 'shopaholic.yandex.market.shop.data';
    const EVENT_YANDEX_MARKET_OFFER_DATA = 'shopaholic.yandex.market.offer.data';

    /**
     * @var array
     */
    protected $arYandexMarketSettings = [];

    /**
     * @var array
     * $arData = [
     *     'shop'   => [
     *          'name'         => '',
     *          'company'      => '',
     *          'url'          => '',
     *          'platform'     => 'October CMS',
     *          'agency'       => '',
     *          'email_agency' => '',
     *          'currencies' => [
     *              'id' => [
     *                  'id'   => '',
     *                  'rate' => '',
     *              ],
     *          ],
     *          'categories' => [
     *              [
     *                  'id'        => '',
     *                  'parent_id' => '',
     *                  'name'      => '',
     *              ],
     *          ]
     *     ],
     *     'offers' => [
     *          [
     *              'rate'           => '',
     *              'name'           => '',
     *              'url'            => '',
     *              'id'             => '',
     *              'price'          => '',
     *              'old_price'      => '',
     *              'currency_id'    => '',
     *              'category_id'    => '',
     *              'images'         => [],
     *              'auto_discounts' => '',
     *              'description'    => '',
     *              'properties'     => [
     *                  [
     *                      'name'    => '',
     *                      'value'   => '',
     *                      'measure' => '',
     *                  ],
     *              ],
     *          ],
     *     ],
     * ]
     */
    protected $arData = [
        'shop'   => [],
        'offers' => [],
    ];

    /**
     * @var Currency
     */
    protected $obDefaultCurrency;

    /**
     * Generate XML file
     */
    public function run()
    {
        //Prepare data
        $this->initShopData();
        $this->initProductListData();

        //Generate XML file
        $obGenerateXML = new GenerateXML();
        $obGenerateXML->generate($this->arData);
    }

    /**
     * Init shop data
     */
    protected function initShopData()
    {
        array_set($this->arData, 'shop.name', YandexMarketSettings::getValue('short_store_name'));
        array_set($this->arData, 'shop.company', YandexMarketSettings::getValue('full_company_name'));
        array_set($this->arData, 'shop.url', YandexMarketSettings::getValue('store_homepage_url'));
        array_set($this->arData, 'shop.platform', 'October CMS');
        array_set($this->arData, 'shop.agency', YandexMarketSettings::getValue('agency'));
        array_set($this->arData, 'shop.email_agency', YandexMarketSettings::getValue('email_agency'));
        array_set($this->arData, 'shop.currencies', $this->getCurrencyList());

        $this->initCategoryList();

        $arShopData = array_get($this->arData, 'shop');
        $arEventData = Event::fire(self::EVENT_YANDEX_MARKET_SHOP_DATA, [$arShopData]);
        if (empty($arEventData)) {
            return;
        }

        foreach ($arEventData as $arEventShopData) {
            if (empty($arEventShopData) || !is_array($arEventShopData)) {
                continue;
            }

            $arShopData = array_merge($arShopData, $arEventShopData);
        }

        $this->arData['shop'] = $arShopData;
    }

    /**
     * Init category list
     */
    protected function initCategoryList()
    {
        $obCategoryList = CategoryCollection::make()->active();
        if ($obCategoryList->isEmpty()) {
            return;
        }

        $arCategoryList = [];
        /** @var CategoryItem $obCategoryItem */
        foreach ($obCategoryList as $obCategoryItem) {
            if ($obCategoryItem->product_count == 0) {
                continue;
            }

            $arCategoryData = [
                'id'   => $obCategoryItem->id,
                'name' => $obCategoryItem->name,
            ];

            if ($obCategoryItem->parent->isNotEmpty()) {
                $arCategoryData['parent_id'] = $obCategoryItem->parent->id;
            }

            $arCategoryList[] = $arCategoryData;
        }

        array_set($this->arData, 'shop.categories', $arCategoryList);
    }

    /**
     * Init product list data
     */
    protected function initProductListData()
    {
        $obProductList = ProductCollection::make()->active();
        if ($obProductList->isEmpty()) {
            return;
        }

        /** @var ProductItem $obProduct */
        foreach ($obProductList as $obProduct) {
            $this->initOfferListData($obProduct);
        }
    }

    /**
     * Init offers data
     *
     * @param ProductItem $obProduct $obProduct
     */
    protected function initOfferListData($obProduct)
    {
        if ($obProduct->category->isEmpty()) {
            return;
        }

        $obOfferList = $obProduct->offer;
        if ($obOfferList->isEmpty()) {
            return;
        }

        foreach ($obOfferList as $obOffer) {
            $this->initOffer($obOffer, $obProduct);
        }
    }

    /**
     * Init offer
     *
     * @param OfferItem   $obOffer
     * @param ProductItem $obProduct
     */
    protected function initOffer($obOffer, $obProduct)
    {
        $arOfferData = [
            'name'           => $obOffer->name,
            'rate'           => YandexMarketSettings::getValue('offers_rate', ''),
            'url'            => $obProduct->getPageUrl(),
            'id'             => $obOffer->id,
            'price'          => $obOffer->price_value,
            'currency_id'    => !empty($this->obDefaultCurrency) ? $this->obDefaultCurrency->code : '',
            'category_id'    => $obProduct->category_id,
            'images'         => $this->getOfferImages($obOffer, $obProduct),
            'properties'     => $this->getOfferProperties($obOffer),
            'auto_discounts' => YandexMarketSettings::getValue('field_enable_auto_discounts', false),
            'description'    => $obOffer->description ? $obOffer->description : $obProduct->description,
            'brand_name'     => $this->getBrandName($obProduct),
            'old_price'      => $this->getOfferOldPrice($obOffer),
        ];

        $arEventData = Event::fire(self::EVENT_YANDEX_MARKET_OFFER_DATA, [$arOfferData]);
        if (!empty($arEventData)) {
            foreach ($arEventData as $arEventOfferData) {
                if (empty($arEventOfferData) || !is_array($arEventOfferData)) {
                    continue;
                }

                $arOfferData = array_merge($arOfferData, $arEventOfferData);
            }
        }

        $this->arData['offers'][] = $arOfferData;
    }

    /**
     * Get offer old price
     *
     * @param OfferItem $obOffer
     * @return string
     */
    protected function getOfferOldPrice($obOffer)
    {
        $bFieldEnableAutoDiscounts = YandexMarketSettings::getValue('field_enable_auto_discounts', false);
        $bFieldOldPrice = YandexMarketSettings::getValue('field_old_price', false);
        $bOffer = empty($obOffer) || !$obOffer instanceof OfferItem || $obOffer->old_price == 0;

        if ($bFieldEnableAutoDiscounts || !$bFieldOldPrice || $bOffer) {
            return '';
        }

        return $obOffer->old_price;
    }

    /**
     * Get brand name
     *
     * @param ProductItem $obProduct
     * @return string
     */
    protected function getBrandName($obProduct)
    {
        $bFieldBrand = YandexMarketSettings::getValue('field_brand', false);
        $sResult = $bFieldBrand ? (string) $obProduct->brand->name : '';

        return $sResult;
    }

    /**
     * Get offer images
     *
     * @param OfferItem   $obOffer
     * @param ProductItem $obProduct
     *
     * @return array
     */
    protected function getOfferImages($obOffer, $obProduct)
    {
        $arResult = [];

        $sCodeModelForImages = YandexMarketSettings::getValue('code_model_for_images', '');
        if (empty($sCodeModelForImages)) {
            return $arResult;
        }

        if (YandexMarketSettings::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obOffer;
        } else {
            $obItem = $obProduct;
        }

        /** @var OfferItem|ProductItem $obItem */
        if (!empty($obItem->preview_image_yandex)) {
            $arResult[] = $obItem->preview_image_yandex->path;   
        } elseif (!empty($obItem->preview_image)) {
            $arResult[] = $obItem->preview_image->path;
        }

        $bFieldImages = YandexMarketSettings::getValue('field_images', false);

        if (!$bFieldImages) {
            return $arResult;
        }
           
        if (!empty($obItem->images_yandex)) {
            foreach ($obItem->images_yandex as $obImage) {
                $arResult[] = $obImage->path;
            }
        } else {
            foreach ($obItem->images as $obImage) {
                $arResult[] = $obImage->path;
            }
        }

        return $arResult;
    }

    /**
     * Get offer property
     *
     * @param OfferItem $obOffer
     * @return array
     */
    protected function getOfferProperties($obOffer)
    {
        $arResult = [];

        $bHasPlugin = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
        $arAvailableProperty = (array) YandexMarketSettings::getValue('field_offer_properties', []);

        if (!$bHasPlugin || empty($arAvailableProperty)) {
            return $arResult;
        }

        $obPropertyList = $obOffer->property->intersect($arAvailableProperty);
        if ($obPropertyList->isEmpty()) {
            return $arResult;
        }


        /** @var PropertyItem $obPropertyItem */
        foreach ($obPropertyList as $obPropertyItem) {
            if (!$obPropertyItem->hasValue()) {
                continue;
            }

            $obPropertyValueList = $obPropertyItem->property_value;
            if ($obPropertyValueList->isEmpty()) {
                continue;
            }

            /** @var PropertyValueItem $obPropertyValueItem */
            foreach ($obPropertyValueList as $obPropertyValueItem) {
                $arResult[] = $this->getProperty($obPropertyItem, $obPropertyValueItem);
            }
        }

        return $arResult;
    }

    /**
     * Get property
     *
     * @param PropertyItem      $obPropertyItem
     * @param PropertyValueItem $obPropertyValueItem
     *
     * @return array
     */
    public function getProperty($obPropertyItem, $obPropertyValueItem)
    {
        $arResult = [
            'name'  => $obPropertyItem->name,
            'value' => $obPropertyValueItem->value,
        ];

        if ($obPropertyItem->measure->isNotEmpty()) {
            $arResult['measure'] = $obPropertyItem->measure->name;
        }

        return $arResult;
    }

    /**
     * Get currencies
     *
     * @return array
     */
    protected function getCurrencyList()
    {
        $arResult = [];
        $this->obDefaultCurrency = Currency::isDefault()->first();
        if (empty($this->obDefaultCurrency)) {
            return $arResult;
        }

        $bUseMainCurrencyOnly = YandexMarketSettings::getValue('use_main_currency_only', false);
        if ($bUseMainCurrencyOnly) {
            $arResult[] = ['id' => $this->obDefaultCurrency->code, 'rate' => '1'];

            return $arResult;
        }

        $obCurrencyList = Currency::active()->get();
        if ($obCurrencyList->isEmpty()) {
            return $arResult;
        }

        foreach ($obCurrencyList as $obCurrency) {
            $sRate = $this->getCurrencyRate($obCurrency);
            if (empty($sRate)) {
                continue;
            }

            $arResult[] = [
                'id'   => $obCurrency->code,
                'rate' => $this->getCurrencyRate($obCurrency),
            ];
        }

        return $arResult;
    }

    /**
     * Get currency rate
     * @param Currency $obCurrency
     * @return string
     */
    protected function getCurrencyRate($obCurrency)
    {
        if ($obCurrency->is_default) {
            return '1';
        }

        $bDefaultCurrencyRates = YandexMarketSettings::getValue('default_currency_rates', true);
        if ($bDefaultCurrencyRates) {
            return $obCurrency->rate;
        }

        $arYandexMarketSettingsRate = (array) YandexMarketSettings::getValue('currency_rates', []);
        if (empty($arYandexMarketSettingsRate) || !is_array($arYandexMarketSettingsRate)) {
            return '';
        }

        $sRate = '';
        foreach ($arYandexMarketSettingsRate as $arRate) {
            $iCurrencyId = array_get($arRate, 'currency_id', '');
            $sRate = array_get($arRate, 'rate', '');
            if (empty($iCurrencyId) || $iCurrencyId != $obCurrency->id) {
                continue;
            }

            if ($sRate == YandexMarketSettings::RATE_DEFAULT) {
                return $obCurrency->rate;
            }
        }

        return $sRate;
    }
}
