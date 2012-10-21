<?php
namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

/**
 * Memcached semaphore adapter
 */
class MemcachedAdapter implements AdapterInterface
{
    protected $memcached;

    /**
     * Constructor
     *
     * @param \Memcached $memcached
     */
    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * {@inheritDoc}
     */
    public function acquire($key, $ttl)
    {
        $ok = $this->memcached->add($key, true, $ttl);

        return $ok ? $key : null;
    }

    /**
     * {@inheritDoc}
     */
    public function release($handle)
    {
        $this->memcached->delete($handle);
    }
}
