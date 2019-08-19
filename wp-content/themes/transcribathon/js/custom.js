jQuery ( document ).ready(function() {
  installEventListeners()
});

function uninstallEventListeners() {
  jQuery("#startdateentry").datepicker("destroy");
  jQuery("#enddateentry").datepicker("destroy");
  jQuery("#person-birthDate-input").datepicker("destroy");
  jQuery("#person-deathDate-input").datepicker("destroy");
  tinymce.remove();
}

function installEventListeners() { 
  var keyWordList = [];   
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/properties?PropertyType=Keyword'
  },
  function(response) {
    var response = JSON.parse(response);
    var content = JSON.parse(response.content);
    for (var i = 0; i < content.length; i++) {
      keyWordList.push(content[i]['PropertyValue']);
    }
    
    jQuery( "#keyword-input" ).autocomplete({
      source: keyWordList,
      delay: 100,
      minLength: 1
    });
  });
  
  jQuery('#startdateentry, #enddateentry').on("change paste keyup", function() {
    var dateText = jQuery(this).val();
    if(dateText.length > 0) {
      jQuery('#item-date-save-button').css('display','block');
    }
    else {
      jQuery('#item-date-save-button').css('display','none');
    }
  })

  // New transcription langauge selected
  jQuery('#transcription-language-selector select').change(function(){
    jQuery('#no-text-selector').css('display','none');
    jQuery('#transcription-selected-languages ul').append(
            '<li onClick="removeTranscriptionLanguage(' + jQuery('#transcription-language-selector option:selected').val() + ', this)">' 
              + jQuery('#transcription-language-selector option:selected').text() 
            + '</li>');
    jQuery('#transcription-language-selector option:selected').prop("disabled", true);   
    var transcriptionText = jQuery('#item-page-transcription-text').text();     
    if(transcriptionText.length != 0) {
      jQuery('#transcription-update-button').css('display','block');
    }
  })
  
  jQuery('.notes-questions').keyup(function() {
  var block_data = jQuery(this).val();
          if(block_data.length==0){
          jQuery('.notes-questions-submit').css('display','none');
          }else{
      jQuery('.notes-questions-submit').css('display','block');
      }
  });
  
  jQuery('#description-area textarea').keyup(function() {
    var text = jQuery(this).val();
    var language = jQuery('#description-language-selector select').val();
    if(text.length == 0 || language == null) {
      jQuery('#description-update-button').css('display','none');
    } 
    else {
      jQuery('#description-update-button').css('display','block');
    }
  });
  jQuery('#description-language-selector select').change(function(){
    var text = jQuery('#description-area textarea').val();
    if(text.length == 0) {
      jQuery('#description-update-button').css('display','none');
    } 
    else {
      jQuery('#description-update-button').css('display','block');
    }
  });

  // Show/Hide Transcription Save button                             
  jQuery('#item-page-transcription-text').keyup(function() {
    jQuery('#no-text-selector').css('display','none');
    var transcriptionText = jQuery('#item-page-transcription-text').text();
    var languages = jQuery('#transcription-selected-languages ul').children().length;
    if(transcriptionText.length != 0 && languages > 0) {
      jQuery('#transcription-update-button').css('display','block');
    }
    else {
      jQuery('#transcription-update-button').css('display','none');
    }
    if(transcriptionText.length == 0 && languages == 0) {
      jQuery('#no-text-selector').css('display','block');
    }
  });
  
  jQuery('#no-text-selector input').click(function(event) {
    var checked = this.checked;
    var transcriptionText = jQuery('#item-page-transcription-text').text();
    if (checked == true) {
      if(transcriptionText.length == 0) {
        jQuery('#transcription-language-selector select').attr("disabled", "disabled");
        jQuery('#transcription-language-selector select').addClass("disabled-dropdown");
        tinymce.remove();
        jQuery('#transcription-update-button').css('display','block');
      }
      else {
        alert("Please remove the transcription text first, if the document has nothing to transcribe");
        event.preventDefault();
        event.stopPropagation();
      }
    }
    else {
      jQuery('#transcription-language-selector select').removeAttr("disabled");
      jQuery('#transcription-language-selector select').removeClass("disabled-dropdown");
      tct_viewer.initTinyWithConfig('#item-page-transcription-text');
      jQuery('#transcription-update-button').css('display','none');
    }
  })


  var startDate = jQuery("#startdateentry").val();
  var endDate = jQuery("#enddateentry").val();
  var birthDate = jQuery("#person-birthDate-input").val();
  var deathDate = jQuery("#person-deathDate-input").val();

  jQuery( "#startdateentry, #enddateentry" ).datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "1000:2019",
    showOn: "button",
    buttonText: "<i class=\'far fa-calendar-edit datepick-calendar-size\'></i>"
  });
   
  jQuery("#startdateentry").val(startDate);
  jQuery("#enddateentry").val(endDate);

  jQuery( "#person-birthDate-input, #person-deathDate-input" ).datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "1000:2019",
    showOn: "button",
    buttonText: "<i class=\'far fa-calendar-edit datepick-calendar-size\'></i>"
  });
  jQuery("#person-birthDate-input").val(birthDate);
  jQuery("#person-deathDate-input").val(deathDate);


  tct_viewer.initTinyWithConfig('#item-page-transcription-text');
}

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
  uninstallEventListeners();

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

