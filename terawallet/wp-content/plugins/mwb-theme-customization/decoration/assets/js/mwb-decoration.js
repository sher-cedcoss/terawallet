jQuery(document).ready(function(){
	var width = jQuery(window).width();
	if(width > 400)
	{
		jQuery(document).octoberLeaves({
			leafStyles: 3,      // Number of leaf styles in the sprite (leaves.png)
			speedC: 10,  // Speed of leaves
			rotation: 1,// Define rotation of leaves
			rotationTrue: 0,    // Whether leaves rotate (1) or not (0)
			numberOfLeaves: 15, // Number of leaves
			size: 60,   // General size of leaves, final size is calculated randomly (with this number as general parameter)
			cycleSpeed: 50      // <a href="https://www.jqueryscript.net/animation/">Animation</a> speed (Inverse frames per second) (10-100)
		})	
	}	
});