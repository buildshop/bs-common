<?php

Yii::import('mod.shop.ShopModule');

class DefaultController extends Controller {

    /**
     * @var OrderCreateForm
     */
    public $form;

    /**
     * @var bool
     */
    protected $_errors = false;

    public function actionRecount() {
        //Yii::app()->cart->clear();
        Yii::app()->request->enableCsrfValidation = false;
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->request->isPostRequest && !empty($_POST['quantities'])) {
                $test = array();
                $test[Yii::app()->request->getPost('product_id')] = Yii::app()->request->getPost('quantities');
                Yii::app()->cart->ajaxRecount($test);
            }
        }
    }

    /**
     * Display list of product added to cart
     */
    public function actionIndex() {
        // Recount
        // ShopModule::registerAssets();
        $this->pageName = Yii::t('CartModule.default', 'MODULE_NAME');
        $this->pageTitle = $this->pageName;

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'),
            $this->pageName);
        if (Yii::app()->request->isPostRequest && Yii::app()->request->getPost('recount') && !empty($_POST['quantities'])) {

            $this->processRecount();
        }
        $this->form = new OrderCreateForm;

        // Make order
        if (Yii::app()->request->isPostRequest && Yii::app()->request->getPost('create')) {

            if (isset($_POST['OrderCreateForm'])) {
                $this->form->attributes = $_POST['OrderCreateForm'];
                if ($this->form->validate()) {
                    $this->form->registerGuest();
                    $order = $this->createOrder();
                    Yii::app()->cart->clear();
                    Yii::app()->user->setFlash('success', Yii::t('CartModule.default', 'SUCCESS_ORDER'));
                    Yii::app()->request->redirect($this->createUrl('view', array('secret_key' => $order->secret_key)));
                }
            }
        }

        $deliveryMethods = ShopDeliveryMethod::model()
                ->applyTranslateCriteria()
                ->active()
                ->orderByName()
                ->findAll();

        $paymenyMethods = ShopPaymentMethod::model()->findAll();

        $this->render('index', array(
            'items' => Yii::app()->cart->getDataWithModels(),
            'totalPrice' => Yii::app()->currency->convert(Yii::app()->cart->getTotalPrice()),
            'deliveryMethods' => $deliveryMethods,
            'paymenyMethods' => $paymenyMethods,
        ));
    }

    public function actionPayment() {
        if (isset($_POST)) {
            $this->form = ShopPaymentMethod::model()->cache($this->cacheTime)->findAll();
            $this->render('_payment', array('model' => $this->form));
        }
    }

    /**
     * Find order by secret_key and display.
     * @throws CHttpException
     */
    public function actionView() {

        $secret_key = Yii::app()->request->getParam('secret_key');
        $model = Order::model()->cache($this->cacheTime)->find('secret_key=:key', array(':key' => $secret_key));
        $this->pageName = Yii::t('CartModule.default', 'VIEW_ORDER', array('{id}' => $model->id));
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'BC_SHOP') => array('/shop'),
            Yii::t('CartModule.default', 'MODULE_NAME') => array('/cart'),
            $this->pageName);
        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.default', 'ERROR_ORDER_NO_FIND'));

        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Validate POST data and add product to cart
     */
    public function actionAdd() {
        $variants = array();

        // Load product model
        $model = ShopProduct::model()
                ->cache($this->cacheTime)
                ->active()
                ->findByPk(Yii::app()->request->getPost('product_id', 0));

        // Check product
        if (!isset($model))
            $this->_addError(Yii::t('CartModule.default', 'ERROR_PRODUCT_NO_FIND'), true);

        // Update counter
        $model->saveCounters(array('added_to_cart_count' => 1));

        // Process variants
        if (!empty($_POST['eav'])) {
            foreach ($_POST['eav'] as $attribute_id => $variant_id) {
                if (!empty($variant_id)) {
                    // Check if attribute/option exists
                    if (!$this->_checkVariantExists($_POST['product_id'], $attribute_id, $variant_id))
                        $this->_addError(Yii::t('CartModule.default', 'ERROR_VARIANT_NO_FIND'));
                    else
                        array_push($variants, $variant_id);
                }
            }
        }

        // Process configurable products
        if ($model->use_configurations) {
            // Get last configurable item
            $configurable_id = Yii::app()->request->getPost('configurable_id', 0);

            if (!$configurable_id || !in_array($configurable_id, $model->configurations))
                $this->_addError(Yii::t('CartModule.default', 'ERROR_SELECT_VARIANT'), true);
        }else
            $configurable_id = 0;


        Yii::app()->cart->add(array(
            'product_id' => $model->id,
            'variants' => $variants,
            'currency_id' => $model->currency_id,
            'supplier_id' => $model->supplier_id,
            'pcs' => $model->pcs,
            'configurable_id' => $configurable_id,
            'quantity' => (int) Yii::app()->request->getPost('quantity', 1),
            'price' => $model->price,
        ));

        $this->_finish($model->name);
    }

    /**
     * Remove product from cart and redirect
     */
    public function actionRemove($id) {
        Yii::app()->cart->remove($id);

        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->request->redirect($this->createUrl('index'));
    }

    /**
     * Clear cart
     */
    public function actionClear() {
        Yii::app()->cart->clear();

        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->request->redirect($this->createUrl('index'));
    }

    /**
     * Render data to display in theme header.
     */
    public function actionRenderSmallCart() {
        $this->widget('cart.widgets.cart.CartWidget', array(
            'skin' => 'currentTheme.views.layouts.partials.widgets.CartWidget'
        ));
    }

    /**
     * Create new order
     * @return Order
     */
    public function createOrder() {
        if (Yii::app()->cart->countItems() == 0)
            return false;
        Yii::import('mod.cart.models.Order');
        Yii::import('mod.cart.models.OrderProduct');
        $order = new Order;

        // Set main data
        $order->user_id = Yii::app()->user->isGuest ? null : Yii::app()->user->id;
        $order->user_name = $this->form->name;
        $order->user_email = $this->form->email;
        $order->user_phone = $this->form->phone;
        $order->user_address = $this->form->address;
        $order->user_comment = $this->form->comment;
        $order->delivery_id = $this->form->delivery_id;
        $order->payment_id = $this->form->payment_id;

        if ($order->validate()) {
            if ($order->save()) {

            }
        } else {
            throw new CHttpException(503, Yii::t('CartModule.default', 'ERROR_CREATE_ORDER'));
        }

        // Process products
        foreach (Yii::app()->cart->getDataWithModels() as $item) {
            $ordered_product = new OrderProduct;
            $ordered_product->order_id = $order->id;
            $ordered_product->product_id = $item['model']->id;
            $ordered_product->configurable_id = $item['configurable_id'];
            $ordered_product->currency_id = $item['model']->currency_id;
            $ordered_product->supplier_id = $item['model']->supplier_id;
            $ordered_product->name = $item['model']->name;
            $ordered_product->quantity = $item['quantity'];
            $ordered_product->sku = $item['model']->sku;
            $ordered_product->date_create = $order->date_create;
            // if($item['currency_id']){
            //     $currency = ShopCurrency::model()->findByPk($item['currency_id']);
            //$ordered_product->price = ShopProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']) * $currency->rate;
            // }else{
            $ordered_product->price = ShopProduct::calculatePrices($item['model'], $item['variant_models'], $item['configurable_id']);
            // }
            // Process configurable product
            if (isset($item['configurable_model']) && $item['configurable_model'] instanceof ShopProduct) {
                $configurable_data = array();

                $ordered_product->configurable_name = $item['configurable_model']->name;
                // Use configurable product sku
                $ordered_product->sku = $item['configurable_model']->sku;
                // Save configurable data

                $attributeModels = ShopAttribute::model()
                        ->cache($this->cacheTime)
                        ->findAllByPk($item['model']->configurable_attributes);
                foreach ($attributeModels as $attribute) {
                    $method = 'eav_' . $attribute->name;
                    $configurable_data[$attribute->title] = $item['configurable_model']->$method;
                }
                $ordered_product->configurable_data = serialize($configurable_data);
            }

            // Save selected variants as key/value array
            if (!empty($item['variant_models'])) {
                $variants = array();
                foreach ($item['variant_models'] as $variant)
                    $variants[$variant->attribute->title] = $variant->option->value;
                $ordered_product->variants = serialize($variants);
            }

            $ordered_product->save();
        }

        // Reload order data.
        $order->refresh(); //@todo panix text email tpl
        // All products added. Update delivery price.
        $order->updateDeliveryPrice();

        // Send email to user.
        $this->sendClientEmail($order);
        // Send email to admin.
        $this->sendAdminEmail($order);
                if (Yii::app()->hasModule('sms')) {
                   /* $this->widget('mod.sms.widgets.SMSWidget', array(
                        'key' => 'ADMIN_ORDER_BEFORE',
                        'model' => $order
                    ));*/
                    $this->widget('mod.sms.widgets.SMSWidget', array(
                        'key' => 'CLIENT_ORDER_BEFORE',
                        'model' => $order
                    ));
                }
        return $order;
    }

    /**
     * Check if product variantion exists
     * @param $product_id
     * @param $attribute_id
     * @param $variant_id
     * @return string
     */
    protected function _checkVariantExists($product_id, $attribute_id, $variant_id) {
        return ShopProductVariant::model()->cache($this->cacheTime)->countByAttributes(array(
                    'id' => $variant_id,
                    'product_id' => $product_id,
                    'attribute_id' => $attribute_id
                ));
    }

    /**
     * Recount product quantity and redirect
     */
    public function processRecount() {
        print_r(Yii::app()->request->getPost('quantities'));
        die;
        Yii::app()->cart->recount(Yii::app()->request->getPost('quantities'));

        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->request->redirect($this->createUrl('index'));
    }

    /**
     * Add message to errors array.
     * @param string $message
     * @param bool $fatal finish request
     */
    protected function _addError($message, $fatal = false) {
        if ($this->_errors === false)
            $this->_errors = array();

        array_push($this->_errors, $message);

        if ($fatal === true)
            $this->_finish();
    }

    /**
     * Process result and exit!
     */
    protected function _finish($product = null) {

        echo CJSON::encode(array(
            'errors' => $this->_errors,
            'message' => Yii::t('CartModule.default', 'SUCCESS_ADDCART', array(
                '{cart}' => Html::link(Yii::t('CartModule.default', 'CART'), array('/cart')),
                '{product_name}' => $product
            )),
        ));
        exit;
    }

    /**
     * Sends email to user after create new order.
     */
    private function sendClientEmail(Order $order) {
        $config = Yii::app()->settings->get('cart');
        $productList = '<ul>';
        foreach ($order->products as $product) {
            $productList .= '<li>' . $product->getRenderFullName() . '</li>';
        }
        $productList .= '</ul>';
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::app()->settings->get('core', 'site_name');
        $mailer->Subject = $this->replace($order, '', $config['tpl_subject_user']);
        $mailer->Body = $this->replace($order, $productList, $config['tpl_body_user']);
        $mailer->AddAddress($order->user_email);
        $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    private function getProductImage($p) {
        if (isset($p->mainImage)) {
            return Html::image($this->createAbsoluteUrl($p->mainImage->getUrl("200x200")), $p->name);
        } else {
            return 'пусто';
        }
    }

    private function sendAdminEmail(Order $order) {
        $thStyle = 'border-color:#D8D8D8; border-width:1px; border-style:solid;';
        $tdStyle = $thStyle;
        $currency = Yii::app()->currency->active->symbol;
        $configShop = Yii::app()->settings->get('cart');
        $config = Yii::app()->settings->get('core');
        $tables = '<table border="0" width="600px" cellspacing="1" cellpadding="5" style="border-spacing: 0;border-collapse: collapse;">'; //border-collapse:collapse;
        $tables .= '<tr>';
        if ($configShop['wholesale']) { // Продажа оптом
            $tables .= '<th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_IMG') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_NAME') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_WHOLESALE', (int) $configShop['wholesale']) . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_PCS') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_PRICE_FOR', (int) $configShop['wholesale']) . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_PRICE') . '</th>';
        } else { // Продажа розничная
            $tables .= '<th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_WHOLESALE', (int) $configShop['wholesale']) . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_NAME') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_IMG') . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_PRICE_FOR', (int) $configShop['wholesale']) . '</th>
            <th style="' . $thStyle . '">' . Yii::t('CartModule.default', 'TABLE_TH_MAIL_TOTALPRICE') . '</th>';
        }
        $tables .= '</tr>';
        if ($configShop['wholesale']) {
            foreach ($order->products as $row) { // Продажа оптом
                $tables .= '<tr>
            <td style="' . $tdStyle . '" align="center"><a href="' . $row->prd->absoluteUrl . '"  target="_blank">' . $this->getProductImage($row->prd) . '</a></td>
            <td style="' . $tdStyle . '"><a href="' . $row->prd->absoluteUrl . '"  target="_blank">' . $row->prd->name . '</a></td>
            <td style="' . $tdStyle . '" align="center">' . $row->quantity . '</td>
            <td style="' . $tdStyle . '" align="center">' . $row->prd->pcs . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->convert($row->prd->price) . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->convert($row->prd->price * $row->prd->pcs * $row->quantity) . ' ' . $currency . '</td>
            </tr>';
            }
        } else {
            foreach ($order->products as $row) { // Продажа розничная
                $tables .= '<tr>
            <td style="' . $tdStyle . '" align="center"><a href="' . $row->prd->absoluteUrl . '"  target="_blank">' . $this->getProductImage($row->prd) . '</a></td>
            <td style="' . $tdStyle . '"><a href="' . $row->prd->absoluteUrl . '"  target="_blank">' . $row->prd->name . '</a></td>
            <td style="' . $tdStyle . '" align="center">' . $row->quantity . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->convert($row->prd->price) . '</td>
            <td style="' . $tdStyle . '" align="center">' . Yii::app()->currency->convert($row->prd->price * $row->quantity) . ' ' . $currency . '</td>
            </tr>';
            }
        }

        $tables .= '</table>';

        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = $config['site_name'];
        $mailer->Subject = $this->replace($order, '', $configShop['tpl_subject_admin']);
        $mailer->Body = $this->replace($order, $tables, $configShop['tpl_body_admin']);

        foreach (explode(',', $configShop['order_emails']) as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . Yii::app()->request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    protected function replace($order, $list, $content) {
        $replace = array(
            '%ORDER_ID%',
            '%ORDER_KEY%',
            '%ORDER_DELIVERY_NAME%',
            '%ORDER_PAYMENT_NAME%',
            '%TOTAL_PRICE%',
            '%USER_NAME%',
            '%USER_PHONE%',
            '%USER_EMAIL%',
            '%USER_ADDRESS%',
            '%USER_COMMENT%',
            '%CURRENT_CURRENCY%',
            '%FOR_PAYMENY%',
            '%LIST%',
            '%LINK_TO_ORDER%',
        );
        $to = array(
            $order->id,
            $order->secret_key,
            $order->deliveryMethod->name,
            $order->paymentMethod->name,
            $order->total_price,
            $order->user_name,
            $order->user_phone,
            $order->user_email,
            $order->user_address,
            (isset($order->user_comment)) ? $order->user_comment : '',
            Yii::app()->currency->active->symbol,
            ShopProduct::formatPrice($order->total_price + $order->delivery_price),
            $list,
            Html::link($this->createAbsoluteUrl('view', array('secret_key' => $order->secret_key)), $this->createAbsoluteUrl('view', array('secret_key' => $order->secret_key)))
        );
        return CMS::textReplace($content, $replace, $to);
    }

}
