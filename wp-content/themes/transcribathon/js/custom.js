jQuery ( document ).ready(function() {
});

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
      jQuery("#item-data-section").draggable()
      jQuery("#item-data-section").draggable('disable')
      jQuery( "#item-data-section" ).resizable()
      jQuery( "#item-data-section" ).resizable('disable')
      jQuery( "#item-data-section" ).removeClass("ui-resizable")
      jQuery( ".ui-resizable-handle" ).css("display", "none")
      jQuery("#location-common-map").addClass("full-map-container")

      jQuery("#item-image-section").resizable_split({
          handleSelector: "#item-splitter",
          resizeHeight: false,
          resizeWidth: true
      });
      jQuery("#item-data-section").css("top", "")
      jQuery("#item-data-section").css("left", "")
      jQuery("#item-data-section").css("position", "relative")
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
      jQuery("#item-data-section").draggable()
      jQuery("#item-data-section").draggable('disable')
      jQuery( "#item-data-section" ).resizable()
      jQuery( "#item-data-section" ).resizable('disable')
      jQuery( "#item-data-section" ).removeClass("ui-resizable")
      jQuery( ".ui-resizable-handle" ).css("display", "none")

      jQuery("#item-image-section").resizable_split({
          handleSelector: "#item-splitter",
          resizeHeight: true,
          resizeWidth: false
      });
      jQuery("#item-data-section").css("top", "")
      jQuery("#item-data-section").css("left", "")
      jQuery("#item-data-section").css("position", "relative")
      break;
    case 'popout':

      jQuery("#item-image-section").css("width", '100%')
      jQuery("#item-image-section").css("height", '100%')
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
      jQuery( "#item-data-section" ).resizable({ handles: "n, e, s, w, se, ne, sw, nw" })
      jQuery("#item-data-section").draggable()
      jQuery("#item-data-section").draggable('enable')
      jQuery( "#item-data-section" ).resizable()
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
    var startDate = jQuery("#startdateentry").val();
    var endDate = jQuery("#enddateentry").val();
    jQuery("#startdateentry").datepicker("destroy");
    jQuery("#enddateentry").datepicker("destroy");
    var dobDate = jQuery("#dob-entry").val();
    var dodDate = jQuery("#dod-entry").val();
    jQuery("#dob-entry").datepicker("destroy");
    jQuery("#dod-entry").datepicker("destroy");
    tinymce.remove();
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
    jQuery('#item-progress-section').css('display', 'none')

    // move image content
    //jQuery('#item-image-section').html(jQuery('#full-view-image').html())
    //jQuery('#full-view-image').html('')

    // move editor content
    jQuery('#editor-tab').html(jQuery('#full-view-editor').html())
    jQuery('#full-view-editor').html('')

    // move tagging content
    jQuery('#tagging-tab').html(jQuery('#full-view-tagging').html())
    jQuery('#full-view-tagging').html('')

    // move info content
    jQuery('#info-tab').html(jQuery('#full-view-info').html())
    jQuery('#full-view-info').html('')
    jQuery('#info-collapse-icon').css( 'display', 'none')
    jQuery('#info-collapse-headline-container').css( 'pointer-events', 'none' )
    jQuery('#info-collapsable').addClass('show')

    // move autoEnrichment content
    jQuery('#autoEnrichment-tab').html(jQuery('#full-view-autoEnrichment').html())
    jQuery('#full-view-autoEnrichment').html('')
    jQuery('#automatic-enrichment-collapse-icon').css( 'display', 'none')
    jQuery('#automaticEnrichment-collapse-headline').css( 'pointer-events', 'none' )
    jQuery('#enrichment-collapsable').addClass('show')

    // move help content
    jQuery('#editor-help').html(jQuery('#full-view-help').html())
    jQuery('#full-view-help').html('')

    // reinitialize tinyMCE instances
    initTinyWithConfig('#editor-tab #item-page-transcription-text');
    //initTinyWithConfig('#editor-tab #item-page-description-text');
    /*
    tinymce.init({
      selector: '#editor-tab #item-page-transcription-text',
      inline: true
    });
    tinymce.init({
      selector: '#editor-tab #item-page-description-text',
      inline: true
    });
    */
   
    jQuery('#no-text-checkbox').change(function() {
      if(this.checked) {
        jQuery('#no-text-label').addClass('theme-color-background');
        jQuery('#no-text-label').removeClass('theme-color');
      }
      else {
        jQuery('#no-text-label').removeClass('theme-color-background');
        jQuery('#no-text-label').addClass('theme-color');
      }
    });

    jQuery( "#startdateentry, #enddateentry" ).datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "1000:2019"
    });
    jQuery("#startdateentry").val(startDate);
    jQuery("#enddateentry").val(endDate);

    jQuery( "#dob-entry, #dod-entry" ).datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "1000:2019"
    });
    jQuery("#dob-entry").val(dobDate);
    jQuery("#dod-entry").val(dodDate);

  } else {
    var startDate = jQuery("#startdateentry").val();
    var endDate = jQuery("#enddateentry").val();
    jQuery("#startdateentry").datepicker("destroy");
    jQuery("#enddateentry").datepicker("destroy");
    var dobDate = jQuery("#dob-entry").val();
    var dodDate = jQuery("#dod-entry").val();
    jQuery("#dob-entry").datepicker("destroy");
    jQuery("#dod-entry").datepicker("destroy");
    tinymce.remove();
    //switch to full view
    jQuery('.site-footer').css('display', 'block')
    jQuery('#full-view-container').css('display', 'block')
    jQuery('#image-view-container').css('display', 'none')
    jQuery('.full-container').css('position', 'relative')
    jQuery('._tct_footer').css('display', 'block')
    jQuery('#item-progress-section').css('display', 'block')

    // move image content
    //jQuery('#full-view-image').html(jQuery('#item-image-section').html())
    //jQuery('#item-image-section').html('')

    // move editor content
    jQuery('#full-view-editor').html(jQuery('#editor-tab').html())
    jQuery('#editor-tab').html('')

    // move tagging content
    jQuery('#full-view-tagging').html(jQuery('#tagging-tab').html())
    jQuery('#tagging-tab').html('')

    // move info content
    jQuery('#full-view-info').html(jQuery('#info-tab').html())
    jQuery('#info-tab').html('')
    jQuery('#info-collapse-icon').css( 'display', 'block')
    jQuery('#info-collapse-headline-container').css( 'pointer-events', 'all' )
    jQuery('#info-collapsable').removeClass('show')

    // move autoEnrichment content
    jQuery('#full-view-autoEnrichment').html(jQuery('#autoEnrichment-tab').html())
    jQuery('#autoEnrichment-tab').html('')
    jQuery('#automatic-enrichment-collapse-icon').css( 'display', 'block')
    jQuery('#automaticEnrichment-collapse-headline').css( 'pointer-events', 'all' )
    jQuery('#enrichment-collapsable').removeClass('show')

    // reinitialize tinyMCE instances
    initTinyWithConfig('#full-view-editor #item-page-transcription-text');
    //initTinyWithConfig('#full-view-editor #item-page-description-text');
    /*
    tinymce.init({
      selector: '#full-view-editor #item-page-transcription-text',
      inline: true
    });
    tinymce.init({
      selector: '#full-view-editor #item-page-description-text',
      inline: true
    });
    */
   
   jQuery('#no-text-checkbox').change(function() {
      if(this.checked) {
        jQuery('#no-text-label').addClass('theme-color-background');
        jQuery('#no-text-label').removeClass('theme-color');
      }
      else {
        jQuery('#no-text-label').removeClass('theme-color-background');
        jQuery('#no-text-label').addClass('theme-color');
      }
    });

    jQuery( "#startdateentry, #enddateentry" ).datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "1000:2019"
    });
    jQuery("#startdateentry").val(startDate);
    jQuery("#enddateentry").val(endDate);
    jQuery( "#dob-entry, #dod-entry" ).datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "1000:2019"
    });
    jQuery("#dob-entry").val(dobDate);
    jQuery("#dod-entry").val(dodDate);
  }
}

