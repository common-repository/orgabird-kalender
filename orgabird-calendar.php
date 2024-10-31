<?php
/*
Plugin Name: OrgaBird Kalender
Description: Bindet einen Kalender der Webseite <a target="_blank" href="https://schulferien.orgabird.de/widgets?utm_source=wordpress-pluginpage">https://schulferien.orgabird.de/widgets</a> in deine Webseite ein.<br> Wahlweise als Sidebar-Widget oder auch als Shortcode für den Content. Einbinden als Content-Plugin: <em>[orgabirdCalender parameter='{"page":"month","type":"all","type":"one","feasts-part":"no","header":"yes"}']</em>
Version: 0.1
Author: BigClick GmbH & Co.KG
Author URI: https://schulferien.orgabird.de/widgets
*/


// Creating the widget
class OrgaBirdCalendar extends WP_Widget {

	function __construct(){
		parent::__construct(
			// Base ID of your widget
			'orgabird_calendar',

			// Widget name will appear in UI
			__('OrgaBird Kalender', 'orgabird_calendar_widget_lng'),

			// Description
			array(
				'description' => __( 'Kalender für die Sidebar', 'orgabird_calendar_widget_lng' ),
			)
		);
	}

	// FrontEnd
	public function widget($args, $instance){

		// Titel übergeben
		$title = apply_filters( 'widget_title', $instance['title']);

		echo $args['before_widget'];

		if(!empty($title)){
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// This is where you run the code and display the output
		echo '<a href="https://schulferien.orgabird.de/" class="orgabird-widget" data-params='.$instance['calendar_parameter'].' title="OrgaBird Schulkalender" id="cal-'.ogbc_generateRandomString().'">Schulferien OrgaBird</a>';

		echo "<script>(function(a,b,c){var js,cwjs=a.getElementsByTagName(b)[0];if(a.getElementById(c))return;js=a.createElement(b);js.id=c;js.src='//schulferien.orgabird.de/connector.js';cwjs.parentNode.insertBefore(js,cwjs);}(document,'script','orgabird-connector'));</script>";

		echo $args['after_widget'];
	}


	// BackEnd
	public function form($instance){

		// Standardwerte setzen
		$defaults = array(
			'title' => __('OrgaBird Kalender', 'orgabird_calendar_widget_lng'),
			'calendar_parameter' => __('{"page":"month","type":"all","type":"one","feasts-part":"no","header":"yes"}', 'orgabird_calendar_widget_lng')
		);

		$instance = wp_parse_args((array)$instance, $defaults);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Widget-Titel:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('calendar_parameter'); ?>"><?php _e( 'Kalender Parameter:' ); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'calendar_parameter' ); ?>" name="<?php echo $this->get_field_name( 'calendar_parameter' ); ?>"><?php echo esc_attr( $instance['calendar_parameter'] ); ?></textarea>
				<a target="_blank" href="https://schulferien.orgabird.de/widgets?utm_source=wordpress-pluginpage">Hier</a> kannst Du einen Kalender konfigurieren
			</p>
		<?php
	}


	public function update($new_instance, $old_instance){

		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['calendar_parameter'] = strip_tags($new_instance['calendar_parameter']);

		return $instance;
	}
}


function ogbc_generateRandomString($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


// Register and load the widget
function orgabird_calendar_load_widget(){
	register_widget('OrgaBirdCalendar');
}

add_action('widgets_init', 'orgabird_calendar_load_widget');


/*
	Einbinden als Content-Plugin
	[orgabirdCalender parameter='{"page":"month","type":"all","type":"one","feasts-part":"no","header":"yes"}']

*/
function fullpage_orgabirdCalender($atts){

  	$a = shortcode_atts( array(
        'parameter' => '{"page":"month","type":"all","type":"one","feasts-part":"no","header":"yes"}'
    ), $atts);

    if(!session_id()){
        session_start();
    }

	$r = '<a href="https://schulferien.orgabird.de/" class="orgabird-widget" data-params='.$a['parameter'].' title="OrgaBird Schulkalender" id="cal-'.ogbc_generateRandomString().'">Schulferien OrgaBird</a>';

	$r .= "<script>(function(a,b,c){var js,cwjs=a.getElementsByTagName(b)[0];if(a.getElementById(c))return;js=a.createElement(b);js.id=c;js.src='//schulferien.orgabird.de/connector.js';cwjs.parentNode.insertBefore(js,cwjs);}(document,'script','orgabird-connector'));</script>";

	return $r;
}

add_shortcode('orgabirdCalender', 'fullpage_orgabirdCalender');
