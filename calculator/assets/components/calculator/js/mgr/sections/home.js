calculator.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'calculator-panel-home',
            renderTo: 'calculator-panel-home-div'
        }]
    });
    calculator.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(calculator.page.Home, MODx.Component);
Ext.reg('calculator-page-home', calculator.page.Home);