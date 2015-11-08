<div class="panel panel-default">
    <div class="panel-heading"><div class="panel-title">Каталог</div></div>
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-4">
                    <label class="control-label" for="tree-search"><?php echo Yii::t('ShopModule.admin', 'Поиск:') ?></label>
                </div>
                <div class="col-sm-8">
                    <input id="tree-search" class="form-control" placeholder="Поиск..." type="text" onkeyup='$("#ShopCategoryTree2").jstree("search", $(this).val());' />
                </div>
            </div>

            <div class="form-group clearfix">
                <div class="col-sm-12">
                    <?= Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', "Используйте 'drag-and-drop' для сортировки категорий."), false); ?>
                </div>
            </div>

            <div class="form-group">
                <?php
                Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/category.js', CClientScript::POS_END);



                /*

                  $this->widget('ext.jstree.JsTree', array(
                  'id' => 'ShopCategoryTree2',
                  'data' => ShopCategoryNode::fromArray(ShopCategory::model()->language(Yii::app()->language->active)->findAllByPk(1)),
                  'options' => array(
                  //   'data' => ShopCategoryNode::fromArray(ShopCategory::model()->language(Yii::app()->language->active)->findAllByPk(1)),
                  "themes" => array('stripes' => true),
                  'core' => array("check_callback" => true,),
                  "plugins" => array(
                  "contextmenu",
                  "dnd",
                  'crrm',
                  "search",
                  "ui",
                  "themes",
                  // "wholerow"
                  ),
                  'crrm' => array(
                  'move' => array('check_move' => 'js: function(m){
                  // Disallow categories without parent.
                  // At least each category must have `root` category as parent.
                  var p = this._get_parent(m.r);
                  if (p == -1) return false;
                  return true;
                  }')
                  ),
                  'cookies' => array(
                  'save_selected' => false,
                  ),
                  'ui' => array(
                  'initially_select' => array('#ShopCategoryTreeNode_' . (int) Yii::app()->request->getParam('id'))
                  ),
                  'dnd' => array(
                  'drag_finish' => 'js:function(data){
                  //alert(data);
                  }',
                  ),
                  // 'plugins' => array('themes', 'html_data', 'ui', 'dnd', 'crrm', 'search', 'cookies', 'contextmenu'),
                  ),
                  ));
                 */






                $this->widget('mod.shop.widgets.jstree.JsTree', array(
                    'id' => 'ShopCategoryTree',
                    'data' => ShopCategoryNode::fromArray(ShopCategory::model()->language(1)->findAllByPk(1), array('switch' => true)),
                    'options' => array(
                        "themes" => array("stripes" => true),
                        'core' => array('initially_open' => 'ShopCategoryTreeNode_1'),
                        'plugins' => array('themes', 'html_data', 'ui', 'dnd', 'crrm', 'search', 'cookies', 'contextmenu'),
                        'crrm' => array(
                            'move' => array('check_move' => 'js: function(m){
				// Disallow categories without parent.
				// At least each category must have `root` category as parent.
				var p = this._get_parent(m.r);
				if (p == -1) return false;
				return true;
			}')
                        ),
                        'dnd' => array(
                            'drag_finish' => 'js:function(data){
				//alert(data);
			}',
                        ),
                        'cookies' => array(
                            'save_selected' => false,
                        ),
                        'ui' => array(
                            'initially_select' => array('#ShopCategoryTreeNode_' . (int) Yii::app()->request->getParam('id'))
                        ),
                        'contextmenu' => array(
                            'items' => array(
                                'view' => array(
                                    'label' => Yii::t('ShopModule.admin', 'Перейти'),
                                    'action' => 'js:function(obj){ CategoryRedirectToFront(obj); }'
                                ),
                                'products' => array(
                                    'label' => Yii::t('ShopModule.admin', 'Продукты'),
                                    'action' => 'js:function(obj){ CategoryRedirectToAdminProducts(obj); }',
                                    'icon' => 'icon-cart-3'
                                ),
                                //'create'=>false,
                                'create' => array(
                                    'label' => Yii::t('app', 'CREATE', 1),
                                    'action' => 'js:function(obj){ CategoryRedirectToParent(obj); }',
                                    'icon' => 'icon-plus'
                                ),
                                'rename' => false,
                                'remove' => array(
                                    'label' => Yii::t('app', 'DELETE'),
                                    'icon' => 'icon-trashcan'
                                //'action'=>'js:function(obj){ CategoryRename(obj); }'
                                ),
                                'switch' => array(
                                    'label' => Yii::t('app', 'SWITCH'),
                                    'icon' => 'icon-eye'
                                //'action'=>'js:function(obj){ CategoryStatus(obj); }'
                                ),
                                'ccp' => false,
                            )
                        )
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>