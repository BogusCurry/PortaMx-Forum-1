<?php
/**
 * This file has all the Cache routines for Portamx Forum.
 *
 * PortaMx Forum
 * @package PortaMx
 * @author PortaMx
 * @copyright 2017 PortaMx
 *
 * @version 1.0 RC3
 */

if (!defined('PMX'))
	die('No direct access...');

/**
* Init the cache functions array
*/
global $pmxCache, $pmxCacheFunc, $cache_enable, $cachedir, $boardurl, $cache_accelerator;

$pmxCache['key'] = md5($boardurl . filemtime(__FILE__)) .'-pmx_';
$pmxCache['vals'] = array(
	'enabled' => $cache_enable,
	'loaded' => 0,
	'saved' => 0,
	'time' => 0
);

$pmxCacheFunc = array(
	'get' =>   'pmxCacheGet',
	'put' =>   'pmxCachePut',
	'clean' => 'pmxCacheClean',
	'drop' =>  'pmxCacheDrop'
);

// Create Functions for the selected accelerator
switch ($cache_accelerator)
{
	/**
	* memCached
	*/
	case 'memcache':
	{
		// Get key data from cache
		function pmxCacheGet($key, $useMember = false)
		{
			global $pmxCache, $cache_enable, $mcache;

			if(empty($cache_enable))
				return ($null_array ? array() : null);

			$st = microtime(true);
			$ckey = $pmxCache['key'] . $key . (!empty($useMember) ? pmxCacheMemGroupAcs() : '');

			// connected?
			if(empty($mcache))
				connect_mcache();
			if($mcache)
			{
				$value = memcache_get($mcache, $ckey);
				if(!empty($value))
				{
					$pmxCache['vals']['loaded'] += strlen($value);
					$value = cache_json_decode($value);
					$pmxCache['vals']['time'] += microtime(true) - $st;
					return $value;
				}
			}
			$pmxCache['vals']['time'] += microtime(true) - $st;;
			return ($null_array ? array() : null);
		}

		// Put key data to cache
		function pmxCachePut($key, $value, $ttl = 0, $useMember = false)
		{
			global $pmxCache, $cache_enable, $mcache;

			if(empty($cache_enable))
				return;

			$st = microtime(true);
			$ckey = $pmxCache['key'] . $key . (!empty($useMember) ? pmxCacheMemGroupAcs() : '');

			// connected?
			if(empty($mcache))
				connect_mcache();
			if($mcache)
			{
				if($value !== null && (($value = cache_json_encode($value, true)) !== null))
					memcache_set($mcache, $ckey, $value, 0, $ttl);
				else
					memcache_delete($mcache, $ckey);

				if($value !== null)
					$pmxCache['vals']['saved'] += strlen($value);
				$pmxCache['vals']['time'] += microtime(true) - $st;
			}
		}

		// Clean the cache completely
		function pmxCacheClean()
		{
			global $pmxCache, $cache_enable, $mcache;

			// connected?
			if(empty($mcache))
				connect_mcache();
			if($mcache)
			{
				// clear it out
				if (function_exists('memcache_flush'))
					memcache_flush($mcache);
				else
					memcached_flush($mcache);

				$pmxCache['vals']['loaded'] = 0;
				$pmxCache['vals']['saved'] = 0;
				$pmxCache['vals']['time'] = 0;
			}
		}

		// Connect a memcached server
		function connect_mcache($level = 3)
		{
			global $mcache, $db_persist, $cache_memcache;

			$servers = explode(',', $cache_memcache);
			$server = trim($servers[array_rand($servers)]);
			$port = 0;

			// Normal host names do not contain slashes, while e.g. unix sockets do. Assume alternative transport pipe with port 0.
			if(strpos($server,'/') !== false)
				$host = $server;
			else
			{
				$server = explode(':', $server);
				$host = $server[0];
				$port = isset($server[1]) ? $server[1] : 11211;
			}

			// Don't try more times than we have servers!
			$level = min(count($servers), $level);

			// Don't wait too long: yes, we want the server, but we might be able to run the query faster!
			if (empty($db_persist))
				$mcache = memcache_connect($host, $port);
			else
				$mcache = memcache_pconnect($host, $port);

			if (!$mcache && $level > 0)
				connect_mcache($level - 1);
		}
		break;
	}

	/**
	* Alternative PHP Cache (APC)
	*/
	case 'apc':
	{
		// Get key data from cache
		function pmxCacheGet($key, $useMember = false)
		{
			global $pmxCache, $cache_enable;

			if(empty($cache_enable))
				return null;

			$st = microtime(true);
			$ckey = $pmxCache['key'] . $key . ($useMember ? pmxCacheMemGroupAcs() : '');
			$value = apc_fetch($ckey);
			if(!empty($value))
			{
				$pmxCache['vals']['loaded'] += strlen($value);
				$value = cache_json_decode($value);
				$pmxCache['vals']['time'] += microtime(true) - $st;
				return $value;
			}
			$pmxCache['vals']['time'] += microtime(true) - $st;
			return null;
		}

		// Put key data to cache
		function pmxCachePut($key, $value, $ttl = 0, $useMember = false)
		{
			global $pmxCache, $cache_enable;

			if(empty($cache_enable))
				return;

			$st = microtime(true);
			if($value !== null && (($value = cache_json_encode($value, true)) !== null))
				apc_store($pmxCache['key'] . $key . ($useMember ? pmxCacheMemGroupAcs() : ''), $value, $ttl);
			else
				apc_delete($pmxCache['key'] . $key . ($useMember ? pmxCacheMemGroupAcs() : ''));

			if($value !== null)
				$pmxCache['vals']['saved'] += strlen($value);
			$pmxCache['vals']['time'] += microtime(true) - $st;
		}

		// Clear the cache
		function pmxCacheClean()
		{
			global $pmxCache;

			apc_clear_cache('user');

			$pmxCache['vals']['loaded'] = 0;
			$pmxCache['vals']['saved'] = 0;
			$pmxCache['vals']['time'] = 0;
		}
		break;
	}

	/**
	* file cache
	*/
	case 'file':
	{
		// Get key data from cache
		function pmxCacheGet($key, $useMember = false)
		{
			global $pmxCache, $cache_enable, $cachedir;

			if(!is_dir($cachedir) || empty($cache_enable))
				return null;

			$st = microtime(true);
			$fname = $cachedir .'/data-'. $pmxCache['key']. $key . ($useMember ? pmxCacheMemGroupAcs() : '');
			if(file_exists($fname) && is_readable($fname) && time() <= @filemtime($fname))
			{
				$value = file_get_contents($fname);
				$pmxCache['vals']['loaded'] += strlen($value);
				$value = cache_json_decode($value);
				$pmxCache['vals']['time'] += microtime(true) - $st;

				return !empty($value) ? $value : null;
			}
			else
			{
				@unlink($fname);
				$pmxCache['vals']['time'] += microtime(true) - $st;
			}
		}

		// Put key data to cache
		function pmxCachePut($key, $value, $ttl = 0, $useMember = false)
		{
			global $pmxCache, $cache_enable, $cachedir;

			if(!is_dir($cachedir) || empty($cache_enable))
				return;

			$st = microtime(true);
			$fname = $cachedir .'/data-'. $pmxCache['key']. $key . ($useMember ? pmxCacheMemGroupAcs() : '');
			if($value !== null && (($value = cache_json_encode($value, true)) !== null))
			{
				$cache_bytes = file_put_contents($fname, $value, LOCK_EX);

				// Check that the cache write was successfully
				if($cache_bytes != strlen($value))
					@unlink($fname);
				else
				{
					$newTime = time() + $ttl;
					@touch($fname, $newTime, $newTime);
					$pmxCache['vals']['saved'] += $cache_bytes;
				}
				$pmxCache['vals']['time'] += microtime(true) - $st;
			}
			else
			{
				@unlink($fname);
				$pmxCache['vals']['time'] += microtime(true) - $st;
			}
		}

		// Clear the cache
		function pmxCacheClean()
		{
			global $pmxCache, $cachedir;

			if(is_dir($cachedir))
			{
				$dh = opendir($cachedir);
				while ($file = readdir($dh))
				{
					if(!in_array($file, array('.', '..', 'index.php', '.htaccess')))
						@unlink($cachedir . '/' . $file);
				}
				closedir($dh);
				clearstatcache();

				$pmxCache['vals']['loaded'] = 0;
				$pmxCache['vals']['saved'] = 0;
				$pmxCache['vals']['time'] = 0;
			}
		}
		break;
	}

	// dummy function they do nothing
	default:
	{
		function pmxCacheGet($key, $useMember = false)
		{
			return null;
		}
		function pmxCachePut($key, $value, $ttl = 0)
		{
			return;
		}
		function pmxCacheClean()
		{
			return;
		}
	}
}

