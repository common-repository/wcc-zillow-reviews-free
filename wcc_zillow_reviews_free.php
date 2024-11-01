<?php
/**
* Plugin Name: WCC Zillow Reviews - Free
* Description: Zillow Reviews Free Plugin by We Connect Code.
* Version: 1.1.0
* Author: WeConnectCode
**/
global $zillow_review_db_version;
$zillow_review_db_version = '1.1.0';
register_activation_hook( __FILE__, 'zillow_review_plugin_install' );
register_deactivation_hook( __FILE__, "zillow_plugin_deactivated");




function zillow_plugin_update_db_check() {
    global $zillow_review_db_version;
    if ( get_site_option( 'zillow_review_db_version' ) != $zillow_review_db_version ) {
        zillow_review_plugin_install();
    }
}

function zillow_review_plugin_install() {
	global $wpdb;
	global $zillow_review_db_version;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	add_option( 'zillow_review_db_version', $zillow_review_db_version );
}
function zillow_plugin_deactivated()
{
	global $wpdb;
	delete_site_option( 'zillow_review_db_version' );
	delete_site_option( 'zillow_heading' );
	delete_site_option( 'zillow_reviews_type' );
	delete_site_option( 'zillow_screenname' );
	delete_site_option( 'zillow_company_name' );
	delete_site_option( 'zillow_plugin_status' );
	delete_site_option( 'zillow_num_reviews' );
	delete_site_option( 'zillow_num_reviews_type' );
	delete_site_option( 'zillow_total_cache' );
	delete_site_option( 'zillow_rating_cache' );
	delete_site_option( 'zillow_review_cache' );
}
add_action( 'plugins_loaded', 'zillow_plugin_update_db_check' );
add_action('admin_menu', 'zillow_review_main_menu');


add_action( 'wp_footer', 'zillow_footer_scripts' );
function zillow_footer_scripts(){  ?>
  <script type="text/javascript">
  	jQuery(document).ready(function () {
  		jQuery.each(jQuery('.zillioSlide'),function(){
			 jQuery(this).slick({
				 slidesToShow: 3,
				 slidesToScroll: 1,
				 autoplay: true,
    			
    			autoplaySpeed: 4000,
				 nextArrow:'<button type="button" title="Next" data-role="none" class="slick-next slick-arrow" aria-label="Next" role="button" style="display: block;">Next</button>',
				 prevArrow:'<button type="button" title="Previous" data-role="none" class="slick-prev slick-arrow" aria-label="Previous" role="button" style="display: block;">Previous</button>',
				 responsive: [ {
				            breakpoint: 1025,
				            settings: {
				                slidesToShow: 2,
				                slidesToScroll: 2,
				            }
				        },
				        {
				            breakpoint: 600,
				            settings: {
				                slidesToShow: 1,
				                slidesToScroll: 1
				            }
				        },
				]
			 });
  		});
  		jQuery(window).resize(function () {
		    jQuery('.js-slider').not('.slick-initialized').slick('resize');
		});
		jQuery(window).on('orientationchange', function () {
		    jQuery('.js-slider').not('.slick-initialized').slick('resize');
		});
  		jQuery(document).delegate(".ZillowviewMore","click",function(){
  			jQuery(".vb_review_text").html(jQuery(this).parents(".review_main")[0].outerHTML);
  			setTimeout(function(){ jQuery(".vb_review_modal").css("bottom","0"); }, 100);
  			
  		})
  		jQuery(document).delegate(".vb_review_modal_close","click",function(){
  			setTimeout(function(){ jQuery(".vb_review_modal").css("bottom","-400px"); }, 100);
  		})
  		jQuery(document).on('keyup', function(e) {
		  if (e.key == "Escape") {
  			setTimeout(function(){ jQuery(".vb_review_modal").css("bottom","-400px"); }, 100);
		  }
		});
	});
  </script>
  <?php
}
	
function zillow_review_main_menu(){
    add_menu_page( 'WCC Zillow Reviews - Free', 'WCC Zillow Reviews - Free', 'manage_options', 'zillow_review-menu-function', 'zillow_review_menu_fun' , plugins_url( 'img/zillow-Logo.png' ,__FILE__));
}
function zillow_review_menu_fun(){
     require_once(plugin_dir_path( __FILE__ ) .'includes/setting.php');
}
add_action( 'wp_ajax_nopriv_get_snippets', "get_snippets_fun" );
add_action( 'wp_ajax_get_snippets', "get_snippets_fun" );

add_action( 'wp_ajax_nopriv_get_snippet_subcategory', "get_snippet_subcategory_fun" );
add_action( 'wp_ajax_get_snippet_subcategory', "get_snippet_subcategory_fun" );

add_action( 'wp_ajax_nopriv_get_snippets_file', "get_snippets_file_fun" );
add_action( 'wp_ajax_get_snippets_file', "get_snippets_file_fun" );

