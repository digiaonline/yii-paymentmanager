<?php
/**
 * PaymentManager class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

class PaymentManager extends CApplicationComponent
{
    /**
     * @var array
     */
    public $gateways = array();

    /**
     * @var string
     */
    public $transactionClass = 'PaymentTransaction';

    /**
     * @var array list of payment context configurations.
     */
    public $contexts = array();

    /**
     * @var PaymentContext[] list of context component available.
     */
    private $_contexts = array();

    /**
     * Initializes this component.
     */
    public function init()
    {
        parent::init();
        $this->initContexts();
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
        $gateway = Yii::createComponent($config);
        $gateway->manager = $this;
        $gateway->init();
        return $gateway;
    }

    /**
     * Processes the given transaction.
     * @param PaymentTransaction $transaction
     * @throws CException
     */
    public function process(PaymentTransaction $transaction)
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

        $gateway = $this->createGateway($transaction->gateway);
        try {
            $gateway->prepareTransaction($transaction);
            $this->changeTransactionStatus(PaymentTransaction::STATUS_STARTED, $transaction);
            $gateway->processTransaction($transaction);
            $this->changeTransactionStatus(PaymentTransaction::STATUS_PROCESSED, $transaction);
            $gateway->resolveTransaction($transaction);
        } catch (CException $e) {
            $this->changeTransactionStatus(PaymentTransaction::STATUS_FAILED, $transaction);
            throw $e;
        }
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

    /**
     * Resolves a payment context by name.
     * @param string $name the name of the payment context.
     * @return PaymentContext the payment context.
     * @throws CException if context cannot be found.
     */
    public function resolveContext($name)
    {
        if (!isset($this->_contexts[$name])) {
            throw new CException(sprintf('Failed to find payment context "%s".', $name));
        }
        return $this->_contexts[$name];
    }

    /**
     * Creates the payment context components from the configuration.
     * @throws CException if context config is empty or context cannot be created.
     */
    protected function initContexts()
    {
        if (empty($this->contexts)) {
            throw new CException('PaymentManager.contexts cannot be empty.');
        }
        foreach ($this->contexts as $name => $config) {
            if (!isset($config['class'])) {
                $config['class'] = 'PaymentContext';
            }
            if (!isset($config['name'])) {
                $config['name'] = $name;
            }
            $context = Yii::createComponent($config);
            if (!($context instanceof PaymentContext)) {
                throw new CException('Payment context must be an instance of PaymentContext.');
            }
            $this->_contexts[$name] = $context;
        }
    }
}