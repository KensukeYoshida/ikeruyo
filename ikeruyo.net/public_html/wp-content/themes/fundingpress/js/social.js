var Message;
jQuery("#facebooklogin").click(function(e) {
	social_startlogin('facebook', false);
	return false;
});
jQuery("#twitterlogin").click(function(e) {
	social_startlogin('twitter', false);
	return false;
});
jQuery("#googlelogin").click(function(e) {
	social_startlogin('google', false);
	return false;
});

jQuery(".reg #facebooklogin").click(function(e) {
	social_startlogin('facebook', false);
	return false;
});
jQuery(".reg #twitterlogin").click(function(e) {
	social_startlogin('twitter', false);
	return false;
});
jQuery(".reg #googlelogin").click(function(e) {
	social_startlogin('google', false);
	return false;
});


