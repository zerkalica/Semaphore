<?php
namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

/**
 * Sem semaphore adapter
 */
class SemAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function deleteExpired(\DateTime $time)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function acquire($key)
    {
        $id = sem_get(crc32($key));
        $ok = sem_acquire($id);

        return $ok ? $id : null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException If lock't can't acquired
     */
    public function release($handle)
    {
        sem_release($handle);
    }
}
