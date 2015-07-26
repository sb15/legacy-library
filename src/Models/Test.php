<?php

require_once 'CoreAutoloader.php';
\CoreAutoloader::init();

$di = new \Phalcon\DI\FactoryDefault();
$di->set('db', function() {
    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => 'localhost',
        "username" => 'root',
        "password" => 'admin',
        "dbname" => 'fanofasses'
    ));
    return $connection;
});

$di->set('filter', function() {
    return new \Zend_Filter_Word_UnderscoreToCamelCase();
});

//new \Models\FilesGenerator('d:\\11111', $di);
echo \Models\FilesGenerator::getNameMany('name') . "\n";
echo \Models\FilesGenerator::getNameMany('alias'). "\n";
echo \Models\FilesGenerator::getNameMany('city'). "\n";