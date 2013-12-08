##Opis Database##

Opis Database is an advanced Database Abstraction Layer with support for MySQL, PostgreSQL, DB2, Firebird, Oracle

###Documentation###

* [Fetching records](https://github.com/opis/database/blob/master/doc/fetching.md)
    * [Aggregate functions](https://github.com/opis/database/blob/master/doc/aggregate.md)
    * [WHERE clauses](https://github.com/opis/database/blob/master/doc/where.md)

###Examples###

Connecting to a database

```php
use \Opis\Database\Database;
use \Opis\Database\Connection;

Connection::mysql('test', true)
  ->database('db1')
  ->username('user')
  ->password('pass')
  ->charset('utf8');

$db = Database::connection('test');
```

####Selecting records####

```sql
SELECT * FROM `products` WHERE `category` = 'laptops' AND `quantity` > 10
```

```php
$result = $db->from('products')
             ->where('category', 'laptops')
             ->andWhere('quantity', 10, '>')
             ->select();
```

```sql
SELECT * FROM `customers` WHERE `name` LIKE 'A%'
```

```php
$result = $db->from('customers')
             ->whereLike('name', 'A%')
             ->select();
```

```sql
SELECT `orders`.`id`, `customers`.`name`, `orders`.`date` FROM `orders`
INNER JOIN `customers` ON `orders`.`customer` = `customers`.`id`
```

```php
$result = $db->from('orders')
             ->join('customers', 'orders.customer', 'customer.id')
             ->select(array('orders.id', 'cutsomers.name', 'orders.date'));
```

####Creating records####

```sql
INSERT INTO `laptops` VALUES ('Toshiba', 'white')
```

```php
$result = $db->insert('laptops')
             ->values(array('Toshiba', 'white'))
             ->execute();
```

```sql
INSERT INTO `laptops` (`brand`, `color`) VALUES ('Toshiba', 'white')
```

```php
$result = $db->insert('laptops', array('brand', 'color'))
             ->values(array('Toshiba', 'white'))
             ->execute();
```

```sql
INSERT INTO `laptops` (`brand`, `color`) VALUES ('Toshiba', 'white'), ('Dell', 'black')
```

```php
$result = $db->insert('laptops', array('brand', 'color'))
             ->values(array('Toshiba', 'white'))
             ->values(array('Dell', 'black'))
             ->execute();
```

####Updating records###

```sql
UPDATE `orders` SET `product` = 'Toshiba', `color` = 'white' WHERE `id` = 2013
```

```php
$result = $db->update('orders')
             ->where('id', 2013)
             ->set(array('product' => 'Toshiba', 'color' => 'white'))
             ->execute();
```

```sql
UPDATE `orders` SET `product` = 'Toshiba', `color` = 'white', `last_update` = NOW() WHERE `id` = 2013
```

```php
$result = $db->update('orders')
             ->where('id', 2013)
             ->set(array('product' => 'Toshiba', 'color' => 'white'))
             ->set('last_update', function($expr){
                $expr->now();
             })
             ->execute();
```

```sql
UPDATE `orders` SET `product` = 'Toshiba', `color` = UCASE(`color`) WHERE `id` = 2013
```

```php
$result = $db->update('orders')
             ->where('id', 2013)
             ->set('product' => 'Toshiba')
             ->set('color', function($expr){
                $expr->ucase('color');
             })
             ->execute();
```

####Deleting records####

```sql
DELETE FROM `orders` WHERE `id` = 2013
```

```php
$result = $db->from('orders')
             ->where('id', 2013)
             ->delete();
```

Delete from multiple tables

```sql
DELETE `customers`, `orders` FROM `customers`
INNER JOIN `orders` ON `orders`.`customer` = `customers`.`id`
WHERE `customers`.`id` = 102
```

```php
$result = $db->from('customers')
             ->where('customers.id', 102)
             ->join('orders', 'orders.customer', 'customers.id')
             ->delete(array('customers', 'orders'));
```

####Select into###

```sql
SELECT * INTO `copy` FROM `customers`
```

```php
$result = $db->from('customers')
             ->into('copy')
             ->select();
```

```sql
SELECT * INTO `copy` IN `other_DB` FROM `customers`
```

```php
$result = $db->from('customers')
             ->into('copy', 'other_DB')
             ->select();
```