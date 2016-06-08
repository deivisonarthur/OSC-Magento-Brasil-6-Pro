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

            if (element.checked) {
                me.switchToMethod(element.value);
            }

            element.observe('click', function(e) {
                me.switchToMethod(element.value);
            });
        })
    },

    switchToMethod: function(methodCode, forced) {
        if (this.currentMethod !== methodCode || forced) {
            OnestepcheckoutCore.updater.startRequest(this.saveShipmentUrl, {
                method: 'post',
                parameters: Form.serialize(this.container.id, true)
            });
            OSCShipment.currentMethod = this.currentMethod = methodCode;
        }
    }
};
