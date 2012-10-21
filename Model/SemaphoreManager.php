<?php
namespace Millwright\Semaphore\Model;

/**
 * Semaphore manager
 */
class SemaphoreManager implements SemaphoreManagerInterface
{
    protected $defaultAdapter;
    protected $tryCount;
    protected $sleepTime;
    protected $prefix;

    protected $handlers = array();

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param integer          $tryCount  try count, if lock not acquired
     * @param integer          $sleepTime time in seconds , if lock not acquired wait and try again
     * @param string           $prefix    lock key namespace
     */
    public function __construct(AdapterInterface $adapter, $tryCount = 5, $sleepTime = 1, $prefix = 'millwright_semaphore')
    {
        $this->defaultAdapter = $adapter;
        $this->tryCount       = $tryCount;
        $this->sleepTime      = $sleepTime;
        $this->prefix         = $prefix;
    }

    /**
     * {@inheritDoc}
     *
     * @throws  \ErrorException If can't acquire lock
     */
    public function acquire($key, $maxLockTime = 60)
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }
        $key = $this->prefix . $key;

        $adapter = $this->defaultAdapter;
        $try     = $this->tryCount;
        $ok      = null;

        while ($try > 0 && !$ok = $adapter->acquire($key, $maxLockTime)) {
            $try--;
            sleep($this->sleepTime);
        }

        if (!$ok) {
            throw new \ErrorException(sprintf('Can\'t acquire lock for %s', $key));
        } else {
            $this->handlers[$key] = $ok;
        }

        return $key;
    }

    /**
     * {@inheritDoc}
     */
    public function release($key)
    {
        if (!array_key_exists($key, $this->handlers)) {
            throw new \LogicException(sprintf('Call ::acquire(\'%s\') first', $key));
        }

        $this->defaultAdapter->release($this->handlers[$key]);

        unset($this->handlers[$key]);
    }
}
