var MeFileURL = "";
var FileHolders= ""; //is the uploaded image
jQuery(document).ready(function() {

	   	jQuery('#meimg').fileupload({
			 url: templateDir + '/include/UploadHandler.php',
			  sequentialUploads: true,
			  add: function(e, data) {
	                var uploadErrors = [];
	                var acceptFileTypes = /^image\/(jpe?g|png)$/i;
	                if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
	                    uploadErrors.push('Not an accepted file type');
	                }
	                if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5000000) {
	                    uploadErrors.push('Filesize is too big');
	                }
	                if(uploadErrors.length > 0) {
	                    jQuery("#status").html(uploadErrors.join("\n"));
	                } else {
	                	 jQuery("#status").html(settingsCustom.uploadphotoscu);
	                	jQuery('#load').append("<div id='loadingimage'></div>");
	                    data.submit();
	                }
	        }

		}) .bind('fileuploaddone', function (e, data) {

			MeFileLocation = uploadsettings.wp_upload_dir_path + data.files[0].name
	    	MeFileURL = uploadsettings.wp_upload_dir_url + data.files[0].name


			jQuery('#loadingimage').remove();
	        jQuery('#files').html('<img id="uploadedimage" style="width:100%;" src="'+MeFileURL+'" alt="" /><br />');

	      	  if (typeof jcrop_api != 'undefined') {
			  jcrop_api.destroy();
			}
			jQuery("<img/>").attr("src", MeFileURL).load(function(){
			     s = {w:this.width, h:this.height};
			     jQuery('#uploadedimage').Jcrop({
	                bgColor:     'black',
	                bgOpacity:   0.4,
	                allowMove: true,
	                allowResize: true,
	                minSize: [250, 250],
	                aspectRatio: 1,
	                allowSelect: false,

	                trueSize: [s.w, s.h],
	                setSelect:   [ 0, 0, 250, 250 ]
	            },function(){
	              jcrop_api = this;
	            });
			  });


	            jQuery("#me").fadeOut();
	            jQuery("#status").fadeOut();
	            jQuery("#cropme").fadeIn();
				jQuery("#cancelme0").fadeIn();
			});


		jQuery("#cropme").click(function() {
		    //Show PLEASE WAIT, we're cropping
		    var attribs = jcrop_api.tellSelect();
		    var data = {
		        x: attribs.x,
		        x2: attribs.x2,
		        y:attribs.y,
		        y2: attribs.y2,
		        file:MeFileLocation
		    };
			jQuery.post(templateDir + '/include/imageresizer1.php', data, function(responses) {
				//Show all kewl, we received cropped image
				var res = responses.split("_-*-_");
				jQuery('#files').html('<img id="uploadedimage" src="'+ res[0] +'" alt="" /><br />');

				FileHolders = res[1];
				jQuery('#postImage').val(res[0] );
				jQuery("#status").fadeOut();
				jQuery("#cropme").fadeOut();
				jQuery("#cancelme0").fadeOut();
				jQuery("#cancelme").fadeIn();

			});
		});

		jQuery("#cancelme").click(function(e) {

			var data = {
		        file: FileHolders
		    };
			jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {
				if (responses.substr(0,2) == "ok") {

					jQuery('#files').html('<img id="uploadedimage" src="" alt="" />'); //remove preview image
					jQuery("#status").html(''); // remove any status messages
					jQuery("#status").fadeIn(); //give back status previews
		            jQuery("#me").fadeIn();   // give back option to upload
					jQuery("#cancelme").fadeOut(); //hide this button
				}

			});

		});
		jQuery("#cancelme0").click(function(e) {
			var data = {
		        file: MeFileLocation
		    };
			jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {
				if (responses.substr(0,2) == "ok") {

					jQuery('#files').html('<img id="uploadedimage" src="" alt="" />'); //remove preview image
					jQuery("#status").html(''); // remove any status messages
					jQuery("#status").fadeIn(); //give back status previews
					jQuery("#cropme").fadeOut(); // hide the fadeout
		            jQuery("#me").fadeIn();   // give back option to upload
					jQuery("#cancelme0").fadeOut(); //hide this button
				}
			});

		});
 });
