var calculator = function (config) {
    config = config || {};
    calculator.superclass.constructor.call(this, config);
};
Ext.extend(calculator, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('calculator', calculator);

calculator = new calculator();