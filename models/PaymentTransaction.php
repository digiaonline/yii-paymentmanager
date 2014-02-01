<?php
/**
 * PaymentTransaction class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.models
 */

/**
 * This is the model class for table "payment_transaction".
 *
 * The followings are the available columns in table 'payment_transaction':
 * @property string $id
 * @property integer $userIdentifier
 * @property integer $methodId
 * @property integer $shippingContactId
 * @property integer $billingContactId
 * @property string $referenceNumber
 * @property string $description
 * @property string $currency
 * @property string $locale
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property PaymentMethod $method
 * @property PaymentContact $shippingContact
 * @property PaymentContact $billingContact
 * @property PaymentItem[] $items
 *
 * The following methods are available through the "WorkflowBehavior" class:
 * @method boolean changeStatus($newStatus)
 */
class PaymentTransaction extends PaymentActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_DEFAULT = 0;
    const STATUS_STARTED = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_PENDING = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 30;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_transaction';
    }

    /**
     * @return array attached behaviors.
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            array(
                'workflow' => array(
                    'class' => 'vendor.crisu83.yii-arbehaviors.behaviors.WorkflowBehavior',
                    'defaultStatus' => self::STATUS_DEFAULT,
                    'statuses' => array(
                        self::STATUS_DEFAULT => array(
                            'label' => t('payment', 'Default'),
                            'transitions' => array(self::STATUS_STARTED, self::STATUS_DELETED),
                        ),
                        self::STATUS_STARTED => array(
                            'label' => t('payment', 'Started'),
                            'transitions' => array(self::STATUS_PROCESSED, self::STATUS_FAILED),
                        ),
                        self::STATUS_PROCESSED => array(
                            'label' => t('payment', 'Processed'),
                            'transitions' => array(self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_FAILED),
                        ),
                        self::STATUS_PENDING => array(
                            'label' => t('payment', 'Pending'),
                            'transitions' => array(self::STATUS_COMPLETED, self::STATUS_FAILED),
                        ),
                        self::STATUS_COMPLETED => array(
                            'label' => t('payment', 'Completed'),
                        ),
                        self::STATUS_FAILED => array(
                            'label' => t('payment', 'Failed'),
                        ),
                        self::STATUS_DELETED => array(
                            'label' => t('payment', 'Deleted'),
                        ),
                    ),
                ),
            )
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('methodId, shippingContactId, description, currency, locale', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('methodId, shippingContactId, billingContactId', 'length', 'max' => 10),
            array('userIdentifier, referenceNumber, description, currency, locale', 'length', 'max' => 255),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'method' => array(self::BELONGS_TO, 'PaymentMethod', 'methodId'),
            'shippingContact' => array(self::BELONGS_TO, 'PaymentContact', 'shippingContactId'),
            'billingContact' => array(self::BELONGS_TO, 'PaymentContact', 'billingContactId'),
            'items' => array(self::HAS_MANY, 'PaymentItem', 'transactionId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('payment', 'ID'),
            'userIdentifier' => Yii::t('payment', 'User identifier'),
            'methodId' => Yii::t('payment', 'Method'),
            'shippingContactId' => Yii::t('payment', 'Shipping contact'),
            'billingContactId' => Yii::t('payment', 'Billing contact'),
            'referenceNumber' => Yii::t('payment', 'Reference number'),
            'description' => Yii::t('payment', 'Description'),
            'currency' => Yii::t('payment', 'Currency'),
            'locale' => Yii::t('payment', 'Locale'),
            'status' => Yii::t('payment', 'Status'),
        );
    }

    /**
     * @param array $attributes
     * @return PaymentItem
     */
    public function addItem($attributes)
    {
        $item = new PaymentItem;
        $item->attributes = $attributes;
        $item->transactionId = $this->id;
        if (!$item->save()) {
            throw new CException(sprintf('Failed to save item for transaction #%d.', $this->id));
        }
        return $item;
    }

    /**
     * @param $attributes
     */
    public function addShippingContact($attributes)
    {
        $contact = PaymentContact::create($attributes);
        $this->shippingContactId = $contact->id;
    }

    /**
     * @param $attributes
     */
    public function addBillingContact($attributes)
    {
        $contact = PaymentContact::create($attributes);
        $this->billingContactId = $contact->id;
    }

    /**
     * Creates a new payment transaction.
     * @param array $attributes
     * @return PaymentTransaction
     * @throws CException
     */
    public static function create(array $attributes)
    {
        $model = new PaymentTransaction;
        $model->attributes = $attributes;
        $model->userIdentifier = Yii::app()->user->id;
        $model->locale = isset($attributes['locale']) ? $attributes['locale'] : Yii::app()->language;
        if (!$model->save()) {
            throw new CException('Failed to save payment transaction.');
        }
        return $model;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PaymentTransaction the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
