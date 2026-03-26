calculator.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'calculator-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('calculator') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('calculator_items'),
                layout: 'anchor',
                items: [{
                    html: _('calculator_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'calculator-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    calculator.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(calculator.panel.Home, MODx.Panel);
Ext.reg('calculator-panel-home', calculator.panel.Home);
