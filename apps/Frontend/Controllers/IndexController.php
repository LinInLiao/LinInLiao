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


    public function notFoundAction() {
        var_dump('notFound');
        exit;
    }

}

