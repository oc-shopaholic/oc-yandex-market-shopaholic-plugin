<?php return [
    'plugin'     => [
        'name'        => 'Экспорт товаров для Yandex Market',
        'description' => 'Интеграция через YAML формата',
    ],
    'menu'       => [
        'yandexmarketsettings' => 'Экспорт в Yandex Market',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Магазин',
    ],
    'field'      => [
        'short_store_name'                           => 'Короткое название магазина',
        'full_company_name'                          => 'Полное наименование компании',
        'store_homepage_url'                         => 'URL главной страницы магазина',
        'agency'                                     => 'Наименование агентства, которое оказывает техническую поддержку магазину',
        'email_agency'                               => 'Email адрес агентства, осуществляющего техподдержку',
        'path_to_export_the_file'                    => 'Путь для экспорта файла (по умолчанию storage/app/media/yandex_market_yaml.xml)',
        'use_main_currency_only'                     => 'Использовать только основную валюту',
        'default_currency_rates'                     => 'Использовать курсы валют заданные по умолчанию',
        'currency_rates'                             => 'Курсы валют',
        'offers_rate'                                => 'Ставка товарных предложений (bid)',
        'field_enable_auto_discounts'                => 'Автоматический расчет скидок',
        'field_offer_properties'                     => 'Свойства товарного рпедложения',
        'code_model_for_images'                      => 'Откуда брать изображения?',
        'section_management_additional_fields_offer' => 'Управление дополнительными полями',
        'section_yandex_market'                      => 'Яндекс маркет',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Обновить каталог в XML файле',
        'download'              => 'Скачать',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_yandex_market' => 'Экспорт каталога в XML файл для Yandex Market',
    ],
    'permission' => [
        'yandexmarketsettings' => 'Управление экспортом для Yandex Market',
    ],
    'message'    => [
        'export_is_complete'            => 'Экспорт завершен',
        'update_catalog_to_xml_confirm' => 'Обновить каталог в XML файле?',
    ],
];
