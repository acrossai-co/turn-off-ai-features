( function () {
	var main = document.getElementById( 'toaif_disable_ai' );
	var sub  = document.getElementById( 'toaif_hide_connectors' );
	if ( ! main || ! sub ) {
		return;
	}
	var row = sub.closest( 'tr' );
	if ( ! row ) {
		return;
	}
	var update = function () {
		row.style.display = main.checked ? '' : 'none';
	};
	main.addEventListener( 'change', update );
	update();
} )();
