<?php namespace Lovata\YandexMarketShopaholic\Classes\Helper;

use Event;
use Lovata\PropertiesShopaholic\Classes\Item\PropertyItem;
use Lovata\PropertiesShopaholic\Classes\Item\PropertyValueItem;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\YandexMarketShopaholic\Models\YandexMarketSettings as Config;
use Lovata\Shopaholic\Models\Currency;
use System\Classes\PluginManager;

/**
 * Class DataCollection
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class DataCollection
{
    const EVENT_YANDEX_MARKET_SHOP_DATA  = 'shopaholic.yandex.market.shop.data';
    const EVENT_YANDEX_MARKET_OFFER_DATA = 'shopaholic.yandex.market.offer.data';

    /**
     * @var array
     */
    protected $arConfig = [];

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
    protected $arData = [];

    /**
     * @var Currency
     */
    protected $obDefaultCurrency;

    /**
     * Generate
     */
    public function generate()
    {
        $this->initShopData();
        $this->initProductListData();

        $obGenerateXML = new GenerateXML();
        $obGenerateXML->generate($this->arData);
    }

    /**
     * Init shop data
     */
    protected function initShopData()
    {
        if (!is_array($this->arData)) {
            return;
        }

        array_set($this->arData, 'shop.name', Config::getValue('short_store_name'));
        array_set($this->arData, 'shop.company', Config::getValue('full_company_name'));
        array_set($this->arData, 'shop.url', Config::getValue('store_homepage_url'));
        array_set($this->arData, 'shop.platform', 'October CMS');
        array_set($this->arData, 'shop.agency', Config::getValue('agency'));
        array_set($this->arData, 'shop.email_agency', Config::getValue('email_agency'));
        array_set($this->arData, 'shop.currencies', $this->currencies());

        $this->initCategoryList();

        $arEventShopData = Event::fire(self::EVENT_YANDEX_MARKET_SHOP_DATA, [array_get($this->arData, 'shop')], true);

        if (!empty($arEventShopData) && is_array($arEventShopData)) {
            array_set($this->arData, 'shop', $arEventShopData);
        }
    }

    /**
     * Init category list
     */
    protected function initCategoryList()
    {
        if (!is_array($this->arData)) {
            return;
        }

        $obCategoryList = CategoryCollection::make()->active();

        if ($obCategoryList->isEmpty()) {
            return;
        }

        $arCategoryList = [];
        /** @var CategoryItem $obCategory */
        foreach ($obCategoryList as $obCategory) {
            $obProductList = ProductCollection::make()->category($obCategory->id, true)->active();

            if ($obProductList->isEmpty()) {
                continue;
            }

            $arCategory = [
                'id'   => $obCategory->id,
                'name' => $obCategory->name,
            ];

            if ($obCategory->parent->isNotEmpty()) {
                $arCategory['parent_id'] = $obCategory->parent->id;
            }

            $arCategoryList[] = $arCategory;
        }

        array_set($this->arData, 'shop.categories', $arCategoryList);
    }

    /**
     * Init product list data
     */
    protected function initProductListData()
    {
        if (!is_array($this->arData)) {
            return;
        }

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
        if (empty($obProduct) || !$obProduct instanceof ProductItem || $obProduct->category->isEmpty()) {
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
     * @param OfferItem $obOffer
     * @param ProductItem $obProduct
     */
    protected function initOffer($obOffer, $obProduct)
    {
        $bOffer   = empty($obOffer) || !$obOffer instanceof OfferItem;
        $bProduct = empty($obProduct) || !$obProduct instanceof ProductItem;
        if ($bOffer || $bProduct || !is_array($this->arData) || empty($this->obDefaultCurrency)) {
            return;
        }

        $bFieldEnableAutoDiscounts = Config::getValue('field_enable_auto_discounts', false);
        $bFieldBrand               = Config::getValue('field_brand', false);
        $bFieldOldPrice            = Config::getValue('field_old_price', false);

        $arOfferList = array_pull($this->arData, 'offers', []);
        $arOffer = [
            'name'           => $obOffer->name,
            'rate'           => Config::getValue('offers_rate', ''),
            'url'            => $obProduct->getPageUrl(),
            'id'             => $obOffer->id,
            'price'          => $obOffer->price,
            'currency_id'    => $this->obDefaultCurrency->code,
            'category_id'    => $obProduct->category_id,
            'images'         => $this->getOfferImages($obOffer, $obProduct),
            'properties'     => $this->getOfferProperties($obOffer),
            'auto_discounts' => $bFieldEnableAutoDiscounts,
            'description'    => $obOffer->description,
        ];

        if ($bFieldBrand) {
            $arOffer['brand_name'] = $this->getBrandName($obProduct);
        }
        if (!$bFieldEnableAutoDiscounts && $bFieldOldPrice) {
            $arOffer['old_price'] = $obOffer->old_price;
        }

        $arEventOfferData = Event::fire(self::EVENT_YANDEX_MARKET_OFFER_DATA, [$arOffer], true);

        if (!empty($arEventOfferData) && is_array($arEventOfferData)) {
            $arOffer = $arEventOfferData;
        }

        $arOfferList[] = $arOffer;

        array_set($this->arData, 'offers', $arOfferList);
    }

    /**
     * Get brand name
     *
     * @param ProductItem $obProduct
     * @return string
     */
    protected function getBrandName($obProduct)
    {
        if ($obProduct->isEmpty() || !$obProduct instanceof ProductItem || $obProduct->brand->isEmpty()) {
            return '';
        }

        return $obProduct->brand->name;
    }

    /**
     * Get offer images
     *
     * @param OfferItem|OfferItem $obObOffer
     * @param OfferItem|ProductItem $obProduct
     *
     * @return array
     */
    protected function getOfferImages($obObOffer, $obProduct)
    {
        $arResult = [];

        $sCodeModelForImages = Config::getValue('code_model_for_images');

        if (Config::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obObOffer;
        } else {
            $obItem = $obProduct;
        }

        if ( empty($obItem) || (!$obItem instanceof OfferItem && !$obItem instanceof ProductItem)) {
            return $arResult;
        }

        $obModel = $obItem->getObject();

        if (empty($obModel)) {
            return $arResult;
        }

        if (!empty($obModel->preview_image_yandex)) {
            $arResult[] = $obModel->preview_image_yandex->path;
        }

        $bFieldImages = Config::getValue('field_images', false);

        if (!$bFieldImages || $obModel->images_yandex->isEmpty()) {
            return $arResult;
        }

        foreach ($obModel->images_yandex as $obImage) {
            $arResult[] = $obImage->path;
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
        $arAvailableProperty = Config::getValue('field_offer_properties', []);

        if (!$bHasPlugin || empty($obOffer) || !$obOffer instanceof OfferItem || !is_array($arAvailableProperty)) {
            return $arResult;
        }
        $obPropertyList = $obOffer->property;

        if ($obPropertyList->isEmpty()) {
            return $arResult;
        }


        /** @var PropertyItem $obPropertyItem */
        foreach ($obPropertyList as $obPropertyItem) {
            if (!$obPropertyItem->hasValue() || !in_array($obPropertyItem->id, $arAvailableProperty)) {
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
     * @param PropertyItem $obPropertyItem
     * @param PropertyValueItem $obPropertyValueItem
     *
     * @return array
     */
    public function getProperty($obPropertyItem, $obPropertyValueItem)
    {
        if (!$obPropertyItem instanceof PropertyItem || !$obPropertyValueItem instanceof PropertyValueItem) {
            return [];
        }

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
    protected function currencies()
    {
        $arResult = [];
        $this->obDefaultCurrency = Currency::isDefault()->first();

        if (empty($this->obDefaultCurrency)) {
            return $arResult;
        }

        $bUseMainCurrencyOnly = Config::getValue('use_main_currency_only', false);
        if ($bUseMainCurrencyOnly) {
            $arResult[] = ['id'   => $this->obDefaultCurrency->code, 'rate' => '1'];

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
        if (empty($obCurrency) || !$obCurrency instanceof Currency) {
            return '';
        }

        if ($obCurrency->is_default) {
            return '1';
        }

        $bDefaultCurrencyRates = Config::getValue('default_currency_rates', true);

        if ($bDefaultCurrencyRates) {
            return $obCurrency->rate;
        }

        $arConfigRate = (array) Config::getValue('currency_rates', []);
        if (empty($arConfigRate) || !is_array($arConfigRate)) {
            return '';
        }

        $sRate = '';

        foreach ($arConfigRate as $arRate) {
            $iCurrencyId = array_get($arRate, 'currency_id', '');
            $sRate       = array_get($arRate, 'rate', '');

            if (empty($iCurrencyId) || $iCurrencyId != $obCurrency->id) {
                continue;
            }

            if ($sRate == Config::RATE_DEFAULT) {
                return $obCurrency->rate;
            }
        }

        return $sRate;
    }
}
