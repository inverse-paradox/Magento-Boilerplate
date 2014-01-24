<?php
class InverseParadox_Boilerplate_Block_Sales_Order_Items extends Mage_Sales_Block_Order_Items {
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