<?php

namespace Lininliao\Frontend\Controllers;

use Lininliao\Frontend\Controllers\BaseController,
    Lininliao\Frontend\Controllers\Components\OrderComponent,
    Lininliao\Frontend\Controllers\Components\StoreComponent,
    Lininliao\Plugins\BaseControllerPlugin;


class OrderController extends BaseController
{
    public function indexAction() {
        $order_id = $this->getParams('order_id');
    }

    public function orderDrinkAction() {
        $order_id = $this->getParams('order_id');
    }

    public function hookOrderAction() {
        $store_id = $this->getParams('store_id');
        $store_component = new StoreComponent();
        $order_component = new OrderComponent();
        $order_data = array(
            'store_id' => $store_id,
            'title' => 'test order title',
            'uid' => 0,
            'deadline' => date('Y-m-d H:i:s', strtotime("+1 day")),
        );
        if (false !== ($order_id = $order_component->hookOrder($order_data))) {
            $this->response->redirect('/order/'. $order_id);
        }
    }
}

