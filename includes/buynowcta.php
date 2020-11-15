<?php
/**
 * CommerceGurus Enable coupon cookie (then used by js on checkout to show coupon code box.
 *
 * @package commercegurus
 */

?>

<script>
;
( function( $ ) {
	"use strict";

	$(document).ready(function(){

		function cgexp_body() {
			// check if vmexp cookie is set.
			var cgexp_cookie = getCookie("cgexp");
			if ( cgexp_cookie == 'bnw1' ) {
				$(document.body).addClass('bnw1');
			} else if ( cgexp_cookie == 'bnw2' ) {
				$(document.body).addClass('bnw2');				
			} else {
				$(document.body).addClass('bnw0');								
			}
		}
		cgexp_body();
	});

}( jQuery ) );

function getCookie(name) {
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);
	if (begin == -1) {
		begin = dc.indexOf(prefix);
		if (begin != 0) return null;
	}
	else
	{
		begin += 2;
		var end = document.cookie.indexOf(";", begin);
		if (end == -1) {
		end = dc.length;
		}
	}
	// because unescape has been deprecated, replaced with decodeURI
	//return unescape(dc.substring(begin + prefix.length, end));
	return decodeURI(dc.substring(begin + prefix.length, end));
} 
</script>

<style>
	.cta-widget {
		display: none;
	}

	.bnw0 .cta-widget--bnw0 {
		display: block;
	}

	.bnw1 .cta-widget--bnw1 {
		display: block;
	}

	.bnw2 .cta-widget--bnw2 {
		display: block;
	}
</style>
