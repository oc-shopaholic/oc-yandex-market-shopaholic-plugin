<?php return [
    'plugin'     => [
        'name'        => 'Экспорт каталога в Яндекс маркета',
        'description' => 'Настройки экспорта каталога для Яндекс Маркета',
    ],
    'menu'       => [
        'yandexmarketsettings' => 'Экспорт каталога для Яндекс маркета',
    ],
    'component'  => [],
    'tab'        => [
        'store' => 'Магазин',
    ],
    'field'      => [
        'short_store_name'                            => 'Короткое название магазина',
        'full_company_name'                          => 'Полное наименование компании',
        'store_homepage_url'                         => 'URL главной страницы магазина',
        'agency'                   => 'Наименование агентства, которое оказывает техническую поддержку магазину',
        'email_agency'             => 'Email адрес агентства, осуществляющего техподдержку',
        'path_to_export_the_file'                    => 'Путь для экспорта файла (по умолчанию app/)',
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
    'permission' => [
        'yandexmarketsettings' => 'Управление экспортом каталога для Яндекс маркета',
    ],
];
