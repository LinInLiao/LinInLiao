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

    public static function getStoreCategories($store_id, $status = 'active') {
        $results = self::find(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'store_id = :store_id: AND status = :status:',
            'bind' => array('store_id' => $store_id , 'status' => $status),
            'bindTypes' => array(
                'status' => \Phalcon\Db\Column::BIND_PARAM_STR,
                'store_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
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
