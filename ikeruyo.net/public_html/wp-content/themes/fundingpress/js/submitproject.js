/*global jQuery:false */
jQuery.noConflict();
var ImageHolderDefault = jQuery('img.pbimage').attr("src");
var FileHolder = "";
var FileHolderBck = "";
var CurrAjaxUploader = "";
var WhoCalled = 0;
var mestatus = jQuery('#mestatus');
var files = jQuery('#files');
var mestatusBck = jQuery('#mestatusBck');
var filesBck = jQuery('#filesBck');
var OldMe = jQuery("#me").html();
var OldMeBck = jQuery("#meBck").html();
var MeFileLocation = "";
var MeFileURL = "";
var MeFileLocationBck = "";
var MeFileURLBck = "";
var RewardsHolderIDs = 0;



/************Campaign form validation*****************/
jQuery(document).ready(function() {
    try {
        jQuery("#primaryPostForm").validate();
    } catch (err) {
        // Handle error(s) here
    }

});


/******************Imageupload handling*************************/

jQuery(document).ready(function() {
  setTimeout(function() {
    jQuery('#me').fadeIn('slow');

  }, 1000);
    jQuery("#me").mouseenter(function() {
        jQuery('#meimgbck').fileupload();
        jQuery('#meimgbck').fileupload('destroy');
        jQuery('#meimg').fileupload({
            url: templateDir + '/include/UploadHandler.php',
            sequentialUploads: true,
            add: function(e, data) {
                var uploadErrors = [];
                var acceptFileTypes = /^image\/(jpe?g|png)$/i;
                if (data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                    uploadErrors.push('Not an accepted file type');
                }
                if (data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5000000) {
                    uploadErrors.push('Filesize is too big');
                }
                if (uploadErrors.length > 0) {
                    jQuery("#status").html(uploadErrors.join("\n"));
                } else {
                    jQuery('.wp-post-image').attr("src", ImageHolderDefault); //remove image preview
                    jQuery("#status").html(settingsCustom.uploadphotoscu);
                    jQuery('#load').append("<div id='loadingimage'></div>");
                    TurnOffMeBck();
                    data.submit();
                }
            }

        }).bind('fileuploaddone', function(e, data) {

            MeFileLocation = uploadsettings.wp_upload_dir_path + data.files[0].name
            MeFileURL = uploadsettings.wp_upload_dir_url + data.files[0].name
            jQuery('#loadingimage').remove();
            jQuery('#files').html('<img id="uploadedimage" style="width:100%;" src="' + MeFileURL + '" alt="" /><br />');

            if (typeof jcrop_api != 'undefined') {
                jcrop_api.destroy();
            }
            jQuery("<img/>").attr("src", MeFileURL).load(function() {
                s = {
                    w: this.width,
                    h: this.height
                };
                jQuery('#uploadedimage').Jcrop({
                    bgColor: 'black',
                    bgOpacity: 0.4,
                    allowMove: true,
                    allowResize: true,
                    minSize: [360, 240],
                    allowSelect: false,
                    aspectRatio: 3 / 2,
                    trueSize: [s.w, s.h],
                    setSelect: [0, 0, 360, 240]
                }, function() {
                    jcrop_api = this;
                });
            });


            jQuery("#me").fadeOut();
            jQuery("#status").fadeOut();
            jQuery("#cropme").fadeIn();
            jQuery("#cancelme0").fadeIn();
        });
    });


    /* For the capaign photo*/






    jQuery("#meBck").mouseenter(function() {
        /* For the capaign background*/
        jQuery('#meimg').fileupload();
        jQuery('#meimg').fileupload('destroy');
        jQuery('#meimgbck').fileupload({
            url: templateDir + '/include/UploadHandler.php',
            sequentialUploads: true,
            add: function(e, data) {
                var uploadErrors = [];
                var acceptFileTypes = /^image\/(jpe?g|png)$/i;
                if (data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                    uploadErrors.push('Not an accepted file type');
                }
                if (data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5000000) {
                    uploadErrors.push('Filesize is too big');
                }
                if (uploadErrors.length > 0) {
                    jQuery("#statusBck").html(uploadErrors.join("\n"));
                } else {
                    jQuery("#statusBck").html(settingsCustom.uploadphotoscu);
                    jQuery('#loadBck').append("<div id='loadingimageBck'></div>");
                    TurnOffMe();
                    data.submit();
                }
            }
        }).bind('fileuploaddone', function(e, data) {
            MeFileLocationBck = uploadsettings.wp_upload_dir_path + data.files[0].name
            MeFileURLBck = uploadsettings.wp_upload_dir_url + data.files[0].name
            jQuery('#loadingimageBck').remove();
            jQuery('#filesBck').html('<img id="uploadedimageBck" style="width:100%;" src="' + MeFileURLBck + '" alt="" /><br />');
            if (typeof jcrop_api != 'undefined') {
                jcrop_api.destroy();
            }
            jQuery("<img/>").attr("src", MeFileURLBck).load(function() {
                s = {
                    w: this.width,
                    h: this.height
                };
                jQuery('#uploadedimageBck').Jcrop({
                    bgColor: 'black',
                    bgOpacity: 0.4,
                    allowMove: true,
                    allowResize: true,
                    minSize: [360, 240],
                    allowSelect: false,
                    trueSize: [s.w, s.h],
                    setSelect: [0, 0, 360, 240]
                }, function() {
                    jcrop_api = this;
                });
            });


            jQuery("#meBck").fadeOut();
            jQuery("#statusBck").fadeOut();
            jQuery("#cropmeBck").fadeIn();
            jQuery("#cancelme0Bck").fadeIn();
        });
    });





    jQuery("#cropme").click(function() {
        //Show PLEASE WAIT, we're cropping
        var attribs = jcrop_api.tellSelect();
        var data = {
            x: attribs.x,
            x2: attribs.x2,
            y: attribs.y,
            y2: attribs.y2,
            file: MeFileLocation
        };
        jQuery.post(templateDir + '/include/imageresizer.php', data, function(responses) {
            //Show all kewl, we received cropped image
            var res = responses.split("_-*-_");
            jQuery('#files').html('<img id="uploadedimage" src="' + res[0] + '" alt="" /><br />');

            jQuery('#postImage').val(res[1]);

            jQuery('.wp-post-image').attr("src", res[0]);
            jQuery('img.pbimage').attr("src", res[0]);
            jQuery("#status").fadeOut();
            jQuery("#cropme").fadeOut();
            jQuery("#cancelme0").fadeOut();

            jQuery("#cancelme").fadeIn();
            TurnOnMeBck();
            jQuery("#meBck").html(OldMeBck);
        });
    });


    jQuery("#cancelme").click(function(e) {
        var FileHolders = jQuery('#postImage').val(); //is the uploaded image
        var data = {
            file: FileHolders
        };
        jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {
            if (responses.trim().substr(0, 2) == "ok") {
                jQuery('#postImage').val(''); //set image post url to blank
                jQuery('img.pbimage').attr("src", ImageHolderDefault); //remove image preview
                jQuery('#files').html('<img id="uploadedimage" src="" alt="" /><br />'); //remove preview image
                jQuery("#status").html(''); // remove any status messages
                jQuery("#status").fadeIn(); //give back status previews
                jQuery("#me").fadeIn(); // give back option to upload
                jQuery("#cancelme").fadeOut(); //hide this button
            }
        });
        TurnOnMeBck();
        jQuery("#meBck").html(OldMeBck);

    });
    jQuery("#cancelme0").click(function(e) {
        var data = {
            file: MeFileLocation
        };

        jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {

            if (responses.trim().substr(0, 2) == "ok") {
                jQuery('#postImage').val(''); //set image post url to blank
                jQuery('img.pbimage').attr("src", ImageHolderDefault); //remove image preview
                jQuery('#files').html('<img id="uploadedimage" src="" alt="" /><br />'); //remove preview image
                jQuery("#status").html(''); // remove any status messages
                jQuery("#status").fadeIn(); //give back status previews
                jQuery("#cropme").fadeOut(); // hide the fadeout
                jQuery("#me").fadeIn(); // give back option to upload
                jQuery("#cancelme0").fadeOut(); //hide this button
            }
        });
        TurnOnMeBck();
        jQuery("#meBck").html(OldMeBck);

    });


    jQuery("#cropmeBck").click(function() {
        //Show PLEASE WAIT, we're cropping
        var attribs = jcrop_api.tellSelect();
        var data = {
            x: attribs.x,
            x2: attribs.x2,
            y: attribs.y,
            y2: attribs.y2,
            file: MeFileLocationBck
        };
        jQuery.post(templateDir + '/include/imageresizerother.php', data, function(responses) {
            //Show all kewl, we received cropped image
            var res = responses.split("_-*-_");
            jQuery('#filesBck').html('<img id="uploadedimageBck" src="' + res[0] + '" alt="" /><br />');

            jQuery('#postBackground').val(res[1]);

            jQuery("#statusBck").fadeOut();
            jQuery("#cropmeBck").fadeOut();
            jQuery("#cancelme0Bck").fadeOut();

            jQuery("#cancelmeBck").fadeIn();
            TurnOnMe();
            jQuery("#me").html(OldMe);
        });
    });

    jQuery("#cancelmeBck").click(function(e) {
        var FileHolderBck1 = jQuery('#postBackground').val(); //is the uploaded image
        var data = {
            file: FileHolderBck1
        };
        jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {
            if (responses.trim().substr(0, 2) == "ok") {
                jQuery('#postBackground').val(''); //set image post url to blank
                jQuery('#filesBck').html('<img id="uploadedimageBck" src="" alt="" /><br />'); //remove preview image
                jQuery("#statusBck").html(''); // remove any status messages
                jQuery("#statusBck").fadeIn(); //give back status previews
                jQuery("#meBck").fadeIn(); // give back option to upload
                jQuery("#cancelmeBck").fadeOut(); //hide this button
            }
        });
        TurnOnMe();
        jQuery("#me").html(OldMe);
    });
    jQuery("#cancelme0Bck").click(function(e) {
        var data = {
            file: MeFileLocationBck
        };
        jQuery.post(templateDir + '/include/imageremove.php', data, function(responses) {
            if (responses.trim().substr(0, 2) == "ok") {
                jQuery('#postBackground').val(''); //set image post url to blank
                jQuery('#filesBck').html('<img id="uploadedimageBck" src="" alt="" /><br />'); //remove preview image
                jQuery("#statusBck").html(''); // remove any status messages
                jQuery("#statusBck").fadeIn(); //give back status previews
                jQuery("#cropmeBck").fadeOut(); // hide the fadeout
                jQuery("#meBck").fadeIn(); // give back option to upload
                jQuery("#cancelme0Bck").fadeOut(); //hide this button
            }
        });
        TurnOnMe();
        jQuery("#me").html(OldMe);
    });

    //Adding a reward handler
    jQuery("#add_reward").click(function(e) {
        jQuery("#rewards_holder").append('<div class="reward_holder" id="reward_' + RewardsHolderIDs + '">' +
            '<label for="title_' + RewardsHolderIDs + '">' + settingsCustom.rewtitle + '</label><input type="text" id="title_' + RewardsHolderIDs + '" name="title_' + RewardsHolderIDs + '" class="required"> ' +
            '<label for="description_' + RewardsHolderIDs + '">' + settingsCustom.rewdesc + '</label><textarea id="description_' + RewardsHolderIDs + '" name="description_' + RewardsHolderIDs + '" class="required"></textarea> ' +
            '<label for="minimum_' + RewardsHolderIDs + '">' + settingsCustom.minimumammount + '</label><input type="text" id="minimum_' + RewardsHolderIDs + '" name="minimum_' + RewardsHolderIDs + '" class="required"> ' +
            '<label for="available_' + RewardsHolderIDs + '">' + settingsCustom.available + '</label><input type="text" id="available_' + RewardsHolderIDs + '" name="available_' + RewardsHolderIDs + '" class="required"> ' +
            '<div class="removeme button-small button-inverted" onclick="RemoveMe(' +
            RewardsHolderIDs + ')" id="' + RewardsHolderIDs +
            '">' + settingsCustom.remove + '</div></div>');
        RewardsHolderIDs++;
    });



});