/**
* if usegroups given, clear all groups cache
* else clear the give key cache
*/
function pmxCacheDrop($key, $usegroups = false)
{
	global $pmxCacheFunc;

	if(empty($usegroups))
		$pmxCacheFunc['put']($key, null);
	else
	{
		$cgrps = $pmxCacheFunc['get']('usedgroups', false);
		if(!empty($cgrps))
		{
			if(strpos($cgrps, ',') !== false)
			{
				$grps = explode(',', $cgrps);
				foreach($grps as $grp)
					$pmxCacheFunc['put']($key .'_'. $grp, null);
			}
			else
				$pmxCacheFunc['put']($key .'_'. $cgrps, null);
		}
	}
}

/**
* Handle membergroup access data
*/
function pmxCacheMemGroupAcs()
{
	global $pmxCacheFunc, $user_info;

	$acs = $pmxCacheFunc['get']('accessgroups', false);

	// need to reload group keys ?
	if(empty($acs))
	{
		$acs = pmxCache_getGroups();
		$pmxCacheFunc['put']('accessgroups', $acs, 691200, false);
	}
	$tmp = array_keys(array_intersect($acs, $user_info['groups']));
	$grp = implode('-', $tmp);
	pmxCacheUsedGroups($grp);
	return '_'. implode('-', $tmp);
}

