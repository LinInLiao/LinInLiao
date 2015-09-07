<?php

namespace Lininliao\Frontend\Controllers;

use Lininliao\Frontend\Controllers\BaseController,
    Lininliao\Models\Orders;

class IndexController extends BaseController
{

    public function indexAction() {
      Orders::getById('testDb');
    }


    public function notFoundAction() {
      var_dump('notFound');
      exit;
    }

}

