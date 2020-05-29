import DPT from '../../../../frontend/js/partials/index.js';

const { __ } = wp.i18n;
const { Component, Fragment, createRef } = wp.element;
const { InspectorControls } = wp.editor;
const { apiFetch } = wp;
const { PanelBody, TextControl, SelectControl, RangeControl, ToggleControl, ServerSideRender } = wp.components;

import MultipleCheckboxControl from './mcc';

class DisplayPostTypes extends Component {

	constructor() {
		super( ...arguments );
		this.state = {
			postTypes: [],
			pageList: [],
			taxonomies: [],
			termsList: [],
			styleList: [],
		};
		this.fetching = false;
		this.styleSupport = {};
		this.elemRef = createRef();
		this.servRendTimer = null;
		this.servRendMax = 0;
		this.dpt = false;
	}

	apiDataFetch(data, path) {
		if (this.fetching) {
			setTimeout( this.apiDataFetch.bind(this, data, path), 200 );
			return;
		}
		let obj = {};
		this.fetching = true;
		apiFetch( {
			path: '/dpt/v1/' + path,
		} )
		.then( ( items ) => {
			let itemsList = Object.keys(items);
			itemsList = itemsList.map(item => {
				return {
					label: items[item],
					value: item,
				};
			});
			obj[data] = itemsList;
			this.setState(obj);
			this.fetching = false;
		} )
		.catch( () => {
			obj[data] = [];
			this.setState(obj);
			this.fetching = false;
		} );
	}
	
	componentDidMount() {
		const {attributes} = this.props;
		const {postType} = attributes;
		this.apiDataFetch('postTypes', 'posttypes');
		if (postType) {
			if ('page' === postType) {
				this.getPagesList();
			} else {
				this.updateTaxonomy();
				this.updateTerms();
			}
		}
		this.getStyleList();
		this.libManager();
	}

	libManager() {
		clearTimeout(this.servRendTimer);
		this.servRendTimer = setTimeout( () => {
			const dom  = this.elemRef.current;
			const domc = dom ? dom.firstElementChild : null;

			if (domc && domc.classList.contains('mo')) {
				this.servRendMax = 0;
				return;
			} else if (false !== this.dpt) {
				this.dpt.destroy();
				this.dpt = false;
			}

			const elems = domc ? domc.querySelectorAll('.dpt-wrapper') : [];
			if (elems.length) {
				this.dpt = new DPT(Array.from(elems));
				domc.classList.add('mo');
				this.servRendMax = 0;
			} else if (this.servRendMax < 1000) {
				this.servRendMax += 100;
				this.libManager();
			} else {
				this.servRendMax = 0;
			}
		}, 100 );
	}

	componentDidUpdate( prevProps ) {
		const {
			postType: oldPostType,
			taxonomy: oldTaxonomy
		} = prevProps.attributes;
		const { postType, taxonomy } = this.props.attributes;

		if (oldPostType !== postType) {
			this.updateTaxonomy();
			if ('page' === postType) { this.getPagesList() }
		}

		if (oldTaxonomy !== taxonomy) { this.updateTerms() }
		
		this.libManager();
		// Similar to ServerSideRender (100ms added by libManager).
		setTimeout(this.libManager.bind(this), 400);

		// Rerun for longer server response time.
		setTimeout(this.libManager.bind(this), 2000);
	}

	updateTaxonomy() {
		const { attributes } = this.props;
		const { postType } = attributes;
		if (!postType || 'page' === postType) {
			this.setState( { taxonomies: [], termsList: [] } );
		} else {
			this.apiDataFetch('taxonomies', 'taxonomies/' + postType);
		}
	}

	updateTerms() {
		const { attributes } = this.props;
		const { taxonomy } = attributes;
		if (!taxonomy) {
			this.setState( { termsList: [] } );
		} else {
			this.apiDataFetch('termsList', 'terms/' + taxonomy);
		}
	}

	getPagesList() {
		this.apiDataFetch('pageList', 'pagelist');
	}

