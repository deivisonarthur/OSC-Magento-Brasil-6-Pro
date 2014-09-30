/* CART SUMMARY */
OnestepcheckoutReviewCart = Class.create();
OnestepcheckoutReviewCart.prototype = {
    initialize: function(config){
        this.config = config;

        this.container = $$(config.containerSelector).first();
        this.useForShippingCheckboxContainer = $$(config.useForShippingCheckboxContainerSelector).first();
        this.urlToUpdateBlocksAfterACP = config.urlToUpdateBlocksAfterACP;
        this.overlayConfig = config.overlayConfig;

        this.initObservers();
        var me = this;
        Event.observe(window, 'dom:loaded', function(e) {
            me.initRelatedBlockElements();
        });
    },

    initObservers: function() {
        var me = this;
        $$(this.config.removeLinkSelector).each(function(el){
            el.observe('click', me.onClickOnRemoveLink.bind(me));
        });
    },

    initRelatedBlockElements: function() {
        this.relatedBlockContainer = $$(this.config.relatedBlockContainerSelector).first();
    },
    _onAjaxCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.removeLoaderFromRelated();
            return;
        }
        if (json.success) {
            if ("blocks" in json) {
                this._updateBlocksFromJSONResponse(json.blocks);
                var action = OnestepcheckoutCore.updater._getActionFromUrl(transport.request.url);
                OnestepcheckoutCore.updater.removeActionBlocksFromQueue(action, json);
                if ("can_shop" in json && json.can_shop) {
                    this.useForShippingCheckboxContainer.removeClassName('no-display')
                }
                this.initObservers();
                this.initRelatedBlockElements();
            }
        }
        this.removeLoaderFromRelated();
    },

    _updateBlocksFromJSONResponse: function(json) {
        if (json.related && this.relatedBlockContainer) {
            var storage = new Element('div');
            storage.innerHTML = json.related;
            var newBlock = storage.select('#' + this.relatedBlockContainer.getAttribute('id')).first();
            this.relatedBlockContainer.update(newBlock.innerHTML);
        }
    },

    addLoaderToRelated: function(){
        if (!this.relatedBlockContainer) {
            return;
        }
        OnestepcheckoutCore.addLoaderOnBlock(this.relatedBlockContainer, this.overlayConfig);
    },

    removeLoaderFromRelated: function(){
        if (!this.relatedBlockContainer) {
            return;
        }
        OnestepcheckoutCore.removeLoaderFromBlock(this.relatedBlockContainer, this.overlayConfig);
    }
};