/**
* Handle used groups data
*/
function pmxCacheUsedGroups($groups)
{
	global $pmxCacheFunc;

	$havit = $pmxCacheFunc['get']('usedgroups');
	$havit = (!is_null($havit) && !empty($havit)) ? explode(',', $havit) : array();
	if(!in_array($groups, $havit))
	{
		$havit = array_unique(array_merge($havit, array($groups)));
		$return = implode(',', $havit);
		$pmxCacheFunc['put']('usedgroups', $return, 691200, false);
	}
	if(is_bool($groups))
		return $havit;
}

/**
* get usergroup id's
*/
function pmxCache_getGroups()
{
	global $pmxcFunc;

	// guest & normal members
	$result = array('-1', '0');

	// get PMX membergroups
	$request = $pmxcFunc['db_query']('', '
		SELECT id_group
		FROM {db_prefix}membergroups
		ORDER BY id_group',
		array()
	);
	while($row = $pmxcFunc['db_fetch_assoc']($request))
		$result[] = $row['id_group'];

	$pmxcFunc['db_free_result']($request);
	return $result;
}

// Encode a array to Json, on error return null
function cache_json_encode($data)
{
	$returnData = @json_encode($data, true);
	if(json_last_error() == JSON_ERROR_NONE)
		return $returnData;
	else
		return null;
}

// Decode cached Json, on error return empty array
function cache_json_decode($data)
{
	$returnArray = array();
	if (empty($data) || !is_string($data))
		return null;

	$returnArray = @json_decode($data, true);
	if(json_last_error() == JSON_ERROR_NONE)
		return $returnArray;
	else
		return null;
}
?>