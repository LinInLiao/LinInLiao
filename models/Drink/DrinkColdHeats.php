<?php

namespace Lininliao\Models\Drink;


class DrinkColdHeats extends \Phalcon\Mvc\Model {
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
    public $store_coldheat_id;

    /**
     *
     * @var string
     */
    public $status;


    public function initialize() {
        $this->setSource('drink_coldheats');
        $this->useDynamicUpdate(true);
    }

    public function add($data){
        $this->id = $data['id'];
        $this->drink_id = $data['drink_id'];
        $this->store_coldheat_id = $data['store_coldheat_id'];
        $this->status = 'active';
        if (false === $this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'id' => 'id',
            'drink_id' => 'drink_id',
            'store_coldheat_id' => 'store_coldheat_id',
            'status' => 'status',
        );
    }

}
