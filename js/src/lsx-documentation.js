/**
 * Scripts
 *
 * @package lsx-documentation
 */

;(function($, window, document, undefined) {
	'use strict';

	var initSlider = function () {
		var $documentationSlider = $('#lsx-documentation-slider, #lsx-products-slider');

		$documentationSlider.on('init', function (event, slick) {
			if (slick.options.arrows && slick.slideCount > slick.options.slidesToShow)
				$documentationSlider.addClass('slick-has-arrows');
		});

		$documentationSlider.on('setPosition', function (event, slick) {
			if (!slick.options.arrows)
				$documentationSlider.removeClass('slick-has-arrows');
			else if (slick.slideCount > slick.options.slidesToShow)
				$documentationSlider.addClass('slick-has-arrows');
		});

		$documentationSlider.slick({
			draggable: false,
			infinite: true,
			swipe: false,
			cssEase: 'ease-out',
			dots: true,
			responsive: [{
				breakpoint: 992,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					draggable: true,
					arrows: false,
					swipe: true
				}
			}, {
				breakpoint: 768,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					draggable: true,
					arrows: false,
					swipe: true
				}
			}]
		});

		$('.single-documentation a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			$('#lsx-services-slider, #lsx-documentation-slider, #lsx-products-slider, #lsx-testimonials-slider, #lsx-team-slider, .lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider').slick('setPosition');
		});
	},

	initIsotope = function() {
		var $container = $( '.lsx-documentation-row' );

		$container.isotope( {
			itemSelector : '.lsx-documentation-column',
			layoutMode : 'fitRows'
		} );

		var $option_sets = $( '.lsx-documentation-filter' ),
			$option_links = $option_sets.find( 'a' );

		$option_links.click( function() {
			var $this = $( this );

			if ( $this.parent().hasClass( 'active' ) ) {
				return false;
			}

			// var $option_sets = $this.parents( '.lsx-documentation-filter' );

			$option_sets.find( '.active' ).removeClass( 'active' );
			$this.parent().addClass( 'active' );

			var selector = $( this ).attr( 'data-filter' );
			$container.isotope( { filter: selector } );

			return false;
		} );

		setTimeout( function() {
			$container.isotope();
		}, 300 );

		$( document ).on( 'lazybeforeunveil', function() {
			setTimeout( function() {
				$container.isotope();
			}, 300 );
		} );

		$( window ).load( function() {
			$container.isotope();
		} );
	},

	fixDocumentationSidebar = function() {
		var gap = 30;

		$('body.single-documentation .entry-fixed-sidebar').scrollToFixed({
			marginTop: function() {
				var wpadminbar = $( '#wpadminbar' ),
					menu = $( '#masthead' ),
					marginTop = gap;

				if ( wpadminbar.length > 0 ) {
					marginTop += wpadminbar.outerHeight(true);
				}

				if ( menu.length > 0 ) {
					marginTop += menu.outerHeight(true);
				}

				return marginTop;
			},

			limit: function() {
				var limit = $(this).outerHeight(true) + gap;

				if ($( '.lsx-documentation-section.lsx-full-width').length > 0) {
					limit = $( '.lsx-documentation-section.lsx-full-width').first().offset().top - limit;
				} else if ($('.entry-tabs').length > 0) {
					limit = $('.entry-tabs').offset().top - limit;
				} else if ($('#footer-cta').length > 0) {
					limit = $('#footer-cta').offset().top - limit;
				} else if ($('#footer-widgets').length > 0) {
					limit = $('#footer-widgets').offset().top - limit;
				} else {
					limit = $('footer.content-info').offset().top - limit;
				}

				return limit;
			},

			minWidth: 768,
			removeOffsets: true
		});
	};

	initSlider();
	fixDocumentationSidebar();

	if ( $( 'body' ).hasClass( 'post-type-archive-documentation' ) ) {
		initIsotope();
	}

})(jQuery, window, document);

jQuery(function ($) { $('.faq li .question').click(function () {
  $(this).find('.plus-minus-toggle').toggleClass('collapsed');
  $(this).parent().toggleClass('active');
});});
