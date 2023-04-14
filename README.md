[//]: # (File format based on https://www.makeareadme.com/)

# Color Order Customer Grid
### Features
* Admin can colorize the order and customer grid.
* Admin can colorize the order grid based on the several order statuses.
* Admin can colorize the customer grid based on the different customer group.
* Configuration has also been provided to enabling the colors for order and customer grids.


## Installation

1. Please run the following command
```shell
composer require devhub/color-order-customer-grid
```

2. Update the composer if required
```shell
composer update
```

3. Enable module
```shell
php bin/magento module:enable DeveloperHub_Core
php bin/magento module:enable DeveloperHub_ColorOrderCustomerGrid
php bin/magento setup:upgrade
php bin/magento cache:clean
php bin/magento cache:flush
```
4. If your website is running in production mode then you need to deploy static content and
then clear the cache
```shell
php bin/magento setup:static-content:deploy
php bin/magento setup:di:compile
```



##### This extension is compatible with all the versions of Magento 2.4.*.
### Tested on following instances:
##### multiple instances i.e. 2.4.3-p1 and 2.4.5
