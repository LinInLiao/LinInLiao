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

    public static function getDrinksCategories($drink_id) {
        $results = self::find(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'drink_id = :drink_id: AND status = :status:',
            'bind' => array('drink_id' => $drink_id , 'status' => $status),
            'bindTypes' => array(
                'status' => \Phalcon\Db\Column::BIND_PARAM_STR,
                'drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));
        if ($results->count() > 0) {
            return $results;
        }else {
            return false;
        }
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
