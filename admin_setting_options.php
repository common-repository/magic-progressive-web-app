<?php 
function mpwa_setting_page() {  
if( isset( $_POST[ 'mpwa_location_nonce_field' ] ) && wp_verify_nonce($_POST['mpwa_location_nonce_field'], 'mpwa_location_nonce') ){ 
// if user has access of manage options then user can access it where is admin or not.
	if ( current_user_can('manage_options') ) { 
	 if (isset($_POST['action']) && $_POST['action'] =='update') { 
	// wp_verify_nonce( $_POST['message-send'], 'custom-action' );
	 //exit;
		if (isset($_POST['action']) && $_POST['action'] =='update') {
		if(isset($_POST['application_name']))
		{
			if(!empty($_POST['application_name'])){
				$application_name = sanitize_text_field( $_POST['application_name'] ) == '' ? get_bloginfo( 'name' ) : sanitize_text_field( $_POST['application_name'] );
			}else {
				$application_name = get_bloginfo('name');
			}

		} else {
			$application_name = get_bloginfo('name');
		}

		if(isset($_POST['application_short_name']))
		{
			if(!empty($_POST['application_short_name'])){

				$application_short_name = sanitize_text_field( $_POST['application_short_name'] ) == '' ? get_bloginfo( 'name' ) : sanitize_text_field( $_POST['application_short_name'] );

			}else {

				$application_short_name = get_bloginfo('name');
			}
		} else {
			$application_short_name = get_bloginfo('name');
		}
		if(isset($_POST['application_description']))
		{
			if(!empty($_POST['application_description'])){
				$application_description = sanitize_text_field( $_POST['application_description'] ) == '' ? get_bloginfo( 'description' ) : sanitize_text_field( $_POST['application_description'] );
			}else {
				$application_description = get_bloginfo('description');
			}
		} else {
			$application_description = get_bloginfo('description');
		}
		if(isset($_POST['application_icon']))
		{
			if(!empty($_POST['application_icon'])){
				 
				$icon = MPWA_PATH_SRC.'public/images/logo.png';
				$application_icon = sanitize_text_field( $_POST['application_icon'] ) == '' ? $icon:  sanitize_text_field( $_POST['application_icon'] );
			}else { 
			    
				$application_icon = MPWA_PATH_SRC.'public/images/logo.png';
			}
		} else {
			$application_icon = MPWA_PATH_SRC.'public/images/logo.png';
		}
		if(isset($_POST['application_bgcolor']))
		{
			if(!empty($_POST['application_bgcolor'])){
				$application_bgcolor = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['application_bgcolor'] ) ? sanitize_text_field( $_POST['application_bgcolor'] ) : '#1e73be';
			}else {
				$application_bgcolor = '#1e73be';
			}
		} else {
			$application_bgcolor = '#1e73be';
		}
		if(isset($_POST['application_themecolor']))
		{
			if(!empty($_POST['application_themecolor'])){
				$application_themecolor = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['application_themecolor'] ) ? sanitize_text_field( $_POST['application_themecolor'] ) : '#1e73be';
			}else {
				$application_themecolor ='#1e73be';
			}
		} else {
			$application_themecolor = '#1e73be';
		}
		if(isset($_POST['application_orientation']))
		{
			if(!empty($_POST['application_orientation'])){
				$application_orientation = sanitize_text_field( $_POST['application_orientation'] ) == '' ? 'any' : sanitize_text_field( $_POST['application_orientation'] );
			}else {
				$application_orientation = 'any';
			}
		} else {
			$application_orientation = 'any';
		}

		//echo $application_icon;exit;

		//Update  options meta

        update_option('application_name', $application_name);
		update_option('application_short_name', $application_short_name);
		update_option('application_description', $application_description);
		update_option('application_icon', $application_icon);
		update_option('application_bgcolor', $application_bgcolor);
		update_option('application_themecolor', $application_themecolor);
		update_option('application_orientation', $application_orientation);


	} 
	
  
  $icon  = array (
			0 => array (
			  'src' => $application_icon,
			  'sizes' => '128x128',
			  'type' => 'image/png',
			),
			1 => array (
			  'src' => $application_icon,
			  'sizes' => '144x144',
			  'type' => 'image/png',
			),
			2 => array (
			  'src' => $application_icon,
			  'sizes' => '152x152',
			  'type' => 'image/png',
			),
			3 => array (
			  'src' => $application_icon,
			  'sizes' => '192x192',
			  'type' => 'image/png',
			),
			4 => array (
			  'src' => $application_icon,
			  'sizes' => '256x256',
			  'type' => 'image/png',
			),
		  );
	$manifest 						= array();
	$manifest['name']				= $application_name;
	$manifest['short_name']			= $application_short_name;
	$manifest['description'] 		= $application_description;
	$manifest['icons']				= $icon;
	$manifest['background_color']	= $application_bgcolor;
	$manifest['theme_color']		= $application_themecolor;
	$manifest['display']			= 'standalone';
	$manifest['orientation']		= $application_orientation;
	$manifest['start_url']			= home_url().'\/?utm_source=launcher';
	//$manifest['scope']				= '';
	$manifest_write_str	 =  json_encode( $manifest );
	
	$manifest_write_str	 =  json_encode( $manifest );
	$my_file = MPWA_PATH_ABS.'manifest.json';

    $handle = @fopen($my_file, 'w');
	fwrite($handle, $manifest_write_str); 
	fclose($handle); chmod($my_file, 0777);
	}  
	}  
} 
?>
<div>
<h2>Magic MPWA Options</h2>
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<?php  wp_nonce_field('mpwa_location_nonce', 'mpwa_location_nonce_field');?>