function RemoveMe(theid) {
    jQuery("#reward_" + theid).remove();
}


function TurnOnMe() {
    jQuery("#me").fadeIn();
    jQuery("#status").html("");
}

function TurnOffMe() {
    jQuery("#me").fadeOut();
    jQuery("#status").html(settingsCustom.pleasefinishcrop);
}

function TurnOffMeBck() {
    jQuery("#meBck").fadeOut();
    jQuery("#statusBck").html(settingsCustom.pleasefinishcrop);
}

function TurnOnMeBck() {
    jQuery("#meBck").fadeIn();
    jQuery("#statusBck").html("");
}


/*************** Sidebar generate project data *******************/
jQuery('#postTitle').on('keyup', function() {
    jQuery('.sidebar .pb-title h3').text(jQuery(this).val());
});

jQuery(document).ready(function() {
    jQuery('#amount').on('keyup', function() {
        var value = parseInt(jQuery(this).val()).toLocaleString();
        jQuery('.sidebar .pb-target .am').text(value);
    });
});


jQuery(document).ready(function() {
    window.onload = function() {
      if (typeof tinymce !== "undefined") {

        tinymce.get('postStory').on('keyup', function(e) {
          jQuery('.sidebar .pb-content').html(tinymce.get('postStory').getContent().substring(0, 83) + '...');
        });
      }
    }
});

