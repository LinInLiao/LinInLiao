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