/*
    // LOGIN MODAL WINDOW //
    // When the user clicks the button, open the modal 
      jQuery('#sign-log-form').css('display', 'block');
    jQuery('#lock-login').click(function() {
      jQuery('#sign-log-form').css('display', 'block');
    })
    // When the user clicks on <span> (x), close the modal
    jQuery('.close').click(function() {
      jQuery('#sign-log-form').css('display', 'none');
    })*/

  } else {
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
  }
  installEventListeners();
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
function updateItemDescription(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-description-spinner-container').css('display', 'block')
  
  var descriptionLanguage = jQuery('#description-language-selector select').val();
  updateDataProperty('items', itemId, 'DescriptionLanguage', descriptionLanguage);

  var description = jQuery('#item-page-description-text').val()

  // Prepare data and send API requestI
  data = {
            Description: description
          }
  var dataString= JSON.stringify(data);
	
	jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
		'type': 'GET',
		'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
	},
	function(response) {
		// Check success and create confirmation message
		var response = JSON.parse(response);
    var descriptionCompletion = JSON.parse(response.content)[0]["DescriptionStatusName"];
    var oldDescription = JSON.parse(response.content)[0]["Description"];
    
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId,
      'data': data
    },
    // Check success and create confirmation message
    function(response) {
      var amount = description.length - oldDescription.length
      if (amount > 0) {
        amount = amount + 10;
      }
      else { 
        amount = 10;
      }

      scoreData = {
                    ItemId: itemId,
                    UserId: userId,
                    ScoreType: "Description",
                    Amount: amount
                  }
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
          'data': scoreData
      },
      // Check success and create confirmation message
      function(response) {
      })
      var response = JSON.parse(response);
      if (response.code == "200") {
        if (descriptionCompletion == "Not Started") {
          changeStatus(itemId, "Not Started", "Edit", "DescriptionStatusId", 2, editStatusColor, statusCount)
        }
        jQuery('#description-update-button').css('display', 'none')
      }
      jQuery('#item-description-spinner-container').css('display', 'none')
    });
	});
}

