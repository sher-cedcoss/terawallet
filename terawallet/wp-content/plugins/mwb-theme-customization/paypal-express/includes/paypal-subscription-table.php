<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !class_exists( 'MWB_Paypal_Subscrioption_Table' ) )
{
	/**
	 * This is class for managing product layout and other functionalities .
	 *
	 * @name    MWB_WooCommerce_Customization
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	
	class MWB_Paypal_Subscrioption_Table{

		/**
		 * This is construct of class where all action and filter is defined
		 * 
		 * @name __construct
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function __construct() 
		{
			add_filter('manage_edit-yearly_subscription_columns', array($this, 'mwb_yearly_subscription_cpt_columns'));
			add_action('manage_yearly_subscription_posts_custom_column', array($this, 'mwb_yearly_subscription_cpt_column'), 10, 2);
			add_action( 'add_meta_boxes', array( $this, 'mwb_add_recurring_payment_details' ));
		}

	    /**
	     * Adds the meta box.
	     */
	    public function mwb_add_recurring_payment_details() {
	        add_meta_box(
	            'my-meta-box',
	            __( 'Recurring Payment Details' ),
	            array( $this, 'mwb_recurring_payment_details' ),
	            'yearly_subscription'
	        );
	 	}

	 	public function mwb_recurring_payment_details( $post ){
	 		$subscriptionid = $post->ID;

	 		include_once MWB_DIRPATH.'paypal-express/mwb-paypal-express-checkout.php';
			$profile_id = get_post_meta($subscriptionid, 'mwb_paypal_profile_id', true);

			$mwb_paypal_recurring = new MWB_Gateway_Paypal_Recurring();
			$response = $mwb_paypal_recurring->get_recurring_profile($profile_id);

			if(isset($response))
			{	
				?>
				<h2><strong>Profile Details:</strong></h2>
				<table class="wp-list-table widefat fixed striped">
				<?php
					foreach ($response as $key => $value) 
					{
						?>
						<tr>
		 					<th><?php echo $key;?></th>
		 					<td><?php echo urldecode($value);?></td>
		 				</tr>	
						<?php
					}
				?>
				</table>
				<?php
			}
	 		$saved_transaction_data = get_post_meta($subscriptionid, "mwb_paypal_profile_transaction", true);
	 		if(isset($saved_transaction_data) && !empty($saved_transaction_data))
	 		{
	 			?>
	 			<h2><strong>Recurring Transaction Data:</strong></h2>
	 			<table class="wp-list-table widefat fixed striped">
	 				<tr>
	 					<th>Recurring Payment Id</th>
	 					<th>Payer Email</th>
	 					<th>Payment Gross</th>
	 					<th>Txn Id</th>
	 					<th>Payment Date</th>
	 					<th>Payment Status</th>
	 				</tr>
	 			<?php
	 			$saved_transaction_array = json_decode($saved_transaction_data, true);
	 			
	 			foreach ($saved_transaction_array as $key => $saved_transaction) 
	 			{
	 				?>
	 				<tr>
	 					<td><?php echo $saved_transaction['recurring_payment_id'];?></td>
	 					<td><?php echo $saved_transaction['payer_email'];?></td>
	 					<td><?php echo wc_price($saved_transaction['payment_gross']);?></td>
	 					<td><?php echo $saved_transaction['txn_id'];?></td>
	 					<td><?php echo $saved_transaction['payment_date'];?></td>
	 					<td><?php echo $saved_transaction['payment_status'];?></td>
	 				</tr>
	 				<?php
	 			}		
	 			?>
	 			</table>
	 			<?php
	 		}	
	 	}

		public function mwb_yearly_subscription_cpt_columns( $columns )
		{
			$columns["subscription_s_date"] = "Start Date";
			$columns["subscription_n_date"] = "Next Date";
			$columns["subscription_amount"] = "Amount";
			$columns["subscription_status"] = "Status";
			$columns["subscription_suspend"] = "Action";
			return $columns;
		}

		public function mwb_yearly_subscription_cpt_column( $colname, $subscription_id ) 
		{
			include_once MWB_DIRPATH.'paypal-express/mwb-paypal-express-checkout.php';
			$profile_id = get_post_meta($subscription_id, 'mwb_paypal_profile_id', true);
			$subscription_status_saved = get_post_meta($subscription_id, 'mwb_paypal_profile_status', true);
			$subscription_s_date = "";
			$subscription_n_date = "";
			$subscription_amount = "";
			$subscription_status = "";
			if(empty($subscription_status_saved) || $subscription_status_saved != "Cancelled")
			{
				$mwb_paypal_recurring = new MWB_Gateway_Paypal_Recurring();
				$response = $mwb_paypal_recurring->get_recurring_profile($profile_id);
			}
			else
			{
				$response = get_post_meta($subscription_id, 'mwb_paypal_profile_detail', true);
	 		}	
			if($response['ACK'] == "Success")
			{
				$subscription_status = urldecode($response['STATUS']);


				update_post_meta($subscription_id, 'mwb_paypal_profile_status', $subscription_status);
				update_post_meta($subscription_id, 'mwb_paypal_profile_detail', $response);
				if($subscription_status == "Active")
				{
					$subscription_n_date = date_format(date_create(urldecode($response['NEXTBILLINGDATE'])),"M d, Y");
				}	
				$subscription_s_date = date_format(date_create(urldecode($response['PROFILESTARTDATE'])),"M d, Y");
				
				$subscription_amount = wc_price(urldecode($response['AMT'])+urldecode($response['TAXAMT']));
			}	

			if ( $colname == 'subscription_s_date')
		    {
		    	echo $subscription_s_date;
		    }	
		    if ( $colname == 'subscription_n_date')
		    {
		    	echo $subscription_n_date;
		    }
		    if ( $colname == 'subscription_amount')
		    {
		    	echo $subscription_amount;
		    }
		    if ( $colname == 'subscription_status')
		   	{
		    	echo $subscription_status;
		    }
		    if ( $colname == 'subscription_suspend')
        {
		   		if($subscription_status == "Active")
				{
					$args = array(
							'post_type'		=>	'shop_order',
							'post_status'    => 'all',
							'meta_key'	=>	'mwb_order_paypal_profile',
							'meta_value' => $profile_id,
							'meta_compare' => 'LIKE'
						);

					$order_query = new WP_Query( $args );
					$orderid = 0;
					if ( $order_query->have_posts() ) {
						while ( $order_query->have_posts() ) {
							$order_query->the_post();
							$orderid = get_the_ID();
							break;
						}
						wp_reset_postdata();
					}
					?>
			    	<a href="javascript:void(0);" class="button mwb_cancel_profile" data-profileid="<?php echo $profile_id;?>" data-orderid="<?php echo $orderid;?>">Suspend</a>
			    	<?php
			    }
			    else
			    {
			    	echo $subscription_status;
			    }	
		    }
		}
	}
	new MWB_Paypal_Subscrioption_Table();
}
?>