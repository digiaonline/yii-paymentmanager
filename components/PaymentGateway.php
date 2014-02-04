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
    abstract public function handleTransaction($transaction);

    /**
     * Initializes this gateway.
     */
    public function init()
    {
    }

    /**
     * @param CEvent $event
     */
    public function onBeforeProcessTransaction(CEvent $event)
    {
        $this->raiseEvent('onBeforeProcessTransaction', $event);
    }

    /**
     * @param CEvent $event
     */
    public function onAfterProcessTransaction(CEvent $event)
    {
        $this->raiseEvent('onAfterProcessTransaction', $event);
    }

    /**
     * @param CEvent $event
     */
    public function onTransactionFailed(CEvent $event)
    {
        $this->raiseEvent('onTransactionFailed', $event);
    }

    /**
     * @param PaymentTransaction $transaction
     * @return PaymentEvent
     */
    protected function createEvent(PaymentTransaction $transaction)
    {
        return PaymentEvent::create(
            array(
                'transaction' => $transaction,
                'sender' => $this,
            )
        );
    }

    /**
     * @param array $config
     * @return PaymentGateway
     */
    public static function create($config)
    {
        $gateway = Yii::createComponent($config);
        $gateway->init();
        return $gateway;
    }
}