<?php
namespace Millwright\Semaphore\Model;

/**
 * Semaphore manager
 */
interface SemaphoreManagerInterface
{
    /**
     * Acquire semaphore
     *
     * @param string|object $key
     * @param integer       $maxLockTime max lock time in seconds, used if release not called
     *
     * @return mixed handle
     */
    function acquire($key, $maxLockTime = 60);

    /**
     * Release semaphore
     *
     * @param string $key
     *
     * @return void
     */
    function release($key);
}
