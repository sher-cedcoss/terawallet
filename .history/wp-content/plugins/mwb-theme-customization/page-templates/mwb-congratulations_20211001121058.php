<?php
get_header();

if(isset($_GET['email']) && $_GET['email'] != ""){

	$email = $_GET['email'];

	if(class_exists('Mauwoo')){
		
		$user = array();
		$user['email'] = $email ; 
		if(isset($_GET['valid']) && $_GET['valid'] == 1 )
		{
			$user['valid_email'] = "yes" ; 
		}
		
		if(isset($_GET['feedback']) && !empty($_GET['feedback']) )
		{
			$user['email_sentiment_reaction'] = $_GET['feedback'] ; 
		}
		// $user['accepted_report_request'] = "yes" ; 
		if( Mauwoo::is_valid_client_ids_stored() ) {
			
			$flag = true;

			if( Mauwoo::is_access_token_expired() ) {

				$keys = Mauwoo::get_mautic_keys();
				$mpubkey = $keys['client_id'];
				$mseckey = $keys['client_secret'];

				$status =  MauwooConnectionMananager::get_instance()->mauwoo_refresh_token( $mpubkey, $mseckey );

				if( !$status ) 
				{
					$flag = false;
				}
			}

			if( $flag ) {

				MauwooConnectionMananager::get_instance()->create_or_update_contacts( $user );
			}
		}
	}
}
?>
<section class="mwb-congras-section">
	<div class="template-container">
		<div class="template-row">
			<div class="template-col text-center">
				<div class="mwb-congras-wrapper">
				    <?php
				    if(isset($_GET['action']) && $_GET['action'] == 'feedback' )
				    {
				    ?>
    					<h1>Thank You!</h1>
    					<?php
    					if(isset($_GET['feedback']) ) {
        					 if( $_GET['feedback'] == 'great' )
        					 {
    				            ?>
            					<p>You just clicked "Great"</p>
            					<br>
            					<p>We're delighted that you are so happy with us at the moment and we really appreciate your feedback.</p>
        					    <?php
    				        }
    				        else if( $_GET['feedback'] == 'ok' )
    				        {
    				            ?>
            					<p>You just clicked "Ok"</p>
            					<br>
            					<p>We're concerned that things aren't exactly as you expected. Your feedback is appreciated and we will be in touch shortly to understand what more we can do in the future.</p>
        					    <?php
    				        }
    				        else if( $_GET['feedback'] == 'notgood' )
    				        {
    				            ?>
            					<p>You just clicked "Not Good"</p>
            					<br>
            					<p>We're worried that you are unhappy at present. We have been immediately alerted and will be in touch to understand more about your issues.</p>
        					    <?php
    				        }
				        }
				    
				    }
				    else {
				    ?>
    				    <h1>Congratulations!</h1>
    					<p>Your email address has now been verified!!</p>
    					<br>
    					<p>Now you will be the first to get notified to all relevant content, products, and services related information. Stay tuned!</p>
				    <?php
				    }
				    ?>
					<h6>If you have any Query, Feel free to contact us</h6>
					<ul class="mwb-congras-contact-wrapper">
						<li class="skpye">
							<a target="_blank" href="https://join.skype.com/invite/IKVeNkLHebpC" ><i class="fab fa-skype" aria-hidden="true"></i></a>
						</li>
						<li class="contact">
							<a href="mailto:support@makewebbetter.com"><i class="far fa-envelope" aria-hidden="true"></i></a>
						</li>
					</ul>
				</div>
			</div>	
		</div>
	</div>
</section>
<?php
get_footer();
?>