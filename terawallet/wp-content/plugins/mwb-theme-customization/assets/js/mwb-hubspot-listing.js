jQuery(document).ready(function(){
	jQuery('.hub-parallax-effect').paroller();
});
// jQuery( window ).load(function() {
// 	var $gridShuffle = jQuery('#grid');
//     $gridShuffle.shuffle({
//         itemSelector: '.mwbh-prolisting-column'
//     });
//     $gridShuffle.shuffle('shuffle', 'template' );
//     jQuery('.mwbh-prolisting-nav__filter a').click(function (e) {
//         e.preventDefault();
//         jQuery('.mwbh-prolisting-nav__filter a').removeClass('active');
//         jQuery(this).addClass('active');
//         var shuffleGroup = jQuery(this).attr('data-group');
//         $gridShuffle.shuffle('shuffle', shuffleGroup );
//     });
// });