

function cancelFullScreen(el) {
	var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
	if (requestMethod) { // cancel full screen.
		requestMethod.call(el);
	} else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
		var wscript = new ActiveXObject("WScript.Shell");
		if (wscript !== null) {
			wscript.SendKeys("{F11}");
		}
	}
}

function isLogged(){
	"use strict";	
	jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_skripts/transcribe-skript.php", {'q':'ilgd'}, function(res) {
		return res.content;
	});	
}

function toggleFSEditor(what){
	"use strict";
	console.log('in toggleFSEditor');
	what = typeof what !== 'undefined' ? what : 'no';
	if(what != 'no' && jQuery('#'+what).hasClass('no')){
		alert('Please log in first and return to fullscreen mode again.');
	}else{
		if(!jQuery('div#transscriber-huge').is(':visible')){
			jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_skripts/transcribe-skript.php", {'q':'ilgd','transid':jQuery('input#tct-transID').val()}, function(res) {
				if(res.status == "ok" && res.content === "islogged"){
					if(res.islocked != "locked"){
						jQuery('div#transscriber-huge').show();
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked');
						lockDocument();
					}else{
						jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked').addClass('locked');
						alert(res.text);
					}
				}else{
					if(res.text !== ""){
						jQuery('div#fs_editor_toggle').removeClass('locked').removeClass('no').addClass('no');
						document.location.reload();
					}
				}
			});	
			jQuery('a#he-close').click(function(){  
				jQuery('div#transscriber-huge').hide();
			});
		}else{
			jQuery('div#transscriber-huge').hide();
		}
		getEditor();
		tinyMCE.DOM.setStyle('mydiv', 'background-color', 'red');
		if(screen.availWidth>750){
			jQuery('div#transscriber-huge').css({'left':((screen.availWidth-750)/2)+'px','top':(screen.availHeight-jQuery('div#transscriber-huge').height())+'px','width':'750px'});
		}else{
			jQuery('div#transscriber-huge').css({'left':((screen.availWidth-jQuery('div#transscriber-huge').width())/2)+'px','top':(screen.availHeight-jQuery('div#transscriber-huge').height())+'px','width':(screen.availWidth-80)+'px'});
		}
		
		jQuery('#fsgo div.tct-image-viewer div.iviewer_common ').each(function(){jQuery(this).removeClass('full').addClass('full'); });
	}
}

function requestFullScreen(el) {
	// Supports most browsers and their versions.
	var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen; 

	
	if (requestMethod) { // Native full screen.
		requestMethod.call(el);
	} else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
		
		var wscript = new ActiveXObject("WScript.Shell");
		alert(wscript);
		if (wscript !== null) {
			wscript.SendKeys("{F11}");
		}
	}else{
		
	}
	
	jQuery(document).keydown(function(e) {
    // ESCAPE key pressed
		if (e.keyCode == 27) {
			e.preventDefault();
			cancelFullScreen(document);
			goSmall();
		}
	});
	return false
}

if (document.addEventListener){
    document.addEventListener('webkitfullscreenchange', exitHandler, false);
    document.addEventListener('mozfullscreenchange', exitHandler, false);
    document.addEventListener('fullscreenchange', exitHandler, false);
    document.addEventListener('MSFullscreenChange', exitHandler, false);
    document.addEventListener('fullscreenChange', exitHandler, false);
}

function fullscreenEdit() {
  console.log('show fullscreen Editor');
  toggleFSEditor('fs_editor_toggle');
}

let fullscreenEditButton = new OpenSeadragon.Button({
  srcRest: `/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/pencil_norm.jpg`,
  srcGroup: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/pencil_norm.jpg',
  srcHover: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/pencil_hover.jpg',
  srcDown: '/wp-content/plugins/tct-transcribe-viewer-iiif/assets/images/pencil_active.jpg',
  onClick: fullscreenEdit,
  id: 'fullScreenEditButton'
});

