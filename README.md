FedWeb Design Lab Assignment for Information Retrieval
================================
University of Twente - Han & Rik

### Installation

~~~
composer require "fxp/composer-asset-plugin:1.0.*@dev"
composer install
~~~

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

and import the file `designlab.sql` into the database

### Running
The data of the `sample-search-files` are placed in `data/queries/*` to import the queries you can run the command 
```
./yii lab/import queries
```

(note that the file `yii` has the execution method (`chmod +x yii`)

To import all the samples into the database run:
```
./yii lab/import samples
```
this will take a while, after completing this the website is available at `http://your-host/index.php/site/search`