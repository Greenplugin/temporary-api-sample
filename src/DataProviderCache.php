<?php
declare(strict_types=1);

namespace Api;

use Psr\Log\LoggerInterface;
use Psr\Cache\CacheItemPoolInterface;

class DataProviderCache implements DataProviderInterface
{

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DataProviderCache constructor.
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->$cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param string $path
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws DataProviderException
     */
    public function get(string $path): string
    {
        $cacheKey = $this->getCacheKey(DefaultDataProvider::class, $path);
        $cacheItem = $this->cache->getItem($cacheKey);

        if($cacheItem->isHit()){
            return $cacheItem->get();
        }

        try {
            $result = $this->dataProvider->get($path);
        } catch (DataProviderException $e) {
            $this->logger->error($e->getMessage());
            throw $e; // прерываемся и отдаем exception выше
        }

        $cacheItem
            ->set($result)
            ->expiresAt(new \DateTime('+1 Day'));

        $this->cache->save($cacheItem);

        return $result;
    }

    /**
     * @param string $class
     * @param string $path
     * @return string
     */
    private function getCacheKey(string $class, string $path)
    {
        return sha1($class . $path);
    }
}