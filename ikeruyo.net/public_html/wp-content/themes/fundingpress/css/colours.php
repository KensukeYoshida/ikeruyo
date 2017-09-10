<style>
    /* Backgrounds colours */

.green-bg,
.dropdown-menu li > a:hover,
.dropdown-menu li > a:focus,
.dropdown-submenu:hover > a,
.tagcloud a:hover,
#pager li a.active, #pager li a:hover,
.menu ul > li > a:hover,
.navbar-inverse .nav > li > a:focus,
.navbar-inverse .nav > li > a:hover,
.navbar-inverse .nav .active > a,
.navbar-inverse .nav .active > a:hover,
.navbar-inverse .nav .active > a:focus,
.navbar-inverse .navbar-nav > li > a:after,
.navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
.navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
.tabbable .nav nav-tabs li a,
.bgcolor, #bgcolor, body .nav-tabs > li.active > a, body .nav-tabs > li.active > a:hover, body .nav-tabs > li.active > a:focus,
.progress .bar, body .newsbv li .ncategory, .ncategory,
.bar-green.progress-striped .bar, input[type=submit],
.navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle,a.next:hover, a.prev:hover, .pb-category, .nav-collapse ul.nav li.megamenu > ul > li > ul > li a:hover, .navbar .nav > .active > a, .navbar .nav > .active > a:hover, .navbar .nav > .active > a:focus, .cart-outer {
    background-color:<?php echo of_get_option('primary_color'); ?> ;
}

.woocommerce nav.woocommerce-pagination ul li span.current, .single-product.woocommerce div.product form.cart .button.single_add_to_cart_button, .woocommerce-message a.button {
	background-color:<?php echo of_get_option('primary_color'); ?> !important;
}

/* Border colours */
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus,
.navbar-inverse .brand:hover,
.navbar-inverse .nav > li > a:hover,
.menu ul > li > a:hover,
.project-update-info img, body .nav-tabs > li.active > a, body .nav-tabs > li.active > a:hover, body .nav-tabs > li.active > a:focus,
.project-backer .cl-lg-3 img,
.project-comment .cl-lg-1 img,
.project-gallery .gallery-image a,
.tabbable .nav nav-tabs li a, .nav-tabs .active a:active, .highlight-block .project-thumb-wrapper a,
.project-gallery .gallery-image a:hover,
#funding-form #field-amount, .fund-tabs-cont .nav-tabs > li > a:hover, .fund-tabs-cont .nav-tabs > li.active > a, footer h3,
.widget_images a img:hover, .widget h3, .navbar .nav > .active > a, .navbar .nav > .active > a:hover, .navbar .nav > .active > a:focus, body.woocommerce nav.woocommerce-pagination ul li a,  body.woocommerce nav.woocommerce-pagination ul li span.current {
  border-color: <?php echo of_get_option('primary_color'); ?> !important ;
  /* IE6-9 */

}

/* Color */
.newsb-title a:hover, body a, a.login-top i, a.register-top i, a.account-top i, a.submit-top i, a.logout-top i, .category-container .project-stats li strong,
.container ul.top-nav li.current-menu-item a, .comment_author_name, .footer_widget > ul > li > a:hover:after, .category-container #post-content .project_collected strong,
.navbartop-wrapper .container ul.top-nav li:hover, .project-thumb-wrapper h5 a:hover, .category-menu li a:hover:after, .category-menu li.current a:after,
.nav-tabs .active a:active, .fund-tabs-cont .nav-tabs > li > a strong, .category-menu li a:hover, .category-menu li.current a,
body.user_project #wpcontent a, .author-info a, h5.bbcard_name a:hover, .footer_widget > ul > li > a:after,
body.user_project .breadcrumbs a, .project-card p.plocation span, .blog-list h2 a:hover, .carousel_rating,
 .navbartop-wrapper .container ul.top-nav li a:hover, a.next:hover, a.prev:hover, #project-rewards-list span, #funding-form #field-amount, .icon span{
    color: <?php echo of_get_option('primary_color'); ?> ;
}
.button-green, .button-small, .button-medium, .nav-tabs .ui-state-active a, body .nav > li > a:hover, .nav-tabs li.active a, .nav-tabsin > .active > a, .nav-tabsin > .active > a:hover, .nav-tabsin > .active > a:focus{
    background-color: <?php echo of_get_option('button_green'); ?>
}
.button-green:hover, .button-small:hover, .button-medium:hover, .navsin > li > a, body.woocommerce nav.woocommerce-pagination ul li a:hover{
    background-color: <?php echo of_get_option('button_hover'); ?> ;
}

.progress .bar, .highlight-block .project-thumb-wrapper a{
	/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#1e5799+0,82f3bd+0,7db9e8+100 */
	background: <?php echo of_get_option('pb-second'); ?>; /* Old browsers */
	background: -moz-linear-gradient(left, <?php echo of_get_option('pb-first'); ?>  0%, <?php echo of_get_option('pb-second'); ?> 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(left, <?php echo of_get_option('pb-first'); ?> 0%, <?php echo of_get_option('pb-second'); ?> 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to right, <?php echo of_get_option('pb-first'); ?> 0%, <?php echo of_get_option('pb-second'); ?> 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo of_get_option('pb-first'); ?>', endColorstr='<?php echo of_get_option('pb-second'); ?>',GradientType=1 ); /* IE6-9 */

}
body.single-project .project-info .progress .bar{
	/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#1e5799+0,82f3bd+0,7db9e8+100 */
	background: <?php echo of_get_option('pb-second'); ?>; /* Old browsers */
	background: -moz-linear-gradient(bottom, <?php echo of_get_option('pb-first'); ?>  0%, <?php echo of_get_option('pb-second'); ?> 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(bottom, <?php echo of_get_option('pb-first'); ?> 0%, <?php echo of_get_option('pb-second'); ?> 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to top, <?php echo of_get_option('pb-first'); ?> 0%, <?php echo of_get_option('pb-second'); ?> 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo of_get_option('pb-first'); ?>', endColorstr='<?php echo of_get_option('pb-second'); ?>',GradientType=1 ); /* IE6-9 */
}
</style>