/* COUPON CODE */
OnestepcheckoutReviewCoupon = Class.create();
OnestepcheckoutReviewCoupon.prototype = {
    initialize: function(config) {
        // init dom elements
        this.msgContainer = $$(config.msgContainerSelector).first();
        this.couponCodeInput = $(config.couponCodeInput);
        // init urls
        this.applyCouponUrl = config.applyCouponUrl;
        // init messages
        this.successMessageBoxCssClass = config.successMessageBoxCssClass;
        this.errorMessageBoxCssClass = config.errorMessageBoxCssClass;
        this.jsErrorMsg = config.jsErrorMsg;
        this.jsSuccessMsg = config.jsSuccessMsg;
        // init config
        this.isCouponApplied = config.isCouponApplied;
        // init "Apply Coupon Button"
        this.isApplyCouponButton = config.isApplyCouponButton;
        this.applyCouponButton = $$(config.applyCouponButtonSelector).first();
        this.cancelCouponButton = $$(config.cancelCouponButtonSelector).first();
        // init behaviour
        this.ajaxRequestId = 0;
        this.init();
    },
    init: function() {
        if (this.isApplyCouponButton) {
            if (this.applyCouponButton) {
                this.applyCouponButton.observe('click', this.applyCoupon.bind(this));
                this.cancelCouponButton.observe('click', this.applyCoupon.bind(this))
            }
        } else {
            if (this.couponCodeInput) {
                this.couponCodeInput.observe('change', this.applyCoupon.bind(this))
            }
        }
    },
    applyCoupon: function(e) {
        this.removeMsg();
        if (this.isApplyCouponButton) {
            if (!this.isCouponApplied) {
                this.couponCodeInput.addClassName('required-entry');
                var validationResult = Validation.validate(this.couponCodeInput)
                this.couponCodeInput.removeClassName('required-entry');
                if (!validationResult) {
                    return;
                }
            } else {
                this.couponCodeInput.setValue('');
            }
        } else {
            if (!this.couponCodeInput.getValue() && !this.isCouponApplied) {
                return;
            }
        }
        var me = this;
        this.ajaxRequestId++;
        var currentAjaxRequestId = this.ajaxRequestId;
        var requestOptions = {
            method: 'post',
            parameters: {
                coupon_code: this.couponCodeInput.getValue()
            },
            onComplete: function(transport){
                if (currentAjaxRequestId !== me.ajaxRequestId) {
                    return;
                }
                me._onAjaxCouponActionCompleteFn(transport);
            }
        };
        OnestepcheckoutCore.updater.startRequest(this.applyCouponUrl, requestOptions);
    },
    _onAjaxCouponActionCompleteFn: function(transport) {
        try {
            eval("var json = " + transport.responseText + " || {}");
        } catch(e) {
            this.showError(this.jsErrorMsg);
            return;
        }
        this.isCouponApplied = json.coupon_applied;
        if (json.success) {
            var successMsg = this.jsSuccessMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                successMsg = json.messages;
            }
            this.showSuccess(successMsg);
            if (this.isCouponApplied) {
                this.applyCouponButton.hide();
                this.cancelCouponButton.show();
            } else {
                this.applyCouponButton.show();
                this.cancelCouponButton.hide();
            }
        } else {
            var errorMsg = this.jsErrorMsg;
            if (("messages" in json) && ("length" in json.messages) && json.messages.length > 0) {
                errorMsg = json.messages;
            }
            this.showError(errorMsg);
        }
    },
    showError: function(msg, afterShowFn){
        OnestepcheckoutCore.showMsg(msg, this.errorMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    showSuccess: function(msg, afterShowFn){
        OnestepcheckoutCore.showMsg(msg, this.successMessageBoxCssClass, this.msgContainer);
        //add effect for height change
        var afterShowFn = afterShowFn || new Function();
        new Effect.Morph(this.msgContainer, {
            style: {
                height: this.msgContainer.down().getHeight() + 'px'
            },
            duration: 0.3,
            afterFinish: function(e){
                afterShowFn();
            }
        });
    },
    removeMsg: function() {
        if (this.msgContainer.down()) {
            var me = this;
            new Effect.Morph(this.msgContainer, {
                style: {
                    height: 0 + 'px'
                },
                duration: 0.3,
                afterFinish: function(e) {
                    OnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.errorMessageBoxCssClass);
                    OnestepcheckoutCore.removeMsgFromBlock(me.msgContainer, me.successMessageBoxCssClass);
                }
            });
        }
    }
};

/* COMMENTS */
OnestepcheckoutReviewComments = Class.create();
OnestepcheckoutReviewComments.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.newRowCount = config.newRowCount||5;
        this.saveValuesUrl = config.saveValuesUrl;

        var me = this;
        this.container.select('textarea').each(function(textarea) {
            textarea.setStyle({
                'overflow-y': 'hidden'
            });
            me.initShowEffectObserver(textarea);
        });
        Form.getElements(this.container).each(function(element){
            element.observe('change', me.requestToValuesSave.bind(me));
        });
    },

    requestToValuesSave: function(e) {
        new Ajax.Request(this.saveValuesUrl, {
            method: 'post',
            parameters: Form.serialize(this.container, true)
        });
    },

    initShowEffectObserver: function(textarea) {
        var originalScrollHeight = textarea.scrollHeight;
        var originalRowCount = parseInt(textarea.getAttribute('rows'));
        var originalHeight = parseInt(textarea.getStyle('height'));

        var me = this;
        textarea.observe('focus', function(e){
            var currentRowCount = originalRowCount +
                (((textarea.scrollHeight - originalScrollHeight) * originalRowCount) /  originalHeight);
            if (currentRowCount < me.newRowCount) {
                currentRowCount = me.newRowCount;
            } else {
                currentRowCount++; //add on empty line
            }
            var currentHeight = (originalHeight/originalRowCount)*currentRowCount;
            me.doChangeRowsAttributeEffect(textarea, currentRowCount, currentHeight, function(){
                textarea.setStyle({
                    'overflow-y': 'auto'
                });
            });
        });
        textarea.observe('blur', function(e){
            var lengthOfValue = textarea.getValue().strip().length;
            if (lengthOfValue === 0) {
                me.doChangeScrollOfTextareaEffect(textarea, function(){
                    textarea.setStyle({
                        'overflow-y': 'hidden'
                    });
                    me.doChangeRowsAttributeEffect(textarea, originalRowCount, originalHeight);
                });
            } else {
                var newHeight = (originalHeight/originalRowCount)*me.newRowCount;
                me.doChangeScrollOfTextareaEffect(textarea, function(){
                    textarea.setStyle({
                        'overflow-y': 'hidden'
                    });
                    me.doChangeRowsAttributeEffect(textarea, me.newRowCount, newHeight);
                });
            }
        });
    },

    doChangeRowsAttributeEffect: function(textarea, newRows, newHeight, afterFinish) {
        if (textarea.effect) {
            textarea.effect.cancel();
        }
        var afterFinish = afterFinish||new Function();
        textarea.effect = new Effect.Morph(textarea, {
            style: {
                height: newHeight + "px"
            },
            duration: 0.5,
            afterFinish:function() {
                textarea.setAttribute('rows', newRows);
                delete textarea.effect;
                afterFinish();
            }
        });
    },

    doChangeScrollOfTextareaEffect: function(textarea, afterFinish) {
        if (textarea.effect) {
            textarea.effect.cancel();
        }
        var afterFinish = afterFinish||new Function();
        if (textarea.scrollTop === 0) {
            afterFinish();
            return;
        }
        new Effect.Tween(textarea, textarea.scrollTop, 0, {
            duration: 0.5,
            afterFinish:function() {
                afterFinish();
            }
        }, 'scrollTop');
    }
};

