<?php
namespace Millwright\Semaphore\Tests\Mock;

use Millwright\Semaphore\Model\SemaphoreManagerInterface;

/**
 * Semaphoremanager mock
 */
class SemaphoreManagerMock implements SemaphoreManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function acquire($key)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function release($key)
    {
    }
}
