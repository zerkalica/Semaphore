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
    public function deleteExpired(\DateTime $time)
    {
        $sqlDate = $time->format('Y-m-d H:i:s');
        $query   = 'DELETE FROM %table% WHERE expire_date < ?';

        $this->exec($query, array($sqlDate));
    }

    /**
     * Execute statement
     *
     * @param string $query
     * @param array  $arg
     */
    abstract protected function exec($query, array $args);

    /**
     * Insert record
     *
     * @param string $query
     * @param mixed  $arg
     *
     * @return boolean is inserted
     */
    abstract protected function insert($query, $arg);

    /**
     * {@inheritDoc}
     */
    public function acquire($key)
    {
        $query   = 'INSERT INTO %table% (expire_date, semaphore_key) VALUES(?, ?)';
        $time    = new \DateTime;
        $sqlDate = $time->format('Y-m-d H:i:s');
        $ok = $this->insert($query, array($sqlDate, $key));

        return $ok ? $key : null;
    }

    /**
     * {@inheritDoc}
     */
    public function release($handle)
    {
        $query = 'DELETE FROM %table% WHERE semaphore_key = ?';
        $this->exec($query, array($handle));
    }
}
