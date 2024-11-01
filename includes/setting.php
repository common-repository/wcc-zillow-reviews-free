<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['save_settig']) && isset($_POST['zillow_reviews_type']) && $_POST['zillow_reviews_type'] && isset($_POST['zillow_screenname']) && $_POST['zillow_screenname'] && isset($_POST['zillow_company_name'])){
        $post = array(
          "zillow_heading" => isset($_POST['zillow_heading']) ? sanitize_text_field($_POST['zillow_heading']) : "",
          "zillow_reviews_type" => isset($_POST['zillow_reviews_type']) ? sanitize_text_field($_POST['zillow_reviews_type']) : "",
          "zillow_screenname" => isset($_POST['zillow_screenname']) ? sanitize_text_field($_POST['zillow_screenname']) : "",
          "zillow_company_name" => isset($_POST['zillow_company_name']) ? sanitize_text_field($_POST['zillow_company_name']) : "",
          "zillow_plugin_status" => isset($_POST['zillow_plugin_status']) ? sanitize_text_field($_POST['zillow_plugin_status']) : "",
          "zillow_num_reviews" => isset($_POST['zillow_num_reviews']) ? sanitize_text_field($_POST['zillow_num_reviews']) : "",
          "zillow_num_reviews_type" => isset($_POST['zillow_num_reviews_type']) ? sanitize_text_field($_POST['zillow_num_reviews_type']) : "",
        );

        foreach ($post as $key => $value) {
          update_option($key,$value);
        }
      $_SESSION['e_msg'] = 'Setting Updated Successfully.';
    }
  }
