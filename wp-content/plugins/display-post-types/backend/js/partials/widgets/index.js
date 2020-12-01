( function( $ ) {
	// Add event triggers to the show/hide items.
	$('#widgets-right').on('change', 'select.dpt-post-type', function() {
		showPosttypeContent( $(this) );
	});

	$('#widgets-right').on('change', 'select.dpt-taxonomy', function() {
		showTerms( $(this) );
	});

	$('#widgets-right').on('change', 'select.dpt-styles', function() {
		showColumnCount( $(this) );
	});

	$('#widgets-right').on('change', 'select.dpt-img-aspect', function() {
		showCroppos( $(this) );
	});

	$('#widgets-right').on('change', '.styles-supported input[type="checkbox"]', function() {
		var _this = $(this),
			value = $(this).val(), parent, custEx;
		if ('excerpt' === value) {
			parent = _this.closest('.styles-supported');
			custEx = parent.next('.custom-excerpts');
			if ($(this).prop('checked')) {
				custEx.show();
				custEx.addClass('has-ex');
			} else {
				parent.next('.custom-excerpts').hide();
				custEx.removeClass('has-ex');
			}
		}
	});

	$( document ).on( 'click', '.dpt-settings-toggle', function( event ) {
		var _this = $( this );
		event.preventDefault();
		_this.next( '.dpt-settings-content' ).slideToggle('fast');
		_this.toggleClass( 'toggle-active' );
	});
	
	function showPosttypeContent( pType ) {
		var postType  = pType.val(),
			parent    = pType.parent(),
			toggle    = parent.nextAll('.dpt-settings-toggle'),
			wrapper   = parent.nextAll('.dpt-settings-content'),
			taxSelec  = wrapper.find( 'select.dpt-taxonomy' );

		if (postType) {
			toggle.show();
			if ('page' === postType) {
				wrapper.find('.page-panel').show();
				wrapper.find('.post-panel').hide();
			} else {
				wrapper.find('.page-panel, .terms-panel, .terms-relation').hide();
				wrapper.find('.post-panel').show();
				taxSelec.find( 'option' ).hide();
				taxSelec.find( '.' + postType ).show();
				taxSelec.find( '.always-visible' ).show();
				taxSelec.val('');
			}
			if ('post' !== postType) {
				wrapper.addClass('not-post');
			} else {
				wrapper.removeClass('not-post');
			}
		} else {
			toggle.hide();
			wrapper.hide();
		}
	}

	function showColumnCount( st ) {
		var style  = st.val(),
			parent = st.parent(),
			align  = [ 'dpt-list1', 'dpt-list2' ],
			multicol = [ 'dpt-grid1', 'dpt-grid2', 'dpt-slider1' ],
			excerpt = [ 'dpt-list1', 'dpt-grid1' ],
			slider  = ['dpt-slider1'],
			supported, custEx;

		if (multicol.includes(style)) {
			parent.nextAll('.colnarr').show();
		} else {
			parent.nextAll('.colnarr').hide();
		}

		if ( align.includes(style) ) {
			parent.nextAll('.posts-imgalign').show();
		} else {
			parent.nextAll('.posts-imgalign').hide();
		}

		if (excerpt.includes(style)) {
			custEx = parent.nextAll('.custom-excerpts');
			if (custEx.hasClass('has-ex')) {
				custEx.show();
			}
		} else {
			parent.nextAll('.custom-excerpts').hide();
		}

		if (slider.includes(style)) {
			parent.nextAll('.autotime').show();
		} else {
			parent.nextAll('.autotime').hide();
		}

		supported = parent.nextAll('.styles-supported');
		supported.find('.style_sup-checklist li').hide();
		supported.find('.' + style).show();
	}

	function showTerms( taxonomy ) {
		var wrapper = taxonomy.closest('.dpt-settings-content');
		if ( taxonomy.val() ) {
			wrapper.find('.terms-panel, .terms-relation').show();
			wrapper.find('.terms-panel').find( '.terms-checklist li' ).hide();
			wrapper.find('.terms-panel').find( '.terms-checklist .' + taxonomy.val() ).show();
		} else {
			wrapper.find('.terms-panel, .terms-relation').hide();
		}
	}

	function showCroppos( crop ) {
		var cropping  = crop.val(),
			parent = crop.parent();

		if ('' !== cropping) {
			parent.next('.posts-imgcrop').show();
		} else {
			parent.next('.posts-imgcrop').hide();
		}
	}
}( jQuery ) );
