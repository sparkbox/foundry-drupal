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

$settings['hash_salt'] = getenv("HASH_SALT");

