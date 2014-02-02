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