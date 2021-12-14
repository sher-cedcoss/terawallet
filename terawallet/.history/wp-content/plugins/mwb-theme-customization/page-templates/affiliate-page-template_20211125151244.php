<?php
get_header();

$db = new WPAM_Data_DataAccess();

if (is_user_logged_in())
{
	$currentUser = wp_get_current_user();

	if ($db->getAffiliateRepository()->isUserAffiliate($currentUser->ID))
	{
		$url = get_option('wpam_aff_home_page');
		wp_redirect( $url );
	}
}
the_content();

get_footer();
