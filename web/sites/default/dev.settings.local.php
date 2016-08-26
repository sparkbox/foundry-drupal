<?php

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/default/services.local.yml';

$config['system.logging']['error_level'] = 'verbose';

$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['cache']['bins']['render'] = 'cache.backend.null';

$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['extension_discovery_scan_tests'] = TRUE;

$settings['rebuild_access'] = TRUE;

$settings['skip_permissions_hardening'] = TRUE;

$databases['default']['default'] = array (
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'mariadb',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = '5GjbwD-hB5xTUtIp-3O2kehoYfH9z4sA2TdKw7oJlBaW35d9gpaEmhLGJVIpFA5YTeiKsZMOOQ';


// Set redis configuration.
$conf['redis_client_host'] = 'redis';
$conf['redis_client_interface'] = 'PhpRedis';
$conf['lock_inc'] = 'sites/all/modules/contrib/redis/redis.lock.inc';
$conf['path_inc'] = 'sites/all/modules/contrib/redis/redis.path.inc';
$conf['cache_backends'][] = 'sites/all/modules/redis/redis.autoload.inc';
$conf['cache_default_class'] = 'Redis_Cache';
$conf['cache_class_cache_form'] = 'DrupalDatabaseCache';
