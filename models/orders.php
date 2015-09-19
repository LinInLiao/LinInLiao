<?php

namespace Lininliao\Models;


class Orders extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $store_id;

    /**
     *
     * @var string
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $notes;

    /**
     *
     * @var string
     */
    public $deadline;

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

    public function initialize() {
        $this->setSource('orders');
        $this->useDynamicUpdate(true);
    }

    public function saveOrder($order_data) {
        $this->id = $order_data['id'];
        $this->store_id = $order_data['store_id'];
        $this->uid = $order_data['uid'];
        $this->title = $order_data['title'];
        $this->notes = isset($order_data['notes']) ? $order_data['notes'] : null;
        $this->status = 'active';
        $this->deadline = $order_data['deadline'];
        $this->created = date('Y-m-d H:i:s');

        if (false === $this->save()){
            return false;
        }else {
            return $this->id ;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'id' => 'id',
            'store_id' => 'store_id',
            'uid' => 'uid',
            'title' => 'title',
            'notes' => 'notes',
            'status' => 'status',
            'deadline' => 'deadline',
            'created' => 'created',
        );
    }

}
