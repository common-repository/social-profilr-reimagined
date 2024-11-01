<?php
/*
 * Plugin Name: Social Profilr Reimagined
 * Plugin URI: http://asif.im/spi
 * Description: Adding All Your Social Profile In WordPress Site Was Never Been This Easy.
 * Version: 2.2.1
 * License: GPL2+
 * Author: M Asif Rahman
 * Author URI: http://asif.im
 * Text Domain: social-profilr-reimagined
 * Min WP Version: 2.5.0
 * Max WP Version: 4.1.0
 */

//-------------------------------------------------------------

	// Plugin class
	if (!class_exists("SocialprofilrClass")) {
		class SocialprofilrClass {

			//-----------------------------------------
			// Options
			//-----------------------------------------
			var $optionsName = 'SocialprofilrOptions';

			var $optionsDefaults = array(
				'layout' => 'vertical',
				'icons' => '16',
				'title' => 'Social Profilr Reimagined',
				'description' => 'Follow me in these Social Networks'
			);

			//-----------------------------------------
			// Paths
			//-----------------------------------------
			var $pluginPath = '';

			//-----------------------------------------
			// Options page
			//-----------------------------------------
			var $optionsPageTitle = 'Social Profilr Reimagined';
			var $optionsMenuTitle = 'Social Profilr Reimagined';

			//-----------------------------------------
			// Links
			//-----------------------------------------
			var $links = array(
				'twitter' => array(
					'site_name' => "twitter",
					'site_title' => "Twitter",
					'icon' => "tw",
					'link' => "http://www.twitter.com/%s"	
				),	
				'facebook' => array(
					'site_name' => "facebook",
					'site_title' => "Facebook",
					'icon' => "fb",
					'link' => "http://www.facebook.com/%s"
				),	
				'google' => array(
					'site_name' => "google",
					'site_title' => "Google+",
					'icon' => "gp",
					'link' => "https://plus.google.com/u/0/%s/posts"
				),	
				'linkedin' => array(
					'site_name' => "linkedin",
					'site_title' => "LinkedIn",
					'icon' => "li",
					'link' => "http://www.linkedin.com/in/%s"
				),
				'flickr' => array(
					'site_name' => "flickr",
					'site_title' => "Flickr",
					'icon' => "fl",
					'link' => "http://www.flickr.com/photos/%s"
				),	
				'youtube' => array(
					'site_name' => "youtube",
					'site_title' => "YouTube",
					'icon' => "yt",
					'link' => "http://www.youtube.com/%s"
				),																								
				'myspace' => array(
					'site_name' => "myspace",
					'site_title' => "My Space",
					'icon' => "ms",
					'link' => "http://www.myspace.com/%s"
				),
				'stumbleupon' => array(
					'site_name' => "stumbleupon",
					'site_title' => "StumbleUpon",
					'icon' => "su",
					'link' => "http://www.stumbleupon.com/stumbler/%s"
				),	
				'technorati' => array(
					'site_name' => "technorati",
					'site_title' => "Technorati",
					'icon' => "ti",
					'link' => "http://technorati.com/people/technorati/%s"
				),							
				'friendster' => array(
					'site_name' => "friendster",
					'site_title' => "Friendster",
					'icon' => "fr",
					'link' => "http://friendster.com/profiles/%s"
				),
				'bebo' => array(
					'site_name' => "bebo",
					'site_title' => "Bebo",
					'icon' => "be",
					'link' => "http://www.bebo.com/%s"
				),
				'friendfeed' => array(
					'site_name' => "friendfeed",
					'site_title' => "FriendFeed",
					'icon' => "ff",
					'link' => "http://friendfeed.com/%s"
				)
			);


			//-----------------------------------------
			function SocialprofilrClass() {
				$this->pluginPath = get_option('siteurl') . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__));
			}

			function activate() {
				$this->getOptions();
			}

			//-----------------------------------------

			function getOptions() {
				$options = $this->optionsDefaults;

				foreach($this->links as $link) {
					$options[$link['site_name'].'_enable'] = 0;
				}


				$o = get_option($this->optionsName);

				if (!empty($o)) {
					foreach($o as $key => $value) {
						$options[$key] = $value;
					}
				}

				update_option($this->optionsName, $options);

				return $options;
			}

			//-----------------------------------------

			function generateWidget() {
				$options = $this->getOptions();

				$widgetLinks = array();
				foreach ($this->links as $link) {
					
					if ($options[$link['site_name'].'_enable'] == 1) {
						$id = $options[$link['site_name'].'_id'];
						$link['link'] = sprintf($link['link'], urlencode($id));
						$widgetLinks[$link['site_name']] = $link;
					}
				}

				$out = '';
				
				if (sizeof($widgetLinks) > 0) {
					switch ($options['layout']) {
						case 'horizontal':
							$out = $this->renderHorizontal($widgetLinks);
							break;
						case 'vertical':
							$out = $this->renderVertical($widgetLinks);
							break;
						case 'dropdown':
							$out = $this->renderDropdown($widgetLinks);
							break;
					}

				}

				return $out;
			}
			
			function renderHorizontal($widgetLinks) {
				$options = $this->getOptions();
				$iconSize = $options['icons'];
				
?>

<div id='sp_div_outer' style="height: <?php echo $iconSize?>px;">
	<div id="sp_div_list">
		<table cellspacing="0" cellpadding="0">
			<tr>
<?php foreach ($widgetLinks as $k => $v) :?>
	<?php
		$linkUrl = $v['link'];
		$siteTitle = $v['site_title']; 
		$icon = $v['icon']; 
		$hash = 'a' . md5($linkUrl);
?>
		<td>
			<a rel="nofollow me" class="sp_link_<?php echo $iconSize ?>" href="<?php echo htmlspecialchars($linkUrl, ENT_QUOTES)?>"
			target="_blank" title="<?php echo htmlspecialchars($siteTitle, ENT_QUOTES)?>" id="<?php echo $hash ?>"></a>
		 </td>
		 <style type="text/css"> 
#<?php echo $hash ?>:hover {
	background-position: 0 -<?php echo $iconSize ?>px;
}