add_action( 'wp_ajax_nopriv_get_snippets_file_data', "get_snippets_file_data_fun" );
add_action( 'wp_ajax_get_snippets_file_data', "get_snippets_file_data_fun" );

function wcc_zillow_review_admin_enqueue($hook_suffix) {
    if($hook_suffix == 'toplevel_page_zillow_review-menu-function') {
        wp_register_style('wcc_zillow_review_snippet_css', plugins_url('style.css',__FILE__ ));
    	wp_enqueue_style('wcc_zillow_review_snippet_css');
    }
}
add_action('admin_enqueue_scripts', 'wcc_zillow_review_admin_enqueue');

function zillow_snippet_js(){
    wp_register_style('zillow_snippet_fa_css', plugins_url('css/all.min.css',__FILE__ ));
    wp_enqueue_style('zillow_snippet_fa_css');;
    wp_register_style('zillow_snippet_main_css', plugins_url('css/main.css',__FILE__ ));
    wp_enqueue_style('zillow_snippet_main_css');;
    wp_enqueue_script( 'slick_js', plugins_url( 'js/slick.min.js', __FILE__ ));
}

add_action('wp_enqueue_scripts', 'zillow_snippet_js');



add_shortcode( 'zillow_review', 'zillow_review_func' );
function zillow_review_func() {
	$zillow_plugin_status = get_option("zillow_plugin_status");
	if($zillow_plugin_status){
		$zillow_reviews_type = get_option("zillow_reviews_type");
		$zillow_screenname = get_option("zillow_screenname");
		$zillow_company_name = get_option("zillow_company_name");
		$zillow_zws_id = get_option("zillow_zws_id");
		$zillow_partner_id = get_option("zillow_partner_id");
		//$zillow_plugin_type = get_option("zillow_plugin_type");
		$zillow_plugin_type = "paid";
		$zillow_num_reviews = get_option("zillow_num_reviews");
		$zillow_num_reviews_type = get_option("zillow_num_reviews_type");
		$zillow_review_cache = get_option("zillow_review_cache");
		$zillow_total_cache = get_option("zillow_total_cache");
		$zillow_rating_cache = get_option("zillow_rating_cache");

		$zillow_review = array();
		$zillow_total = 0;
		$zillow_rating = 0;
		if($zillow_reviews_type && $zillow_screenname){
			if($zillow_screenname){
				$url = "http://crmalldata.com/api/api/zillow";

				$request = array(
					"zillow_reviews_type" => $zillow_reviews_type,
					"zillow_screenname" => $zillow_screenname,
					"zillow_company_name" => $zillow_company_name,
					"zillow_zws_id" => $zillow_zws_id,
					"zillow_partner_id" => $zillow_partner_id,
					"zillow_num_reviews" => $zillow_num_reviews,
					"zillow_num_reviews_type" => $zillow_num_reviews_type,
					"zillow_plugin_type" => $zillow_plugin_type,
				);

				$api_response = wp_remote_post($url,array(
					    'method'      => 'POST',
					    'timeout'     => 45,
					    'redirection' => 5,
					    'httpversion' => '1.0',
					    'blocking'    => true,
					    'headers'     => array(),
					    'body'        => $request,
					    'cookies'     => array()
				));
				$api_response = isset($api_response['body']) && $api_response['body'] ? json_decode($api_response['body'],1) : array();
				if(isset($api_response['zillow_total'])){
					$zillow_total = $api_response['zillow_total'];
					update_option("zillow_total_cache",sanitize_text_field($zillow_total));				
				}else{
					$zillow_total = $zillow_total_cache;
				}
				if(isset($api_response['zillow_rating'])){
					$zillow_rating = $api_response['zillow_rating'];
					update_option("zillow_rating_cache",sanitize_text_field($zillow_rating));
				}else{
					$zillow_rating = $zillow_rating_cache;
				}
				if(isset($api_response['zillow_review'])){
					$zillow_review = $api_response['zillow_review'];
					$save_zillow_review = array();
					foreach ($zillow_review as $key => $value) {
						$save_zillow_review[] = array(
							"name" => isset($value['name']) ? sanitize_text_field($value['name']) : "",
							"link" => isset($value['link']) ? sanitize_text_field($value['link']) : "",
							"image" => isset($value['image']) ? sanitize_text_field($value['image']) : "",
							"rating" => isset($value['rating']) ? sanitize_text_field($value['rating']) : "",
							"text" => isset($value['text']) ? sanitize_text_field($value['text']) : "",
							"description" => isset($value['description']) ? sanitize_textarea_field($value['description']) : "",
							"date" => isset($value['date']) ? sanitize_text_field($value['date']) : "",
						);
					}
					update_option("zillow_review_cache",$save_zillow_review);
				}else if($zillow_review_cache){
					$zillow_review = $zillow_review_cache;
				}
			}
			$zillow_heading = get_option("zillow_heading");		
			require_once(plugin_dir_path( __FILE__ ) .'includes/reviews.php');
		}else{
			echo esc_html(__( "Review Not Available.", 'zillow_review' ));
		}
    }
}