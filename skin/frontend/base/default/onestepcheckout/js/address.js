OnestepcheckoutAddress = Class.create();
OnestepcheckoutAddress.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.useBillingAsShippingCheckbox = $(config.useBillingAsShippingCheckboxId);

        this.billing = {};
        this.billing.container = $$(config.billing.containerSelector).first();
        this.billing.changeAddressSelect = $$(config.billing.changeAddressSelectSelector).first();
        this.billing.newAddressContainer = $$(config.billing.newAddressContainerSelector).first();
        this.billing.pleaseWaitNotice    = $$(config.billing.pleaseWaitPostcodSelector).first();
        
        this.billing.createAccountInput = $(config.billing.createAccountInputId);
        
        this.billing.passwordContainer = $$(config.billing.passwordContainerSelector).first();
        this.billing.addressChangeTriggerElementsIds = config.billing.addressChangeTriggerElementsIds;
        this.billing.postcodeInputElement = $(config.billing.postcodeInputElement);

        if (config.billing.addressCountryRegionElementsIds) {
            this.billing.countrySelectElement = $(config.billing.addressCountryRegionElementsIds.countryId);
            this.billing.regionInputElement = $(config.billing.addressCountryRegionElementsIds.region);
            this.billing.regionIdSelectElement = $(config.billing.addressCountryRegionElementsIds.regionId);
        }
        this.billing.addressCountryRegionData = {};

        this.shipping = {};
        this.shipping.container = $$(config.shipping.containerSelector).first();
        this.shipping.changeAddressSelect = $$(config.shipping.changeAddressSelectSelector).first();
        this.shipping.newAddressContainer = $$(config.shipping.newAddressContainerSelector).first();
        this.shipping.postcodeInputElement = $(config.shipping.postcodeInputElement);
        this.shipping.pleaseWaitNotice    = $$(config.billing.pleaseWaitPostcodSelector).last();
        this.shipping.addressChangeTriggerElementsIds = config.shipping.addressChangeTriggerElementsIds;

        if (config.shipping.addressCountryRegionElementsIds) {
            this.shipping.countrySelectElement = $(config.shipping.addressCountryRegionElementsIds.countryId);
            this.shipping.regionInputElement = $(config.shipping.addressCountryRegionElementsIds.region);
            this.shipping.regionIdSelectElement = $(config.shipping.addressCountryRegionElementsIds.regionId);
        }
        this.shipping.addressCountryRegionData = {};

        this.addressChangedUrl = config.addressChangedUrl;
        this.saveAddressUrl = config.saveAddressUrl;

        this.initMocks();
        this.initObservers();
        this.onBillingAddressSelectChanged();
        this.onShippingAddressSelectChanged();
        this.onUseBillingAsShippingChange();
    },

    initMocks: function() {
        //hack for compatibility with magento templates
        window.billing = {
            newAddress: Prototype.emptyFunction
        };
        window.shipping = {
            newAddress: Prototype.emptyFunction,
            setSameAsBilling: Prototype.emptyFunction
        };
    },

    initObservers: function() {
        if (this.useBillingAsShippingCheckbox) {
            this.useBillingAsShippingCheckbox.observe('click', this.onUseBillingAsShippingChange.bind(this));
        }
        if (this.billing.createAccountInput) {
            this.billing.createAccountInput.observe('click', this.onCreateAccountInputChange.bind(this));
        }

        var me = this;

        // on billing country/region changed observer
        if (this.billing.countrySelectElement) {
            this.billing.countrySelectElement.observe('change', me.onBillingCountrySelectChanged.bind(me));
        }
        if (this.billing.regionIdSelectElement) {
            this.billing.regionIdSelectElement.observe('change', me.onBillingRegionIdSelectChanged.bind(me));
        }
        if (this.billing.regionInputElement) {
            this.billing.regionInputElement.observe('change', me.onBillingRegionInputChanged.bind(me));
        }

        // on shipping country/region changed observer
        if (this.shipping.countrySelectElement) {
            this.shipping.countrySelectElement.observe('change', me.onShippingCountrySelectChanged.bind(me));
        }
        if (this.shipping.regionIdSelectElement) {
            this.shipping.regionIdSelectElement.observe('change', me.onShippingRegionIdSelectChanged.bind(me));
        }
        if (this.shipping.regionInputElement) {
            this.shipping.regionInputElement.observe('change', me.onShippingRegionInputChanged.bind(me));
        }

        //on addresses changed observers
        if (this.billing.addressChangeTriggerElementsIds) {
            this.billing.addressChangeTriggerElementsIds.each(function(elementId){
                var element = $(elementId);
                if (element) {
                    element.observe('change', me.onNewBillingAddressChanged.bind(me));
                }
            });
        }
        if (this.shipping.addressChangeTriggerElementsIds) {
            this.shipping.addressChangeTriggerElementsIds.each(function(elementId){
                var element = $(elementId);
                if (element) {
                    element.observe('change', me.onNewShippingAddressChanged.bind(me));
                }
            });
        }
        if (this.useBillingAsShippingCheckbox) {
            this.useBillingAsShippingCheckbox.observe('click', this.onAddressChanged.bind(this));
        }
        if (this.billing.postcodeInputElement) {
            if (this.billing.postcodeInputElement.value!='' && this.billing.postcodeInputElement.value.length == 8) {
                document.observe("dom:loaded", this.onAddressChanged.bind(this));
            }
            this.billing.postcodeInputElement.observe('blur', this.onAddressChanged.bind(this));
        }
        if (this.shipping.postcodeInputElement) {
            if (this.shipping.postcodeInputElement.value!='' && this.shipping.postcodeInputElement.value.length == 8) {
                document.observe("dom:loaded", this.onAddressChanged.bind(this));
            } 
            this.shipping.postcodeInputElement.observe('blur', this.onAddressChanged.bind(this));
        } 
        if (this.billing.changeAddressSelect) {
            this.billing.changeAddressSelect.observe('change', this.onAddressChanged.bind(this));
        }
        if (this.shipping.changeAddressSelect) {
            this.shipping.changeAddressSelect.observe('change', this.onAddressChanged.bind(this));
        }

        //on address select change
        if (this.billing.changeAddressSelect) {
            this.billing.changeAddressSelect.observe('change', this.onBillingAddressSelectChanged.bind(this))
        }
        if (this.shipping.changeAddressSelect) {
            this.shipping.changeAddressSelect.observe('change', this.onShippingAddressSelectChanged.bind(this))
        }

        //on address minor info changed
        Form.getElements(this.container).each(function(element){
            var elementId = element.getAttribute('id');
            if (element === me.useBillingAsShippingCheckbox) {
                return;
            }
            if (me.billing.addressChangeTriggerElementsIds.indexOf(elementId) !== -1) {
                return;
            }
            if (me.shipping.addressChangeTriggerElementsIds.indexOf(elementId) !== -1) {
                return;
            }
            element.observe('change', me.requestToValuesSave.bind(me));
        });
    },

    requestToValuesSave: function(e) {
        new Ajax.Request(this.saveAddressUrl, {
            method: 'post',
            parameters: Form.serialize(this.container, true)
        });
    },

    onUseBillingAsShippingChange: function(e) {
        if (this.useBillingAsShippingCheckbox) {
            if (this.useBillingAsShippingCheckbox.checked) {
                this.hideShippingAddressContainer();
            } else {
                this.showShippingAddressContainer();
            }
        }
    },

    onCreateAccountInputChange: function(e) {
        if (this.billing.createAccountInput.checked) {
            this.showPasswordContainer();
        } else {
            this.hidePasswordContainer();
        }
    },

    onNewBillingAddressChanged: function(e) {
        //check for full needed data entered
        var isValid = this.billing.addressChangeTriggerElementsIds.all(function(elementId){
            var element = $(elementId);
            if (element) {
                var cn = $w(element.className);
                return cn.all(function(name) {
                    var v = Validation.get(name);
                    try {
                        if(Validation.isVisible(element) && !v.test($F(element), element)) {
                            return false;
                        } else {
                            return true;
                        }
                    } catch(e) {
                        return true;
                    }
                });
            }
        }, this);
        if (isValid) {
            this.onAddressChanged(e);
        }
    },

    onNewShippingAddressChanged: function(e) {
        //check for full needed data entered
        var isValid = this.shipping.addressChangeTriggerElementsIds.all(function(elementId){
            var element = $(elementId);
            if (element) {
                var cn = $w(element.className);
                return cn.all(function(name) {
                    var v = Validation.get(name);
                    try {
                        if(Validation.isVisible(element) && !v.test($F(element), element)) {
                            return false;
                        } else {
                            return true;
                        }
                    } catch(e) {
                        return true;
                    }
                });
            }
        }, this);
        if (isValid) {
            this.onAddressChanged(e);
        }
    },

    onAddressChanged: function(e) {
        var params = {};
        if (this.billing.container) {
            params = Object.extend(params, Form.serialize(this.billing.container, true));
        }
        if (this.shipping.container) {
            params = Object.extend(params, Form.serialize(this.shipping.container, true));
        }
        var requestOptions = {
            method: 'post',
            parameters: params
        };
        OnestepcheckoutCore.updater.startRequest(this.addressChangedUrl, requestOptions);
    },

    onBillingAddressSelectChanged: function() {
        if (!this.billing.changeAddressSelect) {
            return;
        }
        var isNew = !this.billing.changeAddressSelect.value;
        if (isNew) {
            this.showNewAddressContainer(this.billing.newAddressContainer);
        } else {
            this.hideNewAddressContainer(this.billing.newAddressContainer);
        }
    },

    onShippingAddressSelectChanged: function() {
        if (!this.shipping.changeAddressSelect) {
            return;
        }
        var isNew = !this.shipping.changeAddressSelect.value;
        if (isNew) {
            this.showNewAddressContainer(this.shipping.newAddressContainer);
        } else {
            this.hideNewAddressContainer(this.shipping.newAddressContainer);
        }
    },

    onBillingCountrySelectChanged: function() {
        var regionData = this.billing.addressCountryRegionData[this.billing.countrySelectElement.getValue()];
        if (regionData) {
            switch (regionData.type) {
                case 'region_id':
                    this.billing.regionIdSelectElement.setValue(regionData.value);
                    break;
                case 'region':
                    this.billing.regionInputElement.setValue(regionData.value);
                    break;
            }
        }
    },

    onShippingCountrySelectChanged: function() {
        var regionData = this.shipping.addressCountryRegionData[this.shipping.countrySelectElement.getValue()];
        if (regionData) {
            switch (regionData.type) {
                case 'region_id':
                    this.shipping.regionIdSelectElement.setValue(regionData.value);
                    break;
                case 'region':
                    this.shipping.regionInputElement.setValue(regionData.value);
                    break;
            }
        }
    },

    onBillingRegionIdSelectChanged: function() {
        this.billing.addressCountryRegionData[this.billing.countrySelectElement.getValue()] = {
            'type'  : 'region_id',
            'value' : this.billing.regionIdSelectElement.getValue()
        }
    },

    onShippingRegionIdSelectChanged: function() {
        this.shipping.addressCountryRegionData[this.shipping.countrySelectElement.getValue()] = {
            'type'  : 'region_id',
            'value' : this.shipping.regionIdSelectElement.getValue()
        }
    },

    onBillingRegionInputChanged: function() {
        this.billing.addressCountryRegionData[this.billing.countrySelectElement.getValue()] = {
            'type'  : 'region',
            'value' : this.billing.regionInputElement.getValue()
        }
    },

    onShippingRegionInputChanged: function() {
        this.shipping.addressCountryRegionData[this.shipping.countrySelectElement.getValue()] = {
            'type'  : 'region',
            'value' : this.shipping.regionInputElement.getValue()
        }
    },

    /*
       ======================================================
       --------------------SHOW/HIDE functions---------------
       ======================================================
     */
    showShippingAddressContainer: function() {
        var me = this;
        this.shipping.container.setStyle({'display': ''});
        var newHeight = OnestepcheckoutCore.getElementHeight(this.shipping.container);
        this._applyEffect(this.shipping.container, newHeight, 0.3, function(){
            me.shipping.container.setStyle({'height': ''});
            OnestepcheckoutCore.updateNumbers();
        });
    },

    hideShippingAddressContainer: function() {
        var me = this;
        this._applyEffect(this.shipping.container, 0, 0.5, function(){
            me.shipping.container.setStyle({'display': 'none'});
            OnestepcheckoutCore.updateNumbers();
        });
    },
    
    showPasswordContainer: function() {
        var me = this;
        this.billing.passwordContainer.setStyle({'display': ''});
        var newHeight = OnestepcheckoutCore.getElementHeight(this.billing.passwordContainer);
        this._applyEffect(this.billing.passwordContainer, newHeight, 0.3, function(){
            me.billing.passwordContainer.setStyle({'height': ''});
        });
    },

    hidePasswordContainer: function() {
        var me = this;
        this._applyEffect(this.billing.passwordContainer, 0, 0.3, function(){
            me.billing.passwordContainer.setStyle({'display': 'none'});
        });
    },

    showNewAddressContainer: function(container) {
        container.setStyle({'display': ''});
        var newHeight = OnestepcheckoutCore.getElementHeight(container);
        this._applyEffect(container, newHeight, 0.5, function(){
            container.setStyle({'height': ''});
        });
    },

    hideNewAddressContainer: function(container) {
        this._applyEffect(container, 0, 0.5, function(){
            container.setStyle({'display': 'none'});
        });
    },

    _applyEffect: function(element, newHeight, duration, afterFinish) {
        if (element.effect) {
            element.effect.cancel();
        }
        var afterFinishFn = afterFinish || Prototype.emptyFunction;
        element.effect = new Effect.Morph(element, {
            style: {
                'height': newHeight + 'px'
            },
            duration: duration,
            afterFinish: function(){
                delete element.effect;
                afterFinishFn();
            }
        });
    }
};