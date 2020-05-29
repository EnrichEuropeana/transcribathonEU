/**
 * Initialize post types display
 * 
 * @since 1.0.0
 */
class DisplayPostTypes {

	/**
	 * The constructor function.
	 *
	 * @since 1.0.0
	 */
	constructor(elems) {
		this.timeOut = null;
		this.mobile = 450;
		this.tablet = 640;
		this.tabrot = 768;
		this.resize = this.onResize.bind(this);
		this.elems = (elems && Array.isArray(elems)) ? elems : Array.prototype.slice.call(document.querySelectorAll('.dpt-wrapper'));

		this.masonGrid = false;
		this.flicKity = false;
		this.init();
	}

	/**
	 * Script initialize.
	 * 
	 * @since 1.0.0
	 */
	init() {
		this.getElementWidth();
		this.applyLayout(this.elems);
		window.addEventListener('resize', this.resize);
	}

	/**
	 * Provision to add separate styles for narrow and wide elements.
	 * 
	 * @since 1.0.0
	 */
	getElementWidth() {
		this.elems.forEach(elem => {
			const { paddingLeft, paddingRight } = getComputedStyle(elem);
			const width = elem.clientWidth - ( this.getStyleSize(paddingLeft) + this.getStyleSize(paddingRight));

			elem.classList.remove('wide-wrap', 'tab-wrap', 'mob-wrap');
			if (width > this.tabrot) {
				elem.classList.add('wide-wrap');
			} else if (width > this.tablet) {
				elem.classList.add('tab-wrap');
			} else if (width > this.mobile) {
				elem.classList.add('mob-wrap');
			}
		});
	}

	/**
	 * Conditionally apply slider or masonry layout.
	 * 
	 * @since 1.0.0
	 */
	applyLayout() {
		this.elems.forEach(elem => {
			if ( elem.classList.contains('dpt-mason-wrap') ) {
				this.masonGrid = new brickLayer({
					container: elem,
					gutter: 0,
					waitForImages: true,
					useTransform: false,
					callAfter: this.addLoadedClass.bind(this, elem),
				});
				this.masonGrid.init();
			} else if ( elem.classList.contains('dpt-slider') ) {
				this.flicKity = new Flickity(elem, {
					cellAlign: 'left',
					contain: true,
					wrapAround: true,
					prevNextButtons: true,
					imagesLoaded: true,
					cellSelector: '.dpt-entry',
				});
			}
		});
	}

	/**
	 * Destroy the layout.
	 *
	 * @private
	 */
	destroy() {
		if ( this.flicKity ) {
			this.flicKity.destroy();
		} else if ( this.masonGrid ) {
			this.masonGrid.destroy();
		}
		window.removeEventListener('resize', this.resize);
	};

	/**
	 * Reposition grid items on reSize.
	 *
	 * @private
	 */
	onResize() {
		if (!this.timeout) {
			this.timeout = setTimeout(() => {
				this.getElementWidth();
				this.timeout = null;
			}, 200);
		}
	};

	/**
	 * Parse element style values.
	 *
	 * @since 1.0.0
	 * 
	 * @param {int, str, Obj} value
	 */
	getStyleSize(value) {
		const num = parseFloat( value );
		const isValid = value.indexOf('%') == -1 && !isNaN( num );
		return isValid ? num : 0;
	}

	/**
	 * Add display class to the container.
	 * 
	 * @since 1.0.0
	 * 
	 * @param {object} elem
	 */
	addLoadedClass(elem) {
		elem.classList.add('dpt-loaded');
	}
}

export default DisplayPostTypes;