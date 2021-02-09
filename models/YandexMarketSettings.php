<?php namespace Lovata\YandexMarketShopaholic\Models;

use Lang;
use System\Classes\PluginManager;
use October\Rain\Database\Traits\Validation;

use Lovata\Toolbox\Models\CommonSettings;
use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Models\PriceType;

/**
 * Class YandexMarketSettings
 *
 * @package Lovata\YandexMarketShopaholic\Models
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * @mixin \System\Behaviors\SettingsModel
 */
class YandexMarketSettings extends CommonSettings
{
    use Validation;

    const SETTINGS_CODE = 'lovata_shopaholic_yandex_market_export_settings';

    const RATE_DEFAULT = 'DEFAULT';
    const RATE_CBRF = 'CBRF';
    const RATE_NBU = 'NBU';
    const RATE_NBK = 'NBK';
    const RATE_CB = 'CB';

    const CODE_OFFER = 'offer';
    const CODE_PRODUCT = 'product';

    /**
     * @var string
     */
    public $settingsCode = 'lovata_shopaholic_yandex_market_export_settings';
    /**
     * @var array
     */
    public $rules = [
        'short_store_name'   => 'required',
        'full_company_name'  => 'required',
        'store_homepage_url' => 'required',
        'offers_rate'        => 'required|integer',
    ];

    /**
     * @var array
     */
    public $attributeNames = [
        'short_store_name'   => 'lovata.yandexmarketshopaholic::lang.field.short_store_name',
        'full_company_name'  => 'lovata.yandexmarketshopaholic::lang.field.full_company_name',
        'store_homepage_url' => 'lovata.yandexmarketshopaholic::lang.field.store_homepage_url',
        'offers_rate'        => 'lovata.yandexmarketshopaholic::lang.field.offers_rate',
    ];

    /**
     * Get currency options
     *
     * @return array
     */
    public function getCurrencyOptions()
    {
        return Currency::where('is_default', false)
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Get rate options
     *
     * @return array
     */
    public function getRateOptions()
    {
        return [
            self::RATE_DEFAULT => self::RATE_DEFAULT,
            self::RATE_CBRF    => self::RATE_CBRF,
            self::RATE_NBU     => self::RATE_NBU,
            self::RATE_NBK     => self::RATE_NBK,
            self::RATE_CB      => self::RATE_CB,
        ];
    }

    /**
     * Get model potions
     *
     * @return array
     */
    public function getGetImagesFromOptions()
    {
        return [
            self::CODE_OFFER   => Lang::get('lovata.shopaholic::lang.field.offer'),
            self::CODE_PRODUCT => Lang::get('lovata.toolbox::lang.field.product'),
        ];
    }

    /**
     * Get offer properties options
     *
     * @return array
     */
    public function getOfferPropertiesOptions()
    {
        if (!PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            return [];
        }

        return \Lovata\PropertiesShopaholic\Models\Property::active()
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Get price type options
     *
     * @return array
     */
    public function getPriceTypeOptions() : array
    {
        return PriceType::where('active', true)
            ->pluck('name', 'id')
            ->all();
    }
}
