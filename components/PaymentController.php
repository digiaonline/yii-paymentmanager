<?php
/**
 * PaymentController class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

abstract class PaymentController extends CController
{
    /**
     * @var string
     */
    public $managerId = 'payment';

    /**
     * @return array access filters.
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * @return array access control rules.
     */
    public function accessRules()
    {
        return array(
            array('allow', 'users' => array('@')),
            array('deny'),
        );
    }

    /**
     * @return PaymentManager
     * @throws CException
     */
    protected function getPaymentManager()
    {
        if (!Yii::app()->hasComponent($this->managerId)) {
            throw new CException(sprintf('PaytrailController.managerId is invalid.'));
        }
        return Yii::app()->getComponent($this->managerId);
    }
} 