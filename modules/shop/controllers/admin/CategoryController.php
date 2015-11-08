<?php

/**
 * Admin product category controller
 */
class CategoryController extends AdminController {

    public function actionRootJSON() {
        // print_r(ShopCategoryNode::fromArray(ShopCategory::model()->language(Yii::app()->language->active)->findAllByPk(1)));
        $test = ShopCategoryNode::fromArray(ShopCategory::model()->language(Yii::app()->language->active)->findAllByPk(1));
        echo CJSON::encode($test);
        die();
    }

    public function actionIndex() {

        $this->pageName = Yii::t('ShopModule.admin', 'CATEGORIES');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $this->actionUpdate(true);
    }

    public function actionUpdate($new = false) {
        if ($new === true)
            $model = new ShopCategory;
        else {
            $model = ShopCategory::model()
                    ->language(Yii::app()->language->active)
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CATEGORY'));
        $oldImage = $model->image;


        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopCategory'];

            if ($model->validate()) {
                $model->saveImage('image', 'webroot.uploads.categories', $oldImage);

                if (isset($_GET['parent_id'])) {
                    $parent = ShopCategory::model()
                            ->language(Yii::app()->language->active)
                            ->findByPk($_GET['parent_id']);
                } else {
                    $parent = ShopCategory::model()
                            ->language(Yii::app()->language->active)
                            ->findByPk(1);
                }
                if ($model->getIsNewRecord()) {
                    $model->appendTo($parent);
                    $this->redirect(array('create'));
                } else {
                    $model->saveNode();
                }
            }
        }
        $title = ($model->isNewRecord) ? Yii::t('ShopModule.admin', 'Создание категории') :
                Yii::t('ShopModule.admin', 'Редактирование категории');



        $this->pageName = $title;
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDeleteNode() {
        $node = ShopCategory::model()->findByPk($_GET['id'])->deleteNode();
    }

    /**
     * Drag-n-drop nodes
     */
    public function actionMoveNode() {
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $target = ShopCategory::model()->findByPk($_GET['ref']);

        if ((int) $_GET['position'] > 0) {
            $pos = (int) $_GET['position'];
            $childs = $target->children()->findAll();
            if (isset($childs[$pos - 1]) && $childs[$pos - 1] instanceof ShopCategory && $childs[$pos - 1]['id'] != $node->id)
                $node->moveAfter($childs[$pos - 1]);
        }
        else
            $node->moveAsFirst($target);

        $node->rebuildFullPath()->saveNode(false);
    }

    /**
     * Redirect to category front.
     */
    public function actionRedirect() {
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $this->redirect($node->getViewUrl());
    }

    public function actionSwitchNode() {
        $switch = $_GET['switch'];
        $node = ShopCategory::model()->findByPk($_GET['id']);
        $node->switch = ($switch == 1) ? 0 : 1;
        $node->saveNode();
        $message = ($node->switch) ? 'Категория успешно показана' : 'Категория успешно скрыта';
        echo CJSON::encode(array(
            'switch' => $node->switch,
            'message' => $message
        ));
    }

    /**
     * Create root category
     */
    public function actionCreateRoot() {
        $check = ShopCategory::model()->findByPk(1);
        if (!$check) {
            $model = new ShopCategory;
            $model->name = 'root';
            $model->seo_alias = 'root';
            if ($model->validate()) {
                $model->saveNode();
                Yii::app()->user->setFlash('success', 'Root категория успешно создана');
            } else {
                Yii::app()->user->setFlash('error', 'Ошибка создания root категории');
            }
            $this->redirect(array('create'));
        }
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopCategory::model()->findByPk($id);

            //Delete if not root node
            if ($model && $model->id != 1) {
                foreach (array_reverse($model->descendants()->findAll()) as $subCategory)
                    $subCategory->deleteNode();

                $model->deleteNode();
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('create');
        }
    }

}
