<?php 
 
/*
Plugin Name: Advanced Search Block
Description: Adds a custom block pattern to the Gutenberg block editor.
Version: 1.0
Author: Hien
Author URI: https://learn.wordpress.org
*/
 
add_action('wp_enqueue_scripts','ava_test_init');

function ava_test_init() {
  wp_enqueue_script('jquery-js', 'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js', array());
    wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array());
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), true);
    wp_enqueue_style('jpages-css', 'https://cdn.jsdelivr.net/gh/luis-almeida/jPages@b6a51c90640c95d8ea4238f9196741eb9fb61531/css/jPages.css
', array(), true);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), true);
    wp_enqueue_script( 'main', plugins_url( '/src/main.js', __FILE__ ));
    wp_enqueue_style( 'style', plugins_url( '/src/style.css', __FILE__ ));
}

function pagination($pages = '', $range = 4) {
    $showitems = ($range * 2) + 1;
 
    global $paged;
    if(empty($paged)) $paged = 1;
 
    if($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages) {
            $pages = 1;
        }
    }
 
    if(1 != $pages) {
        echo "<div class='pagination'><span>Page " . $paged . " of " . $pages . "</span>";
        $base_link = trailingslashit(home_url());  // Lấy URL cơ bản của trang chủ, đảm bảo có dấu / ở cuối.
        
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='" . $base_link . "?paged=1'>&laquo; First</a>";
        if($paged > 1 && $showitems < $pages) echo "<a href='" . $base_link . "?paged=" . ($paged - 1) . "'>&lsaquo; Previous</a>";
 
        for ($i=1; $i <= $pages; $i++) {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . $base_link . "?paged=" . $i . "' class='inactive'>" . $i . "</a>";
            }
        }
 
        if ($paged < $pages && $showitems < $pages) echo "<a href='" . $base_link . "?paged=" . ($paged + 1) . "'>Next &rsaquo;</a>";
        if ($paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages) echo "<a href='" . $base_link . "?paged=" . $pages . "'>Last &raquo;</a>";
        echo "</div>";
    }
}

function my_custom_wp_block_patterns()
{   
     ob_start(); ?>
     <div class="container">
    <div class="form-search-block">
        <form>
          <div class="form-row row">
            <div class="form-group col-md-4">
              <label for="inputKeyword">Keyword</label>
              <input type="text" class="form-control" name="inputkeyword" id="inputkeyword" placeholder="Keyword">
            </div>
            <div class="form-group col-md-4">
              <label for="inputCategory">Category</label>
                <select id="selectCategory" name="select-category" class="form-control">
                    <option hidden value="">Choose...</option>
                    <?php $taxonomies = get_terms( array(
                        'taxonomy' => 'category',
                        'hide_empty' => false
                    ) );
                    if ( $terms ){
                    foreach ( $taxonomies as $taxonomy ):
                        echo '<option value="'.$taxonomy->term_id.'">'.$taxonomy->name.'</option>';
                    endforeach; 
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
          </div>
          <div class="form-group">
            <label for="inputAddress2">Tags</label>
            <div class="list-tags">
                <?php $tags = get_terms( array(
                    'taxonomy' => 'post_tag',
                    'hide_empty' => false
                ) );
                if ( $tags ){
                  foreach ( $tags as $tag ):
                      echo '<div class="item-tag" style="margin-right: 10px;display: inline-block;">';
                      echo '<div style="display: flex">';
                      echo '<input class="form-check-input" type="checkbox" id="'.$tag->term_id.'" value="'.$tag->term_id.'" />
                  <label style="margin-left: 5px;" for="'.$tag->term_id.'">'.$tag->name.'</label>';
                  echo '</div>';
                  echo '</div>';
                  endforeach; 
                } ?>
                <input type="hidden" name="tags" value="">
            </div>
          </div>
        </form>
    </div>
    <div class="list-post" id="itemContainer" >
        <?php
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $posts = new WP_Query(array(
              'post_type' => 'post',
              'posts_per_page' => 3,
              'post_status' => 'publish',
              'orderby' => 'date',
              'order' => 'DESC',
                'paged' => $paged,
          ));               
      if($posts->have_posts()) :
          while($posts->have_posts()) : ?>
              <div class="item-post">
                  <?php
                      $posts->the_post();
                      if(has_post_thumbnail(get_the_ID())) {
                          $img_item = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                      }
                  ?>
                  <div class="row">
                    <div class="thumb-post col-md-1">
                      <a href="<?php the_permalink(); ?>">
                          <img src="<?= $img_item ?>" alt="">
                      </a>
                    </div>
                      <div class="info-post col-md-11">
                          <a href="<?php the_permalink(); ?>">
                              <p class="hightlight-title"><?php the_title(); ?></p>
                          </a>
                          <p class="excerpt-title"><?php the_excerpt(); ?></p>
                      </div>
                  </div>
              </div> 
              <?php endwhile;
              if (function_exists("pagination")) {
                pagination($posts->max_num_pages);
              }
              wp_reset_query();
          endif;
          ?>
    </div>
</div>
<?php function search_ajax_block()
{
    ob_start();
    $key = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
    $category = isset($_REQUEST['category']) ? sanitize_text_field($_REQUEST['category']) : '';
    $tags = isset($_REQUEST['tags']) ? sanitize_text_field($_REQUEST['tags']) : '';
    $tags = stripslashes($tags);
    $tags_array = json_decode($tags);
    $tax_query = array(
        'relation' => 'AND'
    );
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    if( $category != "" ) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => array($category), 
        );
    };
    if( $tags != "" ) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $tags_array 
        );
    };
    
    $posts = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => 3,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      's' => $key, 
      'tax_query' => $tax_query,
      'paged' => $paged,
    )); 
    if($posts->have_posts()) :
          while($posts->have_posts()) :?>
              <div class="item-post">
                  <?php
                      $posts->the_post();
                      if(has_post_thumbnail(get_the_ID())) {
                          $img_item = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                      }
                  ?>
                  <div class="row">
                    <div class="thumb-post col-md-1">
                      <a href="<?php the_permalink(); ?>">
                          <img src="<?= $img_item ?>" alt="">
                      </a>
                    </div>
                      <div class="info-post col-md-11">
                          <a href="<?php the_permalink(); ?>">
                              <p class="hightlight-title"><?php the_title(); ?></p>
                          </a>
                          <p class="excerpt-title"><?php the_excerpt(); ?></p>
                      </div>
                  </div>
              </div> 
              <?php endwhile;
              if (function_exists("pagination")) {
                pagination($posts->max_num_pages);
              }
              wp_reset_query();
          else:
            echo 'không có bài viết nào';
          endif;
          ?>
    <?php 
    $return = ob_get_contents();
    ob_end_clean();
    echo json_encode(array(
        'content' => $return,
    ));
    exit;
}
add_action('wp_ajax_search_ajax_block', 'search_ajax_block');
add_action('wp_ajax_nopriv_search_ajax_block', 'search_ajax_block');

    $content = ob_get_clean();
    register_block_pattern(
        'my-patterns/my-custom-pattern',
        array(
            'title'       => __('Advanced Search Block', 'transparent-cover'),
 
            'description' => _x('Includes a cover block, two columns with headings and text, a separator and a single-column text block.', 'Block pattern description', 'page-intro-block'),
 
 
 
            'content'     => $content,
 
 
            'categories'  => array('gallery'),
        )
    );
}
add_action('init', 'my_custom_wp_block_patterns');