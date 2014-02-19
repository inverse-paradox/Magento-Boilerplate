<?php
/**
 * My Account Navigation Block
 *
 * @package    InverseParadox_Boilerplate
 * @author     Amanda Mitchell
 * @see        Mage_Customer_Block_Account_Navigation
 */
class InverseParadox_Boilerplate_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    /**
     * Remove block links by name
     *
     * Native Magento class does not have a way to remove links via layout xml,
     * only to add them. This provides the ability to remove them in local.xml
     */
    public function removeLinkByName($name) {
        unset($this->_links[$name]);
    }
}