// Updates the item description
function updateItemTranscription(itemId, userId, currentTranscription, editStatusColor, statusCount) {
  jQuery('#item-transcription-spinner-container').css('display', 'block')
  // Clear confirmation message
  jQuery('#transcription-update-message').html("")

  // Get languages
  var transcriptionLanguages = [];
  jQuery("#transcription-language-selector option").each(function() {
    var nextLanguage = {};
    if (jQuery(this).prop('disabled') == true && jQuery(this).val() != "") {
      nextLanguage.LanguageId = jQuery(this).val();
      transcriptionLanguages.push(nextLanguage);
    }
  });
  var noText = 0;
  if (jQuery('#no-text-checkbox').is(':checked')) {
    noText = 1
  }

  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var itemCompletion = JSON.parse(response.content)[0]["CompletionStatusName"];
      var transcriptionCompletion = JSON.parse(response.content)[0]["TranscriptionStatusName"];
      
      var newTranscriptionLength = 0
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/strip_tags.php', {
        'text': jQuery('#item-page-transcription-text').html().replace("&nbsp;", " ")
      },
      function(response) {
        newTranscriptionLength = response.length;
      })


      // Prepare data and send API request
      data = {
          Text: jQuery('#item-page-transcription-text').html(),
          UserId: userId,
          ItemId: itemId,
          CurrentVersion: 1,
          NoText: noText,
          Languages: transcriptionLanguages,
          }
      var dataString= JSON.stringify(data);
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': 'http://fresenia.man.poznan.pl/tp-api/transcriptions',
        'data': data
      },
      // Check success and create confirmation message
      function(response) {
        var amount = newTranscriptionLength - currentTranscription.length
        if (amount > 0) {
          amount = amount + 10;
        }
        else { 
          amount = 10;
        }
  
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Transcription",
                      Amount: amount
                    }
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        var response = JSON.parse(response);
        if (response.code == "200") {
          if (itemCompletion == "Not Started") {
            changeStatus(itemId, "Not Started", "Edit", "CompletionStatusId", 2, editStatusColor, statusCount)
          }
          if (transcriptionCompletion == "Not Started") {
            changeStatus(itemId, "Not Started", "Edit", "TranscriptionStatusId", 2, editStatusColor, statusCount)
          }
          jQuery('#transcription-update-button').css('display', 'none')
        }
        else {
          jQuery('#transcription-update-message').html("Transcription couldn't be saved")
        }
        jQuery('#item-transcription-spinner-container').css('display', 'none')
      });
    });
  
  /*
  for (var i = 0; i < transcriptionLanguages.length; i++) {
    // Prepare data and send API request
    data = {
      ItemId: itemId,
      LanguageId: transcriptionLanguages[i]
    }
    var dataString= JSON.stringify(data);
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'POST',
    'url': 'http://fresenia.man.poznan.pl/tp-api/transcriptionLanguages',
    'data': data
    },
    // Check success and create confirmation message
    function(response) {
    });
  }*/
}

// Adds an Item Property
function addItemProperty(itemId, userId, e) {
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
        'url': 'http://fresenia.man.poznan.pl/tp-api/itemProperties',
        'data': data
    },
    // Check success and create confirmation message
    function(response) {
      scoreData = {
                    ItemId: itemId,
                    UserId: userId,
                    ScoreType: "Enrichment",
                    Amount: 1
                  }
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
          'data': scoreData
      },
      // Check success and create confirmation message
      function(response) {
      })
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
function changeStatus (itemId, oldStatus, newStatus, fieldName, value, color, statusCount, e) {
  jQuery('#' + fieldName.replace("StatusId", "").toLowerCase() + '-status-indicator').css("color", color)
  jQuery('#' + fieldName.replace("StatusId", "").toLowerCase() + '-status-indicator').css("background-color", color)

  if (fieldName != "CompletionStatusId") {
    if (oldStatus == null) {
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'GET',
        'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
      },
      // Check success and create confirmation message
      function(response) {
        var response = JSON.parse(response);
        if (response.code == "200") {
          var content = JSON.parse(response.content);

          oldStatus = content[0][fieldName.replace("Id", "Name")];

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
  }
  else {
    updateDataProperty("items", itemId , fieldName, value);
  }
}

function removeTranscriptionLanguage(languageId, e) {
  jQuery("#transcription-language-selector option[value='" + languageId + "']").prop("disabled", false)  
  jQuery("#transcription-language-selector select").val("") 
  jQuery(e).remove()
  var transcriptionText = jQuery('#item-page-transcription-text').text();  
  var languages = jQuery('#transcription-selected-languages ul').children().length;   
  if(transcriptionText.length != 0 && languages > 0) {
    jQuery('#transcription-update-button').css('display','block');
  }
  else {
    jQuery('#transcription-update-button').css('display','none');
  }   
  if(transcriptionText.length == 0 && languages == 0) {
    jQuery('#no-text-selector').css('display','block');
  }
}

function saveItemLocation(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-location-spinner-container').css('display', 'block')
  // Prepare data and send API request
  locationName = jQuery('#location-input-name-container input').val();
  [latitude, longitude] = jQuery('#location-input-coordinates-container input').val().split(',');
  if (latitude != null) {
    latitude = latitude.trim();
  } 
  if (longitude != null) {
    longitude = longitude.trim();
  }
  if (isNaN(latitude) || isNaN(longitude)) {
    jQuery('#location-input-coordinates-container span').css('display', 'block');
    jQuery('#item-location-spinner-container').css('display', 'none')
    return 0;
  }

  description = jQuery('#location-input-description-container textarea').val();
  data = {
            Name: locationName,
            Latitude: latitude,
            Longitude: longitude,
            ItemId: itemId,
            Link: "",
            Zoom: 10,
            Comment: description,
            UserId: userId,
            UserGenerated: 1
          }
  var dataString= JSON.stringify(data);
  
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var locationCompletion = JSON.parse(response.content)[0]["LocationStatusName"];
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/places',
          'data': data
      },
      // Check success and create confirmation message
      function(response) {
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Location",
                      Amount: 1
                    }
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        loadPlaceData(itemId);
        if (locationCompletion == "Not Started") {
          changeStatus(itemId, "Not Started", "Edit", "LocationStatusId", 2, editStatusColor, statusCount)
        }
        jQuery('#location-input-section').removeClass('show')
        jQuery('#location-input-section input').val("")
        jQuery('#location-input-section textarea').val("")
        jQuery('#item-location-spinner-container').css('display', 'none')
      });
    });
}