jQuery('#postCategory').on('change', function() {
    //This gets called when category gets changed
    jQuery('.pb-category').text(jQuery('#postCategory').val());
});



/*************** Sidebar fixed *******************/
if (jQuery(".sidebar .pb-container").length !== 0) {
    jQuery(function($) {

        var $sidebar = $(".sidebar .pb-container"),
            $window = $(window),
            offset = $sidebar.offset(),
            topPadding = 70;
        $
        $window.scroll(function() {

            if ($window.scrollTop() > offset.top) {
                if (($window.scrollTop() - offset.top + topPadding + $sidebar.height()) < $("#primary").height()) {
                    $sidebar.stop().animate({
                        marginTop: $window.scrollTop() - offset.top + topPadding
                    });
                }
            } else {
                $sidebar.stop().animate({
                    marginTop: 0
                });
            }
        });

    });
}

/************Days left callers*****************/
jQuery('#postDate').on('change', function() {
    jQuery("#daysLeft").html(DaysLeft());
});


/************Campaign days left calculator*****/

function formattedDate(date) {
    if (formatdatuma == "dd/mm/yy") {
        split = date.split('/');
        return [split[1], split[0], split[2]].join('/');;


    } else {

        var d = new Date(date || Date.now()),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [month, day, year].join('/');
    }
}

