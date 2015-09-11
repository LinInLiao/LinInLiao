<?php

namespace Lininliao\Models\Store;


class StoreCategories extends \Phalcon\Mvc\Model {
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
     * @var string
     */
    public $status;



    public function initialize() {
        $this->setSource('store_categories');
        $this->useDynamicUpdate(true);
    }

    public function add($data){
        $this->id = $data['id'];
        $this->store_id = $data['store_id'];
        $this->name = $data['name'];
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
            'status' => 'status',
        );
    }

}