function saveItemDate(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-date-spinner-container').css('display', 'block')
  // Prepare data and send API request
  data = {
  }
  startDate = jQuery('#startdateentry').val().split('/');
  if (!isNaN(startDate[2]) && !isNaN(startDate[1]) && !isNaN(startDate[0])) {
    data['DateStart'] = startDate[2] + "-" + startDate[1] + "-" + startDate[0];
  }
  endDate = jQuery('#enddateentry').val().split('/');
  if (!isNaN(endDate[2]) && !isNaN(endDate[1]) && !isNaN(endDate[0])) {
    data['DateEnd'] = endDate[2] + "-" + endDate[1] + "-" + endDate[0];
  }
  
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId,
          'data': data
      },
      // Check success and create confirmation message
      function(response) {
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Enrichment",
                      Amount: 1
                    }
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        if (taggingCompletion == "Not Started") {
          changeStatus(itemId, "Not Started", "Edit", "TaggingStatusId", 2, editStatusColor, statusCount)
        }
        jQuery('#item-date-save-button').css('display', 'none')
        jQuery('#item-date-spinner-container').css('display', 'none')
      });
    });
}


function savePerson(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-person-spinner-container').css('display', 'block')
  
  firstName = jQuery('#person-firstName-input').val();
  lastName = jQuery('#person-lastName-input').val();
  birthPlace = jQuery('#person-birthPlace-input').val();
  birthDate = jQuery('#person-birthDate-input').val().split('/');
  deathPlace = jQuery('#person-deathPlace-input').val();
  deathDate = jQuery('#person-deathDate-input').val().split('/');
  description = jQuery('#person-description-input').val();

  if (firstName == "" && lastName == "") {
    return 0;
  }

  // Prepare data and send API request
  data = {
    FirstName: firstName,
    LastName: lastName,
    BirthPlace: birthPlace,
    DeathPlace: deathPlace,
    Link: null,
    Description: description,
    ItemId: itemId
  }
  if (!isNaN(birthDate[2]) && !isNaN(birthDate[1]) && !isNaN(birthDate[0])) {
    data['BirthDate'] = birthDate[2] + "-" + birthDate[1] + "-" + birthDate[0];
  }
  else {
    data['BirthDate'] = null;
  }
  if (!isNaN(deathDate[2]) && !isNaN(deathDate[1]) && !isNaN(deathDate[0])) {
    data['DeathDate'] = deathDate[2] + "-" + deathDate[1] + "-" + deathDate[0];
  }
  else {
    data['DeathDate'] = null;
  }
  
  for (var key in data) {
    if (data[key] == "") {
      data[key] = null;
    }
  }
  
  var dataString= JSON.stringify(data);
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': 'http://fresenia.man.poznan.pl/tp-api/persons',
        'data': data
    },
    // Check success and create confirmation message
    function(response) {
      scoreData = {
                    ItemId: itemId,
                    UserId: userId,
                    ScoreType: "Enrichment",
                    Amount: 1
                  }
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
          'data': scoreData
      },
      // Check success and create confirmation message
      function(response) {
      })

      loadPersonData(itemId);
      if (taggingCompletion == "Not Started") {
        changeStatus(itemId, "Not Started", "Edit", "TaggingStatusId", 2, editStatusColor, statusCount)
      }
      jQuery('#person-input-container').removeClass('show')
      jQuery('#person-input-container input').val("")
      jQuery('#item-person-spinner-container').css('display', 'none')
    });
  });
}

