<?php

use Juliettes\Exchange\ChainExporter;
use Juliettes\Exchange\PendantExporter;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class exportCatalogProcessor extends modProcessor {

    public function process() {
        $mySpreadsheet = new Spreadsheet();
        $mySpreadsheet->removeSheetByIndex(0);

        $worksheetPendant = new Worksheet($mySpreadsheet, "Кулоны");
        $mySpreadsheet->addSheet($worksheetPendant, 0);
        $pendantId = $this->modx->getOption('pendant_id');
        $pend = new PendantExporter($this->modx);
        $pendData = $pend->create($pendantId, 'price,stone_types_list');
        $worksheetPendant->fromArray($pendData);

        $worksheetChain = new Worksheet($mySpreadsheet, "Цепи");
        $mySpreadsheet->addSheet($worksheetChain, 1);
        $chainId = $this->modx->getOption('chain_id');
        $chain = new ChainExporter($this->modx);
        $chainData = $chain->create($chainId,'lenghts');
        $worksheetChain->fromArray($chainData);

        $worksheets = [$worksheetPendant, $worksheetChain];

        foreach ($worksheets as $worksheet)
        {
            foreach ($worksheet->getColumnIterator() as $column)
            {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }
        $t = time();
        $filename = "juliettes_{$t}.xlsx"; // Desired filename for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Adjust for other formats
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($mySpreadsheet);
        $writer->save('php://output');
        exit;
    }
}

return 'exportCatalogProcessor';