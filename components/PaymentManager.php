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
     * @var array
     */
    public $gateways = array();

    /**
     * @var mixed
     */
    public $successUrl;

    /**
     * @var mixed
     */
    public $failureUrl;

    /**
     * @var string
     */
    public $transactionClass = 'PaymentTransaction';

    /**
     * @var string
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
        if (!isset($this->successUrl)) {
            throw new CException('PaymentManager.successUrl must be set.');
        }
        if (!isset($this->failureUrl)) {
            throw new CException('PaymentManager.failureUrl must be set.');
        }
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
            throw new CException(sprintf('Failed to create payment gateway "%s".', $name));
        }
        $config = CMap::mergeArray($this->gateways[$name], $config);
        return PaymentGateway::create($config);
    }

    /**
     * Starts the given transaction.
     * @param PaymentTransaction $transaction
     */
    public function startTransaction(PaymentTransaction $transaction)
    {
        if (!isset($transaction->gateway)) {
            throw new CException('Cannot start transaction without a payment gateway.');
        }
        if (!isset($transaction->shippingContactId)) {
            throw new CException('Cannot start transaction without a shipping contact.');
        }
        if (!count($transaction->items)) {
            throw new CException('Cannot start transaction without any items.');
        }

        $this->changeTransactionStatus(PaymentTransaction::STATUS_STARTED, $transaction);

        $gateway = $this->createGateway($transaction->gateway);
        $manager = $this;
        $gateway->onTransactionProcessed = function(CEvent $event) use ($manager, $transaction) {
            $manager->changeTransactionStatus(PaymentTransaction::STATUS_PROCESSED, $transaction);
        };
        $gateway->onTransactionFailed = function(CEvent $event) use ($manager, $transaction) {
            $manager->changeTransactionStatus(PaymentTransaction::STATUS_FAILED, $transaction);
        };
        $gateway->handleTransaction($transaction);
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