function saveKeyword(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-keyword-spinner-container').css('display', 'block')
  value = jQuery('#keyword-input').val();

  if (value != "" && value != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: value,
      PropertyType: "Keyword"
    }

    var dataString= JSON.stringify(data);
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
    },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/properties?ItemId=' + itemId,
          'data': data
      },
      // Check success and create confirmation message
      function(response) {
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Enrichment",
                      Amount: 1
                    }
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        loadKeywordData(itemId);
        if (taggingCompletion == "Not Started") {
          changeStatus(itemId, "Not Started", "Edit", "TaggingStatusId", 2, editStatusColor, statusCount)
        }
        jQuery('#keyword-input-container').removeClass('show')
        jQuery('#keyword-input-container input').val("")
        jQuery('#item-keyword-spinner-container').css('display', 'none')
      });
    });
  }
}

function saveLink(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-link-spinner-container').css('display', 'block')
  url = jQuery('#link-url-input input').val();
  description = jQuery('#link-description-input textarea').val();

  if (url != "" && url != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: url,
      PropertyDescription: description,
      PropertyType: "Link"
    }
    var dataString= JSON.stringify(data);
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
    },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': 'http://fresenia.man.poznan.pl/tp-api/properties?ItemId=' + itemId,
          'data': data
      },
      // Check success and create confirmation message
      function(response) {
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Enrichment",
                      Amount: 1
                    }
        jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': 'http://fresenia.man.poznan.pl/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        loadLinkData(itemId);
        if (taggingCompletion == "Not Started") {
          changeStatus(itemId, "Not Started", "Edit", "TaggingStatusId", 2, editStatusColor, statusCount)
        }
        jQuery('#link-input-container').removeClass('show')
        jQuery('#link-input-container input').val("")
        jQuery('#link-input-container textarea').val("")
        jQuery('#item-link-spinner-container').css('display', 'none')
      });
    });
  }
}

function loadPlaceData(itemId) {
  // Get new location list
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/places?ItemId=' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      var content = JSON.parse(response.content);
      jQuery('#item-location-list ul').html('')

      for (var i = 0; i < content.length; i++) {      
        if (content[i]['Comment'] != "NULL" && content[i]['Comment'] != null) {
          var comment = content[i]['Comment'];
        }
        else {
            var comment = "";
        } 

        jQuery('#item-location-list ul').append(  
          '<li id="place-' + content[i]['PlaceId'] + '">' +
            '<div class="item-data-output-element-header">' +
                '<h6 class="collapse-controller" data-toggle="collapse" href="#location-data-output-' + content[i]['PlaceId'] + '">' +
                    content[i]['Name'] +
                '</h6>' +
                '<i class="delete-item-data fal fa-trash-alt"' +
                    'onClick="deleteItemData(\'places\', ' + content[i]['PlaceId'] + ', ' + itemId + ', \'place\')"></i>' +
                '<div style="clear:both;"></div>' +
              '</div>' +
              '<div id="location-data-output-' + content[i]['PlaceId'] + '" class="collapse">' + 
                  '<span>' +
                      'Description: ' +
                      comment +
                  '</span>' +
              '</div>' +
          '</li>'    
        )
      }
    }
  });
}

