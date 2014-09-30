OnestepcheckoutShipment = Class.create();
OnestepcheckoutShipment.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.switchMethodInputs = $$(config.switchMethodInputsSelector);
        this.saveShipmentUrl = config.saveShipmentUrl;

        this.init();
        this.initObservers();
    },

    init: function() {
        var me = this;
        this.switchMethodInputs.each(function(element) {
            var methodCode = element.value;
            if (element.checked) {
                me.currentMethod = methodCode;
            }
        });
    },

    initObservers: function() {
        var me = this;
        this.switchMethodInputs.each(function(element) {
            element.observe('click', function(e) {
                me.switchToMethod(element.value);
            });
        })
    },

    switchToMethod: function(methodCode) {
        if (this.currentMethod !== methodCode) {
            OnestepcheckoutCore.updater.startRequest(this.saveShipmentUrl, {
                method: 'post',
                parameters: Form.serialize(this.container, true)
            });
            this.currentMethod = methodCode;
        }
    }
};