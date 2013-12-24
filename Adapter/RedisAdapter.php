<?php
namespace Millwright\Semaphore\Adapter;

use Predis\Client;
use Millwright\Semaphore\Model\AdapterInterface;

/**
 * Redis semaphore adapter
 */
class RedisAdapter implements AdapterInterface
{
    protected $redis;

    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritDoc}
     */
    public function acquire($key, $ttl)
    {
        $acquired = $this->redis->sadd($key, 1);

        if ($acquired) {
            $this->redis->expire($key, $ttl);
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function release($handle)
    {
        $this->redis->del($handle);
    }

}