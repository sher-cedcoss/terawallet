<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !class_exists( 'MWB_Decoration_Customization' ) )
{
	/**
	 * This is class for managing product layout and other functionalities .
	 *
	 * @name    MWB_Decoration_Customization
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	
	class MWB_Decoration_Customization
	{
		/**
		 * This is construct of class where all action and filter is defined
		 * 
		 * @name __construct
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function __construct( ) 
		{
			add_action('wp_enqueue_scripts',array( $this, 'mwb_wp_enqueue_script'),25);
			add_action('wp_head', array($this, 'mwb_festive_decoration_custom_css'),15);
		}

		public function mwb_festive_decoration_custom_css(){

			$mwb_festive_decoration_data = get_option('mwb_festive_decoration_data', false);
			$mwb_data = json_decode($mwb_festive_decoration_data , true);

			$mwb_enable_festive_decoration = isset($mwb_data['mwb_enable_festive_decoration'])?$mwb_data['mwb_enable_festive_decoration']:"";

			$mwb_festive_header_image = isset($mwb_data['mwb_festive_header_image'])?$mwb_data['mwb_festive_header_image']:"";

			$mwb_festive_footer_image = isset($mwb_data['mwb_festive_footer_image'])?$mwb_data['mwb_festive_footer_image']:"";

			$mwb_festive_falling_image = isset($mwb_data['mwb_festive_falling_image'])?$mwb_data['mwb_festive_falling_image']:"";

			$mwb_festive_custom_css = isset($mwb_data['mwb_festive_custom_css'])?$mwb_data['mwb_festive_custom_css']:"";

			if(isset($mwb_enable_festive_decoration) && !empty($mwb_enable_festive_decoration))
			{
			?>

				<style>
				.october-leaf {
					background-image: url('<?php echo $mwb_festive_falling_image; ?>') !important;
				}
				.home .page-header {
				    background-image: url('<?php echo $mwb_festive_header_image; ?>') !important;
				}
				.site-footer{
				    background-image: url('<?php echo $mwb_festive_footer_image; ?>') !important;
				}

				<?php echo $mwb_festive_custom_css;  ?>
				</style>
				<?php
			}

		}

		public function mwb_wp_enqueue_script()
		{
			$mwb_festive_decoration_data = get_option('mwb_festive_decoration_data', false);
			$mwb_data = json_decode($mwb_festive_decoration_data , true);

			$mwb_enable_festive_decoration = isset($mwb_data['mwb_enable_festive_decoration'])?$mwb_data['mwb_enable_festive_decoration']:"";

			if(isset($mwb_enable_festive_decoration) && !empty($mwb_enable_festive_decoration))
			{

				if(!is_cart() && !is_checkout())
				{
					wp_enqueue_script('rotate3Di-min-js', MWB_URL.'decoration/assets/js/rotate3Di.min.js?'.time(),array( 'jquery' ));

					wp_enqueue_script('3d-falling-leaves', MWB_URL.'decoration/assets/js/3d-falling-leaves.min.js',array( 'jquery', 'rotate3Di-min-js' ));

					wp_enqueue_script('mwb-festival', MWB_URL.'decoration/assets/js/mwb-decoration.js',array( 'jquery', 'rotate3Di-min-js', '3d-falling-leaves' ));

					wp_enqueue_style('mwb-jquerysctipttop', 'https://www.jqueryscript.net/css/jquerysctipttop.css');
					
					wp_enqueue_style('3d-falling-leaves-css', MWB_URL.'decoration/assets/css/3d-falling-leaves.css' );
				}
			}
		}
	}
	new MWB_Decoration_Customization();
}
?>