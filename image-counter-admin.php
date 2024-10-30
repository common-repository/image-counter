<div class="wrap">
<div id="icon-options-general" class="icon32"></div><h2><?php _e('Image Counter', 'image-counter'); ?></h2>
<?php
  if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	if ($_POST['ic_submit']=="1") //если вызвали метод Post
	{    
		function trim_r($array) {
			if (is_string($array)) {
				return trim($array);
			} else if (!is_array($array)) {
				return '';
			}
			$keys = array_keys($array);
			for ($i=0; $i<count($keys); $i++) {
				$key = $keys[$i];
				if ( is_array($array[$key]) ) {
				$array[$key] = trim_r($array[$key]);
				} else if ( is_string($array[$key]) ) {
				$array[$key] = trim($array[$key]);
				}
			}	
			return $array;
		}
		function array_remove_empty($arr){
			$narr = array();
			while(list($key, $val) = each($arr)){
				if (is_array($val)){
					$val = array_remove_empty($val);
					// does the result array contain anything?
					if (count($val)!=0){
						// yes :-)
						$narr[$key] = $val;
					}
				}
				else {
					if (trim($val) != ""){
						$narr[$key] = $val;
					}
				}
			}
			unset($arr);
			return $narr;
		}


		$options['blacklist'] = array_remove_empty(trim_r(explode("\n", $_POST['ic_blacklist'])));
		$options['css'] = $_POST['ic_css'];
		$options['limit'] = $_POST['ic_limit'];
		$options['before'] = $_POST['ic_before'];
		$options['after'] = $_POST['ic_after'];
		
		update_option('image_counter', $options);
		?>
		<div class="updated"><p><strong><?php _e('Plugin settings were successfully updated!', 'image-counter'); ?></strong></p></div>
    <?php
	} 
  $options = image_counter_options();
	$plugin_dir = get_bloginfo('wpurl') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/';
	?>

	<form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
	<div id="poststuff" class="ui-sortable">
  <div class="postbox opened">
  <h3><?php _e('General settings', 'image-counter'); ?></h3>
  <div class="inside">
  <p>
		<strong>Please consider donating 10 cents if you like this plugin! :)</strong>	</p>
		<p>
	<a href="http://goo.gl/lL28M"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="donate" /></a>    
	</p>
  <div><p><?php _e( 'If you want to switch the counter off in a specific post, just add a custom field "image-counter" with the "off" value to the post.', 'image-counter' ); ?></p>
  <p><?php _e( 'If you want a certain image to be skipped by a counter, add a "no-counter" class to it or add it\'s name to ignore list below.', 'image-counter' ); ?></p></div>
  <table class="form-table">
  <tbody>
  <tr valign="top">
  
  <th scope="row"><?php _e( 'Do not add counter if the post has less than', 'image-counter' ); ?> </th>
  <td>
  <input name="ic_limit" type="text" id="ic_limit" class="small-text" value="<?php echo $options['limit']; ?>" /> <?php _e( 'images', 'image-counter' ); ?></td>
  </tr>
  <tr valign="top">
  
  <th scope="row"><?php _e( 'Add text before image number', 'image-counter' ); ?> </th>
  <td>
  <input name="ic_before" type="text" id="ic_before" value="<?php echo $options['before']; ?>" /></td>
  </tr>
 <tr valign="top">
  
  <th scope="row"><?php _e( 'Add text after image number', 'image-counter' ); ?> </th>
  <td>
  <input name="ic_after" type="text" id="ic_after" value="<?php echo $options['after']; ?>" /></td>
  </tr>
   
   <tr valign="top">
  
  <th scope="row"><?php _e( 'Ignore images with these keywords in their url (each in a new line):', 'image-counter' ); ?> </th>
  <td>
  <textarea  cols="50" rows="10" name="ic_blacklist" type="text" id="ic_blacklist" ><?php echo implode("\n",$options['blacklist']); ?></textarea></td>
  </tr>
  <tr valign="top">
  
  <th scope="row"><?php _e( 'Include plugin\'s CSS for styling the counter', 'image-counter' ); ?> </th>
  <td>
  <input name="ic_css" type="checkbox" id="ic_css" <?php if ($options['css'] == 'on') echo 'checked="checked"'; ?> /></td>
  </tr>
  <tr valign="top">
  
  <th scope="row"><?php _e( 'If you want to style your counter yourself, you can copy this css in to your theme\'s css file and edit it.', 'image-counter' ); ?></th>
  <td><pre style="overflow:auto; width:500px; height:200px;" >span.image {
	position:relative; 
	display:inline-block;
	}
span.image .image-count {
	display:none; /* delete or comment this line to make the counter visible always (not only on hover)  */
	position:absolute;
	top:0; /* change this line to bottom:0; if you want the counter to appear in the bottom of the image  */
	left:0; /* change this line to right:0; if you want the counter to appear on the right side of the image  */
	margin:10px;
	padding:3px 8px;
	background-color:white;
	color:black;
	font-size: 0.8em;
	font-weight: bold;	
	border-radius: 5px;
	box-shadow: 1px 1px 2px black;
	moz-border-radius: 5px;
	moz-box-shadow: 1px 1px 2px black;
	webkit-border-radius: 5px;
	webkit-box-shadow: 1px 1px 2px black;
	filter: alpha(opacity=70);
	ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=70)";
	opacity: 0.70;}
span.image:hover .image-count {
	display:block;
	}
</pre>
</td>
  </tr>
  
  

  </tbody></table>
  
  <input type="hidden" name="ic_submit" value="1" />
  <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Update settings', 'image-counter'); ?>"></p>

  </div>
  </div>
  </div>
  </form>
  
</div>  