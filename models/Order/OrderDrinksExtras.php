<?php

namespace Lininliao\Models\Order;


class OrderDrinksExtras extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $order_drinks_id;

    /**
     *
     * @var string
     */
    public $store_extra_id;


    public function getById($order_drinks_id) {
        // $result = self::find(array(
        //     'columns' => array_keys(self::columnMap()),
        //     'conditions' => 'order_drinks_id = :order_drinks_id:',
        //     'bind' => array('order_drinks_id' => $order_drinks_id),
        //     'bindTypes' => array(
        //         'order_drinks_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
        //     ),
        // ));
        $phql = "SELECT ode.order_drinks_id, se.name, se.price
            FROM \Lininliao\Models\Order\OrderDrinksExtras ode
            INNER JOIN \Lininliao\Models\Store\StoreExtras se ON ode.store_extra_id = se.id
            WHERE ode.order_drinks_id = :order_drinks_id:";
        $orderDrinksExtras = $this->modelsManager->executeQuery($phql, array('order_drinks_id' => $order_drinks_id));
        return $orderDrinksExtras;
    }

    public function initialize() {
        $this->setSource('order_drinks_extras');
        $this->useDynamicUpdate(true);
    }

    public function saveOrderDrinkExtras($extra_data) {
        $this->order_drinks_id = $extra_data['order_drinks_id'];
        $this->store_extra_id = $extra_data['store_extra_id'];

        if (false === $this->save()){
            return false;
        }else {
            return true ;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'order_drinks_id' => 'order_drinks_id',
            'store_extra_id' => 'store_extra_id',
        );
    }

}
