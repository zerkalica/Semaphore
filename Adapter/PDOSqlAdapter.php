<?php

namespace Millwright\Semaphore\Adapter;

/**
 * Sql semaphore adapter
 */
class PDOSqlAdapter extends SqlAdapter
{
    protected $db;
    protected $table;

    /**
     * Constructor
     *
     * @param \PDO $db
     * @param string $table
     */
    public function __construct(\PDO $db, $table = null)
    {
        $this->db = $db;

        if (is_null($table)) {
            $table = 'semaphore__semaphore';
        }
        $this->table = $table;
    }

    /**
     * Execute statement
     *
     * @param string $query
     * @param array $args
     */
    protected function exec($query, array $args)
    {
        $sth = $this->db->prepare(strtr($query, array('%TABLE%' => $this->table)));
        $sth->execute($args);
    }

    /**
     * {@inheritDoc}
     */
    protected function insert($query, $args)
    {
        try {
            $this->exec($query, $args);
            $ok = true;
        } catch (\PDOException $e) {
            $ok = false;
        }

        return $ok;
    }
}
