<?php return [
    'plugin'     => [
        'name'        => 'Экспорт товаров в Яндекс.Маркет',
        'description' => 'Генерация XML файла для интеграции с Яндекс.Маркет',
    ],
    'menu'       => [
        'settings'             => 'Экспорт в Яндекс.Маркет',
        'settings_description' => 'Настройка экспорта каталога в Яндекс.Маркет',
    ],
    'field'      => [
        'short_store_name'                           => 'Название магазина',
        'short_store_name_placeholder'               => 'BestSeller',
        'full_company_name'                          => 'Полное наименование компании',
        'full_company_name_placeholder'              => 'Tne Best inc.',
        'store_homepage_url'                         => 'URL главной страницы магазина',
        'store_homepage_url_placeholder'             => 'http://best.seller.ru',
        'agency'                                     => 'Наименование агентства, которое оказывает техническую поддержку магазину',
        'agency_placeholder'                         => 'Shopaholic team',
        'email_agency'                               => 'Email адрес агентства, осуществляющего техподдержку',
        'email_agency_placeholder'                   => 'info@shopaholic.one',
        'use_main_currency_only'                     => 'Использовать только основную валюту',
        'default_currency_rates'                     => 'Использовать курсы валют заданные по умолчанию',
        'currency_rates'                             => 'Курсы валют',
        'offers_rate'                                => 'Ставка товарных предложений (bid)',
        'field_enable_auto_discounts'                => 'Автоматический расчет скидок',
        'code_model_for_images'                      => 'Получать изображения из:',
        'section_management_additional_fields_offer' => 'Дополнительные поля',
        'section_yandex_market'                      => 'Яндекс.Маркет',
        'field_price_type'                           => 'Тип цены',
    ],
    'button'     => [
        'export_catalog_to_xml' => 'Запуск экспорта',
        'download'              => 'Скачать',
    ],
    'widget'     => [
        'export_catalog_to_xml_for_yandex_market' => 'Экспорт каталога в Яндекс.Маркет',
    ],
    'permission' => [
        'yandexmarketsettings' => 'Управление настройками экспорта в Яндекс.Маркет',
    ],
    'message'    => [
        'export_is_completed'            => 'Экспорт завершен',
        'update_catalog_to_xml_confirm' => 'Запустить экспорт каталога в XML файл?',
    ],
];
