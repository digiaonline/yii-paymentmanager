<?php
/**
 * PaymentEvent class file.
 * @author Christoffer Niska <christoffer.niska@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2014
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-payment.components
 */

class PaymentEvent extends CEvent
{
    /**
     * @var PaymentTransaction
     */
    public $transaction;

    /**
     * @param array $properties
     * @return PaymentEvent
     * @throws CException
     */
    public static function create(array $properties)
    {
        if (!isset($properties['transaction'])) {
            throw new CException('Cannot create payment event without a transaction.');
        }
        $event = new PaymentEvent;
        foreach ($properties as $key => $value) {
            if (property_exists($event, $key)) {
                $event->$key = $value;
            }
        }
        return $event;
    }
} 