function DaysLeft() { // WARNING: DATEPICKER HAS TO BE MM/DD/YYYY
    var date = jQuery('#postDate').val(); //DATE IN FORMAT 'MM/DD/YYY'
    var date1 = formattedDate(date);

    //GET CURRENT UTC TIME
    var d = new Date();
    var firstDate = new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds());

    var oneDay = 24 * 60 * 60 * 1000; //this is one day


    var days = parseInt(date1.substring(3, 5)) + 1;
    var secondDate = new Date(parseInt(date1.substring(6)), parseInt(date1.substring(0, 2)) - 1, days);

    if (firstDate.getTime() < secondDate.getTime()) {
        var diffDays = Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay));
        return Math.round(diffDays);
    } else {
        return 0;
    }
}

/****************************Set values on edit**************************/
jQuery(document).ready(function() {
    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    if ($_GET['edit']) {

        try {

            jQuery('.pb-category').text(jQuery('#postCategory').val());
            jQuery('.pb-title h3').text(jQuery('#postTitle').val());

            jQuery('.am').text(jQuery('#amount').val());
            jQuery("#daysLeft").html(DaysLeft());

             window.onload = function() {
                jQuery('.sidebar .pb-content').html(tinymce.get('postStory').getContent().substring(0, 83) + '...');
            }

        } catch (err) {};

    }
});
