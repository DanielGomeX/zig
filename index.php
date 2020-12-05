<?php

ini_set('display_errors', 1);
  ini_set('display_startup_erros', 1);
  error_reporting(E_ALL);

  $tempodevida = 2678400; // 1 ano de vida
  session_set_cookie_params($tempodevida);
  session_start();
  setcookie(session_name(), session_id(), time() + $tempodevida, '/');

if (
    (getenv('APP_ENV', 'local') != 'production' && getenv('APP_DISPLAY_ERRORS', 'false') == 'true') ||
    !file_exists('.env')
  ) {
  ini_set('display_errors', 1);
  ini_set('display_startup_erros', 1);
  error_reporting(E_ALL);
}

require_once(__DIR__ . '/vendor/autoload.php');

if (!file_exists(__DIR__. '/.env')) {
  require_once(__DIR__.'/System/Bootstrap/install.php');
  exit;
}

# Load env configuration
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

header('Access-Control-Allow-Origin: *');
header('Content-type: text/html; charset=UTF-8');

System\Session\Session::start();

System\Session\Token::verify();

date_default_timezone_set(getenv('TIMEZONE', 'America/Bahia'));

use System\Route\GetRoute;
use System\Route\SelectController;

# Load controllers
$route = new SelectController(new GetRoute);

# Load routes
require_once(__DIR__ . '/routes/routes.php');
