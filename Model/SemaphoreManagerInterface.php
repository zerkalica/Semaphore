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
     * @param string $key
     *
     * @return void
     */
    function acquire($key);

    /**
     * Release semaphore
     *
     * @param string $key
     *
     * @return void
     */
    function release($key);
}
