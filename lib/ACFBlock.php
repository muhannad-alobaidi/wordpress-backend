<?php

namespace Sekdev\lagerblad;

abstract class ACFBlock {
  public function __construct(Array $arr) {
    $cn = get_class($this);
    \add_shortcode('acf-' . $arr['name'], [$cn, 'renderShortcode']);
    \acf_register_block_type($arr);
  }

  public static function renderShortcode($atts = []) {
    return "Shortcode: " . print_r($atts, true);
  }

  
}