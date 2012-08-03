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

        $this->exec($query, $sqlDate);
    }

    /**
     * Execute statement
     *
     * @param string $query
     * @param mixed  $arg
     */
    abstract protected function exec($query, $arg);

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
        $query = 'INSERT INTO %table% WHERE semaphore_key = ?';

        $ok = $this->insert($query, $key);

        return $ok ? $key : null;
    }

    /**
     * {@inheritDoc}
     */
    public function release($handle)
    {
        $query = 'DELETE FROM %table% WHERE semaphore_key = ?';
        $this->exec($query, $handle);
    }
}
