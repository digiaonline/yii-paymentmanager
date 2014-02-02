yii-paymentmanager
==================

Payment manager for the Yii PHP framework.

## Usage

### Configuration

Add the following to your application configuration:

```php
.....
'components' => array(
    .....
    'payment' => array(
        'class' => 'vendor.nordsoftware.yii-paymentmanager.components.PaymentManager',
        'gateways' => array(
            'paytrail' => array(
                'class' => 'vendor.nordsoftware.yii-paytrail.components.PaytrailGateway',
            ),
        ),
    ),
),
.....
```

### Create a transaction

With the payment manager it is easy to create a unified transaction and pay it using the payment gateway of your choice.
Below you can find a simple example on how to create a transaction and process it using the payment manager.

```php
$transaction = PaymentTransaction::create(
    array(
        'methodId' => 1, // payment gateway id
        'description' => 'Test payment',
        'price' => 100.00,
        'currency' => 'EUR',
        'vat' => 28.00,
        'successUrl' => Yii::app()->createAbsoluteUrl('/bookPurchase/success'),
        'failureUrl' => Yii::app()->createAbsoluteUrl('/bookPurchase/failure'),
    )
);

$transaction->addShippingContact(
    array(
        'firstName' => 'Foo',
        'lastName' => 'Bar',
        'email' => 'foo@bar.com',
        'phoneNumber' => '1234567890',
        'mobileNumber' => '0400123123',
        'companyName' => 'Test company',
        'streetAddress' => 'Test street 1',
        'postalCode' => '12345',
        'postOffice' => 'Helsinki',
        'countryCode' => 'FIN',
    )
);

$transaction->addItem(
    array(
        'description' => 'Test product',
        'code' => '01234',
        'quantity' => 5,
        'price' => 19.90,
        'vat' => 23.00,
        'discount' => 10.00,
        'type' => 1,
    )
);

$transaction->addItem(
    array(
        'description' => 'Another test product',
        'code' => '43210',
        'quantity' => 1,
        'price' => 49.90,
        'vat' => 23.00,
        'discount' => 50.00,
        'type' => 1,
    )
);

// associate the transaction with order #1 and starts it
Yii::app()->payment->startTransaction(1, $transaction);
```