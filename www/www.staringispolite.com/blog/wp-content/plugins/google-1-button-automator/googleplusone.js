function googleone_changePreview(){
    // shows preview of button
    var xpos=0;
    var ypos=0;
    switch(document.getElementById('googleone_size').value){
        case 'small': ypos=0; break;
        case 'medium': ypos=-100; break;
        case 'tall': ypos=-300; break;
        default: ypos=-200; // standard
    } // switch
	 
    switch(document.getElementById('googleone_displaycount').value){
        case 'none': xpos=-200; break;
        case 'inline': xpos=-400; break;
        default: xpos=0; // standard
    } // switch
	 

    document.getElementById('googleone_preview').style.backgroundPosition=xpos+'px '+ypos+'px';
}
// initialize onload
jQuery(document).ready(function($) {
    googleone_changePreview();
});
