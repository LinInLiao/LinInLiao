<?php

namespace Lininliao\Plugins;

use Lininliao\Plugins\UUID,
    Lininliao\Models\Stores,
    Lininliao\Models\Store\StoreCategories,
    Lininliao\Models\Store\StoreSugars,
    Lininliao\Models\Store\StoreColdHeats,
    Lininliao\Models\Store\StoreColdHeatsLevels,
    Lininliao\Models\Store\StoreExtras,
    Lininliao\Models\Drink\DrinkCategories,
    Lininliao\Models\Drink\DrinkColdheats,
    Lininliao\Models\Drink\DrinkExtras,
    Lininliao\Models\Drink\DrinkHeats,
    Lininliao\Models\Drink\DrinkIces,
    Lininliao\Models\Drink\DrinkItems,
    Lininliao\Models\Drink\DrinkSizes,
    Lininliao\Models\Drink\DrinkSugar;




class StorePlugin extends \Phalcon\Mvc\User\Plugin {
    public function generateStore($store_arrays, $store_datas) {
        $this->addStore($store_arrays);
        // var_dump($store_datas);

    }

    private function addStore($store_arrays) {
        $transaction = $this->transactionManager->get();
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
                        $this->saveStoreCategories($_data, $store_id,  $transaction);
                        break;
                    case 'sugar':
                        $this->saveStoreSugars($_data, $store_id,  $transaction);
                        break;
                    case 'coldheat':
                        $this->saveStoreColdHeats($_data, $store_id,  $transaction);
                        break;
                    case 'ice':
                        $this->saveStoreColdHeatsLevels($_data, $store_id,  $transaction);
                        break;
                    case 'extra':
                        $this->saveStoreExtras($_data, $store_id,  $transaction);
                        break;
                }
            }
            $transaction->commit();
        } catch (\Phalcon\Mvc\Model\Transaction\Failed $e) {
            var_dump($e->getMessage());
        } catch (\PDO\Exception $e) {
            var_dump($e->getMessage());
        }

    }

    private function saveStoreSugars(array $sugars, $store_id, $transaction) {
        array_walk($sugars, function($_item) use($store_id, $transaction){
            $storeSugars = new StoreSugars();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeSugars->add($data)) {
                var_dump($storeSugars->getMessages());
                $transaction->rollback('a');
                return false;
            }
        });
        return true;
    }

    private function saveStoreColdHeats(array $coldheats, $store_id, $transaction) {
        array_walk($coldheats, function($_item) use($store_id, $transaction){
            $storeColdHeats = new StoreColdHeats();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeColdHeats->add($data)) {
                $transaction->rollback('b');
                return false;
            }
        });
        return true;
    }

    private function saveStoreColdHeatsLevels(array $coldheatsLevels, $store_id, $transaction) {
        array_walk($coldheatsLevels, function($_item) use($store_id, $transaction){
            $storeColdHeatsLevels = new StoreColdHeatsLevels();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeColdHeatsLevels->add($data)) {
                var_dump($storeColdHeatsLevels->getMessages());
                $transaction->rollback('c');
                return false;
            }
        });
        return true;
    }

    private function saveStoreCategories(array $categories, $store_id, $transaction) {
        array_walk($categories, function($_item) use($store_id, $transaction){
            $storeCategories = new StoreCategories();
            $data = array(
                'id' => UUID::v4(),
                'store_id' => $store_id,
                'name' => $_item,
            );
            if (false === $storeCategories->add($data)) {
                $transaction->rollback('d');
                return false;
            }
        });
        return true;
    }

    private function saveStoreExtras(array $extras, $store_id, $transaction) {
        array_walk($extras, function($_item) use($store_id, $transaction){
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
                $transaction->rollback('e');
                return false;
            }
        });
        return true;
    }
}
