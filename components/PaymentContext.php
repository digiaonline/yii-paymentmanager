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

    /**
     * @var mixed a valid callback to run if the payment gateway calls a "notify" endpoint.
     * This can be useful when the payment gateway marks the payment as paid at an unknown point in time.
     * @see http://php.net/manual/en/language.types.callable.php
     */
    public $notifyCallback;
}