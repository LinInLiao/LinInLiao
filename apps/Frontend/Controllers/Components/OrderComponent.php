<?php

namespace Lininliao\Frontend\Controllers\Components;

use Lininliao\Plugins\UUID,
    Lininliao\Models\Orders;

final class OrderComponent extends \Phalcon\DI\Injectable {

    public function hookOrder($order_data) {
        $orders = new Orders;
        $data = array(
            'id' => UUID::v4(),
            'store_id' => $order_data['store_id'],
            'uid' => $order_data['uid'],
            'title' => $order_data['title'],
            'notes' => isset($order_data['notes']) ? $order_data['notes'] : null,
            'deadline' => $order_data['deadline'],
        );
        return $orders->saveOrder($data);
    }

}
