<?php
$relationships = getenv("PLATFORM_RELATIONSHIPS");
if (!$relationships) {
  return;
}

$relationships = json_decode(base64_decode($relationships), TRUE);

foreach ($relationships['database'] as $endpoint) {
  if (empty($endpoint['query']['is_master'])) {
    continue;
  }
  $databases['default']['default'] = array (
    'database' => $endpoint['path'],
    'username' => $endpoint['username'],
    'password' => $endpoint['password'],
    'prefix' => '',
    'host' => $endpoint['host'],
    'port' => $endpoint['port'],
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => $endpoint['scheme'],
  );
}

$config['system.logging']['error_level'] = 'verbose';

// Set redis configuration.
if (extension_loaded('redis')) {

  if (!empty($relationships['redis'][0])) {
    $redis = $relationships['redis'][0];

    // Set Redis as the default backend for any cache bin not otherwise specified.
    $settings['cache']['default'] = 'cache.backend.redis';
    $settings['redis.connection']['host'] = $redis['host'];
    $settings['redis.connection']['port'] = $redis['port'];

    // Apply changes to the container configuration to better leverage Redis.
    // This includes using Redis for the lock and flood control systems, as well
    // as the cache tag checksum. Alternatively, copy the contents of that file
    // to your project-specific services.yml file, modify as appropriate, and
    // remove this line.
    $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

    // Allow the services to work before the Redis module itself is enabled.
    $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

    // Manually add the classloader path, this is required for the container cache bin definition below
    // and allows to use it without the redis module being enabled.
    $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');

    // Use redis for container cache.
    // The container cache is used to load the container definition itself, and
    // thus any configuration stored in the container itself is not available
    // yet. These lines force the container cache to use Redis rather than the
    // default SQL cache.
    $settings['bootstrap_container_definition'] = [
      'parameters' => [],
      'services' => [
        'redis.factory' => [
          'class' => 'Drupal\redis\ClientFactory',
        ],
        'cache.backend.redis' => [
          'class' => 'Drupal\redis\Cache\CacheBackendFactory',
          'arguments' => ['@redis.factory', '@cache_tags_provider.container'],
        ],
        'cache.container' => [
          'class' => '\Drupal\redis\Cache\PhpRedis',
          'factory' => ['@cache.backend.redis', 'get'],
          'arguments' => ['container'],
        ],
        'cache_tags_provider.container' => [
          'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
          'arguments' => ['@redis.factory'],
        ],
      ],
    ];

    // Set a fixed prefix so that all requests share the same prefix, even if
    // on different domains.
    $settings['cache_prefix'] = 'prefix_';

    // Drupal 8.1 has a bug where certain special caches that should use the
    // APCu cache if available will not do so if a non-SQL default is specified.
    // The following lines explicitly force those cache bins to use the correct
    // cache backend. This block may be removed in Drupal 8.2.
    // @see https://www.drupal.org/node/2753989
    $settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
    $settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
    $settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
    $settings['cache']['bins']['static'] = 'cache.backend.memory';
  }
}
