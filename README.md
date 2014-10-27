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

The samples must be structures in the following way at `data/queries`
```
examples
data/queries/e001/7011.xml
data/queries/e146/7431.xml
```
Command:
```
./yii lab/import snippets 
```
this will take a while, after completing this the website is available at `http://your-host/index.php/site/search`

For Design Two in the url ```&engine=rik``` should be added, for example
```http://designlab.plank.nl/site/search?q=TERM&engine=rik```

For Design One this is not necessary.

To create a report as we need to, run the following command
Note that the `[our dir]` must be writeable
```
 ./yii report/create [url where query is replaced with {q}] [out dir]
```
Example for Han (default `[out dir]` is `data/report`)

```
./yii report/create http://designlab.plank.nl/site/search?q={q}
```

Example for Rik (make sure the folder `webroot` is at the same level as `data`)
```
./yii report/create "http://localhost/designlab2/DesignLab/webroot/site/search?q={q}&engine=rik" data/report_rik
```