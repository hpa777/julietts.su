<?
namespace Juliettes\Exchange;

class PendantExporter extends AbstractExporter {

    public static $colTitles = ["id", "категория", "наименование", "базовая цена"];
    private $stoneTypes;   
    public function getStoneTypes() {
        if(!$this->stoneTypes) {
            $this->stoneTypes = [];
            foreach($this->modx->getCollection('modResource', [
                'parent' => $this->modx->getOption('stone_types_id'),
                'deleted' => 0
            ]) as $st) {
                $this->stoneTypes[$st->get('id')] = $st->get('pagetitle');
            }
        }        
        return $this->stoneTypes;
    }

    function createTitleRow() :array {
        return array_merge(self::$colTitles, array_values($this->getStoneTypes()));
    }

    function createRow($categories, $el):array {
        $row = [$el['id'], $categories, $el['pagetitle'], trim($el['price'])];
        if (empty($el['stone_types_list'])) return $row;       
        foreach (array_keys($this->getStoneTypes()) as $key) {
            $price = '';
            foreach($el['stone_types_list'] as $st) {
                if ($key == $st['stonetype']) {
                    $price = trim($st['price']);
                    break; 
                }
            }
            $row[] = $price;
        }
        return $row;
    }
}