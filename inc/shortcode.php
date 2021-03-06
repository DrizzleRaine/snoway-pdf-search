<?php

function snoway_pdf_search_func( $atts ) {

  global $pluginSlug;

  wp_enqueue_style($pluginSlug . '-css');
  wp_enqueue_script('pdf-js');
  wp_localize_script( $pluginSlug . '-js', 'wp_data', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'plugin_slug' => $pluginSlug,
  ));
  wp_enqueue_script($pluginSlug . '-js');

  $html = '<div id="' . $pluginSlug . '">';
    $html .= '<noscript>The PDF Search requires JavaScript to be enabled.</noscript>';

    // loading
    $html .= '<div id="loading" class="progress-line"></div>';

    // Search UI
    $html .= '<form id="' . $pluginSlug . '-form">';
      $html .= '<input id="search-text" name="search-text" type="text" placeholder="Search ..." />';
      // Taxonomies fields
      $taxonomies = get_object_taxonomies('manuals', 'objects');
      foreach ($taxonomies as $tax):
        $name = $tax->name;
        $label = $tax->label;
        // skip product_names
        if ($name === 'product_name'):
          continue;
        endif;
        $html .= '<select id="' . $name . '" name="' . $name . '">';
          $html .= '<option value="">All ' . $label . '</option>';
          $terms = get_terms(array(
            'taxonomy' => $name,
            'hide_empty' => true
          ));
          foreach ($terms as $term):
            $html .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
          endforeach;
        $html .= '</select>';
      endforeach;
      $html .= '<button type="submit">Search</button>';
      $html .= '<button id="' . $pluginSlug . '-reset" type="button">🔄 Reset</button>';
    $html .= '</form>';

    // Results stats + list
    $html .= '<div id="' . $pluginSlug . '-results-stats"></div>';
    $html .= '<ul id="' . $pluginSlug . '-results"></ul>';

  $html .= '</div>';

  return $html;
}

add_shortcode( $pluginSlug, 'snoway_pdf_search_func' );

?>
