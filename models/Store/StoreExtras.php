<?php

namespace Lininliao\Models\Store;


class StoreExtras extends \Phalcon\Mvc\Model {
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
    public $name;

    /**
     *
     * @var numic
     */
    public $price;

    /**
     *
     * @var string
     */
    public $status;



    public function initialize() {
        $this->setSource('store_extras');
        $this->useDynamicUpdate(true);
    }

    public function add($data){
        $this->id = $data['id'];
        $this->store_id = $data['store_id'];
        $this->name = $data['name'];
        $this->price = (int) $data['price'];
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
            'store_id' => 'store_id',
            'name' => 'name',
            'price' => 'price',
            'status' => 'status',
        );
    }

}
