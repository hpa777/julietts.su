<?php

/**
 * The home manager controller for calculator.
 *
 */
class calculatorHomeManagerController extends modExtraManagerController
{
    /** @var calculator $calculator */
    public $calculator;


    /**
     *
     */
    public function initialize()
    {
        $this->calculator = $this->modx->getService('calculator', 'calculator', MODX_CORE_PATH . 'components/calculator/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['calculator:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('calculator');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->calculator->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/calculator.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->calculator->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        calculator.config = ' . json_encode($this->calculator->config) . ';
        calculator.config.connector_url = "' . $this->calculator->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "calculator-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="calculator-panel-home-div"></div>';

        return '';
    }
}