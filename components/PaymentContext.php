<?php
/**
 * PaymentContext class file.
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

/**
 * Context component for having different settings for payments in different scenarios.
 */
class PaymentContext extends CComponent
{
    /**
     * @var string the name of the context.
     */
    public $name;

    /**
     * @var string the context success url.
     */
    public $successUrl;

    /**
     * @var string the context failure url.
     */
    public $failureUrl;
}