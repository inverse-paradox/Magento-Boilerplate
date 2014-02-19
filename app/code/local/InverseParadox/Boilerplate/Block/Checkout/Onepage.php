<?php
/**
 * Onepage Checkout Block
 *
 * Combine shipping and billing blocks into single address block.
 * Last compatability check: 1.8.0.1
 *
 * @package    InverseParadox_Boilerplate
 * @author     Amanda Mitchell
 * @see        Mage_Checkout_Block_Onepage
 */
class InverseParadox_Boilerplate_Block_Checkout_Onepage extends Mage_Checkout_Block_Onepage
{
    /**
     * Get 'one step checkout' step data
     *
     * @return array
     */
    public function getSteps()
    {
        $steps = array();
        $stepCodes = array('login', 'address', 'shipping_method', 'payment', 'review');

        if ($this->isCustomerLoggedIn()) {
            $stepCodes = array_diff($stepCodes, array('login'));
        }

        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }

        return $steps;
    }

    /**
     * Get active step
     *
     * @return string
     */
    public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? 'address' : 'login';
    }
}