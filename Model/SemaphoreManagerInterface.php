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
     *
     * @return mixed handle
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