<?php

namespace Lininliao\Models\Drink;


class DrinkCategories extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $drink_id;

    /**
     *
     * @var string
     */
    public $store_category_id;


    /**
     *
     * @var string
     */
    public $status;

    public function initialize() {
        $this->setSource('drink_categories');
        $this->useDynamicUpdate(true);
    }

    public function add($data){
        $this->drink_id = $data['drink_id'];
        $this->store_category_id = $data['store_category_id'];
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
            'store_category_id' => 'store_category_id',
            'status' => 'status',
        );
    }

}
