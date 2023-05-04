<?php

namespace Sekdev\lagerblad;

class AjaxEndpoint {
  private static $points = [];
  private static $initialized = false;

  private static function init() {
    if (!self::$initialized) {
      self::$initialized = true;
      \add_action('rest_api_init', [__CLASS__, 'registerEndpoints']);
    }
  }

  public static function registerEndpoints() {
    foreach (self::$points as $p) {
      \register_rest_route($p[0], $p[1], $p[2]);
      error_log("Registered route");
    }
  }

  public static function add($ns, $path, $opts) {
    self::init();
    self::$points[] = [$ns, $path, $opts];
  }

  public static function addGet($ns, $path, $callable) {
    self::add($ns, $path, [
      'method' => 'GET',
      'permission_callback' => '__return_true',
      'args' => [],
      'callback' => $callable,
    ]);
  }
}