?>
<?php $zillow_heading = get_option("zillow_heading"); ?>
<?php $zillow_reviews_type = get_option("zillow_reviews_type"); ?>
<?php $zillow_screenname = get_option("zillow_screenname"); ?>
<?php $zillow_company_name = get_option("zillow_company_name"); ?>
<?php $zillow_plugin_type = "paid"; ?>
<?php $zillow_plugin_status = get_option("zillow_plugin_status"); ?>
<?php $zillow_num_reviews = get_option("zillow_num_reviews"); ?>
<?php $zillow_num_reviews_type = get_option("zillow_num_reviews_type"); ?>
<?php 
  $zillow_review = array();
  if($zillow_screenname){
      $url = "http://crmalldata.com/api/api/zillow";
        
      $request = array(
        "zillow_reviews_type" => $zillow_reviews_type,
        "zillow_screenname" => $zillow_screenname,
        "zillow_company_name" => $zillow_company_name,
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

      if(isset($api_response['zillow_review']) && $api_response['zillow_review']){
        $zillow_review = $api_response['zillow_review'];
      }
  }
?>
<?php if(isset($_SESSION['e_msg']) && $_SESSION['e_msg']){ ?>
  <div id="setting-error-settings_updated" class="notice notice-success is-dismissible"> 
  <p><strong>Success : <?php echo esc_html($_SESSION['e_msg']); ?></strong></p>
</div>
<br/>
<?php unset($_SESSION['e_msg']); } ?>
<form action="<?php menu_page_url('snippet-menu-function') ?>" method="post" class="wpforms-form">
  <?php if($zillow_screenname && $zillow_reviews_type){ ?>
  <?php } ?>
  <div class="wcc-mt-3">
    <div class="tab">
      <div style="text-align: center; padding-bottom: 20px">
        <img src="<?php echo esc_url(plugins_url( '../img/zillow_logo.png', __FILE__ )) ?>" width="150px" title="Zillow">
        <h4 style="margin-bottom: 0px">Version 1.0.0</h4>
        <h4 style="margin-bottom: 0px">We Connect Code</h4>
      </div>
      <hr/>
      <div class="vb_tab_btn">
        <button title="General" type="button" class="tablinks" onclick="openCity(event, 'general_tab')" id="defaultOpen"><img class="vb_na_img" src="<?php echo esc_url(plugins_url( '../img/setting-lines.png', __FILE__ )) ?>"><img class="vb_a_img" src="<?php echo esc_url(plugins_url( '../img/setting-lines-h.svg', __FILE__ )) ?>"> General</button>
        <?php if($zillow_reviews_type && $zillow_screenname){ ?>
          <button title="My Reviews" type="button" class="tablinks" onclick="openCity(event, 'list_tab')"><img class="vb_na_img" src="<?php echo esc_url(plugins_url( '../img/list.svg', __FILE__ )) ?>" ><img class="vb_a_img" src="<?php echo esc_url(plugins_url( '../img/list-h.svg', __FILE__ )) ?>" > My Reviews</button>
        <?php } ?>
      </div>
    </div>
    <div id="general_tab" class="tabcontent">
       <table class="wp-list-table"  style="float: left;width: 100%"> 
          <tbody>
            <?php if($zillow_screenname && $zillow_reviews_type){ ?>
            <tr>
              <td width="200"><label class="form-label">Shortcode:</label></td>
              <td>
                <code class="realistic-marker-highlight">[zillow_review]</code>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td width="200"><label for="zillow_heading" class="form-label">Heading:</label></td>
              <td>
                <input type="text" id="zillow_heading" name="zillow_heading" value="<?php echo esc_attr( $zillow_heading ); ?>" class="fbrev-page-id form-control" placeholder="Heading" style='width: 100%'/>
              </td>
            </tr>
            <tr>
              <td width="200"><label class="form-label">Zillow Reviews Type:</label></td>
              <td>
                <div class="switch-field">
                  <input type="radio"  onclick="jQuery('.zillow_company_name').hide()" id="radio-three" name="zillow_reviews_type" value="profile_review" <?php echo !$zillow_reviews_type || $zillow_reviews_type == "profile_review" ? esc_attr("checked='checked'") : "" ?>/>
                  <label for="radio-three" title="Profile Review ">Profile Review </label>
                  <input type="radio"  onclick="jQuery('.zillow_company_name').hide()" id="radio-four" name="zillow_reviews_type" value="lender_review" <?php echo $zillow_reviews_type == "lender_review" ? esc_attr("checked='checked'") : "" ?>/>
                  <label for="radio-four" title="Lender Review">Lender Review</label>
                  <input type="radio"  onclick="jQuery('.zillow_company_name').show()" id="radio-five" name="zillow_reviews_type" value="institutional_company" <?php echo $zillow_reviews_type == "institutional_company" ? esc_attr("checked='checked'") : "" ?>/>
                  <label for="radio-five" title="Institutional Company">Institutional Company</label>
                </div>
              </td>
            </tr>
            <tr>
              <td width="200"><label class="form-label" for="zillow_screenname">Zillow Screenname:</label></td>
              <td>
                <input type="text" id="zillow_screenname" name="zillow_screenname" value="<?php echo esc_attr($zillow_screenname) ?>" class="fbrev-page-id form-control" placeholder="Zillow Screenname" style='width: 100%'/>
              </td>
            </tr>
            <tr class="zillow_company_name" style="<?php echo esc_attr($zillow_reviews_type == "institutional_company" ? "display: block'" : "display: none") ?>">
              <td width="200"><label class="form-label" for="zillow_company_name">Zillow Company Name:</label></td>
              <td>
                  <input type="text" id="zillow_company_name" name="zillow_company_name" value="<?php echo esc_attr($zillow_company_name) ?>" style='width: 100%' class="fbrev-page-id form-control" placeholder="Zillow Company Name " />
              </td>
            </tr>
            <tr>
              <td width="200"><label for="zillow_num_reviews" class="form-label">Number Of Reviews:</label></td>
              <td>
                <input type="Number" id="zillow_num_reviews" name="zillow_num_reviews" value="<?php echo esc_attr($zillow_num_reviews) ? $zillow_num_reviews : 10 ?>" class="fbrev-page-id form-control" placeholder="Number Of Reviews" style='width: 100%' max="10" min="1"/>
                <small>Maximum 10 Reviews</small>
              </td>
            </tr>
            <tr>
              <td width="200"><label for="zillow_num_reviews_type" class="form-label">Type:</label></td>
              <td>
                <select name="zillow_num_reviews_type" id="zillow_num_reviews_type">
                  <option <?php echo $zillow_num_reviews_type == "all" ? esc_attr("selected='selected'") : "" ?> value="all">All</option>
                  <option <?php echo $zillow_num_reviews_type == "5" ? esc_attr("selected='selected'") : "" ?> value="5">5 Star</option>
                  <option <?php echo $zillow_num_reviews_type == "4" ? esc_attr("selected='selected'") : "" ?> value="4">4-5 Star</option>
                </select>
              </td>
            </tr>
            <tr>
              <td width="200"><label class="form-label" for="zillow_plugin_status">Status:</label></td>
              <td>
                <select class="fbrev-page-id form-control" id="zillow_plugin_status" name="zillow_plugin_status">
                  <option value="1" <?php echo $zillow_plugin_status == 1 ? esc_attr("selected='selected'") : "" ?>>Active</option>
                  <option value="0" <?php echo $zillow_plugin_status == 0 ? esc_attr("selected='selected'") : "" ?>>Inactive</option>
                </select>
              </td>
            </tr>
            <tr>
              <td width="200"></td>
              <td>
                <input type="submit" name="save_settig" value="Save" title="Save" class="page-title-action btn">
              </td>
            </tr>
          </tbody>
        </table>      
    </div>
    <?php if($zillow_reviews_type && $zillow_screenname){ ?>
    <div id="list_tab" class="tabcontent">
      <table class="wp-list-table widefat fixed striped posts"  style="float: left;width: 100%">
        <thead>
          <tr>
            <td>Name</td>
            <td>Rating</td>
            <td>Text</td>
            <td>Description</td>
            <td>Date</td>
          </tr>
        </thead>
        <tbody id="the-list">
        <?php if($zillow_review){ ?>
        <?php foreach ($zillow_review as $key => $value) { ?>
          <tr>
            <td><a target="_blank" href="<?php echo esc_url( $value['link'] ); ?>"><?php echo esc_html( __( $value['name'], 'zillow_review' )) ?></a></td>
            <td><?php echo esc_html( __( $value['rating'], 'zillow_review' )) ?></td>
            <td><?php echo esc_html( __( $value['text'], 'zillow_review' )) ?></td>
            <td><?php echo esc_html( __( $value['description'], 'zillow_review' )) ?></td>
            <td><?php echo esc_html( __( $value['date'], 'zillow_review' )) ?></td>
          </tr>
        <?php } }else{ ?>
          <tr>
            <td colspan="5" align="center">No review Found</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <?php } ?>
    <div class="vb_clear_both"></div>
  </div>
</form>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
