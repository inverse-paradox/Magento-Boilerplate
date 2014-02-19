<?php
/**
 * Invoice view comments form
 *
 * @package    InverseParadox_Boilerplate
 * @author     Amanda Mitchell
 * @see        Mage_Sales_Block_Order_Info
 */
class InverseParadox_Boilerplate_Block_Sales_Order_Info extends Mage_Sales_Block_Order_Info {
    
    /**
     * Retrieve current order model instance
     *
     * Mage::registry('current_order') is always null on the success page, so
     * we must load the last order instead in order to show the order summary
     * view on the success page.
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        $order = Mage::registry('current_order');
        if ($order == null) {
            $lastOrderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $order = Mage::getModel('sales/order')->loadByIncrementId($lastOrderId);
        }
        return $order;
    }
}