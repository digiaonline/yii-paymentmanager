<?php
/**
 * PaymentMethod class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.models
 */

/**
 * This is the model class for table "payment_gateway".
 *
 * The followings are the available columns in table 'payment_gateway':
 * @property string $id
 * @property string $name
 * @property string $label
 * @property integer $status
 */
class PaymentMethod extends PaymentActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payment_method';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, label', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('name, label', 'length', 'max' => 255),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'        => Yii::t('payment', 'ID'),
            'name'      => Yii::t('payment', 'Name'),
            'label'     => Yii::t('payment', 'Label'),
            'status'    => Yii::t('payment', 'Status'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PaymentMethod the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
