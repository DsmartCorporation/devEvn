<?php /* Template Name: Test */ 
get_header(); 


?>
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
					foreach ( $taxonomies as $taxonomy ):
						echo '<option value="'.$taxonomy->term_id.'">'.$taxonomy->name.'</option>';
					endforeach; ?>
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
				foreach ( $tags as $tag ):
					echo '<div class="item-tag" style="margin-right: 10px;display: inline-block;">';
					echo '<input class="form-check-input" type="checkbox" id="'.$tag->term_id.'" value="'.$tag->term_id.'" />
		    	<label for="'.$tag->term_id.'">'.$tag->name.'</label>';
		    	echo '</div>';
				endforeach; ?>
				<input type="hidden" name="tags" value="">
		    </div>
		  </div>
		</form>
	</div>
	<div class="list-post" id="itemContainer" >
		<?php
    	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
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
	                  }else {
	                      $img_item = get_stylesheet_directory_uri() . '/src/no-image.jpg';
	                  }
	              ?>
	              <div class="row">
	              	<div class="thumb-post col-md-1">
	                  <a href="<?php the_permalink(); ?>">
	                      <img src="<?= crop_img(64,64,$img_item) ?>" alt="">
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
	          wp_pagenavi(array( 'query' => $posts));
	          wp_reset_query();
	      endif;
	      ?>
	</div>
</div>
<script>
	 jQuery(document).ready(function($){  
	    $('.list-tags input').on('change', function() {
	        var selectedTags = $('.list-tags input:checked').map(function() {
	            return this.value;
	        }).get();
	        $('input[name="tags"]').val(JSON.stringify(selectedTags));
	    });
	    $('.form-search-block button').click(function(e) {
	    	e.preventDefault();
	    	var formData = new FormData();
		    formData.append("action", "search_ajax");
		    formData.append("keyword", $('input[name="inputkeyword"]').val());
		    formData.append("category", $('select[name="select-category"] option:checked').val());
		    formData.append("tags", $('input[name="tags"]').val());
		    $.ajax({
		      url: "/wp-admin/admin-ajax.php",
		      type: "POST",
		      dataType: "json",
		      contentType: false,
		      processData: false,
		      data: formData,
		      beforeSend: function () {},
		      success: function (data) {
	        	$('.list-post').html(data.content);
		      },
		      error: function () {},
		    });
	    });

	});
</script>
<?php
get_footer();