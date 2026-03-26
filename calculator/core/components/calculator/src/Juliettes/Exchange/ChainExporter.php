<?
namespace Juliettes\Exchange;

class ChainExporter extends AbstractExporter {

    public static $colTitles = ["id", "категория", "наименование"];
    private $lenghts;

    public function getLenghts() {
        return array_keys($this->lenghts);
    }

    private function addLenght($lenght) {
        if (!$this->lenghts) {
            $this->lenghts = [];
        }
        $this->lenghts[$lenght] = 1;
    }

    private function updateResult() {
        $r = [];
        foreach($this->result as $row) {
            $newRow = array_slice($row, 0, count(self::$colTitles));            
            foreach($this->lenghts as $key => $v) {
                $newRow[] = $row[$key] ?? '';
            }
            $r[] = $newRow;
        }
        $this->result = $r;
    }

    function createTitleRow() :array {        
        ksort($this->lenghts);
        $this->updateResult();              
        return array_merge(self::$colTitles, array_map(function($el) { return $el . 'см';}, $this->getLenghts()));
    }

    function createRow($categories, $el):array {        
        $row = [$el['id'], $categories, $el['pagetitle']];        
        foreach($el['lenghts'] as $l) {
            $this->addLenght($l['length']);
            $row[$l['length']] = trim($l['price']);
        }        
        return $row;
    }
}