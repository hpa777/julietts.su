<?php
namespace Juliettes\Exchange;

abstract class AbstractExporter {
    protected $modx;
    private $pdo;    
    protected $result;
    public function __construct(&$modx) {
        $this->modx = $modx;
        $this->pdo = $modx->getService('pdoFetch');        
    }
    protected static function titleImplode($separator, ...$parts) {        
        $a = [];
        foreach ($parts as $p) {
            if (!empty($p)) {
                $a[] = $p;
            }
        }
        return implode($separator, $a);
    }
    abstract function createRow($categories, $el) :array;
    abstract function createTitleRow() :array;
    private function procTree($el, $title) {
        if (!isset($el['children'])) {
            $this->result[] = $this->createRow($title, $el);
            return;
        }    
        foreach($el['children'] as $child) {            
            $this->procTree($child, self::titleImplode(' - ', $title, $el['pagetitle']));
        } 
    }    
    public function create($parent, $tvs, $depth = 2, $title = '') {
        $resources = $this->modx->runSnippet('pdoResources', [
            'parents' => $parent,
            'depth' => $depth,
            'includeTVs' => $tvs,
            'tvPrefix' => '',
            'return' => 'data',
            'limit' => 0
        ]);        
        $tree = $this->pdo->buildTree($resources);
        $this->result = [];
        $this->procTree(reset( $tree), $title);
        return array_merge([$this->createTitleRow()], $this->result);
    }    
}