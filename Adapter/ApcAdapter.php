<?php
namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

/**
 * Apc semaphore adapter
 */
class ApcAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function acquire($key, $ttl)
    {
        $ok = apc_store($key, false, $ttl);

        return $ok ? $key : null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException If lock't can't acquired
     */
    public function release($handle)
    {
        apc_delete($handle);
    }
}
