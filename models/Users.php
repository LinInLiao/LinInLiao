<?php

namespace Lininliao\Models;


class Users extends \Phalcon\Mvc\Model {
    /**
     *
     * @var int
     */
    public $uid;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $pass;

    /**
     *
     * @var string
     */
    public $mail;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var string
     */
    public $access;

    /**
     *
     * @var string
     */
    public $login;

    /**
     *
     * @var string
     */
    public $status;

    public function initialize() {
        $this->setSource('users');
        $this->useDynamicUpdate(true);
    }

    /**
     * Independent Column Mapping.
     */
    public static function columnMap() {
        return array(
            'uid' => 'uid',
            'name' => 'name',
            'pass' => 'pass',
            'mail' => 'mail',
            'created' => 'created',
            'access' => 'access',
            'login' => 'login',
            'status' => 'status',
        );
    }

}
