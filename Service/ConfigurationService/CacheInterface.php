<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService;

/**
 * Gestor de cache de configuracion
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface CacheInterface 
{
    /**
     * @param type $key
     * @param type $wrapperName
     * @return \Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface representacion en objeto
     */
    public function getConfiguration($key,$wrapperName);
    
    /**
     * Fetches an entry from the cache.
     *
     * @param string $id The id of the cache entry to fetch.
     *
     * @return mixed The cached data or FALSE, if no cache entry exists for the given id.
     */
    public function fetch($key,$wrapperName);
    
    /**
     * Tests if an entry exists in the cache.
     *
     * @param string $id The cache id of the entry to check for.
     *
     * @return bool TRUE if a cache entry exists for the given cache id, FALSE otherwise.
     */
    public function contains($key,$wrapperName);
    
    /**
     * Puts data into the cache.
     *
     * If a cache entry with the given id already exists, its data will be replaced.
     *
     * @param string $id       The cache id.
     * @param mixed  $data     The cache entry/data.
     * @param int    $lifeTime The lifetime in number of seconds for this cache entry.
     *                         If zero (the default), the entry never expires (although it may be deleted from the cache
     *                         to make place for other entries).
     *
     * @return bool TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function save($key,$wrapperName, $data, $lifeTime = 0);
    
    /**
     * Deletes a cache entry.
     *
     * @param string $id The cache id.
     *
     * @return bool TRUE if the cache entry was successfully deleted, FALSE otherwise.
     *              Deleting a non-existing entry is considered successful.
     */
    public function delete($key,$wrapperName);
    
    /**
     * Limpia todos los valores de la cache
     *
     * @return bool TRUE if the cache entries were successfully flushed, FALSE otherwise.
     */
    public function flush();
    
    /**
     * Genera la cache
     */
    public function warmUp(array $configurations);
    
    public function setAdapter(\Tecnoready\Common\Service\ConfigurationService\Adapter\ConfigurationAdapterInterface $adapter);
}
