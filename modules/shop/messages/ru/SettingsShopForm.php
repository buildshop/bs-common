<?php

/**
 * Message translations.
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 * 
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package modules.messages.ru
 */
return array (
  'AUTO_FILL_SHORT_DESC' => 'Автоматически заполнять краткое описание "Характеристиками"',
  'AJAX_MODE' => 'Режим AJAX',
  'HINT_AJAX_MODE' => 'В этом режиме магазин будет максимально переключатся быстрее',
  'FP_PENNY' => 'Копейки',
  'FP_SEPARATOR_THOUSANDTH' => 'Разделитель тысячной',
  'FP_SEPARATOR_HUNDREDTH' => 'Разделитель сотых',
  'AUTO_GEN_URL' => 'Автоматически генерировать транслит ссылку товара?',
  'WHOLESALE' => 'Продажа оптом',
  'AUTO_GEN_CAT_META' => 'Автоматическая генерация метаданных категорий',
  'AUTO_GEN_CAT_TPL_TITLE' => 'Шаблон заголовка для категории',
  'AUTO_GEN_CAT_TPL_KEYWORDS' => 'Шаблон ключевых слов для категории',
  'AUTO_GEN_CAT_TPL_DESCRIPTION' => 'Шаблон мета описание для категории',
  'AUTO_GEN_META' => 'Автоматическая генерация метаданных товара',
  'AUTO_GEN_TPL_TITLE' => 'Шаблон заголовка для товара',
  'AUTO_GEN_TPL_KEYWORDS' => 'Шаблон ключевых слов для товара',
  'AUTO_GEN_TPL_DESCRIPTION' => 'Шаблон мета описание для товара',
  'IMG_PREVIEW_SIZE_LIST' => 'Размер изображений в списке товаров',
  'IMG_VIEW_SIZE' => 'Размер главного изображение в просмотре товара',
  'IMG_VIEW_THUMBS_SIZE' => 'Размер миниатюрных изображений в просмотре товара',
  'PER_PAGE' => 'Количество товаров на сайте',
  'MAXFILESIZE' => 'Максимальный размер файла',
  'MAXIMUM_IMAGE_SIZE' => 'Максимальный размер изображения',
  'WATERMARK_ACTIVE' => 'Активен',
  'WATERMARK_IMAGE' => 'Изображение',
  'WATERMARK_CORNER' => 'Позиция водяного знака',
  'WATERMARK_OFFSETX' => 'Отступ по горизонтали',
  'WATERMARK_OFFSETY' => 'Отступ по вертикали',
  'CORNER_LEFT_TOP' => 'Левый верхний угол',
  'CORNER_RIGHT_TOP' => 'Правый верхний угол',
  'CORNER_LEFT_BOTTOM' => 'Левый нижний угол',
  'CORNER_RIGHT_BOTTOM' => 'Правый нижний угол',
  'CORNER_CENTER' => 'Центр изображения',
  'ERRPR_WM_NO_IMAGE' => 'Ошибка. Водяной знак не изображение.',
  'HINT_PER_PAGE' => 'Вы можете указать несколько значений разделяя их запятой. Например: 10,20,30',
  'HINT_WHOLESALE' => 'Если оптом то цена умножатся не только на количество но и на количество в ящике',
  'HINT_AUTO_GEN_URL' => 'Формат генерации "[Название категории] [Производитель] [Артикул]"',
  'TAB_WM' => 'Водяной знак',
  'TAB_IMG' => 'Изображения',
  'TAB_SEO' => 'SEO товаров',
  'TAB_CAT_SEO' => 'SEO категорий',
  'TAB_GENERAL' => 'Основные',
  'TAB_FORMATPRICE' => 'Формат цены',
  'FILTER_ENABLE_PRICE' => 'Активировать фильтр диапазона цен?',
  'FILTER_ENABLE_BRAND' => 'Активировать фильтр производителей?',
  'FILTER_ENABLE_ATTR' => 'Активировать фильтр атрибутов?',
  'CREATE_BTN_ACTION' => 'Создавать товар сразу с типом товара.',
  'HINT_CREATE_BTN_ACTION' => 'Если Вы используете один "Тип товара", то вы можете привязать кнопку к этому типу.<br/>Если Вы используете несколько "Типов товаров" - выберете "Не привязывать."',
  'META_TPL' => '<p><code>%PRODUCT_NAME%</code> - Заголовок товара.</p><p><code>%PRODUCT_PRICE%</code> - Цена товара.</p>        <p><code>%PRODUCT_ARTICLE%</code> - Артикул товара.</p>        <p><code>%PRODUCT_PCS%</code> - Количество в ящике.</p>        <p><code>%PRODUCT_BRAND%</code> - Производитель товара.</p>        <p><code>%PRODUCT_MAIN_CATEGORY%</code> - Главная категория товара.</p>        <p><code>%CURRENT_CURRENCY%</code> - Текущая валюта ({currency}).</p>',
  'META_CAT_TPL' => '<p><code>%CATEGORY_NAME%</code> - Название категории.</p><p><code>%SUB_CATEGORY_NAME%</code> - Название предка категории.</p>        <p><code>%CURRENT_CURRENCY%</code> - Текущая валюта ({currency}).</p>',
);