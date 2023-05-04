<?php

require_once('vendor/autoload.php');

Sekdev\lagerblad\Site::init();

/* Include all the custom blocks */


Sekdev\lagerblad\AjaxEndpoint::addGet(
  'lagerblad/v1',
  'posts',
  [Sekdev\lagerblad\Site::class, 'getPosts']
);

Sekdev\lagerblad\AjaxEndpoint::addGet(
  'lagerblad/v1',
  'pages',
  [Sekdev\lagerblad\Site::class, 'getPages']
);

Sekdev\lagerblad\AjaxEndpoint::addGet(
  'lagerblad/v1',
  'navi',
  [Sekdev\lagerblad\Site::class, 'getNavi']
);

Sekdev\lagerblad\AjaxEndpoint::addGet(
  'lagerblad/v1',
  'objects',
  [Sekdev\lagerblad\Site::class, 'getObjects']
);

function searchProductsEndpoint() {
  register_rest_route( 'lagerblad/v1', '/searchProducts', [
    'method' => 'GET',
    'callback' => ['Sekdev\\lagerblad\\Site', 'getSearchProducts'],
    'args' => [ 'search_query' => [
        'description' => esc_html__( 'The search term.', 'namespace' ),
        'type'        => 'string',
      ],
    ],
  ] );
}
add_action( 'rest_api_init', 'searchProductsEndpoint');

function searchRecipesEndpoint() {
  register_rest_route( 'lagerblad/v1', '/searchRecipes', [
    'method' => 'GET',
    'callback' => ['Sekdev\\lagerblad\\Site', 'getSearchRecipes'],
    'args' => [ 'search_query' => [
        'description' => esc_html__( 'The search term.', 'namespace' ),
        'type'        => 'string',
      ],
    ],
  ] );
}
add_action( 'rest_api_init', 'searchRecipesEndpoint');

function allProductsEndpoint() {
  register_rest_route( 'lagerblad/v1', '/allProducts', [
    'method' => 'GET',
    'callback' => ['Sekdev\\lagerblad\\Site', 'getProducts'],
    'args' => [ 'query' => [
        'description' => esc_html__( 'page number.', 'namespace' ),
        'type'        => 'string',
      ],
    ],
  ] );
}
add_action( 'rest_api_init', 'allProductsEndpoint');




