<?php

namespace Lininliao\Models;


class Drinks extends \Phalcon\Mvc\Model {
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
    public $cover;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var string
     */
    public $modified;

    public function initialize() {
        $this->setSource('drinks');
        $this->useDynamicUpdate(true);
    }

    public static function getById($id) {
        $result = self::findFirst(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'id = :id:',
            'bind' => array('id' => $id),
            'bindTypes' => array(
                'id' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));

        return $result;
    }

    public function addDrink($data){
        $this->id = $data['id'];
        $this->store_id = $data['store_id'];
        $this->name = $data['name'];
        $this->cover = isset($data['cover']) ? $data['cover'] : 0;
        $this->created = date('Y-m-d H:i:s');
        $this->modified = date('Y-m-d H:i:s');
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
            'cover' => 'cover',
            'created' => 'created',
            'modified' => 'modified',
        );
    }

}
