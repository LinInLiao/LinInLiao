<?php

namespace Lininliao\Frontend\Controllers;

use Lininliao\Frontend\Controllers\BaseController,
    Lininliao\Frontend\Controllers\Components\OrderComponent,
    Lininliao\Frontend\Controllers\Components\StoreComponent,
    Lininliao\Frontend\Controllers\Components\DrinkComponent,
    Lininliao\Plugins\BaseControllerPlugin,
    Lininliao\Models\Orders;



class ResourceController extends BaseController
{
    public function orderStoreAction() {
        $order_id = $this->getParams('order_id');
        $order = Orders::getById($order_id);
        $store_component = new StoreComponent();
        $menus = $store_component->getStoreDrinks($order->store_id);
        if ($menus !== false) {
            $data = array(
                'menus' => $menus,
                'status' => 'ok',
            );
        }
        BaseControllerPlugin::responseJson($data);
    }

    public function orderDrinkAction() {
        $order_id = $this->getParams('drink_id');
        $coldheat_id = $this->getParams('coldheat_id');
        $drink_component = new DrinkComponent();

    }

}

