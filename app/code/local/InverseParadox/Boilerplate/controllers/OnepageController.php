<?php
require_once 'Mage/Checkout/controllers/OnepageController.php';
class InverseParadox_Boilerplate_OnepageController extends Mage_Checkout_OnepageController
{
    public function saveAddressAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {

            // save method
            $method = $this->getRequest()->getPost('checkout_method');
            $result = $this->getOnepage()->saveCheckoutMethod($method);

            // save billing
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $billingResult = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($billingResult['error'])) {
                /* check quote for virtual */
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $billingResult['goto_section'] = 'payment';
                    $billingResult['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $billingResult['goto_section'] = 'shipping_method';
                    $billingResult['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $billingResult['allow_sections'] = array('shipping_method');
                    $billingResult['duplicateBillingInfo'] = 'true';
                } else {
                    $billingResult['goto_section'] = 'shipping_method';
                }
            }

            // save shipping
            if (!$this->getOnepage()->getQuote()->isVirtual() && !(isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1)) {
                $data = $this->getRequest()->getPost('shipping', array());
                $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                $shippingResult = $this->getOnepage()->saveShipping($data, $customerAddressId);

                if (!isset($shippingResult['error'])) {
                    $shippingResult['goto_section'] = 'shipping_method';
                    $shippingResult['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );
                }
            } else {
                $shippingResult = array();
            }

            if (isset($billingResult['error'])) {
                $result = $billingResult;
            } else if (isset($shippingResult['error'])) {
                $result = $shippingResult;
            } else {
                $result = array_merge($billingResult, $shippingResult);
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}