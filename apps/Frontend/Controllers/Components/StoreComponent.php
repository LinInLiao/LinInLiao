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


    public static function getStores() {
        $stores = Stores::getStores();
        return $stores->toArray();
    }

    public function getStoreDrinks($store_id) {
        $storeCategories = StoreCategories::getStoreCategories($store_id);
        $storeColdHeats = StoreColdHeats::getStoreColdHeats($store_id);
        if ($storeColdHeats !== false && $storeCategories !== false) {
            return $this->drinksByColdHeat($storeColdHeats, $storeCategories);
        }else {
            $drinks = Drinks::getDrinks($store_id);
            return $drinks;
        }
    }

    private function drinksByColdHeat($storeColdHeats, $storeCategories) {
        $coldheat_drinks = array();

        foreach ($storeColdHeats as $coldheat) {
            $categories = array();

            foreach ($storeCategories as $category) {
                $category->drinks = $this->getDrinksByColdHeatAndCategory($coldheat->id, $category->id);
                array_push($categories , (array) $category);
            }
            $coldheats = array(
                'name' => $coldheat->name,
                'coldheat_id' => $coldheat->id,
                'categories' => $categories,
            );

            array_push($coldheat_drinks, $coldheats);
        }

        return $coldheat_drinks;
    }


    private function getDrinksByColdHeatAndCategory($coldheat_id, $category_id) {
        $drinks = array();
        $builder = $this->modelsManager->createBuilder();
        $builder->columns(array('d.id as drink_id', 'd.name as drink_name'));
        $builder->addFrom('Lininliao\Models\Drink\DrinkCategories', 'dc');
        $builder->innerJoin('Lininliao\Models\Drinks', 'd.id = dc.drink_id', 'd');
        $builder->innerJoin('Lininliao\Models\Drink\DrinkColdheats', 'd.id = dch.drink_id', 'dch');
        $builder->andWhere('d.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('dc.store_category_id = :store_category_id:', array('store_category_id' => $category_id), array('store_category_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('dch.store_coldheat_id = :store_coldheat_id:', array('store_coldheat_id' => $coldheat_id), array('store_coldheat_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $results = $builder->getQuery()->execute();
        foreach ($results as $_drink) {
           array_push($drinks, (array) $_drink);
        }
        return $drinks;
    }



}
