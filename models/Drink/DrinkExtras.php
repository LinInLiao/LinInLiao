<?php

namespace Lininliao\Models\Drink;


class DrinkExtras extends \Phalcon\Mvc\Model {
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
    public $store_extra_id;

    /**
     *
     * @var string
     */
    public $status;



    public function add($data){
        $this->drink_id = $data['drink_id'];
        $this->drink_coldheat_id = $data['drink_coldheat_id'];
        $this->store_extra_id = $data['store_extra_id'];
        $this->status = 'active';
        if (false === $this->save()) {
            return false;
        }
        return true;
    }



    public function initialize() {
        $this->setSource('drink_extras');
        $this->useDynamicUpdate(true);
    }


    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'drink_id' => 'drink_id',
            'drink_coldheat_id' => 'drink_coldheat_id',
            'store_extra_id' => 'store_extra_id',
            'status' => 'status',
        );
    }

}
