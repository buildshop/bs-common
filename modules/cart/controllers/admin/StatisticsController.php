<?php

class StatisticsController extends AdminController {

    /**
     * Display stats by count
     */
    public function actionIndex() {
        $this->pageName = Yii::t('CartModule.admin', 'STATS');
        $this->breadcrumbs = array($this->pageName);
        $data = array();
        $data_total = array();
        $request = Yii::app()->request;

        $year = (int) $request->getParam('year', date('Y'));
        $month = (int) $request->getParam('month', date('n'));

        $orders = $this->loadOrders($year, $month);
        $grouped = $this->groupOrdersByDay($orders);

        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); ++$i) {
            $count = 0;
            $totalPrice = 0;
            if (array_key_exists($i, $grouped)) {
                $count = sizeof($grouped[$i]);
                $totalPrice = $this->getTotalPrice($grouped[$i]);
            }

            //$data[] = array('day' => $i, 'value' => $count);
            $data[] = $count;
            $data_total[] = $totalPrice;
        }

        $this->render('index', array(
            'data' => $data,
            'data_total' => $data_total,
            'year' => $year,
            'month' => $month
        ));
    }

    /**
     * @param $year
     * @param $month
     * @return array
     */
    public function loadOrders($year, $month) {
        $month = (int) $month;

        if ($month < 10)
            $month = '0' . $month;

        $date_match = (int) $year . '-' . $month;

        $query = new CDbCriteria(array(
                    'condition' => "date_create LIKE '$date_match%'"
                ));

        return Order::model()->findAll($query);
    }

    public function groupOrdersByDay(array $orders) {
        $result = array();

        foreach ($orders as $order) {
            $day = date('j', strtotime($order->date_create));
            if (!isset($result[$day]))
                $result[$day] = array();

            $result[$day][] = $order;
        }

        return $result;
    }

    /**
     * @param array $orders
     * @return int
     */
    public function getTotalPrice(array $orders) {
        $result = 0;

        foreach ($orders as $o)
            $result += $o->getFull_price();

        return $result;
    }

    /**
     * @return array
     */
    public function getAvailableYears() {
        $result = array();
        $command = Yii::app()->db->createCommand('SELECT date_create FROM {{order}} ORDER BY date_create')->queryAll();

        foreach ($command as $row) {
            $year = date('Y', strtotime($row['date_create']));
            $result[$year] = $year;
        }

        return $result;
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                'url' => Yii::app()->createUrl('/admin/cart'),
                'icon' => 'icon-cart-3',
                'visible' => true
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => Yii::app()->createUrl('/admin/cart/statuses'),
                'icon' => 'icon-plus',
                'visible' => true
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'HISTORY'),
                'url' => Yii::app()->createUrl('/admin/cart/history'),
                'icon' => 'icon-checkmark',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => Yii::app()->createUrl('/admin/cart/settings'),
                'icon' => 'icon-settings',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
