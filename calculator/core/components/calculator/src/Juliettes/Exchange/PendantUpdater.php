<?php
namespace Juliettes\Exchange;

class PendantUpdater extends AbstractUpdater {

    function updateItem($key) {
        $src = $this->src[$key];
        $resource = $this->modx->getObject('modResource', (int) $key);
        if (!$resource) return;
        $c = count($this->colsTitles);
        
        $newPageTitle = trim($src[$c - 2]);
        if ($resource->get('pagetitle') != $newPageTitle) {
            $resource->set('pagetitle', $newPageTitle);
            $resource->save();
        }

        $newBasePrice = trim($src[$c - 1]);
        if ($resource->getTVValue('price') != $newBasePrice) {
            $resource->setTVValue('price', $newBasePrice);
        }

        $price = [];
        foreach(array_keys($this->migxColsTitles) as $key => $val) {
            $price[$val] = trim($src[$c + (int)$key]);
        }
        $tvValue = $this->modx->fromJSON($resource->getTVValue('stone_types_list'));
        $needUpdate = false;
        foreach($tvValue as &$item) {
            $key = $item['stonetype'];
            if (array_key_exists($key, $price) && $item['price'] != $price[$key]) {
                $item['price'] = $price[$key];
                $needUpdate = true;
            }            
        }
        if ($needUpdate) {            
            $resource->setTVValue('stone_types_list', $this->modx->toJSON($tvValue));            
        }
    }
}