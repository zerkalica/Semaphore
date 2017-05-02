<?php

namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

/**
 * Sql semaphore adapter
 */
abstract class SqlAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    protected function deleteExpired(\DateTime $time)
    {
        $sqlDate = $time->format('Y-m-d H:i:s');
        $query = 'DELETE FROM %TABLE% WHERE expire_date < ?';

        $this->exec($query, array($sqlDate));
    }

    /**
     * Invalidate old locks
     *
     * @param integer $ttl time to leave in seconds
     */
    protected function invalidate($ttl)
    {
        $time = new \DateTime;
        $time->sub(new \DateInterval(sprintf('PT0H0M%sS', $ttl)));
        $this->deleteExpired($time);
    }

    /**
     * Execute statement
     *
     * @param string $query
     * @param array $arg
     */
    abstract protected function exec($query, array $args);

    /**
     * Insert record
     *
     * @param string $query
     * @param mixed $arg
     *
     * @return boolean is inserted
     */
    abstract protected function insert($query, $arg);

    /**
     * {@inheritDoc}
     */
    public function acquire($key, $ttl)
    {
        $this->invalidate($ttl);

        $query = 'INSERT INTO %TABLE% (expire_date, semaphore_key) VALUES(?, ?)';
        $time = new \DateTime;
        $sqlDate = $time->format('Y-m-d H:i:s');
        $ok = $this->insert($query, array($sqlDate, $key));

        return $ok ? $key : null;
    }

    /**
     * {@inheritDoc}
     */
    public function release($handle)
    {
        $query = 'DELETE FROM %TABLE% WHERE semaphore_key = ?';
        $this->exec($query, array($handle));
    }
}
