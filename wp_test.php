<?php
/**
 * Plugin Name: WP Test
 * Plugin URI: http://wp-test.com/
 * Description: WP Test
 * Version: 1.0.0
 * Author: Andry Setiawan
 */

if(!class_exists('WP_Test'))
{
	/**
	* 
	*/
	class WP_Test
	{
				
		protected static $instance = null;

        public static function instance() {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        function __construct()
		{
			add_action('init', array($this, 'save_to_db'));
			add_shortcode( 'test_form', array($this, 'test_form_func') );
			add_action( 'admin_menu', array($this,'test_admin_menu') );
			add_shortcode( 'show_data', array($this, 'show_data_func') );
			
		}

		// Soal No 1
		public function test_form_func()
		{
			ob_start();
			?>
			<form id="usrform" method='post'>
			  	Name:<br>
			  	<input type="text" name="name">
			  	<br>
			  	Email:<br>
			  	<input type="email" name="email">
			  	<br>
			  	Message:<br>
			  	<textarea rows="4" cols="50" name="message" form="usrform"></textarea>
			  	<input type="submit" name="submit-act" value="Submit">
			</form>
			<?php
			return ob_get_clean();
		}

		//Soal No 2
		public function save_to_db()
		{
			global $wpdb;

			$nama = isset($_POST['name']) && $_POST['nama']!='' ? $_POST['nama'] : '';
			$email = isset($_POST['email']) && $_POST['email']!='' ? $_POST['email'] : '';
			$message = isset($_POST['message']) && $_POST['message']!='' ? $_POST['message'] : '';

			if($_POST['submit-act'])
			{
				$tbl = $wpdb->prefix.'test_wp_comments';
				$wpdb->insert( 
					$tbl, 
					array( 
						'name' => $nama, 
						'email' => $email,
						'message' => $message 
					), 
					array( 
						'%s', 
						'%s', 
						'%s' 
					) 
				);

			}

			//Soal No 5
			$author_email = get_option('admin_email');
			$subject = "Form Submission";
			$message = "Name : ".$_POST['name'].", Email : ".$_POST['email'].", Message : ".$_POST['message'];
			wp_mail( $author_email, $subject, $message);

		}
		

		//Soal No 3
		public function test_admin_menu() {
			//create new top-level menu
			add_menu_page('Test Comments Settings', 'Test Comments', 'manage_options', __FILE__, array($this,'test_comments_settings_page') , 'dashicons-tickets' );

			
		}
		//Soal No 3
		public function test_comments_settings_page()
		{
			global $wpdb;

			$data_comments = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}test_wp_comments", ARRAY_A);
			?>

				<table style="width:100%">
				  <tr>
				    <th>Name</th>
				    <th>Email</th> 
				    <th>Messages</th>
				  </tr>
				  <?php foreach ($data_comments as $value): ?>
				  <tr>
				    <td><?php echo $value['name']; ?></td>
				    <td><?php echo $value['email']; ?></td>
				    <td><?php echo $value['message']; ?></td>
				  </tr>
				<?php endforeach; ?>
				</table>

			<?php
			
		}

		//Soal No 4
		public function show_data_func()
		{
			global $wpdb;

			$data_comments = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}test_wp_comments", ARRAY_A);
			ob_start();
			?>

				<table style="width:100%">
				  <tr>
				    <th>Name</th>
				    <th>Email</th> 
				    <th>Messages</th>
				  </tr>
				  <?php foreach ($data_comments as $value): ?>
				  <tr>
				    <td><?php echo $value['name']; ?></td>
				    <td><?php echo $value['email']; ?></td>
				    <td><?php echo $value['message']; ?></td>
				  </tr>
				<?php endforeach; ?>
				</table>

			<?php
			return ob_get_clean();

		}
	}



}
if (!function_exists('WP_Test')) {
    function WP_Test() {
        return WP_Test::instance();
    }
}

WP_Test();