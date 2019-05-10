// Switches between different tabs within the item page image view
function switchItemTab(event, tabName) {
  var i, tabcontent, tablinks;

  // Hide all tab contents
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  // Make tab icons inactive
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show clicked tab content and make icon active
  document.getElementById(tabName).style.display = "block";
  event.currentTarget.className += " active";    
}

// Switches between different views within the item page image view
function switchItemView(event, viewName) {
  // Make tab icons inactive
  icons = document.getElementsByClassName("view-switcher-icons");
  for (i = 0; i < icons.length; i++) {
    icons[i].className = icons[i].className.replace(" active", "");
  }

  // Make icon active
  event.currentTarget.className += " active";    
  switch(viewName) {
    case 'horizontal':
      
      jQuery("#item-image-section").css("width", '')
      jQuery("#item-image-section").css("height", '')
      jQuery("#image-view-container").removeClass("panel-container-vertical")
      jQuery("#image-view-container").addClass("panel-container-horizontal")
      jQuery("#item-image-section").removeClass("panel-top")
      jQuery("#item-image-section").removeClass("image-popout")
      jQuery("#item-image-section").addClass("panel-left")
      jQuery("#item-data-section").removeClass("panel-bottom")
      jQuery("#item-data-section").removeClass("data-popout")
      jQuery("#item-data-section").addClass("panel-right")
      jQuery("#item-splitter").removeClass("splitter-horizontal")
      jQuery("#item-splitter").addClass("splitter-vertical")
      jQuery("#item-data-section").draggable('disable')
      jQuery( "#item-data-section" ).resizable('disable')
      jQuery("#item-image-section").resizable({
          handleSelector: "#item-splitter",
          resizeHeight: false,
          resizeWidth: true
      });
      jQuery("#item-data-section").css("top", "")
      jQuery("#item-data-section").css("left", "")
      break;
    case 'vertical':
    
      jQuery("#item-image-section").css("width", '')
      jQuery("#item-image-section").css("height", '')
      jQuery("#image-view-container").removeClass("panel-container-horizontal")
      jQuery("#image-view-container").addClass("panel-container-vertical")
      jQuery("#item-image-section").removeClass("panel-left")
      jQuery("#item-image-section").removeClass("image-popout")
      jQuery("#item-image-section").addClass("panel-top")
      jQuery("#item-data-section").removeClass("panel-right")
      jQuery("#item-data-section").removeClass("data-popout")
      jQuery("#item-data-section").addClass("panel-bottom")
      jQuery("#item-splitter").removeClass("splitter-vertical")
      jQuery("#item-splitter").addClass("splitter-horizontal")
      jQuery("#item-data-section").draggable('disable')
      jQuery( "#item-data-section" ).resizable('disable')
      jQuery("#item-image-section").resizable({
          handleSelector: "#item-splitter",
          resizeHeight: true,
          resizeWidth: false
      });
      jQuery("#item-data-section").css("top", "")
      jQuery("#item-data-section").css("left", "")
      break;
    case 'popout':
 
      jQuery("#item-image-section").css("width", '')
      jQuery("#item-image-section").css("height", '')
      jQuery("#item-image-section").addClass("image-popout")
      jQuery("#item-data-section").addClass("data-popout") 
      jQuery("#image-view-container").removeClass("panel-container-horizontal")
      jQuery("#image-view-container").removeClass("panel-container-vertical")
      jQuery("#item-image-section").removeClass("panel-left")
      jQuery("#item-image-section").removeClass("panel-top")
      jQuery("#item-data-section").removeClass("panel-right")
      jQuery("#item-data-section").removeClass("panel-bottom")
      jQuery("#item-splitter").removeClass("splitter-vertical")
      jQuery("#item-splitter").removeClass("splitter-horizontal")
      jQuery("#item-data-section").draggable()
      jQuery( "#item-data-section" ).resizable({ handles: "n, e, s, w, se, ne, sw, nw" })
      jQuery("#item-data-section").draggable('enable')
      jQuery( "#item-data-section" ).resizable('enable')

break;
}
}

