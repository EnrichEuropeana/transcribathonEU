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
  
  jQuery('.description-save textarea').keyup(function() {
    var block_data = jQuery(this).val();
            if(block_data.length==0){
            jQuery('#description-update-button').css('display','none');
            }else{
        jQuery('#description-update-button').css('display','block');
        }
    });

  // Show/Hide Transcription Save button                             
  jQuery('#item-page-transcription-text').keyup(function() {
    var transcriptionText = jQuery('#item-page-transcription-text').text();
    var languages = jQuery('#transcription-selected-languages ul').children().length;
    if(transcriptionText.length != 0 && languages > 0) {
      jQuery('#transcription-update-button').css('display','block');
    }
    else {
      jQuery('#transcription-update-button').css('display','none');
    }
  });
  
  jQuery('#no-text-selector input').change(function() {
    var checked = this.checked;
    var transcriptionText = jQuery(this).text();
    if (checked == true) {
      if(transcriptionText.length == 0) {
        jQuery('#transcription-language-selector select').attr("disabled", "disabled");
        jQuery('#transcription-language-selector select').addClass("disabled-dropdown");
        tinymce.remove();
        jQuery('#transcription-update-button').css('display','block');
      }
      else {
        alert("Please remove the transcription text first, if the document has nothing to transcribe");
      }
    }
    else {
      jQuery('#transcription-language-selector select').removeAttr("disabled");
      jQuery('#transcription-language-selector select').removeClass("disabled-dropdown");
      tct_viewer.initTinyWithConfig('#item-page-transcription-text');
    }
  })

  /*
  jQuery('#no-text-checkbox').change(function() {
    if(this.checked) {
      jQuery('#no-text-label').addClass('theme-color-background');
      jQuery('#no-text-label').removeClass('theme-color');
    }
    else {
      jQuery('#no-text-label').removeClass('theme-color-background');
      jQuery('#no-text-label').addClass('theme-color');
    }
  });*/

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

  // Get languages
  var transcriptionLanguages = [];
  jQuery("#transcription-language-selector option").each(function() {
    var nextLanguage = {};
    if (jQuery(this).prop('disabled') == true && jQuery(this).val() != "") {
      nextLanguage.LanguageId = jQuery(this).val();
      transcriptionLanguages.push(nextLanguage);
    }
  });

  // Prepare data and send API request
  data = {
            Text: jQuery('#item-page-transcription-text').html(),
            UserId: userId,
            ItemId: itemId,
            CurrentVersion: 1,
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
    var response = JSON.parse(response);
    if (response.code == "200") {
      jQuery('#transcription-update-message').html("Transcription saved")
    }
    else {
      jQuery('#transcription-update-message').html("Transcription couldn't be saved")
    }
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
        'url': 'http://fresenia.man.poznan.pl/tp-api/itemProperties',
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
}

function saveItemLocation(itemId, userId) {
  // Prepare data and send API request
  locationName = jQuery('#location-input-name-container input').val();
  [latitude, longitude] = jQuery('#location-input-coordinates-container input').val().split(',');
  description = jQuery('#location-input-description-container input').val();
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
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/places',
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      jQuery('#item-location-list ul').append(
        '<li>' + jQuery('#location-input-name-container input').val() + '</li>'       
      )
    }
  });
}

function saveItemDate(itemId) {
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
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/items/' + itemId,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
  });
}


function savePerson(itemId) {
  
  firstName = jQuery('#person-firstName-input').val();
  lastName = jQuery('#person-lastName-input').val();
  birthPlace = jQuery('#person-birthPlace-input').val();
  birthDate = jQuery('#person-birthDate-input').val().split('/');
  deathPlace = jQuery('#person-deathPlace-input').val();
  deathDate = jQuery('#person-deathDate-input').val().split('/');
  description = jQuery('#person-description-input').val();


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
      'type': 'POST',
      'url': 'http://fresenia.man.poznan.pl/tp-api/persons',
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
  });
}

function saveKeyword(itemId) {
  value = jQuery('#keyword-input').val();

  if (value != "" && value != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: value,
      PropertyType: "Keyword"
    }

    var dataString= JSON.stringify(data);
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': 'http://fresenia.man.poznan.pl/tp-api/properties?ItemId=' + itemId,
        'data': data
    },
    // Check success and create confirmation message
    function(response) {
    });
  }
}

function saveLink(itemId) {
  url = jQuery('#link-url-input input').val();
  description = jQuery('#link-description-input textarea').val();

  if (url != "" && url != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: url,
      PropertyDescription: description,
      PropertyType: "Link"
    }
    console.log(data);
    var dataString= JSON.stringify(data);
    jQuery.post('/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': 'http://fresenia.man.poznan.pl/tp-api/properties?ItemId=' + itemId,
        'data': data
    },
    // Check success and create confirmation message
    function(response) {
    });
  }
}