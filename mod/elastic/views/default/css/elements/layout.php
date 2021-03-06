<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>

html {
     overflow: -moz-scrollbars-vertical;
     overflow: scroll;
}

@font-face{ 
    font-family: History; 
    src: url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/fonts/Gondola_SD.eot); /* For IE */ 
    src: local('History'), url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/fonts/Capture_it.ttf) format('truetype'); /* For non-IE */ 
}  

.elgg-page-default {
	min-width: 998px;
	min-height: 100%;
	position: relative;
}

.elgg-page-default .elgg-page-topbar > .elgg-inner {
	margin: 0 auto;

}
.elgg-page-default .elgg-page-header > .elgg-inner {
	min-width: 600px;
    max-width: 990px;
	margin: 0 auto;
}

.elgg-page-body {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/timber150.jpg) top left repeat;
	background: #FFF;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/foottile.jpg) top left repeat;
	padding-top: 10px;
	box-shadow: 0 6px 20px #000;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	min-width: 600px;
    max-width: 990px;
	margin: 0px auto;
}

/* ************** FOOTER **************** */


.elgg-above-footer {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/timber_bottom_thin150.png) repeat-x;
	//background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/arrow.png) repeat-x;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/band_bottom.png) repeat-x;
	height: 20px;
	width: 100%;
}
.elgg-above-main {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/timber_top_thin150.png) repeat-x;
	height: 0px;
	width: 100%;
}



/***** PAGE FOOTER ******/

.elgg-page-footer{
        bottom: 0;
        position:relative;
		text-align: left;
		color: #999;
}

#history-font {
	font-family: History, Ubuntu;
}

.elgg-page-footer a:hover {
	color: #666;
}

.elgg-page-default .elgg-page-footer > .elgg-inner {
        min-width: 600px;
        max-width: 990px;
        margin: 0 auto;
        padding: 5px 0;
        min-height: 150px;
        margin-bottom: 20px;
		background: transparent;
}

#main-wrapper{
        background-color: #FFF;
        padding-bottom: 50px;
}



/**************** PAGE MESSAGES ******************/
.elgg-system-messages {
	position: fixed;
	top: 50px;
	right: 20px;
	max-width: 500px;
	z-index: 11000;
}
.elgg-system-messages li {
	margin-top: 20px;
	color: white;
}
.elgg-system-messages li p {
	margin: 0;
	color: white;
}

/* ************** HEADER **************** */
.elgg-page-header {
	position: relative;
    background: transparent;
}

.elgg-heading-site, .elgg-heading-site:hover {
	font-family: History;
	font-style: normal;
	font-size: 2.5em;
	text-shadow: 2px 2px 5px #000;
}

.elgg-heading-site:hover {
	text-shadow: 0px 0px 3px #000;
}

.elgg-page-header > .elgg-inner {
	position: relative;
    padding: 10px 0 30px 0;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/soldiers200.png) right top no-repeat;
}

.elgg-page-header-front
{
    position: relative;
    background-color: #83CAFF;
    height: 350px;
    padding: 50px 0 0 0;
    border-bottom: 5px solid #DDD;
	font-family: History;
}

.livpast-page-header > .elgg-inner {
	min-width: 600px;
    max-width: 990px;
	margin: 0px auto;
	position: relative;
    padding: 10px 0 30px 0;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/soldiers200.png) right top no-repeat;
}


.livpast-page-header-front > .elgg-inner
{
	min-width: 600px;
    max-width: 990px;
	margin: 0px auto;
    position: relative;
    padding: 10px 0 30px 0;
	min-height: 450px;
	//background: transparent url(<?php echo elgg_get_site_url(); ?>mod/elastic/graphics/backgrounds/soldiers200.png) right top no-repeat;
}

div#header-logo {
	z-index: 1;
}

div#header-front-picture-back {
	position: absolute;
	bottom: -16px;
	left: -460px;
	z-index: -1;
}


div#header-front-picture {
	position: absolute;
	top: 0;
	right: 0;
	z-index: -1;
}

div#header-front-picture2 {
	position: absolute;
	bottom: 0;
	left: -300px;
	line-height: 0;
	z-index: -1;
}

div#header-front-picture-wheel {
	position: absolute;
	bottom: 50px;
	right: -50px;
	line-height: 0;
	z-index: 1;
}

div#header-front-picture-advert {
	position: absolute;
	top: 130px;
	left: 100px;
	z-index: 1;
}

div#header-front-picture-advert div {
	display: table;
	line-height: 1.8em;
	float: left;
	color: #CCC;
	font-size: 1.5em;
	vertical-align: center;
	text-shadow: 0 0 3px #000;
}

div#header-front-picture-advert div:last-child  {
	margin-top: 120px;
}

div#header-front-picture2 img {
	font-size: 0;
}

/************* PAGE BODY LAYOUT ********************/


.elgg-layout {
	min-height: 260px;
}
.elgg-layout-one-sidebar {
	
	background: #FFF;
	margin: 20px;
	padding: 20px;

}
.elgg-layout-two-sidebar {
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif) repeat-y right top;
}

.elgg-layout-one-column{
    background: rgba(255,255,255,0.5);
}

.width-730
{
    width: 730px;
}

.elgg-sidebar {
	position: relative;
	padding: 20px 0px;
	float: right;
	width: 220px;
	margin: 0 0 0 10px;
}
.elgg-sidebar h3
{
    color: #AAA;
}

.elgg-sidebar-alt {
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
}
.elgg-main {
	position: relative;
	min-height: 360px;
}
.elgg-main > .elgg-head {
	padding-bottom: 3px;
	//border-bottom: 1px solid #CCCCCC;
	margin-bottom: 10px;
}

div.elgg-body {
	padding-top: 15px;
	padding-bottom: 15px;
}




/* ***********  LOGO *************** */
