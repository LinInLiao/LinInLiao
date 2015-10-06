<?php

namespace Lininliao\Models\Drink;


class DrinkSizes extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $drink_id;

    /**
     *
     * @var string
     */
    public $drink_coldheat_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $price;

    /**
     *
     * @var string
     */
    public $status;

    public static function getDrinkSizes($drink_id, $drink_coldheat_id, $status = 'active') {
        $results = self::find(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'drink_id = :drink_id: AND drink_coldheat_id = :drink_coldheat_id: AND status = :status:',
            'bind' => array('drink_id' => $drink_id , 'drink_coldheat_id' => $drink_coldheat_id , 'status' => $status),
            'bindTypes' => array(
                'status' => \Phalcon\Db\Column::BIND_PARAM_STR,
                'drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
                'drink_coldheat_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));
        if ($results->count() > 0) {
            return $results;
        }else {
            return false;
        }
    }

    public function add($data){
        $this->id = $data['id'];
        $this->drink_id = $data['drink_id'];
        $this->drink_coldheat_id = $data['drink_coldheat_id'];
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->status = 'active';
        if (false === $this->save()) {
            var_dump($this->getMessages());
            return false;
        }
        return true;
    }


    public function initialize() {
        $this->setSource('drink_sizes');
        $this->useDynamicUpdate(true);
    }


    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'id' => 'id',
            'drink_id' => 'drink_id',
            'drink_coldheat_id' => 'drink_coldheat_id',
            'name' => 'name',
            'price' => 'price',
            'status' => 'status',
        );
    }

}
