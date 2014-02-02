<?php
/**
 * PaymentManager class file.
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
class PaymentManager extends CApplicationComponent
{
    /**
     * @var string
     */
    public $methodClass = 'PaymentMethod';

    /**
     * @var string
     */
    public $transactionClass = 'PaymentTransaction';

    /**
     * @var array
     */
    public $gateways = array();

    /**
     * @var string path to the yii-extension library.
     */
    public $yiiExtensionAlias = 'vendor.crisu83.yii-extension';

    /**
     * Initializes this component.
     */
    public function init()
    {
        parent::init();
        Yii::import($this->yiiExtensionAlias . '.behaviors.*');
        $this->attachBehavior('ext', new ComponentBehavior);
        $this->createPathAlias('payment', dirname(__DIR__));
        $this->import('components.*');
        $this->import('models.*');
    }

    /**
     * Creates a payment gateway.
     * @param string $name
     * @param array $config
     * @return PaymentGateway
     * @throws CException
     */
    public function createGateway($name, array $config = array())
    {
        if (!isset($this->gateways[$name])) {
            throw new CException(sprintf('Failed to find payment gateway "%s".', $name));
        }
        $config = CMap::mergeArray($this->gateways[$name], $config);
        return PaymentGateway::create($config);
    }

    /**
     * Starts the given transaction.
     * @param int $orderId
     * @param PaymentTransaction $transaction
     */
    public function startTransaction($orderId, PaymentTransaction $transaction)
    {
        if (!isset($transaction->shippingContactId)) {
            throw new CException('Cannot pay a transaction without a shipping contact.');
        }
        if (!count($transaction->items)) {
            throw new CException('Cannot pay a transaction without any items.');
        }

        $this->changeTransactionStatus(PaymentTransaction::STATUS_STARTED, $transaction);

        $method = $this->loadMethod($transaction->methodId);
        $gateway = $this->createGateway($method->name);

        $manager = $this;

        $gateway->onPaymentSuccess = function(CEvent $event) use ($manager, $transaction) {
            $manager->changeTransactionStatus(PaymentTransaction::STATUS_PROCESSED, $transaction);
        };
        $gateway->onPaymentFailed = function(CEvent $event) use ($manager, $transaction) {
            $manager->changeTransactionStatus(PaymentTransaction::STATUS_FAILED, $transaction);
        };

        $gateway->handleTransaction($orderId, $transaction);
    }

    /**
     * @param int $status
     * @param PaymentTransaction $transaction
     * @throws CException
     */
    public function changeTransactionStatus($status, PaymentTransaction $transaction)
    {
        if (!$transaction->changeStatus($status)) {
            throw new CException(sprintf('Failed to change payment transaction status to %d.', $status));
        }
        PaymentLog::create(
            array(
                'transactionId' => $transaction->id,
                'transactionStatus' => $transaction->status,
            )
        );
    }

    /**
     * Loads a payment method model.
     * @param int $id
     * @return PaymentMethod
     * @throws CException
     */
    public function loadMethod($id)
    {
        $method = CActiveRecord::model($this->methodClass)->findByPk($id);
        if ($method === null) {
            throw new CException(sprintf('Failed to load payment method #%d.', $id));
        }
        return $method;
    }

    /**
     * Loads a payment transaction model.
     * @param int $id
     * @return PaymentTransaction
     * @throws CException
     */
    public function loadTransaction($id)
    {
        $transaction = CActiveRecord::model($this->transactionClass)->findByPk($id);
        if ($transaction === null) {
            throw new CException(sprintf('Failed to load payment transaction #%d.', $id));
        }
        return $transaction;
    }
}