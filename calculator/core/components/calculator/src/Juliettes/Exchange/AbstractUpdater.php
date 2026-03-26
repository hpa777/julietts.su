<?php
namespace Juliettes\Exchange;

abstract class AbstractUpdater {
    protected $modx;    
    protected $src;
    protected $dist;
    protected $colsTitles;
    protected $migxColsTitles;
    public $count;    
    public function __construct(&$modx, $src, $dist, $colsTitles, $migxColsTitles) {
        $this->modx = $modx;
        $this->src = self::prepareArr($src);
        $this->dist = self::prepareArr($dist);
        $this->colsTitles = $colsTitles;
        $this->migxColsTitles = $migxColsTitles;
        $this->count = 0;
    }

    private static function prepareArr($arr) {
        $a = [];
        for ($i = 1; $i < count($arr); $i++) {
            $a[$arr[$i][0]] = $arr[$i];
        }
        return $a; 
    }

    abstract function updateItem($key);
    
    public function update() {
        $this->modx->beginTransaction();
        foreach($this->src as $key => $value) {            
            if (!array_key_exists($key, $this->dist) || empty(array_diff_assoc($this->dist[$key],$value))) continue;
            $this->updateItem($key);
            $this->count++;
        }
        $this->modx->commit();
        return $this->count;
    }
    
}