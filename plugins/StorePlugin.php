<?php

namespace Lininliao\Plugins;

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




class StorePlugin extends \Phalcon\Mvc\User\Plugin {

    public function generateStore($store_arrays, $drinks, $header) {
        $transaction = $this->transactionManager->get();
        if (false === ($store = $this->addStore($store_arrays, $transaction))) {
             $transaction->rollback('stores');
        }
        if (false === $this->addDrinks($drinks, $store, $header, $transaction)) {
             $transaction->rollback('drinks');
        }
        $transaction->commit();
    }

    private function addDrinks($drinks, $store, $header, $transaction) {
        $header = array_flip($header);
        $drinks_saved = array();
        try {
            foreach ($drinks as $_item) {
                // drink -> drink_coldheats -> drink_coldheats_levels -> drink_sugars -> drink_sizes -> drink_extras -> drink_categories
                $drink = $_item[$header['drinks']];
                if (false == ($drink_id = array_search($drink, $drinks_saved))) {
                    if (false === ($drink_id = $this->addDrink($drink, $store['store_id']))){
                        $transaction->rollback('addDrink');
                        return false;
                    }
                    $drinks_saved[$drink_id] = $drink;
                }
                $coldheats = $_item[$header['coldheats']];
                if (false === ($coldheats_id = $this->addDrinkColdHeats($drink_id, $coldheats, $store['coldheats']))){
                    $transaction->rollback('addDrinkcoldheatsError');
                    return false;
                }
                $coldheats_levels = $_item[$header['coldheats_levels']];
                if (false === $this->addDrinkColdHeatsLevels($drink_id, $coldheats_id, $coldheats_levels, $store['coldheats_levels'])){
                    $transaction->rollback('addDrinkcoldheatsLevelsError');
                    return false;
                }
                $categories = $_item[$header['categories']];
                if (false === $this->addDrinkCategories($drink_id, $coldheats_id, $categories, $store['categories'])){
                    $transaction->rollback('addDrinkCategoriesError');
                    return false;
                }
                $sugars = $_item[$header['sugars']];
                if (false === $this->addDrinkSugars($drink_id, $coldheats_id, $sugars, $store['sugars'])){
                    $transaction->rollback('addDrinkSugarsError');
                    return false;
                }
                $extras = $_item[$header['extras']];
                if (false === $this->addDrinkExtras($drink_id, $coldheats_id, $extras, $store['extras'])){
                    $transaction->rollback('addDrinkExtraError');
                    return false;
                }
                $sizes = $_item[$header['sizes']];
                if (false === $this->addDrinkSizes($drink_id, $coldheats_id, $sizes)){
                    $transaction->rollback('addDrinkSizesError');
                    return false;
                }
            }
            return true;
        } catch (\Phalcon\Mvc\Model\Transaction\Failed $e) {
            var_dump($e->getMessage());
        } catch (\PDO\Exception $e) {
            var_dump($e->getMessage());
        }

    }

    private function addStore($store_arrays, $transaction) {
        $stores = new Stores();
        $store_data['id'] = UUID::v4();
        $store_data['alias'] = UUID::v4();
        $store_data['name'] = $store_arrays['store'][0];
        $store_id = $store_data['id'];
        try {
            if (false === $stores->addStore($store_data)) {
                $transaction->rollback();
            }
            foreach ($store_arrays as $key => $_data) {
                switch ($key) {
                    case 'categories':
                        if (false === ($categories = $this->saveStoreCategories($_data, $store_id,  $transaction))){
                            $transaction->rollback();
                        }
                        break;
                    case 'sugars':
                        if (false === ($sugars = $this->saveStoreSugars($_data, $store_id,  $transaction))){
                            $transaction->rollback();
                        }
                        break;
                    case 'coldheats':
                        if (false === ($coldheats = $this->saveStoreColdHeats($_data, $store_id,  $transaction))){
                            $transaction->rollback();
                        }
                        break;
                    case 'coldheats_levels':
                        if (false === ($coldheats_levels = $this->saveStoreColdHeatsLevels($_data, $store_id,  $transaction))){
                            $transaction->rollback();
                        }
                        break;
                    case 'extras':
                        if (false === ($extras = $this->saveStoreExtras($_data, $store_id,  $transaction))){
                            $transaction->rollback();
                        }
                        break;
                }
            }
            $return_store = array(
                'store_id' => $store_id,
                'categories' => $categories,
                'sugars' => $sugars,
                'coldheats' => $coldheats,
                'coldheats_levels' => $coldheats_levels,
                'extras' => $extras,
            );
            return $return_store;
        } catch (\Phalcon\Mvc\Model\Transaction\Failed $e) {
            var_dump($e->getMessage());
        } catch (\PDO\Exception $e) {
            var_dump($e->getMessage());
        }

    }

