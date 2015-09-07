<?php

namespace Lininliao\Models;


class Orders extends \Phalcon\Mvc\Model {
    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $created;

    public function initialize() {
        $this->setSource('orders');
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
        /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'id' => 'id',
            'title' => 'title',
            'created' => 'created',
        );
    }

}
