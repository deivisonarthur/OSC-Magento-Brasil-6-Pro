<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Model_Updater {

    const TARGET_LAYOUT_FULL_ACTION_NAME = 'onestepcheckout_index_index';
    const SHIPPING_METHOD_BLOCK_NAME = 'onestepcheckout.onestep.form.shippingmethod';
    const PAYMENT_METHOD_BLOCK_NAME = 'onestepcheckout.onestep.form.paymentmethod';
    const REVIEW_CART_BLOCK_NAME = 'onestepcheckout.onestep.form.review.cart';
    const REVIEW_COUPON_BLOCK_NAME = 'onestepcheckout.onestep.form.review.coupon';
    const TOP_LINK_BLOCK_NAME = 'top.links';

    protected $_map = array(
        'saveAddress' => array(
            'shipping_method' => self::SHIPPING_METHOD_BLOCK_NAME,
            'payment_method' => self::PAYMENT_METHOD_BLOCK_NAME,
            'review_cart' => self::REVIEW_CART_BLOCK_NAME,
            'review_coupon' => self::REVIEW_COUPON_BLOCK_NAME,
        ),
        'saveShippingMethod' => array(
            'payment_method' => self::PAYMENT_METHOD_BLOCK_NAME,
            'review_cart' => self::REVIEW_CART_BLOCK_NAME,
            'review_coupon' => self::REVIEW_COUPON_BLOCK_NAME,
        ),
        'savePaymentMethod' => array(
            'review_cart' => self::REVIEW_CART_BLOCK_NAME,
            'review_coupon' => self::REVIEW_COUPON_BLOCK_NAME,
        ),
        'applyCoupon' => array(
            'payment_method' => self::PAYMENT_METHOD_BLOCK_NAME,
            'review_cart' => self::REVIEW_CART_BLOCK_NAME,
        ),
        'addProductToWishlist' => array(
            'top_links' => self::TOP_LINK_BLOCK_NAME
        ),
        'updateBlocksAfterACP' => array(
            'shipping_method' => self::SHIPPING_METHOD_BLOCK_NAME,
            'payment_method' => self::PAYMENT_METHOD_BLOCK_NAME,
            'review_cart' => self::REVIEW_CART_BLOCK_NAME,
            'review_coupon' => self::REVIEW_COUPON_BLOCK_NAME,
        )
    );

    /**
     * @param null $controller
     *
     * @return array
     */
    public function getBlocks($layout = null, $fullTargetActionName = null) {
        if (is_null($layout)) {
            $layout = Mage::app()->getLayout();
        }
        if (is_null($fullTargetActionName)) {
            $fullTargetActionName = self::TARGET_LAYOUT_FULL_ACTION_NAME;
        }
        $this->_initLayout($layout, $fullTargetActionName);

        $actionName = Mage::app()->getRequest()->getActionName();
        if (!array_key_exists($actionName, $this->_map)) {
            return array();
        }
        if (!is_array($this->_map[$actionName])) {
            return array();
        }
        $blocks = array();
        foreach ($this->_map[$actionName] as $key => $blockName) {
            $block = $layout->getBlock($blockName);
            if ($block) {
                $blocks[$key] = $block->toHtml();
            }
        }
        return $blocks;
    }

    /**
     * @return array
     */
    public function getMap() {
        return $this->_map;
    }

    protected function _initLayout($layout, $fullTargetActionName) {
        /* -- START-- copypaste from Mage_Core_Controller_Varien_Action -- START -- */
        $update = $layout->getUpdate();
        $update->addHandle('default'); //load default handle
        $update->addHandle('STORE_' . Mage::app()->getStore()->getCode()); // load store handle
        $package = Mage::getSingleton('core/design_package');
        $update->addHandle(
                'THEME_' . $package->getArea() . '_' . $package->getPackageName() . '_' . $package->getTheme('layout')
        ); // load theme handle
        $update->addHandle(strtolower($fullTargetActionName)); // load action handle
        $update->load();
        $layout->generateXml();
        $layout->generateBlocks();
        /* -- END -- copypaste from Mage_Core_Controller_Varien_Action -- END -- */
    }

}
