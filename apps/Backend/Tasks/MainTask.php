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

    public function importStoreAction() {
      var_dump('importStore');
      $filename = 'store.tsv';
      $myfile = fopen(ROOT . DS . 'cache' . DS . 'store' . DS .$filename, "r") or die("Unable to open file!");
      $all_keys = array('store', 'categories', 'item', 'coldheat', 'sugar', 'ice', 'size', 'extra');
      $unique_keys = array('store', 'categories', 'coldheat', 'sugar', 'ice', 'extra');
      $store_lines = array();

      $store_arrays = array(
        'store' => array(),
        'categories' => array(),
        'item' => array(),
        'coldheat' => array(),
        'sugar' => array(),
        'ice' => array(),
        'size' => array(),
        'extra' => array(),
      );
      // $header_index = array();

      $store = null;
      $categories = array();
      $item = array();
      $coldheat = array();
      $sugar = array();
      $ice = array();
      $size = array();
      $count = 0;
      $index = array();

      while(!feof($myfile)) {
        $count++;
        $line = null;
        $line = $this->tsvToArray($myfile);
        $line = array_map('trim',$line);
        array_push($store_lines, $line);
        if ($count === 1) {
          $header_results = $line;
          $header = array(
            'store' => array_search('店名', $header_results),
            'categories' => array_search('分類', $header_results),
            'item' => array_search('品名', $header_results),
            'coldheat' => array_search('冷或熱', $header_results),
            'sugar' => array_search('甜度', $header_results),
            'ice' => array_search('冰塊或溫熱', $header_results),
            'size' => array_search('尺寸', $header_results),
            'extra' => array_search('加料', $header_results),
          );
          $header = array_flip($header);
        }else{
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
      $store_plugin->generateStore($store_arrays, $store_lines);
      fclose($myfile);
    }

    private function tsvToArray($myfile) {
      $line = explode("\t", fgets($myfile));
      return $line;
    }


}
