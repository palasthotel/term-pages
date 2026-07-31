/**
 * Page autocomplete for the "Overriding page" term field.
 *
 * The visible input searches published pages, the adjacent hidden input carries
 * the page ID that is submitted with the term. Typing without picking a
 * suggestion clears the ID, so a half-typed title never silently keeps the
 * previously connected page.
 */
( function ( $ ) {
	'use strict';

	var config = window.termPagesAdmin || {};

	function setup( field ) {
		var $search = $( field );
		var $id = $search.nextAll( '.term-pages-page-id' ).first();

		if ( ! $id.length || $search.data( 'termPagesReady' ) ) {
			return;
		}

		$search.data( 'termPagesReady', true );

		$search.autocomplete( {
			minLength: config.minChars || 2,
			source: function ( request, response ) {
				$.post( config.ajaxUrl, {
					action: config.action,
					_ajax_nonce: config.nonce,
					taxonomy: $search.data( 'taxonomy' ),
					term: request.term
				} )
					.done( function ( result ) {
						response( result && result.success ? result.data : [] );
					} )
					.fail( function () {
						response( [] );
					} );
			},
			select: function ( event, ui ) {
				$search.val( ui.item.value );
				$id.val( ui.item.id );
				return false;
			},
			focus: function () {
				return false;
			}
		} );

		// Any manual edit invalidates the selection until a page is picked again.
		$search.on( 'input', function () {
			$id.val( '' );
		} );
	}

	$( function () {
		$( '.term-pages-page-search' ).each( function () {
			setup( this );
		} );
	} );

	// WordPress adds terms via AJAX and keeps hidden inputs, so reset the field
	// after a term was created to avoid reusing the page for the next one.
	$( document ).ajaxSuccess( function ( event, xhr, settings ) {
		if ( ! settings.data || settings.data.indexOf( 'action=add-tag' ) === -1 ) {
			return;
		}

		$( '.term-pages-page-search' ).val( '' );
		$( '.term-pages-page-id' ).val( '' );
	} );
} )( jQuery );
