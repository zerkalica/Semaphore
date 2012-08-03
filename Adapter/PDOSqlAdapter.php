<?php
namespace Millwright\Semaphore\Adapter;

use Millwright\Semaphore\Model\AdapterInterface;

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
     * @param \PDO    $db
     * @param string  $table
     */
    public function __construct(\PDO $db, $table)
    {
        $this->db    = $db;
        $this->table = $table;
    }

    /**
     * Execute statement
     *
     * @param string $query
     * @param mixed  $arg
     */
    protected function exec($query, array $args)
    {
        $sth = $this->db->prepare(strtr($query, array('%table%' => $this->table)));
        $sth->execute($args);
    }

    /**
     * {@inheritDoc}
     */
    protected function insert($query, array $args)
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