#<?php echo $hash ?> {
	background: url(<?php echo $this->pluginPath ?>/images/<?php echo htmlspecialchars($icon, ENT_QUOTES)?>_<?php echo $iconSize?>.png) no-repeat;
	padding: 0px 1px;
}
		</style> 
<?php endforeach;?>
		</tr>
		</table>
	</div>
</div>


<?php

			}

			function renderVertical($WidgetLinks) {
				$options = $this->getOptions();
				$iconSize = $options['icons'];

		 ?>
<div id='sp_div_outer'>
   <div id="sp_div_list">
	  <div id="sp_ul">
	  <table>
<?php foreach ($WidgetLinks as $k => $v) :?>
   <?php
	  $linkUrl = $v['link'];
	  $siteTitle = $v['site_title']; 
	  $icon = $v['icon']; 
	  $hash = 'a' . md5($linkUrl);
   ?>
   		<tr>
		 <td>
			<a rel="nofollow me" class="sp_link_<?php echo $iconSize ?>" href="<?php echo htmlspecialchars($linkUrl, ENT_QUOTES)?>"
			   target="_blank" title="<?php echo htmlspecialchars($siteTitle, ENT_QUOTES)?>" id="<?php echo $hash ?>" ></a> 
		 </td>
		<td style="vertical-align : bottom;">
			<?php echo $siteTitle ?>

		 <style type="text/css"> 
#<?php echo $hash ?>:hover {
   background-position: 0 -<?php echo $iconSize ?>px;
}

#<?php echo $hash ?> {
   background: url(<?php	 echo $this->pluginPath ?>/images/<?php echo htmlspecialchars($icon, ENT_QUOTES)?>_<?php echo $iconSize?>.png) no-repeat;
   padding-top: 1px;
}
		 </style> 
		</td>
		</tr>
<?php endforeach;?>
  	  </table>
	  </div>
   </div>
</div>