function loadPersonData(itemId) {
  // Get new person list
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/persons?ItemId=' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      var content = JSON.parse(response.content);
      jQuery('#item-person-list ul').html('')

      for (var i = 0; i < content.length; i++) {          
        if (content[i]['FirstName'] != "NULL" && content[i]['FirstName'] != null) {
          var firstName = content[i]['FirstName'];
        }
        else {
            var firstName = "";
        } 
        if (content[i]['LastName'] != "NULL" && content[i]['LastName'] != null) {
            var lastName = content[i]['LastName'];
        }
        else {
            var lastName = "";
        } 
        if (content[i]['BirthPlace'] != "NULL" && content[i]['BirthPlace'] != null) {
            var birthPlace = content[i]['BirthPlace'];
        }
        else {
            var birthPlace = "";
        } 
        if (content[i]['BirthDate'] != "NULL" && content[i]['BirthDate'] != null) {
            var birthTimestamp = Date.parse(content[i]['BirthDate']);
            var birthDate = new Date(birthTimestamp);
            birthDate = birthDate.getDay() + '/' + birthDate.getMonth() + '/' + birthDate.getFullYear();
        }
        else {
            var birthDate = "";
        } 
        if (content[i]['DeathPlace'] != "NULL" && content[i]['DeathPlace'] != null) {
            var deathPlace = content[i]['DeathPlace'];
        }
        else {
            var deathPlace = "";
        } 
        if (content[i]['DeathDate'] != "NULL" && content[i]['DeathDate'] != null) {
            var deathTimestamp = Date.parse(content[i]['DeathDate']);
            var deathDate = new Date(deathTimestamp);
            deathDate = deathDate.getDay() + '/' + deathDate.getMonth() + '/' + deathDate.getFullYear();
        }
        else {
            var deathDate = "";
        } 
        if (content[i]['Description'] != "NULL" && content[i]['Description'] != null) {
            var description = content[i]['Description'];
        }
        else {
            var description = "";
        } 
        jQuery('#item-person-list ul').append(
          '<li id="person-' + content[i]['PersonId'] + '">' +
            '<div class="item-data-output-element-header">' +
              '<h6 class="person-data-ouput-headline collapse-controller" data-toggle="collapse" href="#person-data-output-' + content[i]['PersonId'] + '">' +
                  firstName + ' ' + lastName +
              '</h6>' +
              '<i class="delete-item-data fal fa-trash-alt"' +
                  'onClick="deleteItemData(\'persons\', ' + content[i]['PersonId'] + ', ' + itemId + ', \'person\')"></i>' +
              '<div style="clear:both;"></div>' +
            '</div>' +
            '<div id="person-data-output-' + content[i]['PersonId'] + '" class="collapse">' +
                '<span>' +
                    'Birth: ' +
                    birthDate + ' ' + birthPlace +
                '</span>' +
                '</br>' +
                '<span>' +
                    'Death: ' +
                    deathDate + ' ' + deathPlace +
                '</span>' +
                '</br>' +
                '<span>' +
                    'Description: ' +
                    description +
                '</span>' +
            '</div>' +
          '</li>'
        )
      }     
    }
  });
}

function loadKeywordData(itemId) {
  // Get new keyword list
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      var content = JSON.parse(response.content);
      jQuery('#item-keyword-list ul').html('')
      for (var i = 0; i < content[0]['Properties'].length; i++) {  
        if (content[0]['Properties'][i]['PropertyType'] == "Keyword") { 
          jQuery('#item-keyword-list ul').append( 
            '<li id="keyword-' + content[0]['Properties'][i]['PropertyId'] + '">' +
              '<div class="item-data-output-element-header">' +
                '<h6 class="keyword-data-ouput-headline">' +
                  content[0]['Properties'][i]['PropertyValue'] +
                '</h6>' +
                '<i class="delete-item-data fal fa-trash-alt"' +
                    'onClick="deleteItemData(\'properties\', ' + content[0]['Properties'][i]['PropertyId'] + ', ' + itemId + ', \'keyword\')"></i>' +
                '<div style="clear:both;"></div>' +
              '</div>' +
            '</li>'
          )
        }
      }
    }
  });
}

