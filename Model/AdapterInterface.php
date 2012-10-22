<?php
namespace Millwright\Semaphore\Model;

/**
 * Semaphore adapter interface
 */
interface AdapterInterface
{
    /**
     * Acquire semaphore and return handle
     *
     * @param string  $key
     * @param integer $ttl time to leave in seconds
     *
     * @return boolean
     */
    function acquire($key, $ttl);

    /**
     * Release semaphore
     *
     * @param mixed $handle handle from acquire
     *
     * @return void
     */
    function release($handle);
}
