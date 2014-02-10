<?php
/**
 * PaymentGateway class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

abstract class PaymentGateway extends CComponent
{
    /**
     * @var PaymentManager
     */
    public $manager;

    /**
     * @param PaymentTransaction $transaction
     */
    abstract public function prepareTransaction(PaymentTransaction $transaction);

    /**
     * @param PaymentTransaction $transaction
     */
    abstract public function processTransaction(PaymentTransaction $transaction);

    /**
     * @param PaymentTransaction $transaction
     */
    abstract public function resolveTransaction(PaymentTransaction $transaction);

    /**
     * Initializes this gateway.
     */
    public function init()
    {
    }
}