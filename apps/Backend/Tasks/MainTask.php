<?php

use Lininliao\Plugins\StorePlugin;

final class MainTask extends \Phalcon\CLI\Task {

    public function initialize() {
        set_time_limit(0);
    }
      /**
     * CLI Helper
     * @return null
     */
    public function mainAction() {
        echo "This is a CLI default action.\n";
    }

    public function importStoreAction($tsv_file) {
      var_dump('importStore');
      $filename = $tsv_file;
      $myfile = fopen(ROOT . DS . 'cache' . DS . 'store' . DS .$filename, "r") or die("Unable to open file!");
      if (!isset($tsv_file) || !$myfile) {
        exit;
      }

      $header_map = array(
        'store' => '店名',
        'categories' => '分類',
        'drinks' => '品名',
        'coldheats' => '冷或熱',
        'sugars' => '甜度',
        'coldheats_levels' => '冰塊或溫熱',
        'sizes' => '尺寸',
        'extras' => '加料',
      );
      $all_keys = array('store', 'categories', 'drinks', 'coldheats', 'sugars', 'coldheats_levels', 'sizes', 'extras');
      $unique_keys = array('store', 'categories', 'coldheats', 'sugars', 'coldheats_levels', 'extras');
      $drinks = array();

      $store_arrays = array(
        'store' => array(),
        'categories' => array(),
        'drinks' => array(),
        'coldheats' => array(),
        'sugars' => array(),
        'coldheats_levels' => array(),
        'sizes' => array(),
        'extras' => array(),
      );

      $count = 0;
      $index = array();

      while(!feof($myfile)) {
        $count++;
        $line = null;
        $line = $this->tsvToArray($myfile);
        $line = array_map('trim',$line);
        if ($count === 1) {
          $header_results = $line;
          $header = array();
          foreach ($header_map as $key => $_name) {
            $header[$key] = array_search($_name, $header_results);
          }
          $header = array_flip($header);
        }else{
          array_push($drinks, $line);
          array_walk($line, function($item, $key) use ($header, &$store_arrays ,$unique_keys) {
            $items = explode(',', $item);
            foreach ($items as $_item) {
              if (in_array($header[$key], $unique_keys)) {
                if (!in_array($_item, $store_arrays[$header[$key]])) {
                  array_push($store_arrays[$header[$key]], $_item);
                }
              }
            }
          });
        }
      }
      $store_plugin = new StorePlugin();
      $store_plugin->generateStore($store_arrays, $drinks, $header);
      fclose($myfile);
      var_dump('ImportStoreEnd');

    }

    private function tsvToArray($myfile) {
      $line = explode("\t", fgets($myfile));
      return $line;
    }


}
