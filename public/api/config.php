<?php
// Configurazione ambiente ZeroLegend Agar. I segreti arrivano da Railway.
$requiredEnv = static function (string $key): string {
  $value = getenv($key);
  if (!is_string($value) || $value === '') {
    throw new RuntimeException('Missing required environment variable: ' . $key);
  }
  return $value;
};

$origins = getenv('ALLOWED_ORIGINS') ?: 'https://zerothelegend.com,https://www.zerothelegend.com,https://games.zerothelegend.com';

return [
  'db_host' => $requiredEnv('mysql.railway.internal'),
  'db_name' => $requiredEnv('railway'),
  'db_user' => $requiredEnv('root'),
  'db_pass' => $requiredEnv('RjFKDpoQCfPWfwjZjmvQYpXdXvwMrEcY'),
  'auth_secret' => $requiredEnv('AUTH_SECRET'),
  'server_key'  => $requiredEnv('SERVER_KEY'),
  'token_ttl' => 60 * 60 * 24 * 30,
  'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', $origins)))),
];
