<?php
namespace Probuilder;
class Probuilder
{
	protected $css = array('Dimension' => array('Width' => 'width', 'Minimum width' => 'min-width', 'Maximum width' => 'max-width', 'Height' => 'height', 'Minimum height' => 'min-height', 'Maximum height' => 'max-height', 'Margin left' => 'margin-left', 'Margin right' => 'margin-right', 'Margin top' => 'margin-top', 'Margin bottom' => 'margin-bottom', 'Padding left' => 'padding-left', 'Padding right' => 'padding-right', 'Padding top' => 'padding-top', 'Padding bottom' => 'padding-bottom', 'Left' => 'left', 'Right' => 'right', 'Top' => 'top', 'Bottom' => 'bottom', 'Z-Index' => 'z-index', 'Border' => 'border', 'Border left' => 'border-left', 'Border right' => 'border-right', 'Border top' => 'border-top', 'Border bottom' => 'border-bottom', 'Border radius' => 'border-radius', 'Position' => array('position', 'Static' => 'static', 'Relative' => 'relative', 'Absolute' => 'absolute', 'Fixed' => 'fixed', 'Inherit' => 'inherit'), 'Display' => array('display', 'None' => 'none', 'Inline' => 'inline', 'Block' => 'block', 'List Item' => 'list-item', 'Inline block' => 'inline-block', 'Inline table' => 'inline-table', 'Table' => 'table', 'Table cell' => 'table-cell', 'Table column' => 'table-column', 'Table column group' => 'table-column-group', 'Table footer group' => 'table-footer-group', 'Table header group' => 'table-header-group', 'Table row' => 'table-row', 'Table row group' => 'table-row-group', 'Flex' => 'flex', 'Inline flex' => 'inline-flex', 'Grid' => 'grid'), 'Overflow' => array('overflow', 'Auto' => 'auto', 'Scroll' => 'scroll', 'Hidden' => 'hidden', 'Inherit' => 'inherit'), 'Float' => array('float', 'Left' => 'left', 'Right' => 'right', 'None' => 'none'), 'Clear' => array('clear', 'Left' => 'left', 'Right' => 'right', 'Both' => 'both', 'None' => 'none'), 'Vertical align' => array('vertical-align', 'Baseline' => 'baseline', 'Sub' => 'sub', 'Subline' => 'subline', 'Super' => 'super', 'Text top' => 'text-top', 'Text bottom' => 'text-bottom', 'Middle' => 'middle', 'Top' => 'top', 'Bottom' => 'bottom', 'Inherit' => 'inherit')), 'Font / Text' => array('Color' => 'color', 'Font Family' => 'font-family', 'Font size' => 'font-size', 'Line Height' => 'line-height', 'Font Weight' => 'font-weight', 'Font Style' => array('font-style', 'Normal' => 'normal', 'Italic' => 'italic', 'Oblique' => 'oblique', 'Inherit' => 'inherit'), 'Text Decoration' => array('text-decoration', 'None' => 'none', 'Underline' => 'underline', 'Overline' => 'overline', 'Line through' => 'line-through', 'Blink' => 'blink', 'Inherit' => 'inherit'), 'Text transform' => array('text-transform', 'None' => 'none', 'Capitalize' => 'capitalize', 'Uppercase' => 'uppercase', 'Lowercase' => 'lowercase', 'Inherit' => 'inherit'), 'Text align' => array('text-align', 'Left' => 'left', 'Center' => 'center', 'Right' => 'right', 'Justify' => 'justify', 'Inherit' => 'inherit'), 'Letter spacing' => 'letter-spacing'), 'Background' => array('Background' => 'background', 'Background color' => 'background-color', 'Background image' => 'background-image', 'Background repeat' => array('background-repeat', 'Repeat' => 'repeat', 'No repeat' => 'no-repeat', 'Repeat horizontally' => 'repeat-x', 'Repeat vertically' => 'repeat-y'), 'Background position' => 'background-position', 'Background attachment' => 'background-attachment', 'Background size' => 'background-size', 'Background origin' => 'background-origin'), 'Lists' => array('List type' => array('list-style-type', 'Disc' => 'disc', 'Circle' => 'circle', 'Square' => 'square', 'Decimal' => 'decimal', 'Decimal leading zero' => 'decimal-leading-zero', 'Lower roman' => 'lower-roman', 'Upper roman' => 'upper-roman', 'Lower greek' => 'lower-greek', 'Lower latin' => 'lower-latin', 'Upper latin' => 'upper-latin', 'Armenian' => 'armenian', 'Georgian' => 'georgian', 'Lower alpha' => 'lower-alpha', 'Upper alpha' => 'upper-alpha', 'None' => 'none'), 'List Position' => array('list-style-position', 'Inside' => 'inside', 'Outside' => 'outside', 'Inherit' => 'inherit'), 'List Image' => 'list-style-image'));
	protected $highlight_color;
	protected $media_queries;
	protected $css_keyframes;
	protected $protocol;
	protected $disabled;
	protected $autoprefix;
	protected $license_key;

