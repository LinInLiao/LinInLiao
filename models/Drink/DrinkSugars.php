<?php

namespace Lininliao\Models\Drink;


class DrinkSugars extends \Phalcon\Mvc\Model {
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
    public $store_sugar_id;

    /**
     *
     * @var string
     */
    public $status;

    public function add($data){
        $this->drink_id = $data['drink_id'];
        $this->drink_coldheat_id = $data['drink_coldheat_id'];
        $this->store_sugar_id = $data['store_sugar_id'];
        $this->status = 'active';
        if (false === $this->save()) {
            return false;
        }
        return true;
    }

    public function initialize() {
        $this->setSource('drink_sugars');
        $this->useDynamicUpdate(true);
    }

    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'drink_id' => 'drink_id',
            'drink_coldheat_id' => 'drink_coldheat_id',
            'store_sugar_id' => 'store_sugar_id',
            'status' => 'status',
        );
    }

}
