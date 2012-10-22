<?php
namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

/**
 * flock semaphore adapter
 */
class FlockAdapter implements AdapterInterface
{
    protected $tmpDirectory;

    /**
     * Constructor
     *
     * @param string  $tmpDirectory
     */
    public function __construct($tmpDirectory)
    {
        $this->tmpDirectory = $tmpDirectory;
    }

    /**
     * {@inheritDoc}
     */
    public function acquire($key, $ttl)
    {
        $file   = $this->tmpDirectory . DIRECTORY_SEPARATOR . $key . '.lock';
        $handle = fopen($file, 'r+');

        $locked = flock($handle, \LOCK_EX);
        if (!$locked) {
            @fclose($handle);
        }

        return $locked ? array('handle' => $handle, 'file' => $file) : null;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \LogicException If lock't can't acquired
     */
    public function release($handle)
    {
        $h    = $handle['handle'];
        $file = $handle['file'];

        //@unlink($file);
        flock($h, \LOCK_UN);
        @fclose($handle);
    }
}