/* NEWSLETTER */
OnestepcheckoutReviewNewsletter = Class.create();
OnestepcheckoutReviewNewsletter.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.generalInput = $$(config.generalInputSelector).first();
        this.segmentsContainer = $$(config.segmentsContainerSelector).first();
        this.saveValuesUrl = config.saveValuesUrl;

        if (this.generalInput) {
            this.generalInput.observe('click', this.onSubscriptionChecked.bind(this));
        }
        var me = this;
        Form.getElements(this.container).each(function(element){
            element.observe('click', me.requestToSaveValues.bind(me));
        });
    },

    requestToSaveValues: function(e) {
        new Ajax.Request(this.saveValuesUrl, {
            method: 'post',
            parameters: Form.serialize(this.container, true)
        })
    },

    onSubscriptionChecked: function(e) {
        var me = this;
        if (this.segmentsContainer) {
            if (this.generalInput.getValue()) {
                this.showSegments();
            } else {
                this.hideSegments();
            }
        }
    },

    showSegments: function() {
        this._changeHeightToWithEffect(this._collectRealSegmentsHeight());
    },

    hideSegments: function() {
        this._changeHeightToWithEffect(0);
    },

    _changeHeightToWithEffect: function (height) {
        var me = this;
        if (this.effect) {
            this.effect.cancel();
        }
        this.effect = new Effect.Morph(this.segmentsContainer, {
            style: {'height': height + "px"},
            duration: 0.5,
            afterEffect: function(){
                delete me.effect;
            }
        });
    },

    _collectRealSegmentsHeight: function() {
        var originalHeightStyle = this.segmentsContainer.getStyle('height');
        this.segmentsContainer.setStyle({'height': 'auto'});
        var realHeight = this.segmentsContainer.getHeight();
        this.segmentsContainer.setStyle({'height': originalHeightStyle});
        return realHeight;
    }
};

/* TERMS & CONDITIONS */
OnestepcheckoutReviewTerms = Class.create();
OnestepcheckoutReviewTerms.prototype = {
    initialize: function(config) {
        this.container = $$(config.containerSelector).first();
        this.items = $$(config.itemsSelector);
        this.linkFromItemSelector = config.linkFromItemSelector;
        this.checkboxFromItemSelector = config.checkboxFromItemSelector;
        this.descriptionContainerFromItemSelector = config.descriptionContainerFromItemSelector;
        this.popup = new OnestepcheckoutUIPopup(config.popup);
        this.initObservers();
    },

    initObservers: function() {
        var me = this;
        this.items.each(function(item){
            var link = item.select(me.linkFromItemSelector).first();
            var description = item.select(me.descriptionContainerFromItemSelector).first();
            if (!link || !description) {
                return;
            }
            link.observe('click', function(e){
                me.currentItem = item;
                me.popup.showPopupWithDescription(description.innerHTML);
            });
        });
        this.popup.buttons.accept.onClickFn = function(e){
            if (me.currentItem) {
                var checkbox = me.currentItem.select(me.checkboxFromItemSelector).first();
                if (checkbox) {
                    checkbox.checked = true;
                }
            }
        }
    }
};