<?php
/**
 * PaymentContact class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.models
 */

/**
 * This is the model class for table "payment_contact".
 *
 * The followings are the available columns in table 'payment_contact':
 * @property string $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $phoneNumber
 * @property string $mobileNumber
 * @property string $companyName
 * @property string $streetAddress
 * @property string $postalCode
 * @property string $postOffice
 * @property string $countryCode
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property PaymentTransaction $transaction
 */
class PaymentContact extends PaymentActiveRecord
{
    const TYPE_SHIPPING = 'shipping';
    const TYPE_BILLING = 'billing';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_contact';
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
            array(
                'firstName, lastName, email, streetAddress, postalCode, postOffice, countryCode',
                'required'
            ),
            array('status', 'numerical', 'integerOnly' => true),
            array(
                'firstName, lastName, email, phoneNumber, mobileNumber, companyName, postalCode, postOffice, countryCode',
                'length',
                'max' => 255
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('payment', 'ID'),
            'firstName' => Yii::t('payment', 'First name'),
            'lastName' => Yii::t('payment', 'Last name'),
            'email' => Yii::t('payment', 'Email'),
            'phoneNumber' => Yii::t('payment', 'Phone number'),
            'mobileNumber' => Yii::t('payment', 'Mobile number'),
            'companyName' => Yii::t('payment', 'Company name'),
            'streetAddress' => Yii::t('payment', 'Street address'),
            'postalCode' => Yii::t('payment', 'Postal code'),
            'postOffice' => Yii::t('payment', 'Post office'),
            'countryCode' => Yii::t('payment', 'Country code'),
            'status' => Yii::t('payment', 'Status'),
        );
    }

    /**
     * @param array $attributes
     * @throws CException
     * @return PaymentContact
     */
    public static function create(array $attributes)
    {
        $model = new PaymentContact;
        $model->attributes = $attributes;
        if (!$model->save()) {
            throw new CException('Failed to save payment contact.');
        }
        return $model;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PaymentContact the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
