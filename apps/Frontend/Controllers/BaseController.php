<?php

namespace Lininliao\Frontend\Controllers;

class BaseController extends \Phalcon\Mvc\Controller
{
    public function initialize() {
        if (false === strpos($_SERVER['HTTP_HOST'], ':8080')) {
            $this->view->setVar('liveload' , false);
        }else {
            $this->view->setVar('liveload' , true);
        }
        $this->view->setVar('title', 'Lin In Liao');
    }

    protected function getParams( $key = null, $default = null ) {
        if (is_null($key)) {
            return $this->dispatcher->getParams();
        } else {
            $params = $this->dispatcher->getParams();
            if (count($params) > 0 && isset($params[$key])) {
                return $params[$key];
            } else {
                return $default;
            }
        }
    }

}
