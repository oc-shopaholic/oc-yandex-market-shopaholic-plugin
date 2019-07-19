<?php namespace Lovata\YandexMarketShopaholic\Classes\Console;

use Illuminate\Console\Command;
use Lovata\YandexMarketShopaholic\Classes\Helper\ExportCatalogHelper;

/**
 * Class CatalogExportForYandexMarket
 *
 * @package Lovata\YandexMarketShopaholic\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CatalogExportForYandexMarket extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:catalog_export.yandex_market';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate xml file for Yandex.Market';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $obDataCollection = new ExportCatalogHelper();
        $obDataCollection->run();
    }
}
