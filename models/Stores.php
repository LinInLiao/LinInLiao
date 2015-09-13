<?php

namespace Lininliao\Models;


class Stores extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $alias;

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
    public $status;

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
        $this->setSource('stores');
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

    public function addStore($data){
        $this->id = $data['id'];
        $this->alias = $data['alias'];
        $this->name = $data['name'];
        $this->cover = isset($data['cover']) ? $data['cover'] : 0;
        $this->status = 'active';
        $this->created = date('Y-m-d H:i:s');
        $this->modified = date('Y-m-d H:i:s');
        if (false === $this->save()) {
            return false;
        }
        return true;
    }

    public static function getStores($status = 'active') {
        $results = self::find(array(
            'columns' => array_keys(self::columnMap()),
            'conditions' => 'status = :status:',
            'bind' => array('status' => $status),
            'bindTypes' => array(
                'status' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));
        if ($results->count() > 0) {
            return $results;
        }else {
            return false;
        }
    }


    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'id' => 'id',
            'alias' => 'alias',
            'name' => 'name',
            'cover' => 'cover',
            'status' => 'status',
            'created' => 'created',
            'modified' => 'modified',
        );
    }

}
