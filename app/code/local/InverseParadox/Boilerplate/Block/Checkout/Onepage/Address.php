<?php
/**
 * Onepage Checkout Address Block
 *
 * Combination of Magento's native billing & shipping blocks.
 * Last compatability check: 1.8.0.1
 *
 * @package    InverseParadox_Boilerplate
 * @author     Amanda Mitchell
 * @see        Mage_Checkout_Block_Onepage_Billing, Mage_Checkout_Block_Onepage_Shipping
 */
class InverseParadox_Boilerplate_Block_Checkout_Onepage_Address extends Mage_Checkout_Block_Onepage_Abstract
{
    /**
     * Sales Quote Shipping Address instance
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    protected $_shipping_address;

    /**
     * Sales Quote Billing Address instance
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    protected $_billing_address;

    /**
     * Customer Taxvat Widget block
     *
     * @var Mage_Customer_Block_Widget_Taxvat
     */
    protected $_taxvat;

    /**
     * Initialize address step
     *
     */
    protected function _construct()
    {
        $this->getCheckout()->setStepData('address', array(
            'label'     => Mage::helper('checkout')->__('Address Information'),
            'is_show'   => $this->isShow()
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('address', 'allow', true);
        }
        parent::_construct();
    }

    public function isUseBillingAddressForShipping()
    {
        if (($this->getQuote()->getIsVirtual())
            || !$this->getQuote()->getShippingAddress()->getSameAsBilling()) {
            return false;
        }
        return true;
    }

    /**
     * Return country collection
     *
     * @return Mage_Directory_Model_Mysql4_Country_Collection
     */
    public function getCountries()
    {
        return Mage::getResourceModel('directory/country_collection')->loadByStore();
    }

    /**
     * Return checkout method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->getQuote()->getCheckoutMethod();
    }

    /**
     * Return Sales Quote Address model
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getBillingAddress()
    {
        if (is_null($this->_billing_address)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_billing_address = $this->getQuote()->getBillingAddress();
                if(!$this->_billing_address->getFirstname()) {
                    $this->_billing_address->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_billing_address->getLastname()) {
                    $this->_billing_address->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            } else {
                $this->_billing_address = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_billing_address;
    }

    /**
     * Return Sales Quote Address model (shipping address)
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getShippingAddress()
    {
        if (is_null($this->_shipping_address)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_shipping_address = $this->getQuote()->getShippingAddress();
            } else {
                $this->_shipping_address = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_shipping_address;
    }

    /**
     * Return Customer Address First Name
     * If Sales Quote Address First Name is not defined - return Customer First Name
     *
     * @return string
     */
    public function getFirstname()
    {
        $firstname = $this->getBillingAddress()->getFirstname();
        if (empty($firstname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getFirstname();
        }
        return $firstname;
    }

    /**
     * Return Customer Address Last Name
     * If Sales Quote Address Last Name is not defined - return Customer Last Name
     *
     * @return string
     */
    public function getLastname()
    {
        $lastname = $this->getBillingAddress()->getLastname();
        if (empty($lastname) && $this->getQuote()->getCustomer()) {
            return $this->getQuote()->getCustomer()->getLastname();
        }
        return $lastname;
    }

    /**
     * Check is Quote items can ship to
     *
     * @return boolean
     */
    public function canShip()
    {
        return !$this->getQuote()->isVirtual();
    }

    public function getSaveUrl()
    {
    }

    /**
     * Get Customer Taxvat Widget block
     *
     * @return Mage_Customer_Block_Widget_Taxvat
     */
    protected function _getTaxvat()
    {
        if (!$this->_taxvat) {
            $this->_taxvat = $this->getLayout()->createBlock('customer/widget_taxvat');
        }

        return $this->_taxvat;
    }

    /**
     * Check whether taxvat is enabled
     *
     * @return bool
     */
    public function isTaxvatEnabled()
    {
        return $this->_getTaxvat()->isEnabled();
    }

    public function getTaxvatHtml()
    {
        return $this->_getTaxvat()
            ->setTaxvat($this->getQuote()->getCustomerTaxvat())
            ->setFieldIdFormat('billing:%s')
            ->setFieldNameFormat('billing[%s]')
            ->toHtml();
    }

    public function getCountryHtmlSelect($type)
    {
        if ($type == 'billing') {
            $countryId = $this->getBillingAddress()->getCountryId();
        } else {
            $countryId = $this->getShippingAddress()->getCountryId();
        }
        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
            $select->setExtraParams('onchange="if(window.shipping)shipping.setSameAsBilling(false);"');
        }

        return $select->getHtml();
    }

    public function getAddressesHtmlSelect($type)
    {
        if ($this->isCustomerLoggedIn()) {
            $options = array();
            foreach ($this->getCustomer()->getAddresses() as $address) {
                $options[] = array(
                    'value' => $address->getId(),
                    'label' => $address->format('oneline')
                );
            }

            if ($type == 'billing') {
                $addressId = $this->getBillingAddress()->getCustomerAddressId();
            } else if ($type == 'shipping') {
                $addressId = $this->getShippingAddress()->getCustomerAddressId();
            }
            if (empty($addressId)) {
                if ($type=='billing') {
                    $address = $this->getCustomer()->getPrimaryBillingAddress();
                } else {
                    $address = $this->getCustomer()->getPrimaryShippingAddress();
                }
                if ($address) {
                    $addressId = $address->getId();
                }
            }

            $select = $this->getLayout()->createBlock('core/html_select')
                ->setName($type.'_address_id')
                ->setId($type.'-address-select')
                ->setClass('address-select')
                ->setExtraParams('onchange="address.new'.ucwords($type).'Address(!this.value)"')
                ->setValue($addressId)
                ->setOptions($options);

            $select->addOption('', Mage::helper('checkout')->__('New Address'));

            return $select->getHtml();
        }
        return '';
    }
}
?>