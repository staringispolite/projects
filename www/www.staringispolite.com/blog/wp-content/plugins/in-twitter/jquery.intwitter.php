<?PHP


echo "$(document).ready(function(){

	// Load the twitter widget last.
	var w_twit =  new TWTR.Widget({
		version: 2,
		type: 'search',
		search: 'from:"<?PHP echo ( get_option ( 'intwitter_uid' ) ); ?>"',
		rpp: 2,
		interval: 30000,
		title: '',
		subject: '',
		width: 196,
		height: 211,
		id: 'twitterbox',
		theme: {
			shell: {
				background: '#333',
				color: '#ffffff'
			},
			tweets: {
				background: '#ffffff',
				color: '#000000',
				links: '#094F95'
			}
		},
		features: {
			scrollbar: false,
			loop: false,
			live: true,
			hashtags: true,
			timestamp: true,
			avatars: false,
			toptweets: true,
			behavior: 'all'
		}
	});
	// render the widget to the browser.
	w_twit.render().start();


});"

?>
