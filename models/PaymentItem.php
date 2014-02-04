<?php
/**
 * PaymentItem class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.models
 */

/**
 * This is the model class for table "payment_item".
 *
 * The followings are the available columns in table 'payment_item':
 * @property string $id
 * @property string $transactionId
 * @property string $description
 * @property string $code
 * @property int $quantity
 * @property float $price
 * @property float $vat
 * @property float $discount
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property PaymentTransaction $transaction
 */
class PaymentItem extends PaymentActiveRecord
{
    // todo: consider what to do with discount vat.

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_item';
    }

    /**
     * @return array attached behaviors.
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            array(
                'audit' => array(
                    'class' => 'AuditBehavior',
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
            array('transactionId, price, description, quantity, vat, discount', 'required'),
            array('quantity, status', 'numerical', 'integerOnly' => true),
            array('transactionId, quantity', 'length', 'max' => 10),
            array('description, code', 'length', 'max' => 255),
            array('price, discount', 'length', 'max' => 15),
            array('vat', 'length', 'max' => 5),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'transaction' => array(self::BELONGS_TO, 'PaymentTransaction', 'transactionId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('payment', 'ID'),
            'transactionId' => Yii::t('payment', 'Transaction'),
            'description' => Yii::t('payment', 'Description'),
            'code' => Yii::t('payment', 'Code'),
            'quantity' => Yii::t('payment', 'Quantity'),
            'price' => Yii::t('payment', 'Price'),
            'vat' => Yii::t('payment', 'VAT'),
            'discount' => Yii::t('payment', 'Discount'),
            'status' => Yii::t('payment', 'Status'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PaymentItem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
