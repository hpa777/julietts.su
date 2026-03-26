<?php
use Juliettes\Exchange\ChainUpdater;
use Juliettes\Exchange\PendantUpdater;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Juliettes\Exchange\ChainExporter;
use Juliettes\Exchange\PendantExporter;
class importCatalogProcessor extends modProcessor {

    public function checkPermissions() { 
        return $this->modx->user->isAuthenticated('mgr'); 
    }

    public function process() {
        if (isset($_FILES['spreadsheet_file']) && $_FILES['spreadsheet_file']['error'] === UPLOAD_ERR_OK) {
            $allowed_extensions = ['xlsx']; // Add other formats as needed
            $file_extension = pathinfo($_FILES['spreadsheet_file']['name'], PATHINFO_EXTENSION);

            if (in_array(strtolower($file_extension), $allowed_extensions)) {
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($_FILES['spreadsheet_file']['tmp_name']);
                
                $worksheet = $spreadsheet->getSheet(0);
                $loadedData = $worksheet->toArray();
                $pendantId = $this->modx->getOption('pendant_id');
                $pend = new PendantExporter($this->modx);
                $currentData = $pend->create($pendantId, 'price,stone_types_list');
                $pendUpdater = new PendantUpdater($this->modx, $loadedData, $currentData, PendantExporter::$colTitles, $pend->getStoneTypes());
                $c = $pendUpdater->update();

                $worksheet = $spreadsheet->getSheet(1);
                $loadedData = $worksheet->toArray();
                $chainId = $this->modx->getOption('chain_id');
                $chain = new ChainExporter($this->modx);
                $currentData = $chain->create($chainId,'lenghts');
                $chainUpdater = new ChainUpdater($this->modx, $loadedData, $currentData, ChainExporter::$colTitles, $chain->getLenghts());
                $c+=$chainUpdater->update();
                $msg = "Обработано {$c} стр.";
            } else {
                $msg = "Invalid file type.";
            }
        } else {
            $msg = "No file uploaded or an error occurred during upload.";
        }
        $_SESSION['jw_exchange_msg'] = $msg;
        $this->modx->sendRedirect('/manager');
    }
}

return 'importCatalogProcessor';