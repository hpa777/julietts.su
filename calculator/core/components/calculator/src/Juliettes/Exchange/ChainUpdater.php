<?php
namespace Juliettes\Exchange;

class ChainUpdater extends AbstractUpdater {  

    function updateItem($key) {
        $src = $this->src[$key];
        $resource = $this->modx->getObject('modResource', (int) $key);
        if (!$resource) return;
        $c = count($this->colsTitles);

        $newPageTitle = trim($src[$c - 1]);
        if ($resource->get('pagetitle') != $newPageTitle) {
            $resource->set('pagetitle', $newPageTitle);
            $resource->save();
        }       
        
        $price = [];
        foreach($this->migxColsTitles as $key => $val) {
            $price[$val] = trim($src[$c + (int)$key]);
        }        

        $tvValue = $this->modx->fromJSON($resource->getTVValue('lenghts'));
        $needUpdate = false;
        foreach($tvValue as &$item) {
            $key = $item['length'];
            if (array_key_exists($key, $price) && $item['price'] != $price[$key]) {
                $item['price'] = $price[$key];
                $needUpdate = true;
            }            
        }
        if ($needUpdate) {            
            $resource->setTVValue('lenghts', $this->modx->toJSON($tvValue));            
        }
    }
}