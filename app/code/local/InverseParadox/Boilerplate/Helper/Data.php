<?php
/**
 * Boilerplate general helper
 *
 * @package     InverseParadox_Boilerplate
 */
class InverseParadox_Boilerplate_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    /**
     * Generate classes for responsive columns
     *
     * @param int $count index of item to generate class for
     * @return string class(es) to apply to item
     */
    function getColumnClasses($count)
    {
        $itemclass = '';
        if ($count % 2 == 0) {
            $itemclass = 'even';
        } else {
            $itemclass = 'odd';
        }
        if ($count % 3 == 0) {
            $itemclass .= ' third';
        } else if (($count - 1) % 3 == 0) {
            $itemclass .= ' after-third';
        }
        if ($count % 4 == 0) {
            $itemclass .= ' fourth';
        } else if (($count - 1) % 4 == 0) {
            $itemclass .= ' after-fourth';
        }
        if ($count % 5 == 0) {
            $itemclass .= ' fifth';
        } else if (($count - 1) % 5 == 0) {
            $itemclass .= ' after-fifth';
        }
        return $itemclass;
    }

}