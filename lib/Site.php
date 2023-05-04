<?php

namespace Sekdev\lagerblad;

class Site {

  public static function init() {
    foreach (glob(__DIR__ . '/../blocks/*php') as $file) {
      require_once($file);
    }
    add_action( 'init', function() {
      foreach (glob(__DIR__ . '/cpts/*php') as $file) {
        require_once($file);
      }
      foreach (glob(__DIR__ . '/cts/*php') as $f) {
        require_once($f);
      }
    });
    add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
      $filetype = wp_check_filetype( $filename, $mimes );
      return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
      ];
    }, 10, 4 );
    add_filter( 'upload_mimes', function($mimes ) {
      $mimes['svg'] = 'image/svg+xml';
      return $mimes;
    });
    add_action( 'admin_head', function() {
      print('<style type="text/css">
        .attachment-266x266, .thumbnail img {
          width: 100% !important;
          height: auto !important;
        }
        </style>');
    });
    add_action( 'admin_enqueue_scripts', function() {
      wp_enqueue_style( 'prefix-style', get_template_directory_uri() . '/admin-style.css');
    });
    add_filter( 'block_categories_all' , function($categories) {
      $categories[] = ['slug' => 'lagerblad', 'title' => 'lagerblad'];
      return $categories;
    });
    add_filter( 'allowed_block_types_all', function($bec, $ec) {
      if (!empty($ec->post)) {
        switch ($ec->post->post_type) {
          case 'products':
	          return [
      	      
        	  ];
          case 'person':
/*
foreach (\WP_Block_Type_Registry::get_instance()->get_all_registered() as $k=>$v) {
print($k . "\n");
}
die;
*/
            return [
              'core/list',
              'core/list-item',
              'core/paragraph',
            ];
          case 'page':
            return [
              'core/list',
              'core/list-item',
              'core/paragraph',
              'acf/image',
              'acf/column',
              'acf/sectionintro',
              'acf/servicegroupintro',
              'acf/servicegrouplist',
              'acf/networkpartnerrow',
            ];
          default:
            return [];
        }
      }
    }, 10, 2);
    add_theme_support('menus');
    

    add_action( 'customize_register' , function($c) {
      $c->add_section('lagerblad', [
        'title' => 'lagerblad',
        'priority' => 999,
        'capability' => 'edit_theme_options',
        'description' => __('lagerblad options', 'lagerblad'),
      ]);
      $c->add_setting('lagerblad-deploy-hook', [ 'type' => 'option', 'default' => '' ]);
      $c->add_control('lagerblad-deploy-hook', [
        'type' => 'text',
        'priority' => 10,
        'section' => 'lagerblad',
        'label' => __('Deploy hookin URL', 'lagerblad'),
        'description' => __('Netlify-sivuston deploy hookin URL-osoite', 'lagerblad'),
      ]);
    });
    add_action('save_post', function($pid) {
      $p = get_post($pid);
      if ($p->post_type === 'page') {
        $url = \get_option('lagerblad-deploy-hook');
        if (!empty($url)) {
          try {
            $r = \wp_remote_post($url);
            if (is_a($r, 'WP_Error')) {
              error_log("Deploy hook fail: " . print_r($r->get_error_messages(), true));
            } else if ($r['response']['code'] > 299) {
              error_log("Deploy hook fail: Code " . $r['response']['code']);
            } else {
              error_log("Deploy hook success");
            }
          } catch (\Exception $e) {
            error_log("Could not fire deploy hook at " . $url);
            error_log($e->getMessage());
          }
        }
      }
    });
  }


  public static function parseModules($str) {
    $q = new \WP_Query([
      'post_type' => 'attachment',
      'post_status' => 'any',
      'posts_per_page' => 999,
    ]);
    $atts = [];
    foreach ($q->posts as $p) {
      $atts[$p->ID] = wp_get_attachment_url($p->ID); // str_replace('http:', 'https:', $p->guid);
    }
    $arr = [];
    foreach (\parse_blocks($str) as $block) {
      if ($block['blockName'] === null) {
        continue;
      }
      $content = $block['innerHTML'];
      if (substr($block['blockName'], 0, 4) === 'acf/') {
        $block['attrs']['data'] = array_filter(
          $block['attrs']['data'],
          function($k) {
            return substr($k, 0, 1) !== '_';
          },
          ARRAY_FILTER_USE_KEY
        );
        foreach ($block['attrs']['data'] as $k=>&$v) {
          if (preg_match('/image$/', $k) && is_int($v)) {
            if (array_key_exists(intval($v), $atts)) {
              $v = $atts[intval($v)];
            } else {
              $v = null;
            }
          }
          if (preg_match('/url$/', $k) && strpos($v, \get_site_url()) === 0) {
            $v = \wp_make_link_relative($v);
          }
          if (preg_match('/video$/', $k)) {
            if (array_key_exists(intval($v), $atts)) {
              $v = $atts[intval($v)];
            } else {
              $v = null;
            }
          }
        }
      } else if (substr($block['blockName'], 0, 5) === 'core/') {
        $content = \serialize_blocks([$block]);
      }
      $arr[] = [
        'type' => $block['blockName'],
        'block' => $block,
        'attrs' => $block['attrs'],
        'content' => $content,
      ];
    }
    return $arr;
  }


  public static function getPosts() {
    $args = [
      'numberposts' => -1,
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderBy' => 'menu_order',
      'order' => 'ASC',
    ];
    $data = [];
    foreach (get_posts($args) as $p) {
      $fields = get_fields($p->ID);
      $data[] = [
        'title' => $p->post_title,
        'content' => $p->post_content,
        'excerpt' => $p->post_excerpt,
        'date' => $p->post_date,
        'fields' => $fields,
        'url' => \wp_make_link_relative(get_permalink($p)),
      ];
    }
    $res = new \WP_REST_Response($data);
    $res->set_status(200);
    return $res;
  }

  public static function getObjects() {
    global $wpdb;
    $args = [
      'numberposts' => -1,
      'post_type' => ['product','recipe','post'],
      'post_status' => 'publish',
      'orderBy' => 'menu_order',
      'order' => 'ASC',
    ];
    $data = ['byid' => [], 'taxbyid' => []];
    foreach (get_posts($args) as $p) {
      $pt = $p->post_type;
      if (!array_key_exists($pt, $data)) {
        $data[$pt] = [];
      }

      $fields = get_fields($p->ID);


      $data['byid'][$p->ID] = [
	      'id' => $p->ID,
        'title' => $p->post_title,
      	'content' => self::parseModules($p->post_content),
      	'date' => $p->post_date,
      	'fields' => $fields && count($fields) > 0 ? $fields : null,
      	'slug' => $p->post_name,
      	'url' => \wp_make_link_relative(get_permalink($p)),
      ];
      $data[$pt][] = $p->ID;
    }
    $data['category'] = [];
    $args = [
      'hide_empty' => false,
      'taxonomy' => 'category',
    ];
    foreach (get_terms($args) as $t) {
      $data['category'][] = $t->term_id;
      $data['taxbyid'][$t->term_id] = ['name' => $t->name, 'description' => $t->description, 'slug' => $t->slug];
    }
    $results = $wpdb->get_results('select t.term_id, tt.taxonomy, t.name, t.slug, tr.object_id from wp_term_taxonomy tt, wp_terms t, wp_term_relationships tr, wp_posts p where p.ID=tr.object_id and p.post_status=\'publish\' and tr.term_taxonomy_id=tt.term_taxonomy_id and tt.term_id=t.term_id and tt.taxonomy in (\'category\',\'product\')');
    foreach ($results as $r) {
      if (array_key_exists($r->object_id, $data['byid'])) {
        if (!array_key_exists('tax', $data['byid'][$r->object_id])) {
          $data['byid'][$r->object_id]['tax'] = [];
        }
        if (!array_key_exists($r->taxonomy, $data['byid'][$r->object_id]['tax'])) {
          $data['byid'][$r->object_id]['tax'][$r->taxonomy] = [];
        }
        $data['byid'][$r->object_id]['tax'][$r->taxonomy][] = ['name' => $r->name, 'slug' => $r->slug, 'id' => intval($r->term_id)];
      }
    }
    $res = new \WP_REST_Response($data);
    $res->set_status(200);
    return $res;
  }

  public static function getPages() {
    $home = intval(get_option('page_on_front'));
    $args = [
      'numberposts' => -1,
      'post_type' => 'page',
      'post_status' => 'publish',
      'orderBy' => 'menu_order',
      'order' => 'ASC',
    ];
    $data = [];
    foreach (get_posts($args) as $p) {
      $f = get_fields($p->ID);
      $tmp = [
        'title' => $p->post_title,
        'content' => self::parseModules($p->post_content),
        'path' => substr(\wp_make_link_relative(get_permalink($p)), 1, -1),
      ];
      if ($home == $p->ID) {
        $tmp['home'] = true;
      }
      if ($f) {
        $tmp['fields'] = $f;
      } else {
        $tmp['fields'] = [];
      }
      $data[] = $tmp;
    }
    $res = new \WP_REST_Response($data);
    $res->set_status(200);
    return $res;
  }

  /** All products endpoint  */

  public static function getProducts($query) {
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'order' => 'ASC',
        'posts_per_page' => isset($query['per_page']) ? intval($query['per_page']) : 10,
        'paged' => isset($query['page']) ? intval($query['page']) : 1,
    ];
    
    $offset = ($args['paged'] - 1) * $args['posts_per_page'];
    $args['offset'] = $offset;

    $products_query = new \WP_Query($args);

    

    $data = [];
    if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
            $products_query->the_post();
            $fields = get_fields(get_the_ID());
            $data[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'slug' => get_post_field('post_name'),
                'url' => \wp_make_link_relative(get_permalink()),
                'fields' => $fields && count($fields) > 0 ? $fields : null,
            ];
        }
  
        $total_pages = $products_query->max_num_pages;
    } else {
        $total_pages = 0;
    }

    wp_reset_postdata();
  
    $response_data = [
        'products' => $data,
        'totalProducts' => $products_query->found_posts,
        'currentPage' => intval($args['paged']),
        'totalPages' => $total_pages,
    ];

  
    return $response_data;
}

  /** this fuction to fetch the products acording to search and filttering  */

  public static function getSearchProducts($search_query) {
    $args = [
      'post_type' => 'product',
      'post_status' => 'publish',
      'order' => 'ASC',
      'posts_per_page' => isset($search_query['per_page']) ? intval($search_query['per_page']) : 10,
      'paged' => $search_query['page'],
    ];
  
    $search = $search_query['s'];
    $category = $search_query['category'];
    $type = $search_query['type'];
  
    $total_pages = 1;
  
    if (isset($search)) {
      $args['s'] = sanitize_text_field($search);
    }
  
    if (isset($category)) {
      $args['tax_query'][] = [
        [
          'taxonomy' => 'category',
          'field' => 'slug',
          'terms' => $category
        ]
      ];
    }
    if (isset($type)) {
      $args['tax_query'][] = [
        [
          'taxonomy' => 'producttype',
          'field' => 'slug',
          'terms' => $type
        ]
      ];
    }
  
    $offset = ($args['paged'] - 1) * $args['posts_per_page'];
    $args['offset'] = $offset;
  
    $query = new \WP_Query($args);
  
    $data = [];
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $fields = get_fields(get_the_ID());
        $data[] = [
          'id' => get_the_ID(),
          'title' => get_the_title(),
          'slug' => get_post_field('post_name'),
          'url' => \wp_make_link_relative(get_permalink()),
          'fields' => $fields && count($fields) > 0 ? $fields : null,
        ];
      }
  
      $total_pages = $query->max_num_pages;
    } else {
      $total_pages = 0;
    }
  
    wp_reset_postdata();
  
    $response_data = [
      'products' => $data,
      'totalProducts' => $query->found_posts,
      'currentPage' => intval($args['paged']),
      'totalPages' => $total_pages,
    ];

  
    return $response_data;
  }

  public static function getSearchRecipes($query) {
    $args = [
      'post_type' => 'recipe',
      'post_status' => 'publish',
      'order' => 'ASC',
      'posts_per_page' => isset($query['per_page']) ? intval($query['per_page']) : 10,
      'paged' => isset($query['page']) ? intval($query['page']) : 1,
  ];

    $search = $query['s'];
    $ingredient = $query['ingredient'];
  
    $total_pages = 1;
  
    if (isset($search)) {
      $args['s'] = sanitize_text_field($search);
    }
  
    if (isset($ingredient)) {
      $args['tax_query'][] = [
          'taxonomy' => 'ingredient',
          'field' => 'slug',
          'terms' => sanitize_text_field($ingredient)
      ];
    }
  
  
    $offset = ($args['paged'] - 1) * $args['posts_per_page'];
    $args['offset'] = $offset;
  
    $query = new \WP_Query($args);

  
    $data = [];
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $fields = get_fields(get_the_ID());
        $data[] = [
          'id' => get_the_ID(),
          'title' => get_the_title(),
          'slug' => get_post_field('post_name'),
          'url' => \wp_make_link_relative(get_permalink()),
          'fields' => $fields && count($fields) > 0 ? $fields : null,
        ];
      }
  
      $total_pages = $query->max_num_pages;
    } else {
      $total_pages = 0;
    }
  
    wp_reset_postdata();
  
    $response_data = [
      'recipes' => $data,
      'totalRecipes' => $query->found_posts,
      'currentPage' => intval($args['paged']),
      'totalPages' => $total_pages,
    ];

  
    return $response_data;
  }



 public static function getNavi() {
    $homePageId = intval(get_option('page_on_front'));
    $menus = [];

    foreach (\wp_get_nav_menus() as $menu) {
        $menuItemsById = [];

        // Build an array of menu items indexed by ID
        foreach (\wp_get_nav_menu_items($menu->term_id) as $menuItem) {
            $menuItemUrl = $menuItem->url;

            if (preg_match('/^https?:\/\/' . $_SERVER['HTTP_HOST'] . '/', $menuItemUrl)) {
                $menuItemUrl = substr(\wp_make_link_relative($menuItemUrl), 0, -1);
            }

            $menuItemData = [
                'title' => $menuItem->title,
                'url' => $menuItemUrl,
                'children' => [],
                'id' => $menuItem->ID,
                'parent' => $menuItem->menu_item_parent
            ];

            if (intval($menuItem->object_id) === $homePageId) {
                $menuItemData['index'] = true;
            }

            $menuItemsById[$menuItemData['id']] = $menuItemData;
        }
        // Build a tree-like structure of nested menu items
        foreach ($menuItemsById as &$menuItemData) {
            $parentId = intval($menuItemData['parent']);
            if ($parentId > 0 && array_key_exists($parentId, $menuItemsById)) {
                $menuItemsById[$parentId]['children'][] =& $menuItemData;
            }
        }
        // Find the root items (those without a parent) and add them to the menu array
        $menuItemsByParentId = [];
        foreach ($menuItemsById as &$menuItemData) {
            if (intval($menuItemData['parent']) === 0) {
                $menuItemsByParentId[] =& $menuItemData;
            }
        }
        $menus[$menu->name] = $menuItemsByParentId;
    }

    return $menus;
}
}
