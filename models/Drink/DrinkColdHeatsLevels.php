<?php

namespace Lininliao\Models\Drink;


class DrinkColdHeatsLevels extends \Phalcon\Mvc\Model {

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
    public $store_coldheat_level_id;

    /**
     *
     * @var string
     */
    public $status;


    public function initialize() {
        $this->setSource('drink_coldheats_levels');
        $this->useDynamicUpdate(true);
    }


    public function add($data){
        $this->drink_id = $data['drink_id'];
        $this->drink_coldheat_id = $data['drink_coldheat_id'];
        $this->store_coldheat_level_id = $data['store_coldheat_level_id'];
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
            'drink_id' => 'drink_id',
            'drink_coldheat_id' => 'drink_coldheat_id',
            'store_coldheat_level_id' => 'store_coldheat_level_id',
            'status' => 'status',
        );
    }

}
