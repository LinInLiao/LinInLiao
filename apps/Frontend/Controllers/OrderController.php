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

    public function addDrinkAction() {
        // Check if request has made with POST
        $this->view->disable();
        if ($this->request->isPost()) {
            $order_component = new OrderComponent();
            $post_data = $this->request->getPost();
            $order_data = array(
                'order_id' => $post_data['order_id'],
                'drink_id' => $post_data['drink_id'],
                'store_coldheat_id' => $post_data['store_coldheat_id'],
                'store_coldheat_level_id' => $post_data['drink_ice'],
                'store_sugar_id' => $post_data['drink_sugar'],
                'drink_size' => $post_data['drink_size'],
                'uid' => isset($post_data['uid']) ? $post_data['uid'] : 0,
                'username' => isset($post_data['username']) ? $post_data['username'] : null,
                'amount' => $post_data['drink_amount'],
                'notes' => isset($post_data['notes']) ? $post_data['notes'] : null,
                'drink_extras' => isset($post_data['drink_extras']) ? $post_data['drink_extras'] : null,
            );
            $addDrink = $order_component->addOrderDrinks($order_data);
            if ($addDrink !== false) {
                $data = array(
                    'status' => 'success',
                );
                BaseControllerPlugin::responseJson($data);
            }else {
                $data = array(
                    'status' => 'failed',
                );
                BaseControllerPlugin::responseJson($data);
            }
        }
    }

    public function orderOverviewAction() {

    }
}

