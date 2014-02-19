<?php
/**
 * (Sub) Category thumbnail helper
 *
 * @package     InverseParadox_Boilerplate
 * @TODO        Compare to Ryan's category image model
 */
class InverseParadox_Boilerplate_Helper_Category extends Mage_Core_Helper_Abstract
{
    
    /**
     * Get a category thumbnail in a specific size
     *
     * If the category has a thumbnail set, that will be resized and returned.
     * If not, the thumbnail for the first product with an image in that 
     * category will be returned.
     *
     * @return string url for resized image
     */
    public function getCategoryThumb($category, $width, $height = null)
    {
        $raw_thumb_url = $category->getThumbnail();
        if ($raw_thumb_url) {
            $raw_thumb_url = Mage::getBaseUrl() . 'media/catalog/category/' . $raw_thumb_url;
            $thumb = Mage::helper('ipboilerplate/category_image')->init($raw_thumb_url)->resize($width, $height);
        } else {
            $product_collection = $category->getProductCollection()
                                 ->addAttributeToSelect('small_image')
                                 ->addAttributeToFilter('small_image', array('notnull' => '', 'neq' => 'no_selection'));
            $product_collection->getSelect()->limit(1);
            $product_collection->load();
            foreach ($product_collection as $collection_item) {
                $product = $collection_item;
            }
            $thumb = Mage::helper('catalog/image')->init($product, 'small_image')->resize($width, $height);
        }
        return $thumb;
    }
}