	public function __construct()
	{
		$this->disabled = PROBUILDER_MODE === 'PRO' ? '' : 'disabled';
	    $highlight_option = esc_attr(get_option('highlight-color'));
	    $this->highlight_color = $highlight_option != null ? $highlight_option : PROBUILDER_HIGHLIGHT_COLOR;
	    $media_queries = esc_attr(get_option('custom-media-queries'));
	    $this->media_queries = $media_queries != null ? $media_queries : PROBUILDER_DEFAULT_MEDIA_QUERIES;
	    $this->css_keyframes = esc_attr(get_option('css-keyframes'));
	    $this->protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	    $this->autoprefix = esc_attr(get_option('autoprefix'));
	    $this->license_key = esc_attr(get_option('license_key'));
	}

	public static function get_instance()
	{
	    NULL === self::$instance and self::$instance = new self();
	    return self::$instance;
	}

	public function register()
	{
		if(current_user_can('manage_options'))
		{
			add_action('wp_enqueue_scripts', array($this, 'register_probuilder_styles'));
			add_action('wp_enqueue_scripts', array($this, 'register_probuilder_scripts'));
			add_action('admin_enqueue_scripts', array($this, 'register_probuilder_styles'));
			add_action('admin_enqueue_scripts', array($this, 'register_probuilder_scripts'));
			add_action('admin_enqueue_scripts', array($this, 'register_probuilder_admin_scripts'));
			add_action('admin_enqueue_scripts', array($this, 'register_probuilder_admin_styles'));

			add_action('admin_bar_menu', array($this, 'add_toolbar_toggle'), 999);
			add_action('wp_footer', array($this, 'add_html_code'));

			add_action('wp_ajax_probuilder_ajax_save_css', array($this, 'probuilder_ajax_save_css'));
			add_action('wp_ajax_probuilder_ajax_init_storage', array($this, 'probuilder_ajax_init_storage'));
			add_action('wp_ajax_probuilder_ajax_remove_elem', array($this, 'probuilder_ajax_remove_elem'));
			add_action('wp_ajax_probuilder_ajax_get_all_probuilds', array($this, 'probuilder_ajax_get_all_probuilds'));
		}
		else
		{
			add_action('wp_footer', array($this, 'add_style_tag'));
		}   
	}	

	public function init_admin_menu()
	{
		if(current_user_can('manage_options'))
		{
			add_menu_page(
		        'Probuilder',
		        'Probuilder',
		        'manage_options',
		        'probuilder',
		        array($this , 'render_main_page'),'dashicons-hammer'
		    );
		    add_submenu_page(
		        'probuilder',
		        __('Settings of Probuilder - Live CSS editing', 'probuilder_text_domain'),
		        __('Settings', 'probuilder_text_domain'),
		        'manage_options',
		        'probuilder-settings',
		        array($this , 'render_settings_page')
		    );
		}
	}

