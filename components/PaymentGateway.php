<?php
/**
 * PaymentGateway class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

/**
 * Methods accessible through the 'ComponentBehavior' class:
 * @method createPathAlias($alias, $path)
 * @method import($alias)
 * @method string publishAssets($path, $forceCopy = false)
 * @method void registerCssFile($url, $media = '')
 * @method void registerScriptFile($url, $position = null)
 * @method string resolveScriptVersion($filename, $minified = false)
 * @method CClientScript getClientScript()
 * @method void registerDependencies($dependencies)
 * @method string resolveDependencyPath($name)
 */
abstract class PaymentGateway extends CComponent
{
    /**
     * @param PaymentTransaction $transaction
     */
    abstract public function handleTransaction($transaction);

    /**
     * Initializes this component.
     */
    public function init()
    {
        $this->attachBehavior('ext', new ComponentBehavior);
    }

    /**
     * @param CEvent $event
     */
    public function onPaymentFailed(CEvent $event)
    {
        $this->raiseEvent('onPaymentFailed', $event);
    }

    /**
     * @param CEvent $event
     */
    public function onPaymentSuccess(CEvent $event)
    {
        $this->raiseEvent('onPaymentSuccess', $event);
    }

    /**
     * @param CEvent $event
     */
    public function onPaymentNotify(CEvent $event)
    {
        $this->raiseEvent('onPaymentNotify', $event);
    }

    /**
     * @param CEvent $event
     */
    public function onPaymentPending(CEvent $event)
    {
        $this->raiseEvent('onPaymentPending', $event);
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