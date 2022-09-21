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

It will define the merchant bank account ID, it should be a string with minimum length of 20 characters.

### user.fname

To define the merchant first name, it should be a string with minimum length of 2 characters.

### user.lname

To define the merchant last name, it should be a string with minimum length of 2 characters.

### user.address

To define the merchant address, it should be a string with minimum length of 5 characters.

## How to use?

By using the **QuickTransfer** class, you will be able to **create new payments**, and also **check payments statuses** identified by their transfer ID.

### createPayment

This function will be used to create a new payment, check the example below :

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

#### Parameters

* **returnUrl:** <**string**> (optional), the callback URL that the user will be redirected to after the payment was successfully completed from the payment platform
* **amount:** <**numeric**> (required), the transaction amount
* **rib:** <**string**> (required)
* **fname:** <**string**> (required)
* **lname:** <**string**> (required)
* **address:** <**string**> (required)

> **Important:** **rib**, **fname**, **lname** and **address** can be configured from [the config file](#configuration) to avoid to send them each time you call **createPayment**.

#### Return value

The result will be an array like : 

* **success:** <**integer**>, 0 for false, 1 for true
* **error:** <**integer**>, 0 for false, 1 for true
* **messages:** <**array**>, it will be sent only when **error == 1**
* **response:** <**array**>, it will be sent only when **success == 1**, it contains the API response
    * **transferId:** Payment transfer ID (can be used to check payment status)
    * **redirectUrl:** The redirect url to redirect the client to the payment platform

### paymentStatus

This function will be used to check the payment status, check the example below :

```php
<?php

use SlickPay\QuickTransfer\QuickTransfer;

$result = QuickTransfer::paymentStatus(1);

dd($result);
```

#### Parameters

* **transferId:** <**number**> (required)

#### Return value

The result will be an array like : 

* **success:** <**integer**>, 0 for false, 1 for true
* **error:** <**integer**>, 0 for false, 1 for true
* **status:** <**string**>, it will be sent only when **success == 1**
* **messages:** <**array**>, it will be sent only when **error == 1**
* **response:** <**array**>, it will be sent only when **success == 1**, it contains the API response
    * **date:** The transaction date (format: Y-m-d H:i:s)
    * **amount:** The transaction amount
    * **orderId:** The order ID provided from the payment platform
    * **orderNumber:** The order N° provided from the payment platform
    * **approvalCode:** The approval code returned from the payment platform
    * **respCode:** The response code returned from the payment platform
    * **pdf:** Download the order details as a PDF file

## More help
   * [Slick-Pay platform](https://slick-pay.com)
   * [Reporting Issues / Feature Requests](https://github.com/Slick-Pay-Algeria/quick-transfer/issues)
