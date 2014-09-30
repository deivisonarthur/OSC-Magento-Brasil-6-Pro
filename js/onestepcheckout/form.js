OnestepcheckoutForm = Class.create();
OnestepcheckoutForm.prototype = {
    initialize: function(config) {
        this.form = new VarienForm(config.formId);
        this.cartContainer = $$(config.cartContainerSelector).first();
        // validate shipping and payment
        this.shippingMethodName = config.shippingMethodName;
        this.shippingMethodAdviceSelector = config.shippingMethodAdvice;
        this.shippingValidationMessage = config.shippingValidationMessage;
        this.shippingMethodWrapperSelector = config.shippingMethodWrapperSelector;
        this.paymentMethodName = config.paymentMethodName;
        this.paymentMethodAdviceSelector = config.paymentMethodAdvice;
        this.paymentValidationMessage = config.paymentValidationMessage;
        this.paymentMethodWrapperSelector = config.paymentMethodWrapperSelector;
        //place button functionality
        this.placeOrderUrl = config.placeOrderUrl;
        this.successUrl = config.successUrl;
        this.placeOrderButton = $(config.placeOrderButtonSelector);
        this.pleaseWaitNotice = $$(config.pleaseWaitNoticeSelector).first(),
        this.disabledClassName = config.disabledClassName;
        this.popup = new OnestepcheckoutUIPopup(config.popup);

        this.initOverlay(config.overlayId);

        if (this.placeOrderButton) {
            this.placeOrderButton.observe('click', this.placeOrder.bind(this));
        }

        var me = this;
        var origFn = this.cartContainer.addActionBlocksToQueueBeforeFn || Prototype.emptyFunction;
        this.cartContainer.addActionBlocksToQueueBeforeFn = function(){
            origFn();
            //update place order button
            me.showPriceChangeProcess();
            me.disablePlaceOrderButton();
        };
        var origFn = this.cartContainer.removeActionBlocksFromQueueAfterFn || Prototype.emptyFunction;
        this.cartContainer.removeActionBlocksFromQueueAfterFn = function(response){
            origFn();
            me.enablePlaceOrderButton();
            me.hidePriceChangeProcess();
        };
    },

    placeOrder: function() {
        if (this.validate()) {
            this.showOverlay();
            this.showPleaseWaitNotice();
            this.disablePlaceOrderButton();
            new Ajax.Request(this.placeOrderUrl,
                {
                    method: 'post',
                    parameters: Form.serialize(this.form.form, true),
                    onComplete: this.onComplete.bindAsEventListener(this)
                }
            )
        }
    },

    showPriceChangeProcess: function() {
        this.disablePlaceOrderButton();
    },

    hidePriceChangeProcess: function() {
        this.enablePlaceOrderButton();
    },

    disablePlaceOrderButton: function() {
        this.placeOrderButton.addClassName(this.disabledClassName);
        this.placeOrderButton.disabled = true;
    },

    enablePlaceOrderButton: function() {
        this.placeOrderButton.removeClassName(this.disabledClassName);
        this.placeOrderButton.disabled = false;
    },

    showPleaseWaitNotice: function() {
        this.pleaseWaitNotice.setStyle({'display':'block'});
    },

    hidePleaseWaitNotice: function() {
        this.pleaseWaitNotice.setStyle({'display':'none'});
    },

    initOverlay: function(overlayId) {
        this.overlay = new Element('div');
        this.overlay.setAttribute('id', overlayId);
        this.overlay.setStyle({'display':'none'});
        document.body.appendChild(this.overlay);
    },

    showOverlay: function() {
        this.overlay.show();
    },

    hideOverlay: function() {
        this.overlay.hide();
    },

    onComplete: function(transport) {
        if (transport && transport.responseText) {
            try{
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
            if (response.redirect) {
                setLocation(response.redirect);
                return;
            }
            if (response.success) {
                setLocation(this.successUrl);
            } else if("is_hosted_pro" in response && response.is_hosted_pro) {
                this.popup.showPopupWithDescription(response.update_section.html);
                var iframe = this.popup.contentContainer.select('#hss-iframe').first();
                iframe.observe('load', function(){
                    $('hss-iframe').show();
                    $('iframe-warning').show();
                });
            } else {
                var msg = response.messages;
                if (typeof(msg) == 'object') {
                    msg = msg.join("\n");
                }
                if (msg) {
                    alert(msg);
                }
                this.enablePlaceOrderButton();
                this.hidePleaseWaitNotice();
                this.hideOverlay();
            }
        }
    },
    validate: function() {
        var result = this.form.validator.validate();
        var formData = Form.serialize(this.form.form, true);
        // check shipping
        this.shippingMethodAdvice = $$(this.shippingMethodAdviceSelector).first();
        this.shippingMethodWrapper = $$(this.shippingMethodWrapperSelector).first();
        var shippingValidation = true;
        if (this.shippingMethodAdvice && this.shippingMethodWrapper) {
            if (!formData[this.shippingMethodName]) {
                shippingValidation = false;
                this.shippingMethodAdvice.update(this.shippingValidationMessage).show();
                this.shippingMethodWrapper.addClassName('validation-failed');
            } else {
                shippingValidation = true;
                this.shippingMethodAdvice.update('').hide();
                this.shippingMethodWrapper.removeClassName('validation-failed');
            }
        }
        // check payment
        this.paymentMethodAdvice = $$(this.paymentMethodAdviceSelector).first();
        this.paymentMethodWrapper = $$(this.paymentMethodWrapperSelector).first();
        var paymentValidation = true;
        if (this.paymentMethodAdvice && this.paymentMethodWrapper) {
            if (!formData[this.paymentMethodName]) {
                paymentValidation = false;
                this.paymentMethodAdvice.update(this.paymentValidationMessage).show();
                this.paymentMethodWrapper.addClassName('validation-failed');
            } else {
                paymentValidation = true;
                this.paymentMethodAdvice.update('').hide();
                this.paymentMethodWrapper.removeClassName('validation-failed');
            }
        }
        return (result && shippingValidation && paymentValidation);
    }
};