	getStyleList() {
		apiFetch( {
			path: '/dpt/v1/stylelist',
		} )
		.then( ( items ) => {
			const list = Object.keys(items);
			const styleList = list.map(item => {
				return {
					label: items[item]['label'],
					value: item,
				};
			});
			list.forEach(item => {
				this.styleSupport[item] = items[item]['support'];
			});
			this.setState( { styleList } );
		} )
		.catch( () => {
			this.setState( { styleList: [] } );
		} );
	}

	render() {
		const { postTypes, taxonomies, pageList, termsList, styleList } = this.state;
		const { attributes, setAttributes } = this.props;
		const {
			postType,
			taxonomy,
			terms,
			postIds,
			pages,
			number,
			orderBy,
			order,
			styles,
			styleSup,
			imageCrop,
			imgAspect,
			imgAlign,
			brRadius,
			colNarr,
			plHolder,
			textAlign,
			vGutter,
			hGutter,
			eLength,
			eTeaser,
			offset,
		} = attributes;
		const onChangePostType = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: '' });
			setAttributes({ postType: value });
		};
		const onChangeTaxonomy = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: value });
		};
		const styleSupported = (style) => {
			const all = [
				{ value: 'thumbnail', label: __( 'Thumbnail', 'display-post-types' ) },
				{ value: 'title', label: __( 'Title', 'display-post-types' ) },
				{ value: 'meta', label: __( 'Meta Info', 'display-post-types' ) },
				{ value: 'category', label: __( 'Category', 'display-post-types' ) },
				{ value: 'excerpt', label: __( 'Excerpt', 'display-post-types' ) },
			];
			const supported = this.styleSupport[style];
			if ( 'undefined' === typeof supported ) return false;
			return all.filter(sup => {
				if ( 'category' !== sup.value ) {
					return supported.includes(sup.value);
				} else {
					return ( supported.includes(sup.value) && 'post' === postType );
				}
			});
		};
		const ifStyleSupport = (style, item) => {
			const supported = this.styleSupport[style];
			if ( 'undefined' === typeof supported ) return false;
			return supported.includes(item);
		}
		const termCheckChange = (value) => {
			const index = terms.indexOf(value);
			if (-1 === index) {
				setAttributes({ terms: [...terms, value] });
			} else {
				setAttributes({ terms: terms.filter(term => term !== value) });
			}
		};
		const pageCheckChange = (value) => {
			const index = pages.indexOf(value);
			if (-1 === index) {
				setAttributes({ pages: [...pages, value] });
			} else {
				setAttributes({ pages: pages.filter(page => page !== value) });
			}
		};
		const supCheckChange = (value) => {
			const index = styleSup.indexOf(value);
			if (-1 === index) {
				setAttributes({ styleSup: [...styleSup, value] });
			} else {
				setAttributes({ styleSup: styleSup.filter(sup => sup !== value) });
			}
		};
		const orderbyOptions = [
			{ value: 'date', label: __( 'Publish Date', 'display-post-types' ) },
			{ value: 'modified', label: __( 'Modified Date', 'display-post-types' ) },
			{ value: 'title', label: __( 'Title', 'display-post-types' ) },
			{ value: 'author', label: __( 'Author', 'display-post-types' ) },
			{ value: 'comment_count', label: __( 'Comment Count', 'display-post-types' ) },
			{ value: 'rand', label: __( 'Random', 'display-post-types' ) },
		];
		const aspectOptions = [
			{ value: '', label: __( 'No Cropping', 'display-post-types' ) },
			{ value: 'land1', label: __( 'Landscape (4:3)', 'display-post-types' ) },
			{ value: 'land2', label: __( 'Landscape (3:2)', 'display-post-types' ) },
			{ value: 'port1', label: __( 'Portrait (3:4)', 'display-post-types' ) },
			{ value: 'port2', label: __( 'Portrait (2:3)', 'display-post-types' ) },
			{ value: 'wdscrn', label: __( 'Widescreen (16:9)', 'display-post-types' ) },
			{ value: 'squr', label: __( 'Square (1:1)', 'display-post-types' ) },
		];
		const cropOptions = [
			{ value: 'topleftcrop', label: __( 'Top Left Cropping', 'display-post-types' ) },
			{ value: 'topcentercrop', label: __( 'Top Center Cropping', 'display-post-types' ) },
			{ value: 'centercrop', label: __( 'Center Cropping', 'display-post-types' ) },
			{ value: 'bottomcentercrop', label: __( 'Bottom Center Cropping', 'display-post-types' ) },
			{ value: 'bottomleftcrop', label: __( 'Bottom Left Cropping', 'display-post-types' ) },
		];

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title={ __( 'Setup Display Post Types', 'display-post-types' ) }>
						{
							postTypes &&
							<SelectControl
								label={ __( 'Select Post Type', 'display-post-types' ) }
								value={ postType }
								options={ postTypes }
								onChange={ (value) => onChangePostType(value) }
							/>
						}
					</PanelBody>
					<PanelBody initialOpen={ false } title={ __( 'Get items to be displayed', 'display-post-types' ) }>
						{
							(postType && 'page' !== postType ) &&
							<TextControl
								label={ __( 'Get items by Post IDs (optional)', 'display-post-types' ) }
								value={ postIds }
								onChange={ ( postIds ) => setAttributes( { postIds } ) }
								help={ __( 'Comma separated ids, i.e. 230,300', 'display-post-types' ) }
							/>
						}
						{
							(postType && !! taxonomies.length) &&
							<SelectControl
								label={ __( 'Get items by Taxonomy', 'display-post-types' ) }
								value={ taxonomy }
								options={ taxonomies }
								onChange={ ( value ) => onChangeTaxonomy(value) }
							/>
						}
						{
							(postType && 'page' === postType && !! pageList.length) &&
							<MultipleCheckboxControl
								listItems={ pageList }
								selected={ pages }
								onItemChange={ pageCheckChange }
								label = { __( 'Select Pages', 'display-post-types' ) }
							/>
						}
						{
							(!! termsList.length) &&
							<MultipleCheckboxControl
								listItems={ termsList }
								selected={ terms }
								onItemChange={ termCheckChange }
								label = { __( 'Select Taxonomy Terms', 'display-post-types' ) }
							/>
						}
						{
							(postType && 'page' !== postType ) &&
							<RangeControl
								label={ __( 'Number of items to display', 'display-post-types' ) }
								value={ number }
								onChange={ ( number ) => setAttributes( { number } ) }
								min={ 1 }
							/>
						}
						{
							(postType && 'page' !== postType ) &&
							<RangeControl
								label={ __( 'Offset (number of posts to displace)', 'display-post-types' ) }
								value={ offset }
								onChange={ ( offset ) => setAttributes( { offset } ) }
								min={ 0 }
							/>
						}
						{
							(postType && 'page' !== postType ) &&
							<SelectControl
								label={ __( 'Order By', 'display-post-types' ) }
								value={ orderBy }
								onChange={ ( orderBy ) => setAttributes( { orderBy } ) }
								options={ orderbyOptions }
							/>
						}
						{
							(postType && 'page' !== postType ) &&
							<SelectControl
								label={ __( 'Sort Order', 'display-post-types' ) }
								value={ order }
								onChange={ ( order ) => setAttributes( { order } ) }
								options={ [
									{ value: 'DESC', label: __( 'Descending', 'display-post-types' ) },
									{ value: 'ASC', label: __( 'Ascending', 'display-post-types' ) },
								] }
							/>
						}
					</PanelBody>
					<PanelBody initialOpen={ false } title={ __( 'Styling selected items', 'display-post-types' ) }>
						{
							(postType && !! styleList.length) &&
							<SelectControl
								label={ __( 'Display Style', 'display-post-types' ) }
								value={ styles }
								onChange={ ( styles ) => setAttributes( { styles } ) }
								options={ styleList }
							/>
						}
						{
							(postType && !! styleList.length) &&
							<MultipleCheckboxControl
								listItems={ styleSupported(styles) }
								selected={ styleSup }
								onItemChange={ supCheckChange }
								label = { __( 'Items supported by display style', 'display-post-types' ) }
							/>
						}
						{
							(postType && styleSup.includes('excerpt') && ifStyleSupport(styles, 'excerpt')) &&
							<RangeControl
								label={ __( 'Excerpt Length (in words)', 'display-post-types' ) }
								value={ eLength }
								onChange={ ( eLength ) => setAttributes( { eLength } ) }
								min={ 0 }
								max={ 100 }
							/>
						}
						{
							(postType && styleSup.includes('excerpt') && ifStyleSupport(styles, 'excerpt')) &&
							<TextControl
								label={ __( 'Excerpt Teaser Text', 'display-post-types' ) }
								value={ eTeaser }
								onChange={ ( eTeaser ) => setAttributes( { eTeaser } ) }
								help={ __( 'i.e., Continue Reading, Read More', 'display-post-types' ) }
							/>
						}
						{
							(postType && styles && ifStyleSupport(styles, 'multicol')) &&
							<RangeControl
								label={ __( 'Maximum grid columns (Responsive)', 'display-post-types' ) }
								value={ colNarr }
								onChange={ ( colNarr ) => setAttributes( { colNarr } ) }
								min={ 1 }
								max={ 8 }
							/>
						}
						{
							postType &&
							<SelectControl
								label={ __( 'Image Cropping', 'display-post-types' ) }
								value={ imgAspect }
								onChange={ ( imgAspect ) => setAttributes( { imgAspect } ) }
								options={ aspectOptions }
							/>
						}
						{
							(postType && '' !== imgAspect) &&
							<SelectControl
								label={ __( 'Image Cropping Position', 'display-post-types' ) }
								value={ imageCrop }
								onChange={ ( imageCrop ) => setAttributes( { imageCrop } ) }
								options={ cropOptions }
							/>
						}
						{
							(postType && styles && ifStyleSupport(styles, 'ialign')) &&
							<SelectControl
								label={ __( 'Image Alignment', 'display-post-types' ) }
								value={ imgAlign }
								onChange={ ( imgAlign ) => setAttributes( { imgAlign } ) }
								options={ [
									{ value: '', label: __( 'Left Aligned', 'display-post-types' ) },
									{ value: 'right', label: __( 'Right Aligned', 'display-post-types' ) },
								] }
							/>
						}
					</PanelBody>
					<PanelBody initialOpen={ false } title={ __( 'Additional Styling Options', 'display-post-types' ) }>
						{
							postType &&
							<SelectControl
								label={ __( 'Text Align', 'display-post-types' ) }
								value={ textAlign }
								onChange={ ( textAlign ) => setAttributes( { textAlign } ) }
								options={ [
									{ value: '', label: __( 'Left Align', 'display-post-types' ) },
									{ value: 'r-text', label: __( 'Right Align', 'display-post-types' ) },
									{ value: 'c-text', label: __( 'Center Align', 'display-post-types' ) },
								] }
							/>
						}
						{
							postType &&
							<RangeControl
								label={ __( 'Horizontal Gutter (in px)', 'display-post-types' ) }
								value={ hGutter }
								onChange={ ( hGutter ) => setAttributes( { hGutter } ) }
								min={ 0 }
								max={ 100 }
							/>
						}
						{
							postType &&
							<RangeControl
								label={ __( 'Vertical Gutter (in px)', 'display-post-types' ) }
								value={ vGutter }
								onChange={ ( vGutter ) => setAttributes( { vGutter } ) }
								min={ 0 }
								max={ 100 }
							/>
						}
						{
							postType &&
							<RangeControl
								label={ __( 'Border Radius (in px)', 'display-post-types' ) }
								value={ brRadius }
								onChange={ ( brRadius ) => setAttributes( { brRadius } ) }
								min={ 0 }
								max={ 100 }
							/>
						}
						{
							postType &&
							<ToggleControl
								label={ __( 'Thumbnail Placeholder', 'display-post-types' ) }
								checked={ !! plHolder }
								onChange={ ( plHolder ) => setAttributes( { plHolder } ) }
							/>
						}
					</PanelBody>
				</InspectorControls>
				<div className="dpt-container" ref={this.elemRef}>
					<ServerSideRender
						block="dpt/display-post-types"
						attributes={ this.props.attributes }
					/>
				</div>
			</Fragment>
		);
	}

}

export default DisplayPostTypes;
