<?php

class BootstrapBreadcrumbs extends CWidget {

    /**
     * @var string Ммя тега для крошки контейнера тега. По умолчанию 'div'.
     */
    public $tagName = 'ul';

    /**
     * @var array HTML атрибуты для крошки тега контейнера.
     */
    public $htmlOptions = array('class' => 'breadcrumb');

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
    public $activeLinkTemplate = '<li><a href="{url}">{label}</a></li>';

    /**
     * @var string Строка, определяет, как каждый неактивным визуализируется элемент. По умолчанию
     * "<span>{label}</span>", where "{label}" will be replaced by the corresponding item label.
     * Note that inactive template does not have "{url}" parameter.
     * @since 1.1.11
     */
    public $inactiveLinkTemplate = '<li class="active">{label}</li>';


    /**
     * Renders the content of the portlet.
     */
    public function run() {


        if (empty($this->links))
            return;

        echo Html::openTag($this->tagName, $this->htmlOptions) . "\n";
        $content = '';
        if ($this->homeLink === null) {
            $content .= Html::tag('li', array(), Html::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl), true);
        } elseif ($this->homeLink !== false) {
            $content .= Html::tag('li', array(), $this->homeLink, true);
        }
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url))
                $content .= strtr($this->activeLinkTemplate, array(
                    '{url}' => Html::normalizeUrl($url),
                    '{label}' => $this->encodeLabel ? Html::encode($label) : $label,
                        ));
            else
                $content .= str_replace('{label}', $this->encodeLabel ? Html::encode($url) : $url, $this->inactiveLinkTemplate);
        }
        echo $content;
        echo Html::closeTag($this->tagName);
    }

}