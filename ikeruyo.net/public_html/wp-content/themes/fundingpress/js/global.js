 var Waiting = false;
   jQuery(document).ready(function($) {

		var t = jQuery("ul.sub-menu");
        t.parent().addClass('dropdown');
        t.addClass("dropdown-menu");
        $(".menu-header ul.nav .parent").addClass('dropdown');

        $("ul.children").parent().addClass('dropdown');
        $(".menu ul").parent().addClass('dropdown');

        $('ul.children').hover(
           function(){
               $(this).parent().addClass('active');
           }, function(){
               $(this).parent().removeClass('active');
           }
        );

        $('ul.sub-menu').hover(
           function(){
               $(this).parent().addClass('active');
           }, function(){
                 $(this).parent().removeClass('active');
           }
        );

      //Add Hover effect to menus
jQuery('.menu ul li.parent').hover(function() {
  jQuery(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn();
}, function() {
  jQuery(this).find('.dropdown-menu').stop(true, true).delay(30).fadeOut();
});


         //Add Hover effect to menus
jQuery('ul.nav li.parent').hover(function() {
  jQuery(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn();
}, function() {
  jQuery(this).find('.dropdown-menu').stop(true, true).delay(30).fadeOut();
});

	});


/******************** Isotope project block***********************/
var blog = jQuery(".isoprblck");
if(blog.length !== 0){
if(jQuery.isFunction(jQuery.fn.imagesLoaded)){

	//isotope
	var container = jQuery('.isoprblck');

	container.imagesLoaded( function(){

	// initialize Isotope
	container.isotope({
		// options...
		layoutMode : 'fitRows',
		resizable: false, // disable normal resizing
		// set columnWidth to a percentage of container width
		masonry: {
			columnWidth: container.width() / 3
		}
	});
	});
	// start new block
	jQuery('.cat a').click(function(){

		var selector = jQuery(this).attr('href');
		container.isotope({ filter: selector });
		return false;
	});
	// end new block

	// update columnWidth on window resize
	jQuery(window).smartresize(function(){
		//console.log(container.width());
		// set the widths on resize
		setWidths();
		container.isotope({
			// update columnWidth to a percentage of container width
			masonry: {
				columnWidth: getUnitWidth()
			}
		});
	}).resize();
}
}
/*  Isotope utility GetUnitWidth
    ========================================================================== */
function getUnitWidth() {
	var container = jQuery('.isoprblck');
	var width;
	if (container.width() <= 320) {
		//console.log("320");
		width = Math.floor((container.width() - 20) / 1);
	} else if (container.width() >= 321 && container.width() <= 480) {
		//console.log("321 - 480");
		width = Math.floor((container.width() - 50) / 1);
	} else if (container.width() >= 481 && container.width() <= 662) {
		//console.log("481 - 768");
		width = Math.floor((container.width() - 90) / 2);
	} else if (container.width() >= 663 && container.width() <= 768) {
		//console.log("663 - 768");
		width = Math.floor((container.width() - 90) / 2);
	} else if (container.width() >= 769 && container.width() <= 979) {
		//console.log("769 - 979");
		width = Math.floor((container.width() - 180) / 3);
	} else if (container.width() >= 980 && container.width() <= 1200) {
		//console.log("980 - 1200");
		width = Math.floor((container.width() - 200) / 4);
	} else if (container.width() >= 1201 && container.width() <= 1600) {
		//console.log("1201 - 1600");
		width = Math.floor((container.width() - 135) / 4);
	} else if (container.width() >= 1601 && container.width() <= 1824) {
		//console.log("1601 - 1824");
		width = Math.floor((container.width() - 135) / 8);
	} else if (container.width() >= 1825) {
		//console.log("1825");
		width = Math.floor((container.width() - 135) / 10);
	}
	return width;
}

/*  Isotope utility SetWidths
    ========================================================================== */
function setWidths() {
	var container = jQuery('.isoprblck');
	var unitWidth = getUnitWidth() - 0;
	container.children(":not(.width2)").css({
		width: unitWidth
	});

	if (container.width() >= 321 && container.width() <= 480) {
		//console.log("eccoci 321");
		container.children(".width2").css({
			width: unitWidth * 1
		});
		container.children(".width4").css({
			width: unitWidth * 2
		});
		container.children(".width6").css({
			width: unitWidth * 3
		});
	}
	if (container.width() >= 481) {
		//console.log("480");
		container.children(".width6").css({
			width: unitWidth * 4
		});
		container.children(".width4").css({
			width: unitWidth * 3
		});
		container.children(".width2").css({
			width: unitWidth * 2
		});
	} else {
		container.children(".width2").css({
			width: unitWidth
		});
	}
}

/*  Isotope utility GetUnitWidth
    ========================================================================== */
function getUnitWidthAll() {
    var container = jQuery('.isoprblckall');
    var width;
    if (container.width() <= 320) {
        //console.log("320");
        width = Math.floor((container.width() - 20) / 1);
    } else if (container.width() >= 321 && container.width() <= 480) {
        //console.log("321 - 480");
        width = Math.floor((container.width() - 30) / 1);
    } else if (container.width() >= 481 && container.width() <= 662) {
       // console.log("481 - 768");
        width = Math.floor((container.width() - 100) / 2);
    } else if (container.width() >= 663 && container.width() <= 768) {
        //console.log("663 - 768");
        width = Math.floor((container.width() - 90) / 2);
    } else if (container.width() >= 769 && container.width() <= 979) {
        //console.log("769 - 979");
        width = Math.floor((container.width() - 135) /3);
    } else if (container.width() >= 980 && container.width() <= 1200) {
        //console.log("980 - 1200");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1201 && container.width() <= 1600) {
       // console.log("1201 - 1600");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1601 && container.width() <= 1824) {
       // console.log("1601 - 1824");
        width = Math.floor((container.width() - 135) / 3);
    } else if (container.width() >= 1825) {
       // console.log("1825");
        width = Math.floor((container.width() - 135) / 3);
    }
    return width;
}

/*  Isotope utility SetWidths
    ========================================================================== */
function setWidthsAll() {
    var container = jQuery('.isoprblckall');
    var unitWidth = getUnitWidthAll() - 0;
    container.children(":not(.width2)").css({
        width: unitWidth
    });

    if (container.width() >= 321 && container.width() <= 480) {
        //console.log("eccoci 321");
        container.children(".width2").css({
            width: unitWidth * 1
        });
        container.children(".width4").css({
            width: unitWidth * 2
        });
        container.children(".width6").css({
            width: unitWidth * 3
        });
    }
    if (container.width() >= 481) {
        //console.log("480");
        container.children(".width6").css({
            width: unitWidth * 4
        });
        container.children(".width4").css({
            width: unitWidth * 3
        });
        container.children(".width2").css({
            width: unitWidth * 2
        });
    } else {
        container.children(".width2").css({
            width: unitWidth
        });
    }
}

/******************** Isotope blog ***********************/
var blog = jQuery(".blog");
if(blog.length !== 0){
// Modified Isotope methods for gutters in masonry

jQuery.Isotope.prototype._getMasonryGutterColumns = function() {
	var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
	var containerWidth = this.element.width();

	this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
	// Or use the size of the first item
	this.jQueryfilteredAtoms.outerWidth(true) ||
	// If there's no items, use size of container
	containerWidth;

	this.masonry.columnWidth += gutter;

	this.masonry.cols = Math.floor((containerWidth + gutter) / this.masonry.columnWidth);
	this.masonry.cols = Math.max(this.masonry.cols, 1);
};

jQuery.Isotope.prototype._masonryReset = function() {
	// Layout-specific props
	this.masonry = {};
	// FIXME shouldn't have to call this again
	this._getMasonryGutterColumns();
	var i = this.masonry.cols;
	this.masonry.colYs = [];
	while (i--) {
		this.masonry.colYs.push(0);
	}
};

jQuery.Isotope.prototype._masonryResizeChanged = function() {
	var prevSegments = this.masonry.cols;
	// Update cols/rows
	this._getMasonryGutterColumns();
	// Return if updated cols/rows is not equal to previous
	return (this.masonry.cols !== prevSegments);
};
// modified Isotope methods for gutters in masonry
if(jQuery.isFunction(jQuery.fn.imagesLoaded)){

	//isotope
	var containerblog = jQuery('.isoblog');

	containerblog.imagesLoaded( function(){
	// initialize Isotope

	containerblog.isotope({
		// options...
		layoutMode : 'masonry',
		resizable: false, // disable normal resizing
		// set columnWidth to a percentage of container width
		masonry: {
			columnWidth: (containerblog.width() - 40) / 4,
			gutterWidth: 20
		}
	});
	});

	// start new block
	jQuery('.cat a').click(function(){
		var selector = jQuery(this).attr('href');
		containerblog.isotope({ filter: selector });
		return false;
	});
	// end new block

	// update columnWidth on window resize
	jQuery(window).smartresize(function(){
		//console.log(container.width());
		// set the widths on resize
		setWidthsBlog();
		containerblog.isotope({
			// update columnWidth to a percentage of container width
			masonry: {
				columnWidth: getUnitWidthBlog(),
				gutterWidth: 20
			}
		});
	}).resize();
}
}
/*  Isotope utility SetWidths
    ========================================================================== */
function setWidthsBlog() {
	var unitWidth = getUnitWidthBlog() - 0;
	containerblog.children(":not(.width2)").css({
		width: unitWidth
	});

	if (containerblog.width() >= 321 && containerblog.width() <= 480) {
		//console.log("eccoci 321");
		containerblog.children(".width2").css({
			width: unitWidth * 1
		});
		containerblog.children(".width4").css({
			width: unitWidth * 2
		});
		containerblog.children(".width6").css({
			width: unitWidth * 3
		});
	}
	if (containerblog.width() >= 481) {
		//console.log("480");
		containerblog.children(".width6").css({
			width: unitWidth * 4
		});
		containerblog.children(".width4").css({
			width: unitWidth * 3
		});
		containerblog.children(".width2").css({
			width: unitWidth * 2
		});
	} else {
		containerblog.children(".width2").css({
			width: unitWidth
		});
	}
}

/*  Isotope utility GetUnitWidth
    ========================================================================== */
function getUnitWidthBlog() {
	var width;
	if (containerblog.width() <= 320) {
		//console.log("320");
		width = Math.floor((containerblog.width() - 20)  / 1);
	} else if (containerblog.width() >= 321 && containerblog.width() <= 480) {
		//console.log("321 - 480");
		width = Math.floor((containerblog.width() - 20)  / 1);
	} else if (containerblog.width() >= 481 && containerblog.width() <= 662) {
		//console.log("481 - 768");
		width = Math.floor((containerblog.width() - 20) / 1);
	} else if (containerblog.width() >= 663 && containerblog.width() <= 768) {
		//console.log("663 - 768");
		width = Math.floor((containerblog.width() - 20)  / 2);
	} else if (containerblog.width() >= 769 && containerblog.width() <= 979) {
		//console.log("769 - 979");
		width = Math.floor((containerblog.width() - 40)  / 3);
	} else if (containerblog.width() >= 980 && containerblog.width() <= 1200) {
		//console.log("980 - 1200");
		width = Math.floor((containerblog.width() - 40)  / 4);
	} else if (containerblog.width() >= 1201 && containerblog.width() <= 1600) {
		//console.log("1201 - 1600");
		width = Math.floor((containerblog.width() - 60)  / 4);
	} else if (containerblog.width() >= 1601 && containerblog.width() <= 1824) {
		//console.log("1601 - 1824");
		width = Math.floor((containerblog.width() - 20)  / 8);
	} else if (containerblog.width() >= 1825) {
		//console.log("1825");
		width = Math.floor((containerblog.width() - 20)  / 10);
	}
	return width;
}

/********empty p fix *************/
jQuery(document).ready(function() {

  jQuery('p:empty').remove();

var res = jQuery(".page-template-tmp-my-account");
var res1 = jQuery(".page-template-tmp-all-projects");
var res2 = jQuery(".search-results");
var res3 = jQuery(".page-template-tmp-blog-isotope");
var res4 = jQuery(".isoprblck");
if(res.length !== 0 || res1.length !== 0 || res2.length !== 0 || res3.length !== 0 || res4.length !== 0){

   	if(Waiting == false) {
	    setTimeout(Resizer, 800);
	    setTimeout(Resizer2, 2000);
	    Waiting = true;
	}
	 jQuery( window ).resize(function() {
	             if (Waiting == false) {

                        setTimeout(Resizer, 400);
                        setTimeout(Resizer2, 1000);
                        Waiting = true;
                    }
                });
}

var all_projects_cat_click = jQuery(".get_all_prj");
all_projects_cat_click.on( "click", function() {
	Waiting = false;
	if(Waiting == false) {
	    setTimeout(Resizer, 800);
	    setTimeout(Resizer2, 2000);
	    Waiting = true;
	}
	 jQuery( window ).resize(function() {
	             if (Waiting == false) {

                        setTimeout(Resizer, 400);
                        setTimeout(Resizer2, 1000);
                        Waiting = true;
                    }
                });
});
 });

function Resizer() {
	if(jQuery.isFunction(jQuery.fn.imagesLoaded)){
		var container = jQuery('.isoprblck');
		if (container) {
	        jQuery(window).smartresize(function(){
			//console.log(container.width());
			// set the widths on resize
			setWidths();
			container.isotope({
				// update columnWidth to a percentage of container width
				masonry: {
					columnWidth: getUnitWidth()
				}
			});
		}).resize();
		}
	    Waiting = true;
    }
}


function Resizer2() {
	if(jQuery.isFunction(jQuery.fn.imagesLoaded)){
		var container = jQuery('.isoprblckall');
		if (container) {
	        jQuery(window).smartresize(function(){
			//console.log(container.width());
			// set the widths on resize
			setWidthsAll();
			container.isotope({
				// update columnWidth to a percentage of container width
				masonry: {
					columnWidth: getUnitWidthAll()
				}
			});
		}).resize();
		}
	    Waiting = true;
    }
}

var video = document.getElementById('videop');
if(typeof(video) != 'undefined' && video != null){
video.addEventListener('click',function(){
  video.play();
},false);
}


var postDate = jQuery( "input[name=postDate]" );
if(postDate.length !== 0){
	jQuery(function () {
		postDate.datepicker({minDate: '0', dateFormat: formatdatuma });
	});
}

jQuery(document).ready(function() {
	var funding_methods = jQuery('#funding_methods li');
	funding_methods.on( "click", function() {
		jQuery( this ).find( "input" ).prop('checked', true);
	});


/*Mobile menu parent click with submenu fix*/
if (jQuery(window).width() < 960) {
jQuery('header .nav li a').click(function(e){
    if(!jQuery(this).parent().hasClass('active')) {
        jQuery('.nav li').removeClass('active');
        jQuery(this).parent().addClass('active');
        e.preventDefault();
    } else {
        return true;
    }
});
}

});


/******************** Woocommerce ***********************/
function shopping_cart_dropdown() {
    !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && jQuery(".cart-menu-wrap").addClass("has_products")
}

function shopping_cart_dropdown_show(t) {
    clearTimeout(timeout), !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && "undefined" != typeof t.type && (jQuery(".container .cart-menu-wrap").hasClass("has_products") ? jQuery(".container .cart-notification").is(":visible") ? jQuery(".container .cart-notification").show() : jQuery(".container .cart-notification").fadeIn(400) : setTimeout(function() {
        jQuery(".container .cart-notification").fadeIn(400)
    }, 400), timeout = setTimeout(hideCart, 2700))
}

function hideCart() {
    jQuery(".container .cart-notification").stop(!0, !0).fadeOut()
}