function getEditor(){
	if(!ivchanged){
		jQuery('div#huge-transcibe-area').html(jQuery('div#transcript').html());
	}
	tinymce.init({
		selector: 'div#huge-transcibe-area',
		//selector: 'div.tct-transcriberfield',
		inline: true,
		fixed_toolbar_container: '#mytoolbar',
		event_root: 'div#transscriber-huge',
		height:120,
		plugins: ['autoresize','charmap','paste'],
		toolbar: 'bold italic underline strikethrough removeformat | alignleft aligncenter alignright | missbut unsure position-in-doc',
		menubar: false,
		browser_spellcheck: true,
		paste_auto_cleanup_on_paste : true,
		resize: 'both',
		//forced_root_block : false,
		skin_url: '/wp-content/plugins/tct-transcribe-viewer/assets/skin/transcribathon',
		body_id: 'htranscriptor',
		setup: function (editor) {
		editor.addButton('missbut', {
		title: 'Insert an indicator for missing text',
		text: '',
		icon: 'missing',
		onclick: function () {
			editor.insertContent('<img src=\"/wp-content/plugins/tct-transcribe-viewer/assets/images/missing.gif\" style=\"display:inline;\" class=\"tct_missing\" alt=\"missing\" />');
		}
		});
		editor.addButton('position-in-doc', {
			title: 'Mark selected as side information',
			text: '',
			icon: 'pos-in-text',
			onclick: function () {
				if(editor.selection.getContent({format : 'text'}).split(' ').join('').length < 1){
					editor.insertContent('<span class=\"pos-in-text\"> ...</span>')
				}else{
					if (editor.selection.getStart().className == "pos-in-text") {
						var node = editor.selection.getStart();
						node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
					}
					else if (editor.selection.getEnd().className == "pos-in-text"){
						var node = editor.selection.getEnd();
						node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
					}
					else{
						editor.insertContent('<span class=\"pos-in-text\">'+editor.selection.getContent({format : 'html'})+'</span>');
					}
				}
			}
		});
		editor.addButton('unsure', {
			title: 'Mark selected as unclear',
			text: '',
			icon: 'unsure',
			onclick: function () {
				if(editor.selection.getContent({format : 'text'}).split(' ').join('').length < 1){
					editor.insertContent('<span class=\"tct-uncertain\"> ...</span>')
				}else{
					if (editor.selection.getStart().className == "tct-uncertain") {
						var node = editor.selection.getStart();
						node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
					}
					else if (editor.selection.getEnd().className == "tct-uncertain"){
						var node = editor.selection.getEnd();
						node.parentNode.replaceChild(document.createTextNode(node.innerHTML.replace("&nbsp;", "")), node);
					}
					else{
						editor.insertContent('<span class=\"tct-uncertain\">'+editor.selection.getContent({format : 'html'})+'</span>');
					}
				}
			}
		});
		editor.on('input change', function(e) {
			checkChanges('fs');
		});
		},
		style_formats: [
		{title: 'unclear, please review', inline: 'span', classes: 'tct_unclear'},
		{title: 'Note', inline: 'span', classes: 'tct_note'},
		{title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' }}
		],
		formats: {
		alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'left' },
		aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'center' },
		alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'right' },
		alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'full' },
		bold: { inline: 'span', 'classes': 'bold' },
		italic: { inline: 'span', 'classes': 'italic' },
		underline: { inline: 'span', 'classes': 'underline', exact: true },
		strikethrough: { inline: 'del' },
		},
		content_css: '/wp-content/themes/transcribathon/style.css'
	});
}

function goFull(){
	jQuery('#fsgo div.tct-image-viewer').css({'height':'100%','min-height':'500px'});
	if(!jQuery('div#transscriber-huge').hasClass('inact')){
		jQuery('div#transscriber-huge').show();
	}else{
		jQuery('div#transscriber-huge').hide();
	}
	jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_skripts/transcribe-skript.php", {'q':'ilgd','transid':jQuery('input#tct-transID').val()}, function(res) {
		if(res.status == "ok" && res.content === "islogged"){
			if(res.islocked != "locked"){
				jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked');
			}else{
				jQuery('div#fs_editor_toggle').removeClass('no').removeClass('locked').addClass('locked');
			}
		}else{
			jQuery('div#fs_editor_toggle').removeClass('locked').removeClass('no').addClass('no');
		}
	});	
	getEditor();
	//tinyMCE.DOM.setStyle('mydiv', 'background-color', 'red');
	if(screen.availWidth>750){
		jQuery('div#transscriber-huge').css({'left':((screen.availWidth-750)/2)+'px','top':(screen.availHeight-jQuery('div#transscriber-huge').height())+'px','width':'750px'});
	}else{
		jQuery('div#transscriber-huge').css({'left':((screen.availWidth-jQuery('div#transscriber-huge').width())/2)+'px','top':(screen.availHeight-jQuery('div#transscriber-huge').height())+'px','width':(screen.availWidth-80)+'px'});
	}
	
	iv1.addControl(fullscreenEditButton.element, { anchor: OpenSeadragon.ControlAnchor.TOP_LEFT});
	
	jQuery('#fsgo div.tct-image-viewer div.iviewer_common ').each(function(){jQuery(this).removeClass('full').addClass('full'); });
	
	
}

function goSmall(){
	if(ivchanged && ivchangedid != ""){
		jQuery('div#transscriber-huge').hide();
		jQuery('div#tctoverlay').html("<div class=\"loading\"></div>").show("slow");
		jQuery("#popbg").show("slow"); 
		jQuery("#close").click(function(){  
			disablePopup();  
		}); 
		document.location.href='/?p='+ivchangedid;
	}else{
		jQuery('div#transcript').html(jQuery('div#huge-transcibe-area').html());
		jQuery('div#transscriber-huge').hide();
		jQuery('#fsgo div.tct-image-viewer').css({'height':'500px'}).removeClass('isfullscreen');
		jQuery('#fsgo div.tct-image-viewer div.iviewer_common ').each(function(){jQuery(this).removeClass('full');});
		jQuery("div#popbg").hide();  
	}
	iv1.removeControl(fullscreenEditButton.element);
}

function exitHandler(){
    var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);
	if (isInFullScreen) {
		goFull();
    }else{
		goSmall();	
	}
}

function getFullIfNotYet(elem) {
	//var elem = document.body; // Make the body go full screen.
	var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);
	if (!isInFullScreen) {
		goFull();
		requestFullScreen(elem);
	}
	return false;
}

function toggleFull(elem) {
	//var elem = document.body; // Make the body go full screen.
	//alert(elem.id);
	var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||  (document.mozFullScreen || document.webkitIsFullScreen);

	if (isInFullScreen) {
		cancelFullScreen(document);
		goSmall();
	} else {
		goFull();
		requestFullScreen(elem);
	}
	return false;
}

