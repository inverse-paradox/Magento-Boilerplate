<?php
class InverseParadox_Boilerplate_Model_Category_Image_Observer
{
    /**
     * Is Enabled timage category image cache
     *
     * @var bool
     */
    protected $_isEnabled;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_isEnabled = Mage::app()->useCache('ipcatimg');
    }

    /**
     * Check if full page cache is enabled
     *
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->_isEnabled;
    }

    /**
     * Clean full page cache
     *
     * @return Technooze_Timage_Model_Observer
     */
    public function cleanCache()
    {
        $cacheDir = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'cache';
        mageDelTree($cacheDir);
        @mkdir($cacheDir);
        return $this;
    }

    /**
     * Invalidate timage cache @todo
     * @return Technooze_Timage_Model_Observer
     */
    public function invalidateCache()
    {
        Mage::app()->getCacheInstance()->invalidateType('ipcatimg');
        return $this;
    }
}
