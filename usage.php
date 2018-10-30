<?php
declare(strict_types=1);

include 'vendor/autoload.php';

$dataProvider = new \Api\DefaultDataProvider('host', 'user', 'password');

var_dump($dataProvider->get('path')); // можем использовать напрямую провайдер, без кэша

$cache = new \Cache\Adapter\PHPArray\ArrayCachePool(); // можем использовать любой Psr\Cache\CacheItemPoolInterface

$logger = new Monolog\Logger('apiCache'); // можем использовать любой Psr\Log\LoggerInterface

$cachedDataProvider = new \Api\DataProviderCache($dataProvider, $cache, $logger);

var_dump($cachedDataProvider->get('path')); // то же самое что и строка 8, но уже с кэшем
