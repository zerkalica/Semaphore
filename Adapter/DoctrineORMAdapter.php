<?php
namespace Millwright\Semaphore\Adapter;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DBALException;

/**
 * Sql semaphore adapter
 */
class DoctrineORMAdapter extends SqlAdapter
{
    protected $om;
    protected $dbal;

    /**
     * Constructor
     *
     * @param EntityManager $om
     * @param string        $class
     */
    public function __construct(EntityManager $om, $class)
    {
        $this->dbal  = $om->getConnection();
        $meta        = $om->getClassMetadata($class);
        $this->table = $meta->getTableName();
    }

    /**
     * {@inheritDoc}
     */
    protected function exec($query, array $args)
    {
        $sth = $this->dbal->prepare(strtr($query, array('%TABLE%' => $this->table)));
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
        } catch (DBALException $e) {
            $ok = false;
        }

        return $ok;
    }
}