function loadLinkData(itemId) {
  // Get new link list
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      var content = JSON.parse(response.content);
      jQuery('#item-link-list ul').html('')
      for (var i = 0; i < content[0]['Properties'].length; i++) {  
        if (content[0]['Properties'][i]['PropertyType'] == "Link") { 
          if (content[0]['Properties'][i]['PropertyDescription'] != "NULL" && content[0]['Properties'][i]['PropertyDescription'] != null) {
            var description = content[0]['Properties'][i]['PropertyDescription'];
          }
          else {
            var description = "";
          } 
          jQuery('#item-link-list ul').append( 
            '<li id="link-' + content[0]['Properties'][i]['PropertyId'] + '">' +
              '<div class="item-data-output-element-header">' +
                '<a href="' + content[0]['Properties'][i]['PropertyValue'] + '" target="_blank" class="link-data-ouput-headline">' +
                  content[0]['Properties'][i]['PropertyValue'] +
                '</a>' +
                '<i class="delete-item-data fal fa-trash-alt"' +
                    'onClick="deleteItemData(\'properties\', ' + content[0]['Properties'][i]['PropertyId'] + ', ' + itemId + ', \'link\')"></i>' +
                '<div style="clear:both;"></div>' +
              '</div>' +
              '<div>' +
                  '<span>' +
                      'Description: ' +
                      description +
                  '</span>' +
              '</div>' +
            '</li>'
          )
        }
      }
    }
  });

}

function deleteItemData(type, id, itemId, section) {
  jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'DELETE',
    'url': 'http://fresenia.man.poznan.pl/tp-api/' + type + '/' + id
  },
  function(response) {
    switch (section) {
      case "place":
          loadPlaceData(itemId);
          break;
      case "person":
          loadPersonData(itemId);
          break;
      case "keyword":
          loadKeywordData(itemId);
          break;
      case "link":
          loadLinkData(itemId);
          break;
    }
  });
}

function stripHTML(dirtyString) {
  var container = document.createElement('div');
  var text = document.createTextNode(dirtyString);
  container.appendChild(text);
  return container.innerHTML; // innerHTML will be a xss safe string
}


function getMoreTops(myid,base,limit,kind,cp,subject,showshortnames){
	"use strict";
	document.getElementById("top-transcribers-spinner").style.display = "block";
	
	jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_widgets/tct-top-transcribers/skript/tct-top-transcribers-skript.php",{'q':'gtttrs','myid':myid,'base':base,'limit':limit,'kind':kind,'cp':cp,'subject':subject,'shortnames':showshortnames}, function(res) {	
    
    console.log("test");
    if(res.stat === "ok"){
			jQuery('#tu_list_'+myid).html(res.content);
			jQuery('#ttnav_'+myid).html(res.ttnav);
		}else{
      console.log(jQuery('#tu_list_'+myid).html());
			alert(res.content);	
		}
	});
}

function getMoreTopsPage(myid,limit,kind,cp,subject,showshortnames){
	"use strict";
	var load = document.getElementById("top-transcribers-spinner");
	load.style.display = "block";
	var base = document.getElementById("page_input_" + subject).value;
	if (isNaN(base) || base == ""){
		load.style.display = "none";
		document.getElementById("pageWarning_" + subject).style.display = "block";
		return 0;
	}
	else{
		base = (parseInt(base)-1) * limit;
	}
	
	jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_widgets/tct-top-transcribers/skript/tct-top-transcribers-skript.php",{'q':'gtttrs','myid':myid,'base':base,'limit':limit,'kind':kind,'cp':cp,'subject':subject,'shortnames':showshortnames}, function(res) {	
		if(res.stat === "ok"){
			jQuery('#tu_list_'+myid).html(res.content);
			jQuery('#ttnav_'+myid).html(res.ttnav);
		}else{
			alert(res.content);	
		}
	});
}