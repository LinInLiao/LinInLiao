<?php

namespace Lininliao\Frontend\Controllers\Components;

use Lininliao\Plugins\UUID,
    Lininliao\Models\Drinks,
    Lininliao\Models\Drink\DrinkColdheats,
    Lininliao\Models\Drink\DrinkColdheatsLevels,
    Lininliao\Models\Drink\DrinkSizes,
    Lininliao\Models\Drink\DrinkSugars,
    Lininliao\Models\Drink\DrinkExtras;




final class DrinkComponent extends \Phalcon\DI\Injectable {

    public function getDrink($drink_id, $drink_coldheat_id) {
        $drink = Drinks::getById($drink_id);
        if ($drink !== false) {
            $store_id = $drink->store_id;
            $drink->coldheat_levels = $this->getDrinkColdheatLevels($drink_id, $drink_coldheat_id);
            $drink->sugars = $this->getDrinkSugars($drink_id, $drink_coldheat_id);
            $drink->sizes = $this->getDrinkSizes($drink_id, $drink_coldheat_id);
            $drink->extras = $this->getDrinkExtras($drink_id, $drink_coldheat_id);
            $drink->store_coldheat_id = $this->getDrinkColdheat($drink_id, $drink_coldheat_id);
            return (array) $drink;
        }else {
            return false;
        }
    }

    private function getDrinkColdheat($drink_id, $drink_coldheat_id) {
        $result = DrinkColdheats::findFirst(array(
            'columns' => array('store_coldheat_id'),
            'conditions' => 'id = :id: AND drink_id = :drink_id:',
            'bind' => array('id' => $drink_coldheat_id, 'drink_id' => $drink_id),
            'bindTypes' => array(
                'id' => \Phalcon\Db\Column::BIND_PARAM_STR,
                'drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR,
            ),
        ));
        return $result->store_coldheat_id;
    }

    private function getDrinkColdheatLevels($drink_id, $drink_coldheat_id) {
        $coldheatLevels = array();
        $builder = $this->modelsManager->createBuilder();
        $builder->columns(array('dcl.store_coldheat_level_id','scl.name'));
        $builder->addFrom('Lininliao\Models\Drink\DrinkColdheatsLevels', 'dcl');
        $builder->innerJoin('Lininliao\Models\Store\StoreColdheatsLevels', 'dcl.store_coldheat_level_id = scl.id', 'scl');
        $builder->andWhere('dcl.drink_id = :drink_id:', array('drink_id' => $drink_id), array('drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('dcl.drink_coldheat_id = :drink_coldheat_id:', array('drink_coldheat_id' => $drink_coldheat_id), array('drink_coldheat_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('dcl.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('scl.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $results = $builder->getQuery()->execute();
        foreach ($results as $_coldheat_level) {
           array_push($coldheatLevels, (array) $_coldheat_level);
        }
        return $coldheatLevels;
    }
    private function getDrinkSugars($drink_id, $drink_coldheat_id) {
        $sugars = array();
        $builder = $this->modelsManager->createBuilder();
        $builder->columns(array('ds.store_sugar_id','ss.name'));
        $builder->addFrom('Lininliao\Models\Drink\DrinkSugars', 'ds');
        $builder->innerJoin('Lininliao\Models\Store\StoreSugars', 'ds.store_sugar_id = ss.id', 'ss');
        $builder->andWhere('ds.drink_id = :drink_id:', array('drink_id' => $drink_id), array('drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('ds.drink_coldheat_id = :drink_coldheat_id:', array('drink_coldheat_id' => $drink_coldheat_id), array('drink_coldheat_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('ds.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('ss.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $results = $builder->getQuery()->execute();
        foreach ($results as $_sugar) {
           array_push($sugars, (array) $_sugar);
        }
        return $sugars;
    }

    private function getDrinkSizes($drink_id, $drink_coldheat_id) {
        $drink_sizes = DrinkSizes::getDrinkSizes($drink_id, $drink_coldheat_id);
        if ($drink_sizes !== false) {
            return $drink_sizes->toArray();
        }else {
            return false;
        }
    }
    private function getDrinkExtras($drink_id, $drink_coldheat_id) {
        $extras = array();
        $builder = $this->modelsManager->createBuilder();
        $builder->columns(array('de.store_extra_id','se.name', 'se.price'));
        $builder->addFrom('Lininliao\Models\Drink\DrinkExtras', 'de');
        $builder->innerJoin('Lininliao\Models\Store\StoreExtras', 'de.store_extra_id = se.id', 'se');
        $builder->andWhere('de.drink_id = :drink_id:', array('drink_id' => $drink_id), array('drink_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('de.drink_coldheat_id = :drink_coldheat_id:', array('drink_coldheat_id' => $drink_coldheat_id), array('drink_coldheat_id' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('de.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $builder->andWhere('se.status = :status:', array('status' => 'active'), array('status' => \Phalcon\Db\Column::BIND_PARAM_STR));
        $results = $builder->getQuery()->execute();
        foreach ($results as $_extra) {
           array_push($extras, (array) $_extra);
        }
        return $extras;
    }
}
