<?php
namespace Lininliao\Frontend\Controllers\Components;

use Lininliao\Plugins\UUID,
    Lininliao\Models\Stores,
    Lininliao\Models\Store\StoreCategories,
    Lininliao\Models\Store\StoreSugars,
    Lininliao\Models\Store\StoreColdHeats,
    Lininliao\Models\Store\StoreColdHeatsLevels,
    Lininliao\Models\Store\StoreExtras,
    Lininliao\Models\Drinks,
    Lininliao\Models\Drink\DrinkCategories,
    Lininliao\Models\Drink\DrinkColdheats,
    Lininliao\Models\Drink\DrinkColdheatsLevels,
    Lininliao\Models\Drink\DrinkExtras,
    Lininliao\Models\Drink\DrinkSizes,
    Lininliao\Models\Drink\DrinkSugars;


final class StoreComponent extends \Phalcon\DI\Injectable {

    public static function getStoreDrinksTree($store_id) {
        $drinks = Drinks::getDrinks($store_id);
        var_dump($drinks);
        exit;
    }
}
