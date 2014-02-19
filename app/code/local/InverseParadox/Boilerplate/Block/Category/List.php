<?php
/**
 * Subcategory list block
 *
 * @package    InverseParadox_Boilerplate
 * @author     Amanda Mitchell
 */

class InverseParadox_Boilerplate_Block_Category_List extends Mage_Core_Block_Template
{
    
    /**
     * Retrieve current category model object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', Mage::registry('current_category'));
        }
        return $this->getData('current_category');
    }

    /**
     * Retrieve collection of current category's direct subcategories
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getCurrentSubcategories()
    {
        if (!$this->hasData('current_subcategories')) {
            $subcats = array();
            $subcat_ids = explode(',', $this->getCurrentCategory()->getChildren());
            if ($key = array_search($this->getCurrentCategory()->getId(), $subcat_ids)) {
                unset($subcat_ids[$key]);
            } 
            $collection = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $subcat_ids))
                ->addAttributeToSelect('*')
                ->setOrder('position', 'ASC');
            $this->setData('current_subcategories', $collection);
        }
        return $this->getData('current_subcategories');
    }

}