<?php
if(!defined('ABSPATH')) exit;

class QodeInstagramWidget extends WP_Widget {

	protected $params;

	public function __construct() {
		parent::__construct(
			'qode_instagram_widget',
			'Qode Instagram Widget',
			array( 'description' => __( 'Display instagram images', 'qode' ) )
		);

		$this->setParams();
	}

	protected function setParams() {
		$this->params = array(
			array(
				'name' => 'title',
				'type' => 'textfield',
				'title' => 'Title'
			),
			array(
				'name' => 'tag',
				'type' => 'textfield',
				'title' => 'Tag'
			),
			array(
				'name' => 'number_of_photos',
				'type' => 'textfield',
				'title' => 'Number of photos'
			),
			array(
				'name' => 'number_of_cols',
				'type' => 'dropdown',
				'title' => 'Number of columns',
				'options' => array(
					'2' => 'Two',
					'3' => 'Three',
					'4' => 'Four',
                    '5' => 'Five',
					'6' => 'Six',
					'7' => 'Seven',
					'8' => 'Eight',
					'9' => 'Nine',
				)
			),
			array(
				'name' => 'image_size',
				'type' => 'dropdown',
				'title' => 'Image Size',
				'options' => array(
					'thumbnail' => 'Small',
					'low_resolution' => 'Medium',
					'standard_resolution' => 'Large'
				)
			),
			array(
				'name' => 'transient_time',
				'type' => 'textfield',
				'title' => 'Images Cache Time'
			),
			array(
				'title' => 'Hover Effect',
				'name' => 'hover_effect',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'scale_with_light_overlay' => 'Scale with Light Overlay',
					'scale_with_dark_overlay' => 'Scale with Dark Overlay',
				),
				'save_always' => true
			),
		);
	}
	public function getParams() {
		return $this->params;
	}

	public function widget($args, $instance) {
		extract($instance);

		echo $args['before_widget'];
		echo $args['before_title'].$title.$args['after_title'];

		$instagram_api = QodeInstagramApi::getInstance();
		$images_array = $instagram_api->getImages($number_of_photos, $tag, array(
			'use_transients' => true,
			'transient_name' => $args['widget_id'],
			'transient_time' => $transient_time
		));

		$number_of_cols = $number_of_cols == '' ? 3 : $number_of_cols;
		
		$hover_class = '';
		if (isset($hover_effect) && $hover_effect != 'none') {
			if ($hover_effect == 'scale_with_light_overlay') {
				$hover_class = 'qodef-scale-with-light-overlay';
			} else if ($hover_effect == 'scale_with_dark_overlay') {
				$hover_class = 'qodef-scale-with-dark-overlay';
			} 
		}

		if(is_array($images_array) && count($images_array)) { ?>
			<ul class="qode-instagram-feed clearfix qode-col-<?php echo $number_of_cols; ?> <?php echo $hover_class; ?>">
				<?php
				foreach ($images_array as $image) { ?>
					<li>
						<a href="<?php echo esc_url($instagram_api->getHelper()->getImageLink($image)); ?>" target="_blank">
							<?php echo qode_kses_img($instagram_api->getHelper()->getImageHTML($image, $image_size)); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php }

		echo $args['after_widget'];
	}

	public function form($instance) {
		foreach ($this->params as $param_array) {
			$param_name = $param_array['name'];
			${$param_name} = isset( $instance[$param_name] ) ? esc_attr( $instance[$param_name] ) : '';
		}

		$instagram_api = QodeInstagramApi::getInstance();

		//user has connected with instagram. Show form
		if($instagram_api->hasUserConnected()) {
			foreach ($this->params as $param) {
				switch($param['type']) {
					case 'textfield':
						?>
						<p>
							<label for="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>"><?php echo
								esc_html($param['title']); ?></label>
							<input class="widefat" id="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>" name="<?php echo esc_attr($this->get_field_name( $param['name'] )); ?>" type="text" value="<?php echo esc_attr( ${$param['name']} ); ?>" />
						</p>
						<?php
						break;
					case 'dropdown':
						?>
						<p>
							<label for="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>"><?php echo
								esc_html($param['title']); ?></label>
							<?php if(isset($param['options']) && is_array($param['options']) && count($param['options'])) { ?>
								<select class="widefat" name="<?php echo esc_attr($this->get_field_name( $param['name'] )); ?>" id="<?php echo esc_attr($this->get_field_id( $param['name'] )); ?>">
									<?php foreach ($param['options'] as $param_option_key => $param_option_val) {
										$option_selected = '';
										if(${$param['name']} == $param_option_key) {
											$option_selected = 'selected';
										}
										?>
										<option <?php echo esc_attr($option_selected); ?> value="<?php echo esc_attr($param_option_key); ?>"><?php echo esc_attr($param_option_val); ?></option>
									<?php } ?>
								</select>
							<?php } ?>
						</p>

						<?php
						break;
				}
			}
		}
	}
}

function qode_instagram_widget_load(){
	register_widget('QodeInstagramWidget');
}

add_action('widgets_init', 'qode_instagram_widget_load');