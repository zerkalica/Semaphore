Semaphore - Forked by Millwright semaphore library
============================

This library provides an api for semaphore acquire and release.

Support adapters: flock, pdo, doctrine/orm, apc, memcached, sem

Usage:

```php
$adapter   = new \Millwright\Semaphore\Adapter\ApcAdapter;
$semaphore = new \Millwright\Semaphore\Model\SemaphoreManager($adapter);

$ttl = 60; // Time in seconds, used, if script dies and release never called.

$handle = $semaphore->acquire('lock key', $ttl);

// Do something thread-safe

$semaphore->release($handle);

```

SQL table schema:

``` sql
CREATE TABLE `semaphore__semaphore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expire_date` datetime NOT NULL,
  `semaphore_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `semaphore_key_idx` (`semaphore_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
```

Example model for doctrine adapter.
Doctrine adapter works directrly with sql-table. This model only creates appropriate table.

```php
/**
 * Semaphore entity
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="semaphore__semaphore",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="semaphore_key_idx", columns={"semaphore_key"})}
 * )
 */
class Semaphore
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="expire_date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @ORM\Column(name="semaphore_key", type="string", nullable=false)
     */
    protected $key;
}
```