	public function init_admin_settings()
	{
		register_setting('probuilder-group', 'highlight-color');

		if(PROBUILDER_MODE === 'PRO')
		{
			register_setting('probuilder-pro-group', 'autoprefix');
			register_setting('probuilder-pro-group', 'css-preprocessor');
			register_setting('probuilder-pro-group', 'predefined-vars');
			register_setting('probuilder-pro-group', 'custom-media-queries');
			register_setting('probuilder-pro-group', 'css-keyframes');
			register_setting('probuilder-pro-group', 'license_key');
		}
	}

	public function probuilder_ajax_save_css()
	{
		$path = $_REQUEST['path'];
		$response = new \WP_Ajax_Response;

		if(current_user_can('manage_options') && wp_verify_nonce($_REQUEST['nonce'], 'probuilder_ajax-save-changes') && $_REQUEST["path"] != '' && $_REQUEST["css"] != '')
		{
			global $wpdb;
			$path = $_REQUEST["path"];
			$css = $_REQUEST["css"];
			$mq = $_REQUEST["mq"];
			$update = false;

			$results = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->prefix."probuilder` WHERE `path` = %s AND mq = %s", $path, $mq));
			if($results == 0)
			{
				if($wpdb->query($wpdb->prepare("INSERT INTO `".$wpdb->prefix."probuilder` (`time`, `path`, `css_code`, `mq`) VALUES (NOW(), %s, %s, %s)", $path, $css, $mq)))
				{
					$update = true;
				}
			}
			else
			{
				if($wpdb->query($wpdb->prepare("UPDATE `".$wpdb->prefix."probuilder` SET `css_code` = %s, `mq` = %s WHERE `path` = %s", $css, $mq, $path)))
				{
					$update = true;
				}
			}
			if($update)
			{
				$response->add(array(
					'data'	=> 'success',
					'supplemental' => array(
						'message' => __('CSS code saved successfully.', 'probuilder_text_domain'),
					),
				));
			}
			else
			{
				$response->add(array(
					'data'	=> 'warning',
					'supplemental' => array(
						'message' => __('There was an error when saving the CSS code, please refresh and try again.', 'probuilder_text_domain'),
					),
				));
			}			
		}
		else
		{
			$response->add(array(
				'data'	=> 'error',
				'supplemental' => array(
					'message' => __('CSS code was not saved, did you add some code?', 'probuilder_text_domain'),
				),
			));
		}
		$response->send();
	}

	public function probuilder_ajax_init_storage()
	{
		$response = new \WP_Ajax_Response;

		if(current_user_can('manage_options') && wp_verify_nonce($_REQUEST['nonce'], 'probuilder_ajax-init-storage'))
		{
			global $wpdb;

			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."probuilder` ORDER BY `time` DESC LIMIT %u", PROBUILDER_CSS_CHANGES_LIMIT));
			foreach($results as $key => $val)
			{
				$results[$key]->css_code = stripslashes($results[$key]->css_code);
			}
			
			$response->add(array(
				'data' => 'success',
				'supplemental' => array(
					'data' => json_encode($results),
				),
			));		
		}
		$response->send();
	}

	public function probuilder_ajax_remove_elem()
	{
		$response = new \WP_Ajax_Response;

		if(current_user_can('manage_options') && wp_verify_nonce($_REQUEST['nonce'], 'probuilder_ajax-remove-elem-'.$_REQUEST["id"]))
		{
			global $wpdb;
			$id = $_REQUEST["id"];
			$css_code = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."probuilder` WHERE `id` = '%u' LIMIT 1", $id));

			if($wpdb->delete($wpdb->prefix.'probuilder', array('id' => $id), array('%u')))
			{				
				$response->add(array(
					'data' => 'success',
					'supplemental' => array(
						'data' => $css_code->css_code,
					),
				));	
			}
			else
			{
				$response->add(array(
					'data' => 'error',
					'supplemental' => array(
						'data' => __('No record with that ID was found.', 'probuilder_text_domain'),
					),
				));
			}		
		}
		$response->send();
	}

	public function probuilder_ajax_get_all_probuilds()
	{
		$response = new \WP_Ajax_Response;

		if(current_user_can('manage_options') && wp_verify_nonce($_REQUEST['nonce'], 'probuilder_ajax_get_all_probuilds'))
		{
			global $wpdb;
			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."probuilder` ORDER BY `time` DESC LIMIT %u", PROBUILDER_CSS_CHANGES_LIMIT));
			foreach($results as $key => $val)
			{
				$results[$key]->nonce = wp_create_nonce('probuilder_ajax-remove-elem-'.$results[$key]->id);
			}

			$response->add(array(
				'data' => 'success',
				'supplemental' => array(
					'data' => json_encode($results),
				),
			));		
		}
		$response->send();
	}

	public function render_main_page()
	{
		?>
		<div class="main-page-iframe">
			<iframe src="http://www.lingulo.com/probuilder-iframe-page"></iframe>
		</div>
		<?php
	}

	public function render_settings_page()
	{
		?>		
		<div class="probuilder-settings-container">
			<?php settings_errors(); ?>
			<h3><?=__('General Settings', 'probuilder_text_domain')?></h3>
			<form method="post" action="options.php">
				<?php settings_fields( 'probuilder-group' ); ?>
				<?php do_settings_sections( 'probuilder-group' ); ?>
				<table class="form-table">
			        <tr valign="top">
				        <th scope="row"><?=__('Highlight color', 'probuilder_text_domain')?><small><?=__('Change the color when hovering an element. Note that it is recommended to use a semi-transparent color.', 'probuilder_text_domain')?></small></th>
				        <td><input type="text" id="colorpicker" name="highlight-color"></td>
			        </tr>
			        <tr valign="top">
			        	<th></th>
			        	<td><?php submit_button(); ?></td>
			        </tr>
			    </table>				
			</form>
		</div>
		<div class="probuilder-settings-container">
			<?php settings_errors(); ?>
			<h3 class="probuilder-pro-badge small-margin"><?=__('Pro Settings', 'probuilder_text_domain')?></h3>
			<form method="post" action="options.php">
				<?php settings_fields( 'probuilder-pro-group' ); ?>
				<?php do_settings_sections( 'probuilder-pro-group' ); ?>
				<table class="form-table">
					<tr valign="top">
				        <th scope="row"><?=__('License key<small>You can obtain your license key from <a href="http://sllwi.re/p/1vp" target="_blank">here</a>.', 'probuilder_text_domain')?></small></th>
				        <td>
				        	<input type="password" id="license_key" name="license_key" value="<?=$this->license_key?>">
				        </td>
			        </tr>
			        <tr valign="top">
				        <th scope="row"><?=__('Autoprefix CSS<small>Automatically adds vendor-prefixes in order to allow cross-browser use of certain CSS features (recommended)', 'probuilder_text_domain')?></small></th>
				        <td>
				        	<input type="checkbox" id="autoprefix" name="autoprefix" <?=get_option('autoprefix') === 'on' ? 'checked' : ''?>>
				        	<label for="autoprefix" class="select-label" data-on="<?=__('On', 'probuilder_text_domain')?>" data-off="<?=__('Off', 'probuilder_text_domain')?>"></label>
				        </td>
			        </tr>
			        <!--		         
			        <tr valign="top">
				        <th scope="row"><?=__('CSS Preprocessor', 'probuilder_text_domain')?><small></small></th>
				        <td>
				        	<select name="css-preprocessor">
				        		<option value="Stylus" <?=get_option('css-preprocessor') === 'stylus' ? 'selected' : ''?>>Stylus</option>
				        		<option value="SASS" <?=get_option('css-preprocessor') === 'sass' ? 'selected' : ''?>>SASS</option>
				        		<option value="Less" <?=get_option('css-preprocessor') === 'less' ? 'selected' : ''?>>Less</option>
				        	</select>
				        </td>
			        </tr>
			        
			        <tr valign="top">
				        <th scope="row" class="vertical-align-top">
					        <?=__('Predefined <span class="predefined-vars-type"></span>variables', 'probuilder_text_domain')?>
					        <small><?=__('Add any <span class="predefined-vars-type"></span> variables here in order to use them in Probuilder.<br>SASS example: $primary-color: #333;', 'probuilder_text_domain')?></small>
				        </th>
				        <td>
				        	<textarea name="predefined-vars"></textarea>
				        </td>
			        </tr>
			        -->
			        <tr valign="top">
				        <th scope="row" class="vertical-align-top">
				        	<?=__('Custom Breakpoints', 'probuilder_text_domain')?>
				        	<small><?=__('Add custom breakpoints which you can then use in the editor. To find out more <a href="javascript:;" id="media-query-explain-link">click here</a>.', 'probuilder_text_domain')?></small>
				        	<div id="media-query-explain">
				        		<p>Simply add your breakpoints separated by new lines into the textfield. Probuilder will generate media queries and you will be able to select them in the front end.</p>
				        	</div>
				        </th>
				        <td>
				        	<textarea name="custom-media-queries"><?=$this->media_queries?></textarea>
				        </td>
			        </tr>
			        <tr valign="top">
				        <th scope="row" class="vertical-align-top">
				        	<?=__('CSS Keyframes', 'probuilder_text_domain')?>
				        	<small><?=__('Add custom CSS Keyframes here. Once you added your keyframes you can then use the animation properties in the editor.', 'probuilder_text_domain')?></small>
				        </th>
				        <td>
				        	<textarea name="css-keyframes"><?=$this->css_keyframes?></textarea>
				        </td>
			        </tr>
			        <tr valign="top">
			        	<th></th>
			        	<td><?php
			        	if(PROBUILDER_MODE === 'PRO')
			        	{
			        		submit_button('','primary','submit-pro',true,array('data-autoprefix' => $this->autoprefix));
			        	}
			        	else submit_button('','primary','submit-pro',true,array('disabled' => 'disabled'));
			        	?></td>
			        </tr>
			    </table>				
			</form>
		</div>
		<?php
	}	

	public function register_probuilder_styles()
	{
		wp_register_style('PROBUILDER', plugins_url('assets/css/style.css',PROBUILDER_FILE));
		wp_enqueue_style('PROBUILDER');

		if(PROBUILDER_MODE === 'PRO')
		{
			wp_register_style('PROBUILDER_PRO', plugins_url('assets/css/pro.css',PROBUILDER_FILE));
			wp_enqueue_style('PROBUILDER_PRO');
		}

		wp_register_style('CODEMIRROR', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/codemirror.css');
		wp_enqueue_style('CODEMIRROR');

		wp_register_style('FONTAWESOME', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('FONTAWESOME');
	}

	public function register_probuilder_scripts()
	{
		wp_register_script('PROBUILDER', plugins_url('assets/js/main.js',PROBUILDER_FILE),array('jquery'),'0.0.1',true);
		wp_enqueue_script('PROBUILDER');
	    wp_localize_script('PROBUILDER', 'probuilder_custom', array('plugin_url' => admin_url(),'probuilder_nonce' => wp_create_nonce(),'highlight_color' => $this->highlight_color, 'current_value_txt' => __('Current value')));

	    wp_register_script('CODEMIRROR', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/codemirror.min.js');
	    wp_enqueue_script('CODEMIRROR');

	    wp_register_script('CODEMIRROR_AUTOREFRESH', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/display/autorefresh.min.js');
	    wp_enqueue_script('CODEMIRROR_AUTOREFRESH');

	    wp_register_script('CODEMIRROR_PLACEHOLDER', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/addon/display/placeholder.min.js');
	    wp_enqueue_script('CODEMIRROR_PLACEHOLDER');	        

	    wp_register_script('CSSBEAUTIFY', plugins_url('assets/js/cssbeautify.js',PROBUILDER_FILE),array(),'0.0.1',true);
		wp_enqueue_script('CSSBEAUTIFY');    

	    wp_register_script('CODEMIRROR_CSS', 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/mode/css/css.js');
	    wp_enqueue_script('CODEMIRROR_CSS');

	    wp_register_script('PULSATE', plugins_url('assets/js/pulsate.min.js',PROBUILDER_FILE),array(),'0.0.1',true);
	    wp_enqueue_script('PULSATE');

	    if(PROBUILDER_MODE === 'PRO')
	    {
	    	wp_register_script('AUTOPREFIXER', plugins_url('assets/js/autoprefixer.js',PROBUILDER_FILE));
	    	wp_enqueue_script('AUTOPREFIXER');

	    	wp_register_script('PROBUILDER_PRO', plugins_url('assets/js/pro.js',PROBUILDER_FILE),array('jquery'),'0.0.1',true);
			wp_enqueue_script('PROBUILDER_PRO');
		    wp_localize_script('PROBUILDER_PRO', 'probuilder_custom', array('plugin_url' => admin_url(),'highlight_color' => $this->highlight_color,'no_page_class_txt' => __('Your theme doesn`t seem to support this feature unfortunately. Please write a mail to probuilder@lingulo.com and we will tell you how to manually adapt your DOM path to make this feature work.', 'probuilder_text_domain')));
	    }

	    wp_register_script('PROBUILDER_AJAX', plugins_url('assets/js/ajax.js',PROBUILDER_FILE),array('jquery'),'0.0.1',true);
	    wp_enqueue_script('PROBUILDER_AJAX');
	    wp_localize_script('PROBUILDER_AJAX', 'probuilder_ajax_custom', array('ajax_url' => admin_url('admin-ajax.php', $this->protocol), 'remove_confirm_txt' => __('Do you really want to delete all the custom styling for this element? This can not be undone.', 'probuilder_text_domain')));	    
	}

	public function register_probuilder_admin_scripts()
	{
		wp_register_script('PROBUILDER_COLORPICKER', plugins_url('assets/js/minicolors.min.js',PROBUILDER_FILE),array('jquery'),'',true);
		wp_enqueue_script('PROBUILDER_COLORPICKER');

		wp_register_script('PROBUILDER_ADMIN', plugins_url('assets/js/admin.js',PROBUILDER_FILE),array('jquery'),'0.0.1',true);
		wp_enqueue_script('PROBUILDER_ADMIN');
		wp_localize_script('PROBUILDER_ADMIN', 'probuilder_admin_custom', array('highlight_color' => $this->highlight_color));	
	}

	public function register_probuilder_admin_styles()
	{
		wp_register_style('PROBUILDER_COLORPICKER_CSS', plugins_url('assets/css/minicolors.css',PROBUILDER_FILE));
		wp_enqueue_style('PROBUILDER_COLORPICKER_CSS');
	}

	public function add_toolbar_toggle()
	{
		global $wp_admin_bar;
		$args = array(
			'id' => 'probuilder-toggle',
			'parent' => 'top-secondary',
			'title' => '<div id="probuilder-toggle"><a href="javascript:;">'.__("Activate Probuilder", "probuilder_text_domain").'</a></div>'
		);
		$wp_admin_bar->add_node($args);
	}

	public function add_html_code()
	{
		$code = $this->add_editor();
		$code.= $this->add_style_tag(true);
		echo $code;
	}

	public function add_editor()
	{
		global $wpdb;
		$css_paths = '';
		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."probuilder` ORDER BY `time` DESC LIMIT %u", PROBUILDER_CSS_CHANGES_LIMIT));

		if($results != 0)
		{
			foreach($results as $obj)
			{
				$css_paths.= '<li><i class="fa fa-chevron-circle-right"></i> <a href="javascript:;" data-id="'.$obj->id.'">'.$obj->path.'</a><a href="javascript:;" class="trash" data-id="'.$obj->id.'" data-nonce="'.wp_create_nonce('probuilder_ajax-remove-elem-'.$obj->id).'"><i class="fa fa-trash"></i></a></li>';
			}
		}
		return '
		<div id="probuilder-editor" data-nonce="'.wp_create_nonce('probuilder_ajax-init-storage').'">
			<div id="probuilder-editor-handle">
				<div class="handle-part">
					<p>'.__("Adapt styling", "probuilder_text_domain").'</p>
				</div>
				<div class="handle-part">
					<a href="javascript:;" id="probuilder-close-editor">
						<span class="dashicons dashicons-no-alt"></span>
					</a>
				</div>
			</div>
			<ul class="probuilder-tab-links">
				<li class="current" data-tab="probuilder-tab-1">'.__("Adapt styling").'</li>
				<li data-tab="probuilder-tab-2">'.__("CSS-Editor", "probuilder_text_domain").'</li>
				<li data-tab="probuilder-tab-3">'.__("Settings", "probuilder_text_domain").'</li>				
			</ul>
			<div class="editor-container">
				<div class="probuilder-row pt0 pb0 probuilder-pro-badge probuilder-pro-badge-select">
					<select disabled>
						<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.__("Apply rules to specific device widths", "probuilder_text_domain").'</option>
					</select>
				</div>
				<div class="probuilder-row">
					<input type="text" value="" name="dom-path">
					<a href="javascript:;" id="probuilder-go-parent-element">
						<span class="dashicons dashicons-arrow-up-alt"></span>
					</a>
					<label for="selectors">
						<select name="selectors">
							<option></option>
							<option value="hover">'.__("Hovered element", "probuilder_text_domain").'</option>
							<option value="active">'.__("Active link", "probuilder_text_domain").'</option>
							<option value="focus">'.__("Focussed element", "probuilder_text_domain").'</option>
							<option value="visited">'.__("Visited link", "probuilder_text_domain").'</option>
						</select>
					</label>
					<a href="javascript:;" id="probuilder-set-selectors">
						<span class="dashicons dashicons-plus-alt"></span>
					</a>
				</div>
				<div class="probuilder-tab current" id="probuilder-tab-1">
					<div class="probuilder-vertical-container">
						'.$this->add_css_tabs().'
					</div>
				</div>
				<div class="probuilder-tab" id="probuilder-tab-2">
					<form name="probuilder-editor-form scroll-element">						
						<div class="probuilder-row css-input">
							<textarea id="probuilder-css-input" class="CodeMirrorInput" placeholder="'.__("Your custom CSS, e.g. max-width: 250px;", "probuilder_text_domain").'"></textarea>
						</div>
						<div class="probuilder-row">
							<div>
								<textarea id="probuilder-css-output"></textarea>
							</div>
						</div>					
					</form>
				</div>
				<div class="probuilder-tab" id="probuilder-tab-3">
					<div class="probuilder-row">
						<div>
							<input type="checkbox" value="global" '.$this->disabled.' id="global-checkbox">
							<label for="global-checkbox" class="probuilder-pro-badge-checkbox">
								'.__("The defined rules should only apply to the current page.", "probuilder_text_domain").'
							</label>
						</div>					
					</div>
				</div>
				<div class="probuilder-tab" id="probuilder-tab-4">
					<div class="probuilder-row">
						<div class="scroll-element full-width">
							<ul id="all-probuilds-list">
								'.$css_paths.'
							</ul>
						</div>					
					</div>
				</div>			
				<div class="probuilder-row">
					<button type="button" id="save_changes" data-nonce="'.wp_create_nonce('probuilder_ajax-save-changes').'">'.__("Save changes", "probuilder_text_domain").'</button>
				</div>
			</div>
		</div>
		<div id="all-probuilds-container">
			<a href="javascript:;" id="show-all-probuilds" data-nonce="'.wp_create_nonce('probuilder_ajax_get_all_probuilds').'">'.__("All Probuilds", "probuilder_text_domain").'</a>
			<div id="all-probuilds"></div>
		</div>
		';
	}

	public function add_css_tabs()
	{
		$out = '<div class="probuilder-vertical-tabs-container-left scroll-element">
				<ul class="probuilder-vertical-tab-links">';
		$counter = 1;
		foreach($this->css as $group_key => $group_val)
		{
			$current = $counter === 1 ? ' current' : '';
			$out.= '<li class="'.$current.'" data-tab="probuilder-vertical-tab-'.$counter.'">'.$group_key.'</li>';
			$counter++;
		}
		$out.= '<li class="probuilder-pro-badge">Flexbox</li>
				<li class="probuilder-pro-badge">Multi Columns</li>
				<li class="probuilder-pro-badge">Animation</li>
				<li class="probuilder-pro-badge">Misc</li>';

		$out.= '</ul>
				</div>';
		$counter = 1;
		$out.= '<div class="probuilder-vertical-tabs-container-right scroll-element">';
		foreach($this->css as $group_key => $group_val)
		{
			$current = $counter === 1 ? ' current' : '';
			$out.= '<div class="probuilder-vertical-tabs'.$current.'" id="probuilder-vertical-tab-'.$counter.'">';
			if(is_array($group_val))
			{
				foreach($group_val as $attr_key => $attr_val)
				{
					if(is_array($attr_val))
					{
						$out.= '<label for="'.$attr_val[0].'">'.$attr_key.'</label>
								<select name="'.$attr_val[0].'" data-val="'.$attr_val[0].'"><option value=""></option>';
						unset($attr_val[0]);
						foreach($attr_val as $select_key => $select_val)
						{
							$out.= '<option value="'.$select_val.'" data-val='.$select_val.'>'.$select_key.'</option>';
						}
						$out.= '</select>';
					}
					else
					{
						if($attr_val === 'background-image')
						{
							$out.= '<label for="'.$attr_val.'">'.$attr_key.'</label>
									<input type="text" data-val="'.$attr_val.'" name="'.$attr_val.'" placeholder="e.g. url(\'http://www.example.com/image.jpg\')">';
						}
						elseif($attr_val === 'transition-timing-function')
						{
							$out.= '<label for="'.$attr_val.'">'.$attr_key.'</label>
									<input type="text" data-val="'.$attr_val.'" name="'.$attr_val.'">
									<small class="hint">Use <a href="https://matthewlein.com/ceaser/" target="_blank">Ceaser</a> to create CSS easings.</small>';
						}
						elseif($attr_val === 'transition')
						{
							$out.= '<div class="probuilder-note"><strong>Hint</strong>: To add CSS keyframes go to the <a href="'.get_admin_url().'admin.php?page=probuilder-settings">settings page</a>.</div><label for="'.$attr_val.'">'.$attr_key.'</label>
									<input type="text" data-val="'.$attr_val.'" name="'.$attr_val.'">';
						}
						elseif($attr_val === 'content')
						{
							$out.= '<label for="'.$attr_val.'">'.$attr_key.'</label>
									<input type="text" data-val="'.$attr_val.'" name="'.$attr_val.'" placeholder="e.g. \'Hello World\'">';
						}
						else $out.= '<label for="'.$attr_val.'">'.$attr_key.'</label>
									<input type="text" data-val="'.$attr_val.'" name="'.$attr_val.'">';
					}
				}
			}
			$out.= '</div>';
			$counter++;
		}
		$out.='</div>';
		return $out;
	}

	public function add_style_tag($return = false)
	{
		global $wpdb;
		$css = null;

		$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."probuilder` ORDER BY `mq` ASC, `time` DESC LIMIT %u", PROBUILDER_CSS_CHANGES_LIMIT));

		if($results != 0)
		{
			foreach($results as $obj)
			{
				$css.= stripslashes($obj->css_code);
			}
		}
		$minified_css = $this->minifyCSS($css.$this->css_keyframes);
		if($return)
		{
			return '<style type="text/css" id="probuilder-style-tag" scoped>'.$minified_css.'</style>';
		}
		else echo '<style type="text/css" id="probuilder-style-tag" scoped>'.$minified_css.'</style>';
	}
	private function minifyCSS($css)
	{
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		$css = str_replace(': ', ':', $css);
		$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
		return $css;
	}
}
?>