// Calls script to draw linechart on the profile page
function getTCTlinePersonalChart(what,start,ende,holder,uid){
  "use strict";
  jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/linechart-script.php",
  {
    'q':'get-ln-chart',
    'kind':what,
    'start':start,
    'ende':ende,
    'uid':uid,
    'holder':holder
  }, 
  function(res) {	
    if(res.status === "ok"){
      jQuery('#'+holder).fadeTo(1,0.01,function(){
        jQuery('#'+holder).html(res.content).fadeTo(400,1);
      });
      
    }else{
      alert(res.content);	
    }
	});
}

// Compares two transcriptions to highlight changes
function compareTranscription(oldTranscription, newTranscription, index) {
  var dmp = new diff_match_patch();
  var text1 = oldTranscription;
  var text2 = newTranscription;
  
  // Compare transcriptions
  var d = dmp.diff_main(text1, text2);
  
  // Highlight changes
  dmp.diff_cleanupSemantic(d);
  var ds = dmp.diff_prettyHtml(d);
  jQuery("#transcription-comparison-output-" + index).html(ds);
}

// Switches between image view and full view on the item page
function switchItemPageView() {
  if (jQuery('#full-view-container').css('display') == 'block') {
    //switch to image view
    jQuery('.site-footer').css('display', 'none')
    jQuery('#full-view-container').css('display', 'none')
    jQuery('#image-view-container').css('display', 'flex')
    jQuery('.full-container').css('position', 'static')
    jQuery('#item-view-switcher').css('position', 'absolute')
    jQuery('#item-view-switcher').css('z-index', '9999991')
    jQuery('#item-view-switcher').css('left', '50%')
    jQuery('#item-view-switcher').css('top', '0')
    jQuery('._tct_footer').css('display', 'none')

    // move image content
    jQuery('#item-image-section').html(jQuery('#full-view-image').html())
    jQuery('#full-view-image').html('')

    // move editor content
    jQuery('#editor-tab').html(jQuery('#full-view-editor').html())
    jQuery('#full-view-editor').html('')

    // move tagging content
    jQuery('#tagging-tab').html(jQuery('#full-view-tagging').html())
    jQuery('#full-view-tagging').html('')

    // move info content
    jQuery('#info-tab').html(jQuery('#full-view-info').html())
    jQuery('#full-view-info').html('')

    // move autoEnrichment content
    jQuery('#autoEnrichment-tab').html(jQuery('#full-view-enrichment').html())
    jQuery('#full-view-autoEnrichment').html('')

    // move help content
    jQuery('#editor-help').html(jQuery('#full-view-help').html())
    jQuery('#full-view-help').html('')
  } else {
    //switch to full view
    jQuery('.site-footer').css('display', 'block')
    jQuery('#full-view-container').css('display', 'block')
    jQuery('#image-view-container').css('display', 'none')
    jQuery('.full-container').css('position', 'relative')
    jQuery('._tct_footer').css('display', 'block')
    
    // move image content
    jQuery('#full-view-image').html(jQuery('#item-image-section').html())
    jQuery('#item-image-section').html('')

    // move editor content
    jQuery('#full-view-editor').html(jQuery('#editor-tab').html())
    jQuery('#editor-tab').html('')
    
    // move tagging content
    jQuery('#full-view-tagging').html(jQuery('#tagging-tab').html())
    jQuery('#tagging-tab').html('')
    
    // move info content
    jQuery('#full-view-info').html(jQuery('#info-tab').html())
    jQuery('#info-tab').html('')
    
    // move autoEnrichment content
    jQuery('#full-view-autoEnrichment').html(jQuery('#autoEnrichment-tab').html())
    jQuery('#autoEnrichment-tab').html('')
  }
}

function updateItemStatus(itemId) {
  jQuery('#status-update-message').html("")
  data = {CompletionStatusId: jQuery('#status-selection').val()}
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/Item/update/' + itemId,
      'data': data
  }, 
  function(res) {	
    if (res == "200") {
      jQuery('#status-update-message').html("Status updated")
    }
    else {
      jQuery('#status-update-message').html("Status couldn't be changed")
    }
  });
}

function updateItemDescription(itemId) {
  jQuery('#description-update-message').html("")
  data = {Description: jQuery('#item-page-description-text').val()}
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/Item/update/' + itemId,
      'data': data
  }, 
  function(res) {	
    if (res == "200") {
      jQuery('#description-update-message').html("Description saved")
    }
    else {
      jQuery('#description-update-message').html("Description couldn't be saved")
    }
  });
}

