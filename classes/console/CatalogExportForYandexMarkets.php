<?php namespace Lovata\YandexMarketShopaholic\Classes\Console;

use Illuminate\Console\Command;
use Lovata\YandexMarketShopaholic\Classes\Helper\YandexDataCollection;

/**
 * Class CatalogExportForYandexMarkets
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CatalogExportForYandexMarkets extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:catalog_export_to_yandex';

    /**
     * @var string The console command description.
     */
    protected $description = 'Run catalog export to Yandex markets';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $obYandexDataCollection = new YandexDataCollection();
        $obYandexDataCollection->generate();
    }
}
