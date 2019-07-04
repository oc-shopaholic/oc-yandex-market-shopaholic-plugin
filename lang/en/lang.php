<?php return [
    'plugin'     => [
        'name'        => 'Export catalog for Yandex Market',
        'description' => 'Generating XML file for Yandex Market in YAML format',
    ],
    'menu'       => [
        'yandexmarketsettings' => 'Export catalog to Yandex Market',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Store',
    ],
    'field'      => [
        'short_store_name'                           => 'Short store name',
        'full_company_name'                          => 'Full company name',
        'store_homepage_url'                         => 'Store homepage URL',
        'agency'                                     => 'Name of the agency that provides technical support to the store',
        'email_agency'                               => 'Email address of the technical support agency',
        'path_to_export_the_file'                    => 'Path to export the file (default app/yandex_market_yaml)',
        'use_main_currency_only'                     => 'Use only main currency',
        'default_currency_rates'                     => 'Use default currency rates',
        'currency_rates'                             => 'Currency rates',
        'offers_rate'                                => 'Offers rate (bid)',
        'field_enable_auto_discounts'                => 'Automatic calculation of discounts',
        'field_offer_properties'                     => 'Offer properties',
        'code_model_for_images'                      => 'Where to get the images?',
        'section_management_additional_fields_offer' => 'Management additional fields',
        'section_yandex_market'                      => 'Yandex market',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Export catalog to XML file',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_yandex_market' => 'Export catalog to XML for Yandex market',
    ],
    'permission' => [
        'yandexmarketsettings' => 'Manager export catalog for Yandex Market',
    ],
    'message'    => [
        'export_is_complete' => 'Export is complete',
    ],
];
