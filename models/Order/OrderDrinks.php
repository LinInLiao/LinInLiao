<?php

namespace Lininliao\Models\Order;


class OrderDrinks extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $order_id;

    /**
     *
     * @var string
     */
    public $drink_id;

    /**
     *
     * @var string
     */
    public $store_coldheat_id;

    /**
     *
     * @var string
     */
    public $store_coldheat_level_id;

    /**
     *
     * @var string
     */
    public $store_sugar_id;

    /**
     *
     * @var string
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $username;


    /**
     *
     * @var string
     */
    public $amount;

    /**
     *
     * @var string
     */
    public $notes;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created;

    public static function getById($id) {
        $result = self::findFirst(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'id = :id:',
            'bind' => array('id' => $id),
            'bindTypes' => array(
                'id' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));

        return $result;
    }

    public function getOrderDrinks($order_id) {
        // $result = self::find(array(
        //     'columns' => array_keys(self::columnMap()),
        //     'conditions' => 'order_id = :order_id:',
        //     'bind' => array('order_id' => $order_id),
        //     'bindTypes' => array(
        //         'order_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
        //     ),
        //     'orderBy' => 'created DESC'
        // ));
        $phql = "SELECT od.id, od.amount,
            (SELECT d.name FROM \Lininliao\Models\Drinks d WHERE od.drink_id = d.id limit 1) as drink_name,
            (SELECT sc.name FROM \Lininliao\Models\Store\StoreColdheats sc WHERE od.store_coldheat_id = sc.id limit 1) as drink_coldheat ,
            (SELECT scl.name FROM \Lininliao\Models\Store\StoreColdheatsLevels scl WHERE od.store_coldheat_level_id = scl.id limit 1) as drink_coldheat_level,
            (SELECT ss.name FROM \Lininliao\Models\Store\StoreSugars ss WHERE od.store_sugar_id = ss.id limit 1) as drink_sugar,
            (SELECT CONCAT(ds.name,',',ds.price) FROM \Lininliao\Models\Drink\DrinkSizes ds WHERE od.drink_size = ds.id limit 1) as drink_size
            FROM \Lininliao\Models\Order\OrderDrinks od
            WHERE od.status = 'active' AND od.order_id = :order_id:
            ORDER BY od.created DESC";
        $order_drinks = $this->modelsManager->executeQuery($phql, array('order_id' => $order_id));
        return $order_drinks;

    }

    public function initialize() {
        $this->setSource('order_drinks');
        $this->useDynamicUpdate(true);
    }

    public function saveOrderDrink($drink_data) {
        $this->id = $drink_data['id'];
        $this->order_id = $drink_data['order_id'];
        $this->drink_id = $drink_data['drink_id'];
        $this->store_coldheat_id = $drink_data['store_coldheat_id'];
        $this->store_coldheat_level_id = $drink_data['store_coldheat_level_id'];
        $this->drink_size = $drink_data['drink_size'];
        $this->store_sugar_id = $drink_data['store_sugar_id'];
        $this->uid = $drink_data['uid'];
        $this->username = $drink_data['username'];
        $this->amount = $drink_data['amount'];
        $this->notes = $drink_data['notes'];
        $this->status = 'active';
        $this->created = date('Y-m-d H:i:s');
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
            'id' => 'id',
            'order_id' => 'order_id',
            'drink_id' => 'drink_id',
            'store_coldheat_id' => 'store_coldheat_id',
            'store_coldheat_level_id' => 'store_coldheat_level_id',
            'store_sugar_id' => 'store_sugar_id',
            'drink_size' => 'drink_size',
            'uid' => 'uid',
            'username' => 'username',
            'amount' => 'amount',
            'notes' => 'notes',
            'status' => 'status',
            'created' => 'created',
        );
    }

}