<small class="sp_linkback"><a href="http://asif.im/spi">Social Profilr Reimagined</a></small>

		 <?php
}

		function renderDropdown($WidgetLinks) {
				$options = $this->getOptions();
				$iconSize = $options['icons'];

		 ?>
<div id='sp_div_outer' onmouseout="onSPOut()" style="width: <?php echo $iconSize?>px;">
   <div id='sp_div_icon'  onmouseover="onSPOver();">

		<a rel="nofollow me" class="sp_link_<?php echo $iconSize ?>" href="http://asif.im/spi" target="_blank" title="Social Profilr" id="socialprofilr-icon">
			<img src='<?php echo $this->pluginPath ?>/images/sp_<?php echo $iconSize?>.png' border='0' />
	  	</a>
   </div>
   <div id="sp_div_list"  style="display: none; position: absolute;">
	  <div id="sp_ul">
<?php foreach ($WidgetLinks as $k => $v) :?>
   <?php
	  $linkUrl = $v['link'];
	  $siteTitle = $v['site_title']; 
	  $icon = $v['icon']; 
	  $hash = 'a' . md5($linkUrl);
   ?>
		 <div onmouseover="onSPOver();">
			<a rel="nofollow me" class="sp_link_<?php echo $iconSize ?>" href="<?php echo htmlspecialchars($linkUrl, ENT_QUOTES)?>"
			   target="_blank" title="<?php echo htmlspecialchars($siteTitle, ENT_QUOTES)?>" id="<?php echo $hash ?>"></a>
		 </div>
		 <style type="text/css"> 
#<?php echo $hash ?>:hover {
   background-position: 0 -<?php echo $iconSize ?>px;
}

#<?php echo $hash ?> {
	background: url(<?php echo $this->pluginPath ?>/images/<?php echo htmlspecialchars($icon, ENT_QUOTES)?>_<?php echo $iconSize?>.png) no-repeat;
	padding-top: 1px;
}
		 </style> 
<?php endforeach;?>
	</div>
	</div>
</div>
<script type="text/javascript">
function onSPOver() {
	document.getElementById('sp_div_list').style.display = 'block';
}

function onSPOut() {
	document.getElementById('sp_div_list').style.display = 'none';
}