    private function saveStoreSugars(array $sugars, $store_id, $transaction) {
        $return_datas = array();
        array_walk($sugars, function($_item) use($store_id, &$return_datas, $transaction){
            $storeSugars = new StoreSugars();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeSugars->add($data)) {
                $transaction->rollback();
            }
            $return_datas[$_item] = $data['id'];
        });
        return $return_datas;
    }

    private function saveStoreColdHeats(array $coldheats, $store_id, $transaction) {
        $return_datas = array();
        array_walk($coldheats, function($_item) use($store_id, &$return_datas, $transaction){
            $storeColdHeats = new StoreColdHeats();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeColdHeats->add($data)) {
                $transaction->rollback();
            }
            $return_datas[$_item] = $data['id'];
        });
        return $return_datas;
    }

    private function saveStoreColdHeatsLevels(array $coldheatsLevels, $store_id, $transaction) {
        $return_datas = array();
        array_walk($coldheatsLevels, function($_item) use($store_id, &$return_datas, $transaction){
            $storeColdHeatsLevels = new StoreColdHeatsLevels();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeColdHeatsLevels->add($data)) {
                $transaction->rollback();
            }
            $return_datas[$_item] = $data['id'];
        });
        return $return_datas;
    }

    private function saveStoreCategories(array $categories, $store_id, $transaction) {
        $return_datas = array();
        array_walk($categories, function($_item) use($store_id, &$return_datas, $transaction){
            $storeCategories = new StoreCategories();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeCategories->add($data)) {
                $transaction->rollback();
            }
            $return_datas[$_item] = $data['id'];
        });
        return $return_datas;
    }

    private function saveStoreExtras(array $extras, $store_id, $transaction) {
        $return_datas = array();
        array_walk($extras, function($_item) use($store_id, &$return_datas, $transaction){
            $storeExtras = new StoreExtras();
            $_item = str_replace('：', ':', $_item);
            $explode_item = explode(':', $_item);

            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $explode_item[0],
                'price' => $explode_item[1],

            );
            if (false === $storeExtras->add($data)) {
                $transaction->rollback();
            }
            $return_datas[$_item] = $data['id'];
        });
        return $return_datas;
    }

    private function addDrink($drink_name, $store_id) {
        $drinks = new Drinks();
        $drink_id = UUID::v4();
        $data = array(
            'id' => $drink_id,
            'name' => $drink_name,
            'store_id' => $store_id,
        );
        if (false !== $drinks->addDrink($data)){
            return $drink_id;
        }else {
            return false;
        }
    }
    private function addDrinkColdHeats($drink_id, $drink_coldheats, $store_coldheats) {
        $drink_cold_heats = new DrinkColdHeats();
        $drink_coldheats_id = UUID::v4();
        $data = array(
            'id' => $drink_coldheats_id,
            'drink_id' => $drink_id,
            'store_coldheat_id' => $store_coldheats[$drink_coldheats],
        );
        if (false !== $drink_cold_heats->add($data)){
            return $drink_coldheats_id;
        }else {
            return false;
        }
    }

    private function addDrinkColdHeatsLevels($drink_id, $drink_coldheats_id, $drink_coldheatsLevels, $store_coldheats_levels) {
        $drink_cold_heats_levels = new DrinkColdheatsLevels();
        $drink_coldheatsLevels_array =  explode(',' , $drink_coldheatsLevels);
        foreach ($drink_coldheatsLevels_array as $_key => $_level) {
            $drink_coldheat_level_id = UUID::v4();
            $data = array(
                'drink_id' => $drink_id,
                'drink_coldheat_id' => $drink_coldheats_id,
                'store_coldheat_level_id' => $store_coldheats_levels[$_level],
            );
            if (false === $drink_cold_heats_levels->add($data)){
                return false;
            }
        }
        return true;
    }

    private function addDrinkSugars($drink_id, $drink_coldheats_id, $drink_sugars, $store_sugars) {
        $drink_sugars_model = new DrinkSugars();
        $drink_sugars_array =  explode(',' , $drink_sugars);
        foreach ($drink_sugars_array as $_key => $_level) {
            $data = array(
                'drink_id' => $drink_id,
                'drink_coldheat_id' => $drink_coldheats_id,
                'store_sugar_id' => $store_sugars[$_level],
            );
            if (false === $drink_sugars_model->add($data)){
                return false;
            }
        }
        return true;
    }

    private function addDrinkCategories($drink_id, $drink_coldheats_id, $drink_category, $store_categories) {
        $drink_categories = new DrinkCategories();
        $data = array(
            'drink_id' => $drink_id,
            'store_category_id' => $store_categories[$drink_category],
        );
        if (false === $drink_categories->add($data)){
            return false;
        }
    }

    private function addDrinkExtras($drink_id, $drink_coldheats_id, $drink_extras, $store_extras) {
        $drink_extras_model = new DrinkExtras();
        $drink_extras_array =  explode(',' , $drink_extras);
        foreach ($drink_extras_array as $_key => $_level) {
            $_level = str_replace('：', ':', $_level);
            $data = array(
                'drink_id' => $drink_id,
                'drink_coldheat_id' => $drink_coldheats_id,
                'store_extra_id' => $store_extras[$_level],
            );
            if (false === $drink_extras_model->add($data)){
                return false;
            }
        }
        return true;
    }

    private function addDrinkSizes($drink_id, $drink_coldheats_id, $drink_sizes) {
        $drink_sizes_model = new DrinkSizes();
        $drink_sizes_array = explode(',' , $drink_sizes);
        foreach ($drink_sizes_array as $_key => $_size) {
            $_size = str_replace('：', ':', $_size);
            $_size_explode = explode(':' , $_size);
            $drink_size_id = UUID::v4();
            $data = array(
                'id' => $drink_size_id,
                'drink_id' => $drink_id,
                'drink_coldheat_id' => $drink_coldheats_id,
                'name' => $_size_explode[0],
                'price' => (int) $_size_explode[1],
            );
            if (false === $drink_sizes_model->add($data)){
                return false;
            }
        }
        return true;
    }
}
