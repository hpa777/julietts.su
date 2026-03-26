<?php
class JwExchengeWidget extends modDashboardWidgetInterface
{
    public function render()
    {
        $c = $this->modx->getChunk('tpl.calculator.widget',[
            'message' => $this->modx->getOption('jw_exchange_msg', $_SESSION)
        ]);
        unset($_SESSION['jw_exchange_msg']);
        return $c;
    }
}
return 'JwExchengeWidget';