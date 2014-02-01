<?php
/**
 * PaymentLog class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.models
 */

/**
 * This is the model class for table "payment_log".
 *
 * The followings are the available columns in table 'payment_log':
 * @property string $id
 * @property string $transactionId
 * @property string $transactionStatus
 * @property string $createdAt
 *
 * The followings are the available model relations:
 * @property PaymentTransaction $transaction
 */
class PaymentLog extends PaymentActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('transactionId, transactionStatus, createdAt', 'required'),
            array('transactionStatus', 'numerical', 'integerOnly' => true),
            array('transactionId', 'length', 'max' => 10),
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
            'transactionStatus' => Yii::t('payment', 'Transaction status'),
            'createdAt' => Yii::t('payment', 'Created at'),
        );
    }

    /**
     * @param array $attributes
     * @return PaymentLog
     * @throws CException
     */
    public static function create(array $attributes)
    {
        $model = new PaymentLog;
        $model->attributes = $attributes;
        $model->createdAt = date('Y-m-d H:i:s');
        if (!$model->save()) {
            throw new CException('Failed to save payment log.');
        }
        return $model;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PaymentLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