// Updates specified data over the API
function updateDataProperty(dataType, id, fieldName, value) {
  // Prepare data and send API request
  data = {
          };
  data[fieldName] = value;

  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/' + dataType + '/' + id,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      return 1;
    }
    else {
      alert(response.content);
    }
  });
}

// Updates the item description
function updateItemDescription(itemId) {
  // Clear confirmation message
  jQuery('#description-update-message').html("")

  // Prepare data and send API requestI
  data = {
            Description: jQuery('#item-page-description-text').html()
          }
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      jQuery('#description-update-message').html("Description saved")
    }
    else {
      jQuery('#description-update-message').html("Description couldn't be saved")
    }
  });
}

// Updates the item description
function updateItemTranscription(itemId, userId) {
  // Clear confirmation message
  jQuery('#transcription-update-message').html("")
  // Prepare data and send API request
  data = {
            Text: jQuery('#item-page-transcription-text').html(),
            UserId: userId,
            ItemId: itemId,
            CurrentVersion: 1
          }
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/transcriptions',
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      jQuery('#transcription-update-message').html("Transcription saved")
    }
    else {
      jQuery('#transcription-update-message').html("Transcription couldn't be saved")
    }
  });
}

// Adds an Item Property
function addItemProperty(itemId, e) {
  // Prepare data and send API request
  propertyId = e.value;
  data = {
            ItemId: itemId,
            PropertyId: propertyId,
            UserGenerated: 1
          }
  var dataString= JSON.stringify(data);
  if (e.checked) {
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': 'http://fresenia.man.poznan.pl/tp-api/itemProperties/add',
        'data': data
    },
    // Check success and create confirmation message
    function(response) {
    });
  }
  else {
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'GET',
        'url': 'http://fresenia.man.poznan.pl/tp-api/itemProperties?ItemId=' + itemId + '&PropertyId=' + propertyId,
    },
    // Check success and create confirmation message
    function(response) {
      var response = JSON.parse(response);
      if (response.code == "200") {
        var itemPropertyId = JSON.parse(response.content)[0]['ItemPropertyId'];
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'DELETE',
          'url': 'http://fresenia.man.poznan.pl/tp-api/itemProperties/' + itemPropertyId
        },
        // Check success and create confirmation message
        function(response) {
        });
      }
      else {
        alert(response.content);
      }
    });
  }
}

