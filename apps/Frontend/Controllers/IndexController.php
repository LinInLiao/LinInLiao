<?php

namespace Lininliao\Frontend\Controllers;

use Lininliao\Frontend\Controllers\BaseController,
    Lininliao\Models\Stores,
    Lininliao\Frontend\Controllers\Components\StoreComponent;


class IndexController extends BaseController
{

    public function indexAction() {
      $stores = Stores::getStores();
      $this->view->setVar('Stores', $stores);
    }

    public function testMenuAction() {
      $store_id = $this->getParams('store_id');
      var_dump($store_id);
      StoreComponent::getStoreDrinksTree($store_id);
    }
    public function menuAjaxAction() {

    }
    public function notFoundAction() {
      var_dump('notFound');
      exit;
    }

}