</script>
		 <?php
	}


			function widget_init() {
				if (!function_exists('register_sidebar_widget'))
					return;


				function widget_Socialprofilr_output($args = array()) {
					global $SocialprofilrInstance;

					if (is_array($args)) 
						extract($args);

					$options = $SocialprofilrInstance->getOptions();
					$title = $options['title'];
					$description = $options['description'];
					
					if (empty($title)) {
						$title = $SocialprofilrInstance->optionsDefaults['title'];
					}

					echo $before_widget;
					echo $before_title . $title . $after_title;
					echo '<p>' . $description . '</p>';
					$SocialprofilrInstance->generateWidget();
					echo $after_widget;
				}

				// Register widget for use
				register_sidebar_widget(array('Socialprofilr', 'widgets'), 'widget_Socialprofilr_output');
			}

			//-----------------------------------------

			function optionsPage() {
				$options = $this->getOptions();

				if (isset($_POST['update_options'])) {

					if (get_magic_quotes_gpc()) {
						$_POST = array_map('stripslashes', $_POST);
					}
					
					$options['layout'] = $_POST['layout'];
					$options['icons'] = $_POST['icons'];

					$options['title'] = $_POST['title'];
					$options['description'] = $_POST['description'];

					foreach($this->links as $link) {
						$options[$link['site_name'].'_enable'] = $_POST[$link['site_name'].'_enable'];

						$options[$link['site_name'].'_id'] = $_POST[$link['site_name'].'_id'];
					}

					update_option($this->optionsName, $options);
	?>
	<div class="updated"><p><strong><?php _e('Options saved.','Socialprofilr'); ?></strong></p></div>
	<?php
				}

		// Display options form
	?>

	<div class=wrap>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

		<h2><?php echo $this->optionsPageTitle;?></h2>
By <b><a href="http://asif.im/" title="M Asif Rahman" target="_blank">M Asif Rahman</a></b>  |  <a href="http://asif.im/spi" title="Social Profilr Reimagined" target="_blank">Plugin Home</a>  |  <a href="http://wordpress.org/support/view/plugin-reviews/social-profilr-reimagined" title="Rate Social Profilr Reimagined" target="_blank">Rate & Review</a>  |  <a href="http://wordpress.org/support/plugin/social-profilr-reimagined" title="Support For Social Profilr Reimagined" target="_blank">Get Support</a>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Widget Title</th>
					<td>
						<input type="text" name="title" value="<?php echo $options['title']?>" style="width:200px;"/>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Widget Description</th>
					<td>
						<input type="text" name="description" value="<?php echo $options['description']?>" style="width:300px;"/>
					</td>
				</tr>


				<tr valign="top">
					<th scope="row">Widget Layout</th>
					<td>
						<p>
						<?php
						
							$select = array(
								'horizontal',
								'vertical',
								'dropdown'
							);
						
						?>
							<select name="layout">
						<?php
								foreach($select as $option):
						?>
								<option value="<?php echo($option);?>" <?php if ($options['layout']==$option) echo('selected="selected"');?>><?php echo(ucfirst($option));?></option>
						<?php
								endforeach;
						?>
							</select>
							
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Icon Size</th>
					<td>
						<p>
							<label for="Socialprofilr_icons_16">
							<input type="radio" id="Socialprofilr_icons_16" name="icons" value="16" <?php if ($options['icons'] == "16") { _e('checked="checked"', "PluginName"); }?> /> 16x16</label>&nbsp;&nbsp;&nbsp;&nbsp;

							<label for="Socialprofilr_icons_32">
							<input type="radio" id="Socialprofilr_icons_32" name="icons" value="32" <?php if ($options['icons'] == "32") { _e('checked="checked"', "PluginName"); }?>/> 32x32</label>
						</p>
					</td>
				</tr>


			</tbody>
		</table>


		<table class="form-table">
			<tbody>
				<tr valign="top">
<?php
		foreach($this->links as $link):
?>
					<th scope="row"><strong><?php echo($link['site_title'])?></strong></th>

					<td style="width : 150px;">
						Id: <input type="text" name="<?php echo($link['site_name'])?>_id" value="<?php echo($options[$link['site_name'].'_id'])?>" style="width : 100px;"/>
					</td>

					<td>
						<p>
							<label for="Socialprofilr_enable_<?php echo($link['site_name'])?>_1">
							<input type="radio" id="Socialprofilr_enable_<?php echo($link['site_name'])?>_1" name="<?php echo($link['site_name'])?>_enable" value="1" <?php if ($options[$link['site_name'].'_enable'] == "1") { _e('checked="checked"', "PluginName"); }?> /> On</label>&nbsp;&nbsp;&nbsp;&nbsp;

							<label for="Socialprofilr_enable_<?php echo($link['site_name'])?>_0">
							<input type="radio" id="Socialprofilr_enable_<?php echo($link['site_name'])?>_0" name="<?php echo($link['site_name'])?>_enable" value="0" <?php if ($options[$link['site_name'].'_enable'] == "0") { _e('checked="checked"', "PluginName"); }?> /> Off</label
						</p>
					</td>
				</tr>
<?php
		endforeach;
?>

			</tbody>
		</table>




		<p class="submit">
			<input type="submit" name="update_options" value="<?php _e('Update Settings', 'PluginName') ?>" />
		</p>
		</form>

	</div>
	<?php
			}

			//-----------------------------------------

			function blogHead() {
		?>
<style type="text/css">
.sp_link_16, .sp_link_32 {
   cursor: pointer;
   display: block;
   overflow: hidden;
}
.sp_link_16 {
   width: 16px;   
   height: 16px;   
}
.sp_link_32 {
   width: 32px;   
   height: 32px;   
}

.sp_linkback {
  text-align : center;
  display : block;
}

#sp_div_outer {
}
</style>
		<?php
			}

		}
	}

//-------------------------------------------------------------

	// Class initialization
	if (class_exists("SocialprofilrClass")) {

		$SocialprofilrInstance = new SocialprofilrClass();

	}

//-------------------------------------------------------------

	if (isset($SocialprofilrInstance)) {


		//Actions
		register_activation_hook(__FILE__,array($SocialprofilrInstance, 'activate'));

		if (!function_exists("SocialprofilrAdminMenu")) {
			function SocialprofilrAdminMenu() {
				global $SocialprofilrInstance;

				add_options_page($SocialprofilrInstance->optionsPageTitle, $SocialprofilrInstance->optionsMenuTitle, 'manage_options', basename(__FILE__), array($SocialprofilrInstance, 'optionsPage'));
			}
		}
function spr_setting_links($links, $file) {
    static $spr_setting;
    if (!$spr_setting) {
        $spr_setting = plugin_basename(__FILE__);
    }
    if ($file == $spr_setting) {
        $spr_settings_link = '<a href="options-general.php?page=socialprofilrreimagined.php">Settings</a>';
        array_unshift($links, $spr_settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'spr_setting_links', 10, 2);	

		add_action('admin_menu', 'SocialprofilrAdminMenu');

		add_action('wp_head', array($SocialprofilrInstance, 'blogHead'), 1);

		add_action('widgets_init', array($SocialprofilrInstance, 'widget_init'));

		//Filters
	}
?>