!function(e){var t={};function n(a){if(t[a])return t[a].exports;var l=t[a]={i:a,l:!1,exports:{}};return e[a].call(l.exports,l,l.exports,n),l.l=!0,l.exports}n.m=e,n.c=t,n.d=function(e,t,a){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var l in e)n.d(a,l,function(t){return e[t]}.bind(null,l));return a},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=4)}([function(e,t,n){"use strict";function a(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}var l=function(){function e(t){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.timeOut=null,this.mobile=450,this.tablet=640,this.tabrot=768,this.resize=this.onResize.bind(this),this.elems=t&&Array.isArray(t)?t:Array.prototype.slice.call(document.querySelectorAll(".dpt-wrapper")),this.masonGrid=!1,this.flicKity=!1,this.init()}var t,n,l;return t=e,(n=[{key:"init",value:function(){this.getElementWidth(),this.applyLayout(this.elems),window.addEventListener("resize",this.resize)}},{key:"getElementWidth",value:function(){var e=this;this.elems.forEach(function(t){var n=getComputedStyle(t),a=n.paddingLeft,l=n.paddingRight,s=t.clientWidth-(e.getStyleSize(a)+e.getStyleSize(l));t.classList.remove("wide-wrap","tab-wrap","mob-wrap"),s>e.tabrot?t.classList.add("wide-wrap"):s>e.tablet?t.classList.add("tab-wrap"):s>e.mobile&&t.classList.add("mob-wrap")})}},{key:"applyLayout",value:function(){var e=this;this.elems.forEach(function(t){t.classList.contains("dpt-mason-wrap")?(e.masonGrid=new brickLayer({container:t,gutter:0,waitForImages:!0,useTransform:!1,callAfter:e.addLoadedClass.bind(e,t)}),e.masonGrid.init()):t.classList.contains("dpt-slider")&&(e.flicKity=new Flickity(t,{cellAlign:"left",contain:!0,wrapAround:!0,prevNextButtons:!0,imagesLoaded:!0,cellSelector:".dpt-entry"}))})}},{key:"destroy",value:function(){this.flicKity?this.flicKity.destroy():this.masonGrid&&this.masonGrid.destroy(),window.removeEventListener("resize",this.resize)}},{key:"onResize",value:function(){var e=this;this.timeout||(this.timeout=setTimeout(function(){e.getElementWidth(),e.timeout=null},200))}},{key:"getStyleSize",value:function(e){var t=parseFloat(e);return-1!=e.indexOf("%")||isNaN(t)?0:t}},{key:"addLoadedClass",value:function(e){e.classList.add("dpt-loaded")}}])&&a(t.prototype,n),l&&a(t,l),e}();t.a=l},,,,function(e,t,n){"use strict";n.r(t);var a=n(0),l=wp.components.CheckboxControl;var s=function(e){var t=e.listItems,n=e.selected,a=e.onItemChange,s=e.label;return wp.element.createElement("div",{className:"components-base-control"},wp.element.createElement("label",{class:"components-base-control__label"},s),wp.element.createElement("ul",{className:"multibox__checklist"},t.map(function(e){return wp.element.createElement("li",{key:e.value,className:"multibox__checklist-item"},wp.element.createElement(l,{label:e.label,checked:n.includes(e.value),onChange:function(){a(e.value)}}))})))};function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(e){return function(e){if(Array.isArray(e)){for(var t=0,n=new Array(e.length);t<e.length;t++)n[t]=e[t];return n}}(e)||function(e){if(Symbol.iterator in Object(e)||"[object Arguments]"===Object.prototype.toString.call(e))return Array.from(e)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}function r(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}function p(e,t){return!t||"object"!==i(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function c(e){return(c=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function u(e,t){return(u=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var y=wp.i18n.__,d=wp.element,m=d.Component,f=d.Fragment,h=d.createRef,v=wp.editor.InspectorControls,b=wp.apiFetch,g=wp.components,w=g.PanelBody,x=g.TextControl,C=g.SelectControl,E=g.RangeControl,S=g.ToggleControl,T=g.ServerSideRender,L=function(e){function t(){var e;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),(e=p(this,c(t).apply(this,arguments))).state={postTypes:[],pageList:[],taxonomies:[],termsList:[],styleList:[]},e.fetching=!1,e.styleSupport={},e.elemRef=h(),e.servRendTimer=null,e.servRendMax=0,e.dpt=!1,e}var n,l,i;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&u(e,t)}(t,m),n=t,(l=[{key:"apiDataFetch",value:function(e,t){var n=this;if(this.fetching)setTimeout(this.apiDataFetch.bind(this,e,t),200);else{var a={};this.fetching=!0,b({path:"/dpt/v1/"+t}).then(function(t){var l=Object.keys(t);l=l.map(function(e){return{label:t[e],value:e}}),a[e]=l,n.setState(a),n.fetching=!1}).catch(function(){a[e]=[],n.setState(a),n.fetching=!1})}}},{key:"componentDidMount",value:function(){var e=this.props.attributes.postType;this.apiDataFetch("postTypes","posttypes"),e&&("page"===e?this.getPagesList():(this.updateTaxonomy(),this.updateTerms())),this.getStyleList(),this.libManager()}},{key:"libManager",value:function(){var e=this;clearTimeout(this.servRendTimer),this.servRendTimer=setTimeout(function(){var t=e.elemRef.current,n=t?t.firstElementChild:null;if(n&&n.classList.contains("mo"))e.servRendMax=0;else{!1!==e.dpt&&(e.dpt.destroy(),e.dpt=!1);var l=n?n.querySelectorAll(".dpt-wrapper"):[];l.length?(e.dpt=new a.a(Array.from(l)),n.classList.add("mo"),e.servRendMax=0):e.servRendMax<1e3?(e.servRendMax+=100,e.libManager()):e.servRendMax=0}},100)}},{key:"componentDidUpdate",value:function(e){var t=e.attributes,n=t.postType,a=t.taxonomy,l=this.props.attributes,s=l.postType,i=l.taxonomy;n!==s&&(this.updateTaxonomy(),"page"===s&&this.getPagesList()),a!==i&&this.updateTerms(),this.libManager(),setTimeout(this.libManager.bind(this),400),setTimeout(this.libManager.bind(this),2e3)}},{key:"updateTaxonomy",value:function(){var e=this.props.attributes.postType;e&&"page"!==e?this.apiDataFetch("taxonomies","taxonomies/"+e):this.setState({taxonomies:[],termsList:[]})}},{key:"updateTerms",value:function(){var e=this.props.attributes.taxonomy;e?this.apiDataFetch("termsList","terms/"+e):this.setState({termsList:[]})}},{key:"getPagesList",value:function(){this.apiDataFetch("pageList","pagelist")}},{key:"getStyleList",value:function(){var e=this;b({path:"/dpt/v1/stylelist"}).then(function(t){var n=Object.keys(t),a=n.map(function(e){return{label:t[e].label,value:e}});n.forEach(function(n){e.styleSupport[n]=t[n].support}),e.setState({styleList:a})}).catch(function(){e.setState({styleList:[]})})}},{key:"render",value:function(){var e,t,n,a=this,l=this.state,i=l.postTypes,r=l.taxonomies,p=l.pageList,c=l.termsList,u=l.styleList,d=this.props,m=d.attributes,h=d.setAttributes,b=m.postType,g=m.taxonomy,L=m.terms,O=m.postIds,k=m.pages,A=m.number,M=m.orderBy,R=m.order,P=m.styles,_=m.styleSup,j=m.imageCrop,z=m.imgAspect,I=m.imgAlign,D=m.brRadius,G=m.colNarr,F=m.plHolder,N=m.textAlign,B=m.vGutter,W=m.hGutter,q=m.eLength,K=m.eTeaser,H=m.offset,U=function(e,t){var n=a.styleSupport[e];return void 0!==n&&n.includes(t)},V=[{value:"date",label:y("Publish Date","display-post-types")},{value:"modified",label:y("Modified Date","display-post-types")},{value:"title",label:y("Title","display-post-types")},{value:"author",label:y("Author","display-post-types")},{value:"comment_count",label:y("Comment Count","display-post-types")},{value:"rand",label:y("Random","display-post-types")}],J=[{value:"",label:y("No Cropping","display-post-types")},{value:"land1",label:y("Landscape (4:3)","display-post-types")},{value:"land2",label:y("Landscape (3:2)","display-post-types")},{value:"port1",label:y("Portrait (3:4)","display-post-types")},{value:"port2",label:y("Portrait (2:3)","display-post-types")},{value:"wdscrn",label:y("Widescreen (16:9)","display-post-types")},{value:"squr",label:y("Square (1:1)","display-post-types")}],Q=[{value:"topleftcrop",label:y("Top Left Cropping","display-post-types")},{value:"topcentercrop",label:y("Top Center Cropping","display-post-types")},{value:"centercrop",label:y("Center Cropping","display-post-types")},{value:"bottomcentercrop",label:y("Bottom Center Cropping","display-post-types")},{value:"bottomleftcrop",label:y("Bottom Left Cropping","display-post-types")}];return wp.element.createElement(f,null,wp.element.createElement(v,null,wp.element.createElement(w,{title:y("Setup Display Post Types","display-post-types")},i&&wp.element.createElement(C,{label:y("Select Post Type","display-post-types"),value:b,options:i,onChange:function(e){return function(e){h({terms:[]}),h({taxonomy:""}),h({postType:e})}(e)}})),wp.element.createElement(w,{initialOpen:!1,title:y("Get items to be displayed","display-post-types")},b&&"page"!==b&&wp.element.createElement(x,{label:y("Get items by Post IDs (optional)","display-post-types"),value:O,onChange:function(e){return h({postIds:e})},help:y("Comma separated ids, i.e. 230,300","display-post-types")}),b&&!!r.length&&wp.element.createElement(C,{label:y("Get items by Taxonomy","display-post-types"),value:g,options:r,onChange:function(e){return function(e){h({terms:[]}),h({taxonomy:e})}(e)}}),b&&"page"===b&&!!p.length&&wp.element.createElement(s,{listItems:p,selected:k,onItemChange:function(e){var t=k.indexOf(e);h(-1===t?{pages:[].concat(o(k),[e])}:{pages:k.filter(function(t){return t!==e})})},label:y("Select Pages","display-post-types")}),!!c.length&&wp.element.createElement(s,{listItems:c,selected:L,onItemChange:function(e){var t=L.indexOf(e);h(-1===t?{terms:[].concat(o(L),[e])}:{terms:L.filter(function(t){return t!==e})})},label:y("Select Taxonomy Terms","display-post-types")}),b&&"page"!==b&&wp.element.createElement(E,{label:y("Number of items to display","display-post-types"),value:A,onChange:function(e){return h({number:e})},min:1}),b&&"page"!==b&&wp.element.createElement(E,{label:y("Offset (number of posts to displace)","display-post-types"),value:H,onChange:function(e){return h({offset:e})},min:0}),b&&"page"!==b&&wp.element.createElement(C,{label:y("Order By","display-post-types"),value:M,onChange:function(e){return h({orderBy:e})},options:V}),b&&"page"!==b&&wp.element.createElement(C,{label:y("Sort Order","display-post-types"),value:R,onChange:function(e){return h({order:e})},options:[{value:"DESC",label:y("Descending","display-post-types")},{value:"ASC",label:y("Ascending","display-post-types")}]})),wp.element.createElement(w,{initialOpen:!1,title:y("Styling selected items","display-post-types")},b&&!!u.length&&wp.element.createElement(C,{label:y("Display Style","display-post-types"),value:P,onChange:function(e){return h({styles:e})},options:u}),b&&!!u.length&&wp.element.createElement(s,{listItems:(e=P,t=[{value:"thumbnail",label:y("Thumbnail","display-post-types")},{value:"title",label:y("Title","display-post-types")},{value:"meta",label:y("Meta Info","display-post-types")},{value:"category",label:y("Category","display-post-types")},{value:"excerpt",label:y("Excerpt","display-post-types")}],n=a.styleSupport[e],void 0!==n&&t.filter(function(e){return"category"!==e.value?n.includes(e.value):n.includes(e.value)&&"post"===b})),selected:_,onItemChange:function(e){var t=_.indexOf(e);h(-1===t?{styleSup:[].concat(o(_),[e])}:{styleSup:_.filter(function(t){return t!==e})})},label:y("Items supported by display style","display-post-types")}),b&&_.includes("excerpt")&&U(P,"excerpt")&&wp.element.createElement(E,{label:y("Excerpt Length (in words)","display-post-types"),value:q,onChange:function(e){return h({eLength:e})},min:0,max:100}),b&&_.includes("excerpt")&&U(P,"excerpt")&&wp.element.createElement(x,{label:y("Excerpt Teaser Text","display-post-types"),value:K,onChange:function(e){return h({eTeaser:e})},help:y("i.e., Continue Reading, Read More","display-post-types")}),b&&P&&U(P,"multicol")&&wp.element.createElement(E,{label:y("Maximum grid columns (Responsive)","display-post-types"),value:G,onChange:function(e){return h({colNarr:e})},min:1,max:8}),b&&wp.element.createElement(C,{label:y("Image Cropping","display-post-types"),value:z,onChange:function(e){return h({imgAspect:e})},options:J}),b&&""!==z&&wp.element.createElement(C,{label:y("Image Cropping Position","display-post-types"),value:j,onChange:function(e){return h({imageCrop:e})},options:Q}),b&&P&&U(P,"ialign")&&wp.element.createElement(C,{label:y("Image Alignment","display-post-types"),value:I,onChange:function(e){return h({imgAlign:e})},options:[{value:"",label:y("Left Aligned","display-post-types")},{value:"right",label:y("Right Aligned","display-post-types")}]})),wp.element.createElement(w,{initialOpen:!1,title:y("Additional Styling Options","display-post-types")},b&&wp.element.createElement(C,{label:y("Text Align","display-post-types"),value:N,onChange:function(e){return h({textAlign:e})},options:[{value:"",label:y("Left Align","display-post-types")},{value:"r-text",label:y("Right Align","display-post-types")},{value:"c-text",label:y("Center Align","display-post-types")}]}),b&&wp.element.createElement(E,{label:y("Horizontal Gutter (in px)","display-post-types"),value:W,onChange:function(e){return h({hGutter:e})},min:0,max:100}),b&&wp.element.createElement(E,{label:y("Vertical Gutter (in px)","display-post-types"),value:B,onChange:function(e){return h({vGutter:e})},min:0,max:100}),b&&wp.element.createElement(E,{label:y("Border Radius (in px)","display-post-types"),value:D,onChange:function(e){return h({brRadius:e})},min:0,max:100}),b&&wp.element.createElement(S,{label:y("Thumbnail Placeholder","display-post-types"),checked:!!F,onChange:function(e){return h({plHolder:e})}}))),wp.element.createElement("div",{className:"dpt-container",ref:this.elemRef},wp.element.createElement(T,{block:"dpt/display-post-types",attributes:this.props.attributes})))}}])&&r(n.prototype,l),i&&r(n,i),t}(),O=wp.i18n.__;(0,wp.blocks.registerBlockType)("dpt/display-post-types",{title:O("Display Post Types","display-post-types"),description:O("Filter and display any pages, posts or post types.","display-post-types"),icon:wp.element.createElement("svg",{xmlns:"http://www.w3.org/2000/svg",width:"32",height:"32",viewBox:"0 0 32 32"},wp.element.createElement("path",{d:"M9.143 22.286v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM9.143 13.143v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM20.571 22.286v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM9.143 4v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM20.571 13.143v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM32 22.286v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM20.571 4v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM32 13.143v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714zM32 4v3.429c0 0.946-0.768 1.714-1.714 1.714h-5.714c-0.946 0-1.714-0.768-1.714-1.714v-3.429c0-0.946 0.768-1.714 1.714-1.714h5.714c0.946 0 1.714 0.768 1.714 1.714z"})),category:"layout",keywords:[O("display posts","display-post-types"),O("post types","display-post-types")],edit:L,save:function(){return null}})}]);