<table class="form-table">
	<tr>
		<th scope="row">Application Name</th><td>	
			<fieldset>
		        <input name="application_name" type="text" id="application_name"
				value="<?php echo get_option('application_name'); ?>" />
				<p class="description">
					 Display the full name of the application. <code>12</code> characters or less.</p>
	</fieldset>

	</td>
	</tr>
	<tr>
		<th>Application Short Name</th><td>	
			<fieldset>
		        <input name="application_short_name" type="text" id="application_short_name"
				value="<?php echo get_option('application_short_name'); ?>" />
				<p class="description">
					Display the full name of the application. <code>12</code> characters or less.</p>
	</fieldset>
	</td>
	</tr>
	<tr>
		<th scope="row">Description</th>
		<td>	
			<fieldset>
		        <input name="application_description" type="text" id="application_description"
				value="<?php echo get_option('application_description'); ?>" />
				<p class="description">A brief description of what your app.</p>
		
			</fieldset>

		</td>
	</tr>
	<tr>
		<th scope="row">Application Icon</th>
		<td>	
		<fieldset>
<!-- Application Icon -->
			<input type="text" name="application_icon" id="application_icon" class="pwa-icon regular-text"  value="<?php echo get_option('application_icon');?>">
			<button type="button" class="button pwa-icon-upload" data-editor="content">
			<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> Choose Icon	</button>

			<p class="description">
				This will be the icon of your app when installed on the phone. Must be a <code>PNG</code> image exactly <code>192x192</code> in size.	</p>
		</fieldset>
		</td>
	</tr>
    <tr>
		<th scope="row">Background Color</th>
		<td>
			<fieldset>
				<input type="text" class="color-picker" name="application_bgcolor" id='application_bgcolor' value="<?php echo get_option(application_bgcolor); ?>" />
				<p class="description">
				Background color of the splash screen.	</p>
			</fieldset>
		</td>
	</tr>
	<tr>
		<th scope="row">Theme Color</th>
		<td>
			<fieldset>
				<input type="text" class="color-picker" name="application_themecolor" id='application_themecolor' value="<?php echo get_option('application_themecolor'); ?>" />
				<p class="description">
				Theme color is used on supported devices to tint the UI elements of the browser and app switcher.</p>
			</fieldset>
		</td>
	</tr>
	<tr>
		<th scope="row">Orientation</th>
		<td>
			<fieldset>
			<?php $app_orientation = get_option('application_orientation'); ?>
				<select name="application_orientation" id="application_orientation">
				<option value="any" selected="selected">Follow Device Orientation</option>
				<option value="portrait" <?php echo ($app_orientation == 'portrait')?'selected':'';?>>Portrait</option>
				<option value="landscape" <?php echo ($app_orientation == 'landscape')?'selected':'';?>>Landscape</option>
		</select>
		<p class="description">
		Set the orientation of your app. When set to <code>Follow Device Orientation</code> your app will auto rotate as the device is rotated.</p>
			</fieldset>
		</td>
	</tr>
</table>

<input type="hidden" name="action" value="update" />

<p>
<input type="submit" class="button button-primary"value="<?php _e('Save Settings') ?>" />
</p>

<h2>MPWA Status</h2>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">Manifest</th>
			<td>
			<?php if (file_exists(MPWA_PATH_ABS.'manifest.json'))
			{ ?>
			
			<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> Manifest generated successfully. </p><?php } else { ?><p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span>Manifest not generated successfully.</p><?php } ?> </td>
		</tr>
		<tr><th scope="row">Service Worker</th><td>
		<?php $FileName = ABSPATH.'service-worker.js';
		if (file_exists($FileName) )
		{ ?>
		<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> Service worker generated successfully.</p><?php } else { ?><p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span>Service worker not generated successfully.</p><?php } ?></td></tr>
		<tr><th scope="row">HTTPS</th>
		<td>
		<?php if ( is_ssl() ) {
		
		printf( '<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> ' . __( 'Your website is served over HTTPS.', 'super-progressive-web-apps' ) . '</p>' );
	} else {
		
		printf( '<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span> ' . __( 'Progressive Web Apps require that your website is served over HTTPS. Please contact your host to add a SSL certificate to your domain.', 'super-progressive-web-apps' ) . '</p>' );
	}?></td></tr>
		</tbody>
	</table>

</form>	
</div>
<script>
(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-picker').wpColorPicker();
    });
     
})( jQuery ); </script>
<?php  } ?>