// Change progress status
function changeStatus (itemId, newStatus, fieldName, value, color, statusCount, e) {
  jQuery(e).parent().siblings(".status-indicator").css("color", color)
  jQuery(e).parent().siblings(".status-indicator").css("background-color", color)

  if (fieldName != "CompletionStatusId") {
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
    }, 
    // Check success and create confirmation message
    function(response) {	
      var response = JSON.parse(response);
      if (response.code == "200") {
        var content = JSON.parse(response.content);

        var oldStatus = content[0][fieldName.replace("Id", "Name")];

        // Add "-"" to "Not Started"
        var oldProgress = 'progress-bar-' + oldStatus.replace(" ", "-") + '-section';
        var oldProgressOverlay = 'progress-bar-overlay-' + oldStatus.replace(" ", "-") + '-section';
        var oldProgressOverlayDoughnut = 'progress-doughnut-overlay-' + oldStatus.replace(" ", "-") + '-section';
        var oldProgressWidth = jQuery('#' + oldProgress).html();
        oldProgressWidth = (parseInt(oldProgressWidth.replace("%", "")) - (100 / statusCount));
        jQuery('#' + oldProgress).css('width', oldProgressWidth + "%");
        if (oldProgressWidth == 0) {
          jQuery('#' + oldProgress).html("");
          jQuery('#' + oldProgressOverlay).html("0%");
          //jQuery('#' + oldProgressOverlay).closest('li').css("display", "none");
          jQuery('#' + oldProgressOverlayDoughnut).html("0%");
          //jQuery('#' + oldProgressOverlayDoughnut).closest('li').css("display", "none");
        }
        else {
          jQuery('#' + oldProgress).html(oldProgressWidth + "%");
          jQuery('#' + oldProgressOverlay).html(oldProgressWidth + "%");
          //jQuery('#' + oldProgressOverlay).closest('li').css("display", "list-item");
          jQuery('#' + oldProgressOverlayDoughnut).html(oldProgressWidth + "%");
          //jQuery('#' + oldProgressOverlayDoughnut).closest('li').css("display", "list-item");
        }

        var newProgress = 'progress-bar-' + newStatus.replace(" ", "-") + '-section';
        var newProgressOverlay = 'progress-bar-overlay-' + newStatus.replace(" ", "-") + '-section';
        var newProgressOverlayDoughnut = 'progress-doughnut-overlay-' + newStatus.replace(" ", "-") + '-section';
        var newProgressWidth = jQuery('#' + newProgress).html();
        if (newProgressWidth == ""){
          newProgressWidth =  100 / statusCount;
        }
        else {
          newProgressWidth = (parseInt(newProgressWidth.replace("%", "")) + (100 / statusCount));
        }

        jQuery('#' + newProgress).css('width', newProgressWidth + "%");
        jQuery('#' + newProgress).html(newProgressWidth + "%");
        jQuery('#' + newProgressOverlay).html(newProgressWidth + "%");
        //jQuery('#' + newProgressOverlay).closest('li').css("display", "list-item");
        jQuery('#' + newProgressOverlayDoughnut).html(newProgressWidth + "%");
        //jQuery('#' + newProgressOverlayDoughnut).closest('li').css("display", "list-item");

        updateDataProperty("items", itemId , fieldName, value);
        updateDoughnutStatus(oldStatus, newStatus);
      }
      else {
        alert(response.content);
        return 0;
      }
    });
  }
  else {
    updateDataProperty("items", itemId , fieldName, value);
  }
}

function initTinyWithConfig(selector) {
  tinymce.init({
    selector: selector,
    inline: true,
    height:120,
    plugins: ['charmap','paste'],
    toolbar: 'bold italic underline strikethrough removeformat | alignleft aligncenter alignright | missbut unsure position-in-doc',
    menubar: true,
    browser_spellcheck: true,
    paste_auto_cleanup_on_paste : true,
    body_id: 'htranscriptor',
    setup: function (editor) {
      console.log('in setup');
      editor.ui.registry.addButton('missbut', {
        title: 'Insert an indicator for missing text',
        text: '',
        icon: 'missing',
        onAction: function () {
          editor.insertContent('<span style=\"display:inline;\" class=\"tct_missing\" alt=\"missing\"> MISSING </span>');
        }
      });
      editor.ui.registry.addButton('unsure', {
        title: 'Mark selected as unclear',
        text: '',
        icon: 'unsure',
        onAction: function () {
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
  })
}
