<?php
//This file will be placed to /wp-content/
	if (defined('WP_PLUGIN_DIR')) {
		$result = @include_once(WP_PLUGIN_DIR . '/wp-file-cache/lib/class.FileCache.php');
	}
	else {
		$result = @include_once(WP_CONTENT_DIR . '/plugins/wp-file-cache/lib/class.FileCache.php');
	}

	if (false === $result) {
		require_once(ABSPATH . WPINC . '/cache.php');
		unset($result);
		return;
	}
	else {
		unset($result);

		$GLOBALS['__sjfc_options'] = 'a:5:{s:7:"enabled";i:1;s:7:"persist";i:1;s:4:"path";s:0:"";s:13:"nonpersistent";s:0:"";s:11:"admin_fresh";i:0}';

		/**
		 * wp_cache_add() - Adds data to the cache, if the cache key doesn't aleady exist
		 *
		 * @param int|string $key The cache ID to use for retrieval later
		 * @param mixed $data The data to add to the cache store
		 * @param string $flag The group to add the cache to
		 * @param int $expire When the cache data should be expired
		 * @return unknown
		 */
		function wp_cache_add($key, $data, $flag = '', $expire = 0)
		{
			global $wp_object_cache;
			if (empty($flag)) { $flag = 'default'; }
			return $wp_object_cache->add($key, $data, $flag);
		}

		/**
		 * wp_cache_close() - Closes the cache
		 *
		 * @return bool Always returns True
		 */
		function wp_cache_close()
		{
			return true;
		}

		/**
		 * wp_cache_delete() - Removes the cache contents matching ID and flag
		 *
		 * @param int|string $id What the contents in the cache are called
		 * @param string $flag Where the cache contents are grouped
		 * @return bool True on successful removal, false on failure
		 */
		function wp_cache_delete($id, $flag = '')
		{
			global $wp_object_cache;
			if (empty($flag)) { $flag = 'default'; }
			return $wp_object_cache->delete($id, $flag);
		}

		/**
		 * wp_cache_flush() - Removes all cache items
		 *
		 * @return bool Always returns true
		 */
		function wp_cache_flush()
		{
			global $wp_object_cache;
			return $wp_object_cache->flush();
		}

		/**
		 * wp_cache_get() - Retrieves the cache contents from the cache by ID and flag
		 *
		 * @param int|string $id What the contents in the cache are called
		 * @param string $flag Where the cache contents are grouped
		 * @return bool|mixed False on failure to retrieve contents or the cache contents on success
		 */
		function wp_cache_get($id, $flag = '')
		{
			global $wp_object_cache;
			if (empty($flag)) { $flag = 'default'; }
			return $wp_object_cache->get($id, $flag);
		}

		function wp_cache_init()
		{
			global $wp_object_cache, $__sjfc_options;
			$options = unserialize($__sjfc_options);
			$path = ('' == trim($options['path'])) ? (dirname(__FILE__) . '/plugins/wp-file-cache/cache') : trim($options['path']);

			$no_ext_fetch = ($options['admin_fresh'] && is_admin()) ? true : false;

			$GLOBALS['wp_object_cache'] = FileCache::instance($path, $options['enabled'], $options['persist'], $no_ext_fetch);

			if (!empty($options['nonpersistent'])) {
				$np = explode(',', $options['nonpersistent']);
				if (!empty($np)) {
					wp_cache_add_non_persistent_groups($np);
				}
			}
		}

		/**
		 * wp_cache_replace() - Replaces the contents of the cache with new data
		 *
		 * @param int|string $id What to call the contents in the cache
		 * @param mixed $data The contents to store in the cache
		 * @param string $flag Where to group the cache contents
		 * @param int $expire When to expire the cache contents
		 * @return bool False if cache ID and group already exists, true on success
		 */
		function wp_cache_replace($key, $data, $flag = '', $expire = 0)
		{
			global $wp_object_cache;
			if (empty($flag)) { $flag = 'default'; }
			return $wp_object_cache->replace($key, $data, $flag);
		}

		/**
		 * wp_cache_set() - Saves the data to the cache
		 *
		 * @param int|string $id What to call the contents in the cache
		 * @param mixed $data The contents to store in the cache
		 * @param string $flag Where to group the cache contents
		 * @param int $expire When to expire the cache contents
		 * @return bool False if cache ID and group already exists, true on success
		 */
		function wp_cache_set($key, $data, $flag = '', $expire = 0)
		{
			global $wp_object_cache;
			if (empty($flag)) { $flag = 'default'; }
			return $wp_object_cache->set($key, $data, $flag);
		}

		/**
		 * Adds a group or set of groups to the list of global groups.
		 *
		 * @param string|array $groups A group or an array of groups to add
		 */
		function wp_cache_add_global_groups($groups)
		{
		}

		/**
		 * Adds a group or set of groups to the list of non-persistent groups.
		 *
		 * @param string|array $groups A group or an array of groups to add
		 */
		function wp_cache_add_non_persistent_groups($groups)
		{
			global $wp_object_cache;
			$wp_object_cache->addNonPersistentGroups($groups);
		}
	}

?>
