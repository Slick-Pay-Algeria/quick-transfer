<p align="center"><a href="https://slick-pay.com" target="_blank"><img src="https://azimutbscenter.com/logos/slick-pay.png" width="380" height="auto" alt="Slick-Pay Logo"></a></p>

## Description

Laravel package for [Slick-Pay](https://slick-pay.com) Quick Transfer API implementation.

* [Prerequisites](#prerequisites)
* [Installation](#installation)
* [Configuration](#configuration)
    * [user.rib](#user.rib)
    * [user.fname](#user.fname)
    * [user.lname](#user.lname)
    * [user.address](#user.address)
* [How to use?](#how-to-use)
    * [createPayment](#createPayment)
    * [paymentStatus](#checkStatus)

## Prerequisites

   - PHP 7.4 or above ;
   - [curl](https://secure.php.net/manual/en/book.curl.php) extension must be enabled ;
   - [Laravel](https://laravel.com) 9.0 or above.

## Installation

Just run this command line :

```sh
composer require slick-pay-algeria/quick-transfer
```

## Configuration

First of all, you have to publish the pakage config file with the command line :

```sh
php artisan vendor:publish --tag=quick-transfer-config
```

Now, you can find a file **quick-transfer.php** within your project **config** folder.

```php
<?php

return [
    'user' => [
        'rib'     => "",
        'fname'   => "", // First name
        'lname'   => "", // Last name
        'address' => "",
    ],
];
```

### user.rib

It will define the merchant bank account ID, it should be a string with the exact size of 20 characters.

### user.fname

To define the merchant first name, it should be a string with minimum length of 2 characters.

### user.lname

To define the merchant last name, it should be a string with minimum length of 2 characters.

### user.address

To define the merchant address, it should be a string with minimum length of 5 characters.

## How to use?

By using the **QuickTransfer** class, you will be able to **create new payments**, and also **check payments statuses** identified by their transfer ID.

### createPayment

To create any new payment, you will use the **createPayment** function provided within the **QuickTransfer** Class.

#### Parameters

* **returnUrl:** <**string**> (optional), the callback URL that the user will be redirected to after the payment was successfully completed from the payment platform
* **amount:** <**numeric**> (required), the transaction amount in "Dinar algérien" currency, the minimum accepted amount is **100 DA**
* **[rib](#user.rib):** <**string**> (optional)
* **[fname](#user.fname):** <**string**> (optional)
* **[lname](#user.lname):** <**string**> (optional)
* **[address](#user.address):** <**string**> (optional)

> **Important:** **rib**, **fname**, **lname** and **address** can be configured from [the config file](#configuration) to avoid to send them each time you call the **createPayment** function.

#### Examples

Default usage :

```php
<?php

use SlickPay\QuickTransfer\QuickTransfer;

$result = QuickTransfer::createPayment([
    'returnUrl' => "https://www.google.com",
    'amount'    => 10000,
]);

dd($result);
```

You can provide **rib**, **fname**, **lname** and **address** within **createPayment** parameters array to use diffrent values than provided in [the config file](#configuration) :

```php
<?php

use SlickPay\QuickTransfer\QuickTransfer;

$result = QuickTransfer::createPayment([
    'returnUrl' => "https://www.google.com",
    'amount'    => 10000,
    'rib'       => "00012345678912345678",
    'fname'     => "Lorem",
    'lname'     => "Ipsum",
    'address'   => "Dolor",
]);

dd($result);
```

#### Return value

The result will be an array like : 

* **success:** <**integer**>, 0 for false, 1 for true
* **error:** <**integer**>, 0 for false, 1 for true
* **messages:** <**array**>, contains error messages, it will be sent only when **error == 1**
* **response:** <**array**>, it will be sent only when **success == 1**, it contains the API response
    * **transferId:** Payment transfer ID (can be used to check payment status)
    * **redirectUrl:** The redirect url to redirect the client to the payment platform

### paymentStatus

If you would like to check any payment status, you will use the **paymentStatus** provided within the **QuickTransfer** Class.

#### Parameters

* **transferId:** <**number**> (required), Payment transfer ID
* **[rib](#user.rib):** <**string**> (optional)

#### Examples

Check the example below :

```php
<?php

use SlickPay\QuickTransfer\QuickTransfer;

$result = QuickTransfer::paymentStatus(1);

dd($result);
```

#### Return value

The result will be an array like : 

* **success:** <**integer**>, 0 for false, 1 for true
* **error:** <**integer**>, 0 for false, 1 for true
* **status:** <**string**>, contains payment status, it will be sent only when **success == 1**
* **messages:** <**array**>, contains error messages, it will be sent only when **error == 1**
* **response:** <**array**>, it will be sent only when **success == 1**, it contains the API response
    * **date:** The transaction date (format: Y-m-d H:i:s)
    * **amount:** The transaction amount
    * **orderId:** The order ID provided from the payment platform
    * **orderNumber:** The order N° provided from the payment platform
    * **approvalCode:** The approval code returned from the payment platform
    * **respCode:** The response code returned from the payment platform
    * **pdf:** Download the order details as a PDF file

## More help
   * [Slick-Pay website](https://slick-pay.com)
   * [Reporting Issues / Feature Requests](https://github.com/Slick-Pay-Algeria/quick-transfer/issues)
