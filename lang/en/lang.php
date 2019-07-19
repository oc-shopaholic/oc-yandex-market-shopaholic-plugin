<?php return [
    'plugin'     => [
        'name'        => 'Export catalog for Yandex.Market',
        'description' => 'Generation XML file for integration with Yandex.Market',
    ],
    'menu'       => [
        'settings'             => 'Export to Yandex.Market',
        'settings_description' => 'Configure export catalog to Yandex.Market',
    ],
    'field'      => [
        'short_store_name'                           => 'Short store name',
        'short_store_name_placeholder'               => 'BestSeller',
        'full_company_name'                          => 'Full company name',
        'full_company_name_placeholder'              => 'Tne Best inc.',
        'store_homepage_url'                         => 'Store homepage URL',
        'store_homepage_url_placeholder'             => 'http://best.seller.ru',
        'agency'                                     => 'Name of agency that provides store technical support',
        'agency_placeholder'                         => 'Shopaholic team',
        'email_agency'                               => 'Email address of technical support agency',
        'email_agency_placeholder'                   => 'info@shopaholic.one',
        'use_main_currency_only'                     => 'Use only main currency',
        'default_currency_rates'                     => 'Use default currency rates',
        'currency_rates'                             => 'Currency rates',
        'offers_rate'                                => 'Offers rate (bid)',
        'field_enable_auto_discounts'                => 'Automatic calculation of discounts',
        'code_model_for_images'                      => 'Get images from:',
        'section_management_additional_fields_offer' => 'Additional fields',
        'section_yandex_market'                      => 'Yandex.Market',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Run export',
        'download'              => 'Download',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_yandex_market' => 'Export catalog to Yandex.Market',
    ],
    'permission' => [
        'yandexmarketsettings' => 'Manage settings of catalog export to Yandex.Market',
    ],
    'message'    => [
        'export_is_completed'           => 'Export completed',
        'update_catalog_to_xml_confirm' => 'Run export catalog to XML file?',
    ],
];
