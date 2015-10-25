<?php

namespace Lininliao\Frontend\Controllers\Components;

use Lininliao\Plugins\UUID,
    Lininliao\Models\Orders,
    Lininliao\Models\Order\OrderDrinks,
    Lininliao\Models\Order\OrderDrinksExtras;


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

    public function addOrderDrinks($drink_data) {
        $order_drink = new OrderDrinks();
        $order_drink_id = UUID::v4();
        $data = array(
            'id' => $order_drink_id,
            'order_id' => $drink_data['order_id'],
            'drink_id' => $drink_data['drink_id'],
            'store_coldheat_id' => $drink_data['store_coldheat_id'],
            'store_coldheat_level_id' => $drink_data['store_coldheat_level_id'],
            'store_sugar_id' => $drink_data['store_sugar_id'],
            'drink_size' => $drink_data['drink_size'],
            'uid' => isset($drink_data['uid']) ? $drink_data['uid'] : 0,
            'username' => isset($drink_data['username']) ? $drink_data['username'] : null,
            'amount' => $drink_data['amount'],
            'notes' => isset($drink_data['notes']) ? $drink_data['notes'] : null,
        );
        $transaction = $this->transactionManager->get();
        if (isset($drink_data['drink_extras']) && is_array($drink_data['drink_extras']) && count($drink_data['drink_extras']) > 0) {
            foreach ($drink_data['drink_extras'] as $extra_id) {
            $orderDrinksExtras = new OrderDrinksExtras();
                $extra_data = array(
                    'order_drinks_id' => $order_drink_id,
                    'store_extra_id' => $extra_id
                );
                if (false === $orderDrinksExtras->saveOrderDrinkExtras($extra_data)) {
                    $transaction->rollback();
                    return false;
                }
            }
        }
        if (false === $order_drink->saveOrderDrink($data)) {
            $transaction->rollback();
            return false;
        }

        $transaction->commit();
        return true;

    }

    public function getOrderDrinks($order_id) {
        $drink_list = OrderDrinks::getOrderDrinks($order_id);
        var_dump($drink_list);
        exit;
    }

}
