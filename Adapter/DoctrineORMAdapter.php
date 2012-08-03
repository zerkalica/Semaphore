<?php
namespace Millwright\Semaphore\Adapter;

use Doctrine\ORM\EntityManager;

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
    protected function exec($query, $arg)
    {
        $sth = $this->dbal->prepare(strtr($query, array('%table%' => $this->table)));
        $sth->execute(array($arg));
    }

    /**
     * {@inheritDoc}
     */
    protected function insert($query, $arg)
    {
        try {
            $this->exec($query, $key);
            $ok = true;
        } catch (\ErrorException $e) {
            $ok = false;
        }

        return $ok;
    }
}
