<?php namespace Lovata\YandexMarketShopaholic;

use Event;
use System\Classes\PluginBase;

// Command
use Lovata\YandexMarketShopaholic\Classes\Console\CatalogExportForYandexMarket;

// Offer event
use Lovata\YandexMarketShopaholic\Classes\Event\Offer\ExtendOfferFieldsHandler;
use Lovata\YandexMarketShopaholic\Classes\Event\Offer\OfferModelHandler;
// Product event
use Lovata\YandexMarketShopaholic\Classes\Event\Product\ExtendProductFieldsHandler;
use Lovata\YandexMarketShopaholic\Classes\Event\Product\ProductModelHandler;

/**
 * Class Plugin
 *
 * @package Lovata\YandexMarketShopaholic
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Shopaholic', 'Lovata.Toolbox'];

    /**
     * @var array
     */
    public $require = ['Lovata.Shopaholic', 'Lovata.Toolbox'];

    /**
     * Register settings
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config'    => [
                'label'       => 'lovata.yandexmarketshopaholic::lang.menu.settings',
                'description' => 'lovata.yandexmarketshopaholic::lang.menu.settings_description',
                'category'    => 'lovata.shopaholic::lang.tab.settings',
                'icon'        => 'icon-upload',
                'class'       => 'Lovata\YandexMarketShopaholic\Models\YandexMarketSettings',
                'permissions' => ['shopaholic-menu-yandex-market-export'],
                'order'       => 9000,
            ],
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        // Offer event
        Event::subscribe(ExtendOfferFieldsHandler::class);
        Event::subscribe(OfferModelHandler::class);
        // Product event
        Event::subscribe(ExtendProductFieldsHandler::class);
        Event::subscribe(ProductModelHandler::class);
    }

    /**
     * Register artisan command
     */
    public function register()
    {
        $this->registerConsoleCommand('shopaholic:catalog_export.yandex_market', CatalogExportForYandexMarket::class);
    }

    /**
     * @return array
     */
    public function registerReportWidgets()
    {
        return [
            'Lovata\YandexMarketShopaholic\Widgets\ExportToXML' => [
                'label' => 'lovata.yandexmarketshopaholic::lang.widget.export_catalog_to_xml_for_yandex_market',
            ],
        ];
    }
}
