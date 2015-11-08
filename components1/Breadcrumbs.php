<?php

/**
 * Хлебные крошки отображается список ссылок, указывающих на положение текущей страницы в весь сайт.
 *
 * For example, breadcrumbs like "Home > Sample Post > Edit" means the user is viewing an edit page
 * for the "Sample Post". He can click on "Sample Post" to view that page, or he can click on "Home"
 * to return to the homepage.
 *
 * To use CBreadcrumbs, one usually needs to configure its {@link links} property, which specifies
 * the links to be displayed. For example,
 *
 * <code>
 * $this->widget('Breadcrumbs', array(
 *     'links'=>array(
 *         'Sample post'=>array('post/view', 'id'=>12),
 *         'Edit',
 *     ),
 * ));
 * </code>
 *
 * Because breadcrumbs usually appears in nearly every page of a website, the widget is better to be placed
 * in a layout view. One can define a property "breadcrumbs" in the base controller class and assign it to the widget
 * in the layout, like the following:
 *
 * <code>
 * $this->widget('Breadcrumbs', array('links'=>$this->breadcrumbs));
 * </code>
 *
 * Тогда, в каждом скрипте вида необходимо лишь присвоить «breadcrumbs» свойство по мере необходимости.
 *
 * @package widgets
 * @uses CWidget 
 */
class Breadcrumbs extends CWidget {

    /**
     * @var string Ммя тега для крошки контейнера тега. По умолчанию 'div'.
     */
    public $tagName = 'div';

    /**
     * @var array HTML атрибуты для крошки тега контейнера.
     */
    public $htmlOptions = array('class' => 'breadcrumbs');

    /**
     * @var boolean HTML кодировать ссылку labels. По умолчанию true.
     */
    public $encodeLabel = true;

    /**
     * @var string the first hyperlink in the breadcrumbs (called home link).
     * If this property is not set, it defaults to a link pointing to {@link CWebApplication::homeUrl} with label 'Home'.
     * If this property is false, the home link will not be rendered.
     */
    public $homeLink;

    /**
     * @var array list of hyperlinks to appear in the breadcrumbs. If this property is empty,
     * the widget will not render anything. Each key-value pair in the array
     * will be used to generate a hyperlink by calling CHtml::link(key, value). For this reason, the key
     * refers to the label of the link while the value can be a string or an array (used to
     * create a URL). For more details, please refer to {@link CHtml::link}.
     * If an element's key is an integer, it means the element will be rendered as a label only (meaning the current page).
     *
     * The following example will generate breadcrumbs as "Home > Sample post > Edit", where "Home" points to the homepage,
     * "Sample post" points to the "index.php?r=post/view&id=12" page, and "Edit" is a label. Note that the "Home" link
     * is specified via {@link homeLink} separately.
     *
     * <pre>
     * array(
     *     'Sample post'=>array('post/view', 'id'=>12),
     *     'Edit',
     * )
     * </pre>
     */
    public $links = array();

    /**
     * @var string String, specifies how each active item is rendered. Defaults to
     * "<a href="{url}">{label}</a>", where "{label}" will be replaced by the corresponding item
     * label while "{url}" will be replaced by the URL of the item.
     * @since 1.1.11
     */
    public $activeLinkTemplate = '<a href="{url}">{label}</a>';

    /**
     * @var string Строка, определяет, как каждый неактивным визуализируется элемент. По умолчанию
     * "<span>{label}</span>", where "{label}" will be replaced by the corresponding item label.
     * Note that inactive template does not have "{url}" parameter.
     * @since 1.1.11
     */
    public $inactiveLinkTemplate = '<span>{label}</span>';

    /**
     * @var string разделитель между ссылками в хлебных крошках. По умолчанию ' &raquo; '.
     */
    public $separator = ' &raquo; ';

    /**
     * Renders the content of the portlet.
     */
    public function run() {
        //if (file_exists(Yii::getPathOfAlias('webroot') . '' . Yii::app()->theme->baseUrl . '/assets/images/bc_defis.png')) {
       //     $this->separator = '<img src="' . Yii::app()->controller->assetsUrl . '/images/bc_defis.png" alt="" />';
       // } else {
       // if($this->separator)
          //  $this->separator = '&nbsp;'.Yii::app()->settings->get('core', 'bc_defis').' ';
        //}

        if (empty($this->links))
            return;

        echo Html::openTag($this->tagName, $this->htmlOptions) . "\n";
        $links = array();
        if ($this->homeLink === null)
            $links[] = Html::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
        elseif ($this->homeLink !== false)
            $links[] = $this->homeLink;
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url))
                $links[] = strtr($this->activeLinkTemplate, array(
                    '{url}' => Html::normalizeUrl($url),
                    '{label}' => $this->encodeLabel ? Html::encode($label) : $label,
                        ));
            else
                $links[] = str_replace('{label}', $this->encodeLabel ? Html::encode($url) : $url, $this->inactiveLinkTemplate);
        }
        echo implode($this->separator, $links);
        echo Html::closeTag($this->tagName);
    }

}