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
     * @param string $key
     *
     * @return boolean
     */
    function acquire($key);

    /**
     * Release semaphore
     *
     * @param mixed $handle handle from acquire
     *
     * @return void
     */
    function release($handle);

    /**
     * Delete expired locks
     *
     * @param \DateTime $time delete all records older than this date
     *
     * @return void
     */
    function deleteExpired(\DateTime $time);
}
