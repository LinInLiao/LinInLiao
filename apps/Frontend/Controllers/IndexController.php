<?php

namespace Lininliao\Frontend\Controllers;

use Lininliao\Frontend\Controllers\BaseController,
    Lininliao\Models\Stores,
    Lininliao\Frontend\Controllers\Components\StoreComponent,
    Lininliao\Plugins\BaseControllerPlugin;


class IndexController extends BaseController
{

    public function indexAction() {
      $stores = Stores::getStores();
      $this->view->setVar('Stores', $stores);
    }

    public function testMenuAction() {
    }
    public function menuAjaxAction() {
      $store_id = $this->getParams('store_id');
      $store_component = new StoreComponent();
      $menus = $store_component->getStoreDrinks($store_id);
      $data['menus'] = $menus;
      $data['status'] = 'ok';

      BaseControllerPlugin::responseJson($data);
    }
    public function notFoundAction() {
      var_dump('notFound');
      exit;
    }

}

