<?php if($zillow_review){ ?>
<div class="vb_row">
  <div class="vb_size_50">
 	  <h4 class="vb_margin_0"><?php echo esc_html(__( $zillow_heading, 'zillow_review' )) ?></h4>
  </div>
  <div class="vb_size_50 vb_text_right">
    <a target="_blank" href="https://www.zillow.com/profile/<?php echo esc_html(__( str_replace(" ", "-", $zillow_screenname), 'zillow_review' )) ?>" class="vb_viewMoreReview" title="All Review">All Reviews</a>
  </div>
</div>
	<div class="zillioSlide">
    <?php foreach ($zillow_review as $key => $value) { 
      $rating =$value['rating']; 
      $per = 0;
      if($rating){
        $per = ($rating * 100) / 5; 
      }
      $i1 = 0; 
      $i2 = 0; 
      $i3 = 0; 
      $i4 = 0; 
      $i5 = 0; 
      if($rating > 4){
        $i1 = 10; 
        $i2 = 10; 
        $i3 = 10; 
        $i4 = 10; 
        $i5 = $rating == 5 ? 10 : 5;
      }else if($rating > 3){
         
        $i1 = 10; 
        $i2 = 10; 
        $i3 = 10; 
        $i4 = $rating == 4 ? 10 : 5;
        $i5 = 0;
      }else if($rating > 2){
        $i1 = 10; 
        $i2 = 10; 
        $i3 = $rating == 3 ? 10 : 5;
        $i4 = 0; 
        $i5 = 0;
      }else if($rating > 1){
        $i1 = 10; 
        $i2 = $rating == 2 ? 10 : 5;
        $i3 = 0; 
        $i4 = 0; 
        $i5 = 0;
      }else{
        $i1 = $rating == 1 ? 10 : 5;
        $i2 = 0; 
        $i3 = 0; 
        $i4 = 0; 
        $i5 = 0;
      }
      $rate = array(
          $i1,
          $i2,
          $i3,
          $i4,
          $i5
        );
      ?>
      <div>
	      <figure class="testimonial">
	        <div class="review_main">
	        	<div class="review_profile_img">
	        		<?php if($value['image']) { ?>
		              <img title="<?php echo esc_attr($value['name']) ?>" class="review-profile-image" src="<?php echo esc_url($value['image']) ?>" alt="User profile picture">
              <?php }else{ ?>
                  <img title="<?php echo esc_attr($value['name']) ?>" class="review-profile-image" src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'../img/placeholder-'.rand(1,3).'.png') ?>" alt="User profile picture">
              <?php } ?>
			        <div class="peopl">
			          <h6><?php echo esc_html(__( $value['name'], 'zillow_review' )) ?></h6>
			          <p class="indentity"><?php echo esc_html(__( $value['date'], 'zillow_review' )) ?></p>
			        </div>
	        	</div>
            <h3 class="reivew-list"> <?php foreach ($rate as $key => $v) { ?>
            <?php if($v == 10) { ?>
            <i title="Star" class="vb-star"></i>
            <?php }else if($v > 0) { ?>
            <i title="Star" class="vb-star-half-alt"></i>
            <?php }else { ?>
            <i title="Star" class="vb-star-empty"></i>
            <?php } ?>
            <?php } ?></h3>
	      	  <h6 class="review_text"><?php echo esc_html(__( $value['description'], 'zillow_review' )) ?></h6>
	        </div>
	      </figure>
	  </div>
  <?php } ?>
</div>

<div class="vb_text_center vb_review_footer">
  <strong>Zillow</strong> rating score:<strong><?php echo esc_html(__( $zillow_rating, 'zillow_review' )) ?></strong> of 5,based on <strong><?php echo esc_html(__( $zillow_total, 'zillow_review' )) ?> reviews</strong>.
</div>
<?php }else{ ?>
	<p class="text-center">No Reviews</p>
<?php } ?>