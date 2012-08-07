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
    protected $expireInterval;

    protected $handlers = array();

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param integer          $tryCount
     * @param integer          $sleepTime
     * @param integer          $expireInterval in seconds
     */
    public function __construct(AdapterInterface $adapter, $tryCount = 5, $sleepTime = 1, $expireInterval = 15)
    {
        $this->defaultAdapter = $adapter;
        $this->tryCount       = $tryCount;
        $this->sleepTime      = $sleepTime;
        $this->expireInterval = $expireInterval;
    }

    /**
     * {@inheritDoc}
     *
     * @throws  \ErrorException If can't acquire lock
     */
    public function acquire($key)
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }

        $adapter = $this->defaultAdapter;
        $try     = $this->tryCount;
        $ok      = null;

        $time = new \DateTime;
        $time->sub(new \DateInterval(sprintf('PT0H0M%sS', $this->expireInterval)));

        $adapter->deleteExpired($time);

        while ($try > 0 && !$ok = $adapter->acquire($key)) {
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
