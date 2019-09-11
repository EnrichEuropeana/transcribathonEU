var home_url = WP_URLs.home_url;

jQuery ( document ).ready(function() {

  var x, i, j, selElmnt, a, b, c;
  /*look for any elements with the class "language-selector-background":*/
  x = document.getElementsByClassName("language-selector-background");
  for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("div");
    a.setAttribute("class", "language-select-selected");
    if (jQuery(selElmnt).parent().attr('id') == "description-language-selector") {
      a.setAttribute("id", "description-language-custom-selector");
    }
    if (jQuery(selElmnt).parent().attr('id') == "transcription-language-selector") {
      a.setAttribute("id", "transcription-language-custom-selector");
    }

    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("div");
    b.setAttribute("class", "language-item-select select-hide");
    for (j = 1; j < selElmnt.length; j++) {
      /*for each option in the original select element,
      create a new DIV that will act as an option item:*/
      c = document.createElement("div");
      c.setAttribute("class", "selected-option");
      c.innerHTML = selElmnt.options[j].innerHTML;
      b.appendChild(c);
    }
    x[i].appendChild(b);
  }

  installEventListeners()

});

function uninstallEventListeners() {

  jQuery(".datepicker-input-field").datepicker("destroy");
  tinymce.remove();
}

function installEventListeners() {
  // When the user clicks the button, open the modal 
  jQuery('#lock-login').click(function() {
    jQuery('#item-page-login-container').css('display', 'block');
  })
  jQuery('#lock-loginFS').click(function() {
    jQuery('#item-page-login-container').css('display', 'block');
  })

  // When the user clicks on <span> (x), close the modal
  jQuery('.item-login-close').click(function() {
    jQuery('#item-page-login-container').css('display', 'none');
  })

  jQuery('.item-page-section-headline-container.collapse-headline').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#login').length) {
      event.preventDefault();
      jQuery('#item-page-login-container').css('display', 'block');
    }
  })
  jQuery('#mce-wrapper-transcription').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#login').length) {
      event.preventDefault();
      jQuery('#item-page-login-container').css('display', 'block');
    }
  })
  jQuery('#item-page-description-text').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#login').length) {
      event.preventDefault();
      jQuery('#item-page-login-container').css('display', 'block');
    }
  })
  jQuery('.edit-item-data-icon').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#login').length) {
      event.preventDefault();
      jQuery('#item-page-login-container').css('display', 'block');
    }
  })

// ### Prevent locked functions ### 
  jQuery('#mce-wrapper-transcription').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#transcribeLock').length) {
      event.preventDefault();
      lockWarning();
    }
  })
  jQuery('#item-page-description-text').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#transcribeLock').length) {
      event.preventDefault();
      lockWarning();
    }
  })
  jQuery('.edit-item-data-icon').mousedown(function(event) {
    // Checks if document is locked
    if (jQuery('#transcribeLock').length) {
      event.preventDefault();
      lockWarning();
    }
  })

  var options = document.getElementsByClassName('selected-option');
  for (var i = 0; i < options.length; i++) {
    options[i].addEventListener("click", function(e) {
      /*when an item is clicked, update the original select box,
      and the selected item:*/
      var y, i, k, s, h;
      s = this.parentNode.parentNode.getElementsByTagName("select")[0];
      h = this.parentNode.previousSibling;
      for (i = 0; i < s.length; i++) {
        if (s.options[i].innerHTML == this.innerHTML) {
          s.selectedIndex = i;
          h.innerHTML = this.innerHTML;
          y = this.parentNode.getElementsByClassName("same-as-selected");
          for (k = 0; k < y.length; k++) {
            y[k].removeAttribute("class");
          }
          this.setAttribute("class", "same-as-selected");
          break;
        }
      }
      h.click();
    });
  }

  var selectors = document.getElementsByClassName('language-select-selected');
  for (var i = 0; i < selectors.length; i++) {
    selectors[i].addEventListener("click", function(e) {
        /*when the select box is clicked, close any other select boxes,
        and open/close the current select box:*/
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
        });
  }
  /*if the user clicks anywhere outside the select box,
  then close all select boxes:*/
  document.addEventListener("click", closeAllSelect);

  jQuery('.edit-item-date').click(function() {
    if (jQuery('#transcribeLock').length) {
      event.preventDefault();
      lockWarning();
    }
    else {
      jQuery(this).parent('.item-date-display-container').css('display', 'none')
      jQuery(this).parent('.item-date-display-container').siblings('.item-date-input-container').css('display', 'block')
    }
  })

  jQuery('.search-page-single-result-description').each(function() {
    if (jQuery(this).prop('scrollHeight') > jQuery(this).prop('clientHeight')) {
      jQuery(this).siblings('span').css('display', '-webkit-inline-box');
    }
  })  

  jQuery('.search-page-item-tab-button').click(function() {
    jQuery('#search-page-item-tab').css('display', 'block')
    jQuery('#search-page-story-tab').css('display', 'none')
    jQuery(".search-page-item-tab-button").addClass("theme-color-background");
    jQuery(".search-page-story-tab-button").removeClass("theme-color-background");
  })
  jQuery('.search-page-story-tab-button').click(function() {
    jQuery('#search-page-item-tab').css('display', 'none')
    jQuery('#search-page-story-tab').css('display', 'block')
    jQuery(".search-page-story-tab-button").addClass("theme-color-background");
    jQuery(".search-page-item-tab-button").removeClass("theme-color-background");
  })
  
  jQuery(".search-results-grid-radio").click(function() {
    jQuery(".search-page-single-result").addClass("maingridview");
    jQuery(".grid-view-image").css("display", "block");
    jQuery(".list-view-image").css("display", "none");
    jQuery(".search-results-grid-radio label").addClass("theme-color-background");
    jQuery(".search-results-grid-radio i").removeClass("theme-color");
    jQuery(".search-results-list-radio label").removeClass("theme-color-background");
    jQuery(".search-results-list-radio i").addClass("theme-color");
    jQuery(".search-page-single-result-info").removeClass(".search-page-single-result-description");
  });

  jQuery(".search-results-list-radio").click(function(){
    jQuery(".search-page-single-result").removeClass("maingridview");
    jQuery(".grid-view-image").css("display", "none");
    jQuery(".list-view-image").css("display", "block");
    jQuery(".search-results-list-radio label").addClass("theme-color-background");
    jQuery(".search-results-list-radio i").removeClass("theme-color");
    jQuery(".search-results-grid-radio label").removeClass("theme-color-background");
    jQuery(".search-results-grid-radio i").addClass("theme-color");
    jQuery(".search-page-single-result-info").addClass(".search-page-single-result-description");
  });

  jQuery(document).keyup(function(e) {
    if (e.key === "Escape") {
       if (jQuery('#image-view-container').css('display') != "none") {
         switchItemPageView();
       }
    }
  });
  
  var keyWordList = [];   
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/properties?PropertyType=Keyword'
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
  jQuery('#transcription-language-custom-selector').siblings('.language-item-select').children('.selected-option').click(function(){
    jQuery('#no-text-selector').css('display','none');
    jQuery('#transcription-selected-languages ul').append(
            '<li>' 
              + jQuery('#transcription-language-selector option:selected').text() 
              + '<i class="far fa-times" onClick="removeTranscriptionLanguage(' + jQuery('#transcription-language-selector option:selected').val() + ', this)"></i>'
            + '</li>');
    jQuery('#transcription-language-selector option:selected').prop("disabled", true);   
    var transcriptionText = jQuery('#item-page-transcription-text').text();     
    if(transcriptionText.length != 0) {
      jQuery('#transcription-update-button').addClass('theme-color-background');
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
  jQuery('#description-language-custom-selector').siblings('.language-item-select').children('.selected-option').click(function(){
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
      jQuery('#transcription-update-button').addClass('theme-color-background');
    }
    else {
      jQuery('#transcription-update-button').removeClass('theme-color-background');
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
        jQuery('#transcription-language-custom-selector select').attr("disabled", "disabled");
        jQuery('#transcription-language-custom-selector select').addClass("disabled-dropdown");
        tinymce.remove();
        jQuery('#transcription-update-button').addClass('theme-color-background');
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
      jQuery('#transcription-update-button').removeClass('theme-color-background');
    }
  })


  var startDate = jQuery("#startdateentry").val();
  var endDate = jQuery("#enddateentry").val();
  var birthDate = jQuery("#person-birthDate-input").val();
  var deathDate = jQuery("#person-deathDate-input").val();

  jQuery( ".datepicker-input-field" ).datepicker({
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


function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("language-item-select");
  y = document.getElementsByClassName("language-select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("select-hide");
      }
  }
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
    icons[i].className = icons[i].className.replace(" theme-color", "");
  }

  // Make icon active
  event.currentTarget.className += " active";
  event.currentTarget.className += " theme-color";
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
      jQuery("#item-data-section").removeClass("data-closed")
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
      
      jQuery("#item-data-content").css("display", 'block')
      jQuery("#item-tab-list").css("display", 'block')
      jQuery("#item-status-doughnut-chart").css("display", 'block')
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
      jQuery("#item-data-section").removeClass("data-closed")
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

      jQuery("#item-data-content").css("display", 'block')
      jQuery("#item-tab-list").css("display", 'block')
      jQuery("#item-status-doughnut-chart").css("display", 'block')
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
      jQuery("#item-data-section").removeClass("data-closed")
      jQuery("#item-splitter").removeClass("splitter-vertical")
      jQuery("#item-splitter").removeClass("splitter-horizontal")
      jQuery( "#item-data-section" ).resizable({ handles: "n, e, s, w, se, ne, sw, nw" })
      jQuery("#item-data-section").draggable()
      jQuery("#item-data-section").draggable('enable')
      jQuery( "#item-data-section" ).resizable()
      jQuery( "#item-data-section" ).resizable('enable')

      jQuery("#item-data-content").css("display", 'block')
      jQuery("#item-tab-list").css("display", 'block')
      jQuery("#item-status-doughnut-chart").css("display", 'block')
      break;
    case 'closewindow':
      jQuery("#item-image-section").css("width", '100%')
      jQuery("#item-image-section").css("height", '100%')
      jQuery("#item-data-section").css("width", '')
      jQuery("#item-data-section").css("height", '')
      jQuery("#item-data-section").css("left", '')
      jQuery("#item-data-section").css("bottom", '')
      jQuery("#item-data-section").css("top", '')
      jQuery("#item-data-section").css("right", '')
      jQuery("#item-image-section").addClass("image-popout")
      jQuery("#item-data-section").addClass("data-closed")
      jQuery("#image-view-container").removeClass("panel-container-horizontal")
      jQuery("#image-view-container").removeClass("panel-container-vertical")
      jQuery("#item-image-section").removeClass("panel-left")
      jQuery("#item-image-section").removeClass("panel-top")
      jQuery("#item-data-section").removeClass("panel-right")
      jQuery("#item-data-section").removeClass("panel-bottom")
      jQuery("#item-data-section").removeClass("data-popout")
      jQuery("#item-splitter").removeClass("splitter-vertical")
      jQuery("#item-splitter").removeClass("splitter-horizontal")
      jQuery( "#item-data-section" ).resizable({ handles: "n, e, s, w, se, ne, sw, nw" })
      jQuery("#item-data-section").draggable()
      jQuery("#item-data-section").draggable('disable')
      jQuery( "#item-data-section" ).resizable()
      jQuery( "#item-data-section" ).resizable('disable')
      jQuery( "#item-data-section" ).removeClass("ui-resizable")
      jQuery( ".ui-resizable-handle" ).css("display", "none")
      
      jQuery("#item-data-content").css("display", 'none')
      jQuery("#item-tab-list").css("display", 'none')
      jQuery("#item-status-doughnut-chart").css("display", 'none')
      break;
    }
}

// Calls script to draw linechart on the profile page
function getTCTlinePersonalChart(what,start,ende,holder,uid){
  "use strict";
  jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/linechart-script.php",
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
    var descriptionText = jQuery('#item-page-description-text').val();
    var descriptionLanguage = jQuery('#description-language-selector select').val();

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
    
    jQuery('#item-page-description-text').val(descriptionText);
    jQuery('#description-language-selector select').val(descriptionLanguage);

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
  jQuery('.item-page-slider').slick('refresh')
}

// Updates specified data over the API
function updateDataProperty(dataType, id, fieldName, value) {
  // Prepare data and send API request
  data = {
          };
  data[fieldName] = value;

  var dataString= JSON.stringify(data);
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': home_url + '/tp-api/' + dataType + '/' + id,
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
	
	jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
		'type': 'GET',
		'url': home_url + '/tp-api/items/' + itemId
	},
	function(response) {
		// Check success and create confirmation message
		var response = JSON.parse(response);
    var descriptionCompletion = JSON.parse(response.content)[0]["DescriptionStatusName"];
    var oldDescription = JSON.parse(response.content)[0]["Description"];
    
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': home_url + '/tp-api/items/' + itemId,
      'data': data
    },
    // Check success and create confirmation message
    function(response) {
      if (oldDescription != null) {
        var amount = description.length - oldDescription.length;
      }
      else {
        var amount = description.length;
      }
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
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/scores',
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
function updateItemTranscription(itemId, userId, editStatusColor, statusCount) {
  if (!jQuery('#transcription-update-button').hasClass('theme-color-background')) {
    alert("Please add a text and language first");
    return 0;
  }

  jQuery('#item-transcription-spinner-container').css('display', 'block')

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

  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': home_url + '/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var itemCompletion = JSON.parse(response.content)[0]["CompletionStatusName"];
      var transcriptionCompletion = JSON.parse(response.content)[0]["TranscriptionStatusName"];
      var currentTranscription = "";
      for (var i = 0; i < JSON.parse(response.content)[0]["Transcriptions"].length; i++) {
        if (JSON.parse(response.content)[0]["Transcriptions"][i]["CurrentVersion"] == 1) {
          currentTranscription = JSON.parse(response.content)[0]["Transcriptions"][i]["TextNoTags"];
        }
      }
      
      var newTranscriptionLength = 0
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/strip_tags.php', {
        'text': jQuery('#item-page-transcription-text').html().replace("&nbsp;", " ")
      },
      function(response) {
        newTranscriptionLength = response.length;
      })


      // Prepare data and send API request
      data = {
          Text: tinyMCE.editors[jQuery('#item-page-transcription-text').attr('id')].getContent({format : 'html'}),
          TextNoTags: tinyMCE.editors[jQuery('#item-page-transcription-text').attr('id')].getContent({format : 'text'}),
          UserId: userId,
          ItemId: itemId,
          CurrentVersion: 1,
          NoText: noText,
          Languages: transcriptionLanguages,
          }
      var dataString= JSON.stringify(data);
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': home_url + '/tp-api/transcriptions',
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
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': home_url + '/tp-api/scores',
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
          jQuery('#transcription-update-button').removeClass('theme-color-background');
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
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'POST',
    'url': home_url + '/tp-api/transcriptionLanguages',
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
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': home_url + '/tp-api/itemProperties',
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
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/scores',
          'data': scoreData
      },
      // Check success and create confirmation message
      function(response) {
      })
    });
  }
  else {
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'GET',
        'url': home_url + '/tp-api/itemProperties?ItemId=' + itemId + '&PropertyId=' + propertyId,
    },
    // Check success and create confirmation message
    function(response) {
      var response = JSON.parse(response);
      if (response.code == "200") {
        var itemPropertyId = JSON.parse(response.content)[0]['ItemPropertyId'];
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'DELETE',
          'url': home_url + '/tp-api/itemProperties/' + itemPropertyId
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
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'GET',
        'url': home_url + '/tp-api/items/' + itemId
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
  jQuery(e.closest("li")).remove()
  var transcriptionText = jQuery('#item-page-transcription-text').text();  
  var languages = jQuery('#transcription-selected-languages ul').children().length;   
  if(transcriptionText.length != 0 && languages > 0) {
    jQuery('#transcription-update-button').addClass('theme-color-background');
  }
  else {
    jQuery('#transcription-update-button').removeClass('theme-color-background');
  }   
  if(transcriptionText.length == 0 && languages == 0) {
    jQuery('#no-text-selector').css('display','block');
  }
}

function saveItemLocation(itemId, userId, editStatusColor, statusCount) {
  jQuery('#item-location-spinner-container').css('display', 'block')
  // Prepare data and send API request
  locationName = jQuery('#location-input-section .location-input-name-container input').val();
  [latitude, longitude] = jQuery('#location-input-section .location-input-coordinates-container input').val().split(',');
  if (latitude != null) {
    latitude = latitude.trim();
  } 
  if (longitude != null) {
    longitude = longitude.trim();
  }
  if (isNaN(latitude) || isNaN(longitude)) {
    jQuery('#location-input-section .location-input-coordinates-container span').css('display', 'block');
    jQuery('#item-location-spinner-container').css('display', 'none')
    return 0;
  }

  description = jQuery('#location-input-section .location-input-description-container textarea').val();
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
  
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': home_url + '/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var locationCompletion = JSON.parse(response.content)[0]["LocationStatusName"];
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/places',
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
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': home_url + '/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        loadPlaceData(itemId, userId);
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
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': home_url + '/tp-api/items/' + itemId
  },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      var oldStartDate = JSON.parse(response.content)[0]["DateStart"];
      var oldEndDate = JSON.parse(response.content)[0]["DateEnd"];
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/items/' + itemId,
          'data': data
      },
      // Check success and create confirmation message
      function(response) {
        if (startDate != "" && startDate != oldStartDate) {
          jQuery('#startdateDisplay').parent('.item-date-display-container').css('display', 'block')
          jQuery('#startdateDisplay').parent('.item-date-display-container').siblings('.item-date-input-container').css('display', 'none')
          jQuery('#startdateDisplay').html(jQuery('#startdateentry').val())
        }
        if (endDate != "" && endDate != oldEndDate) {
          jQuery('#enddateDisplay').parent('.item-date-display-container').css('display', 'block')
          jQuery('#enddateDisplay').parent('.item-date-display-container').siblings('.item-date-input-container').css('display', 'none')
          jQuery('#enddateDisplay').html(jQuery('#enddateentry').val())
        }
        scoreData = {
                      ItemId: itemId,
                      UserId: userId,
                      ScoreType: "Enrichment",
                      Amount: 1
                    }
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': home_url + '/tp-api/scores',
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
  description = jQuery('#person-description-input-field').val();

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
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'GET',
    'url': home_url + '/tp-api/items/' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': home_url + '/tp-api/persons',
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
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/scores',
          'data': scoreData
      },
      // Check success and create confirmation message
      function(response) {
      })

      loadPersonData(itemId, userId);
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
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/items/' + itemId
    },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/properties?ItemId=' + itemId,
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
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': home_url + '/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })
        
        loadKeywordData(itemId, userId);
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

function saveLink(itemId, userId, editStatusColor, statusCount, e) {
  jQuery('#item-link-spinner-container').css('display', 'block')
  url = jQuery('#link-input-container .link-url-input input').val();
  description = jQuery('#link-input-container .link-description-input textarea').val();

  if (url != "" && url != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: url,
      PropertyDescription: description,
      PropertyType: "Link"
    }
    var dataString= JSON.stringify(data);
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/items/' + itemId
    },
    function(response) {
      var response = JSON.parse(response);
      var taggingCompletion = JSON.parse(response.content)[0]["TaggingStatusName"];
      jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
          'type': 'POST',
          'url': home_url + '/tp-api/properties?ItemId=' + itemId,
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
        jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
            'type': 'POST',
            'url': home_url + '/tp-api/scores',
            'data': scoreData
        },
        // Check success and create confirmation message
        function(response) {
        })

        loadLinkData(itemId, userId);
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

function loadPlaceData(itemId, userId) {
  // Get new location list
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/places?ItemId=' + itemId
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
          '<li id="location-' + content[i]['PlaceId'] + '">' +
            '<div class="item-data-output-element-header collapse-controller" data-toggle="collapse" href="#location-data-output-' + content[i]['PlaceId'] + '">' +
                '<h6>' +
                    content[i]['Name'] +
                '</h6>' +
                '<i class="fas fa-angle-down"' +  'style= "float:right;"></i>' +
                '<div style="clear:both;"></div>' +
              '</div>' +
              '<div id="location-data-output-' + content[i]['PlaceId'] + '" class="collapse">' +
                            '<div id="location-data-output-display-' + content[i]['PlaceId'] + '" class="location-data-output-content">' +
                                '<span>' +
                                    'Description: ' +
                                     comment +
                                '</span>' +
                                '<i class="edit-item-data-icon fas fa-pencil theme-color-hover"' + 
                                                    'onClick="openLocationEdit(' + content[i]['PlaceId'] + ')"></i>' +
                                '<i class="edit-item-data-icon fas fa-trash-alt theme-color-hover"' +
                                                    'onClick="deleteItemData(\'places\', ' + content[i]['PlaceId'] + ', ' + itemId + ', \'place\', ' + userId + ')"></i>' +
                            '</div>' +

                            '<div id="location-data-edit-' + content[i]['PlaceId'] + '" class="location-data-edit-container">' + 
                                '<div class="location-input-section-top">' +
                                    '<div class="location-input-name-container location-input-container">' +
                                        '<label>Location name:</label><br/>' +
                                        '<input type="text" value="' + content[i]['Name'] + '" name="" placeholder="">' +
                                    '</div>' +
                                    '<div class="location-input-coordinates-container location-input-container">' +
                                        '<label>Coordinates: </label>' +
                                        '<span class="required-field">*</span>' +
                                        '<br/>' +
                                        '<input type="text" value="' + content[i]['Latitude'] + ', ' + content[i]['Longitude'] + '" name="" placeholder="">' +
                                    '</div>' +
                                    "<div style='clear:both;'></div>" +
                                '</div>' +
                
                                '<div class="location-input-description-container location-input-container">' +
                                    '<label>Description:</label><br/>' +
                                    '<textarea rows= "2" style="resize:none;" class="gsearch-form" type="text" id="ldsc" placeholder="" name="">' + comment + '</textarea>' +
                                '</div>' +

                                '<div id="location-input-geonames-search-container" class="location-input-container location-search-container">' +
                                  '<label>WikiData:</label><br/>' +
                                  '<input type="text" id="lgns" placeholder="" name="">' +
                                     '<a id="geonames-search-button" href="">' +
                                        '<i class="far fa-search"></i>' +
                                     '</a>' +
                                '</div>' +
                
                                "<div class='form-buttons-right'>" +
                                    "<button onClick='editItemLocation(" + content[i]['PlaceId'] + ", " + itemId + ", " + userId + ")' " +
                                                "class='item-page-save-button edit-data-save-right theme-color-background'>" +
                                        "SAVE" +
                                    "</button>" +

                                    "<button class='theme-color-background edit-data-cancel-right' onClick='openLocationEdit(" + content[i]['PlaceId'] + ")'>" +
                                        "CANCEL" +
                                    "</button>" +

                                    '<div id="item-location-' + content[i]['PlaceId'] +'-spinner-container" class="spinner-container spinner-container-right">' +
                                        '<div class="spinner"></div>' +
                                    "</div>" +
                                    "<div style='clear:both;'></div>" +
                                "</div>" +
                                "<div style='clear:both;'></div>" +
                               "</div>" +
                        "</div>" +
          '</li>'    
        )
      }
    }
  });
}

function loadPersonData(itemId, userId) {
  // Get new person list
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/persons?ItemId=' + itemId
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
            birthDate = ("0" + birthDate.getDate()).slice(-2) + '/' + ("0" + (birthDate.getMonth() + 1)).slice(-2) + '/' + birthDate.getFullYear();
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
            deathDate = ("0" + deathDate.getDate()).slice(-2) + '/' + ("0" + (deathDate.getMonth() + 1)).slice(-2) + '/' + deathDate.getFullYear();
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
        
        var personHeadline = '<span class="item-name-header">' +
         firstName + ', ' + lastName + ' ' +
        '</span>';
        if (birthDate != "") {
          if (deathDate != "") {
            personHeadline += '(' + birthDate + ' - ' + deathDate + ')';
          }
          else {
            personHeadline += '(Birth: ' + birthDate + ')';
          }
        }
        else {
          if (deathDate != "") {
            personHeadline += '(Death: ' + deathDate + ')';
          }
          else {
            if (description != "") {
              personHeadline += "<span class='person-dots'>(" + description + ")</span>";
            }
          }
        }
        var personHtml = 
        '<li id="person-' + content[i]['PersonId'] + '">' +
          '<div class="item-data-output-element-header collapse-controller" data-toggle="collapse" href="#person-data-output-' + content[i]['PersonId'] + '">' +
            '<h6 class="person-data-ouput-headline">' +
              personHeadline +
            '</h6>' +
            '<span class="person-dots" style="width=10px; white-space: nowrap; text-overflow:ellipsis;"></span>' +
            '<i class="fas fa-angle-down" style= "float:right;"></i>' +
            '<div style="clear:both;"></div>' +
          '</div>' + 
          '<div id="person-data-output-' + content[i]['PersonId'] + '" class="collapse">' +
            '<div id="person-data-output-display-' + content[i]['PersonId'] + '" class="person-data-output-content">' +
              '<div class="person-data-output-birthDeath">' +
                  '<span>' +
                      'Birth Location: ' +
                      birthPlace +
                  '</span>' +
                    '</br>' +
                  '<span>' +
                      'Death Location: ' +
                      deathPlace +
                  '</span>' +
              '</div>' +
              '<div class="person-data-output-birthDeath">' +
                  '<span>' +
                      'Birth Date: ' +
                      birthDate +
                  '</span>' +
                  '</br>' +
                  '<span>' +
                      'Death Date: ' +
                      deathDate +
                  '</span>' +

                  '</br>' +
              '</div>' +
              '<div class="person-data-output-button">'+
                      '<span>'+
                          'Description: '+
                          description +
                      '</span>' +
                      '<i class="edit-item-data-icon fas fa-pencil theme-color-hover"' +
                                          'onClick="openPersonEdit(' + content[i]['PersonId'] +')"></i>' +
                      '<i class="edit-item-data-icon fas fa-trash-alt theme-color-hover"' +
                                          'onClick="deleteItemData(\'persons\', ' + content[i]['PersonId'] + ', ' + itemId + ', \'person\', ' + userId + ')"></i>' +                                            
              '</div>' +
              '<div style="clear:both;"></div>' +
            '</div>' +

            '<div class="person-data-edit-container person-item-data-container" id="person-data-edit-' + content[i]['PersonId'] + '">' +
              '<div class="person-input-names-container">';
                if (firstName != "") {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-firstName-edit"  placeholder="First Name" class="person-input-field" value="' + firstName + '" style="outline:none;">'
                }
                else {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-firstName-edit" class="person-input-field" placeholder="First Name" style="outline:none;">'
                }
                
                if (lastName != "") {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-lastName-edit" class="person-input-field" placeholder="Last Name" value="' + lastName + '" style="outline:none;">'
                }
                else {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-lastName-edit" class="person-input-field" placeholder="Last Name" style="outline:none;">'
                }
              personHtml += 
              '</div>' + 
              
              '<div class="person-location-birth-inputs">';
                if (birthPlace != "") {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-birthPlace-edit" class="person-input-field" value="' + birthPlace + '" placeholder="Birth Location" style="outline:none;">'
                }
                else {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-birthPlace-edit" class="person-input-field" placeholder="Birth Location" style="outline:none;">'
                }
                
                if (birthDate != "") {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-birthDate-edit" class="person-input-field datepicker-input-field" value="' + birthDate + '" placeholder="Birth: dd/mm/yyyy" style="outline:none;">'
                }
                else {
                  personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-birthDate-edit" class="person-input-field datepicker-input-field" placeholder="Birth: dd/mm/yyyy" style="outline:none;">'
                }
                personHtml += 
                '</div>' + 
                
                '<div class="person-location-death-inputs">';
                  if (deathPlace != "") {
                    personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-deathPlace-edit" class="person-input-field" value="' + deathPlace + '" placeholder="Death Location" style="outline:none;">'
                  }
                  else {
                    personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-deathPlace-edit" class="person-input-field" placeholder="Death Location" style="outline:none;">'
                  }
                  
                  if (deathDate != "") {
                    personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-deathDate-edit" class="person-input-field datepicker-input-field" value="' + deathDate + '" placeholder="Death: dd/mm/yyyy" style="outline:none;">'
                  }
                  else {
                    personHtml += '<input type="text" id="person-' + content[i]['PersonId'] + '-deathDate-edit" class="person-input-field datepicker-input-field" placeholder="Death: dd/mm/yyyy" style="outline:none;">'
                  }
                  personHtml += 
                  '</div>' + 

                  '<div class="person-description-input">' +
                      '<label>Additional description:</label><br/>' +
                      '<input type="text" id="person-' + content[i]['PersonId'] + '-description-edit" class="person-input-field" value="' + description + '">' +
                  '</div>' +
                  '<div class="form-buttons-right">' +
                      "<button class='edit-data-save-right theme-color-background'" +
                                  "onClick='editPerson(" + content[i]['PersonId'] + ", " + itemId + ", " + userId + ")'>" +
                          "SAVE" +
                      "</button>" +
                      
                      "<button id='save-personinfo-button' class='theme-color-background edit-data-cancel-right' onClick='openPersonEdit(" + content[i]['PersonId'] + ")'>" +
                          "CANCEL" +
                      "</button>" +
      
                      '<div id="item-person-' + content[i]['PersonId'] + '-spinner-container" class="spinner-container spinner-container-left">' +
                          '<div class="spinner"></div>' +
                      "</div>" +
                      '<div style="clear:both;"></div>' +           
                  '</div>' +
                  '<div style="clear:both;"></div>' +           
                '</div>' +
              '</div>' +
            '</li>'
        jQuery('#item-person-list ul').append(personHtml)
      }   
    }
  });
}

function loadKeywordData(itemId, userId) {
  // Get new keyword list
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/items/' + itemId
  },
  function(response) {
    var response = JSON.parse(response);
    if (response.code == "200") {
      var content = JSON.parse(response.content);
      jQuery('#item-keyword-list ul').html('')
      for (var i = 0; i < content[0]['Properties'].length; i++) {  
        if (content[0]['Properties'][i]['PropertyType'] == "Keyword") { 
          jQuery('#item-keyword-list ul').append( 
            '<li id="add-item-keyword" class="theme-color-background">' +
              content[0]['Properties'][i]['PropertyValue'] +
                '<i class="delete-item-datas far fa-times"' +
                    'onClick="deleteItemData(\'properties\', ' + content[0]['Properties'][i]['PropertyId'] + ', ' + itemId + ', \'keyword\', ' + userId + ')"></i>' +
            '</li>'
          )
        }
      }
    }
  });
}

function loadLinkData(itemId, userId) {
  // Get new link list
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'GET',
      'url': home_url + '/tp-api/items/' + itemId
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
              '<div id="link-data-output-' + content[0]['Properties'][i]['PropertyId'] + '" class="">' +
                '<div id="link-data-output-display-' + content[0]['Properties'][i]['PropertyId'] + '" class="link-data-output-content">' +
                    '<div class="item-data-output-element-header">' +
                        '<a href="' + content[0]['Properties'][i]['PropertyValue'] + '" target="_blank" class="link-data-ouput-headline">' +
                          content[0]['Properties'][i]['PropertyValue'] +
                        '</a>' +
                      
                        '<i class="edit-item-data-icon fas fa-pencil theme-color-hover"' +
                        'onClick="openLinksourceEdit(' + content[0]['Properties'][i]['PropertyId'] + ')"></i>' +
                        '<i class="edit-item-data-icon delete-item-data fas fa-trash-alt theme-color-hover"' +
                                      'onClick="deleteItemData(\'properties\', ' + content[0]['Properties'][i]['PropertyId'] + ', ' + itemId + ', \'link\', ' + userId + ')"></i>' +
                        '<div style="clear:both;"></div>' +
                    '</div>' +
                    '<div>' +
                      '<span>' +
                        'Description: ' +
                        description +
                      '</span>' +
                    '</div>' +
                  '</div>' +

                  '<div class="link-data-edit-container" id="link-data-edit-' + content[0]['Properties'][i]['PropertyId'] +'">' +
                      '<div>' +
                        "<span>Link:</span><br/>" +
                      '</div>' +

                      '<div id="link-' + content[0]['Properties'][i]['PropertyId'] +'-url-input" class="link-url-input">' +
                        '<input type="url" value="' + content[0]['Properties'][i]['PropertyValue'] + '">' +
                      '</div>' +

                      '<div id="link-' + content[0]['Properties'][i]['PropertyId'] +'-description-input" class="link-description-input" >' +
                        '<label>Additional description:</label><br/>' +
                        '<textarea rows= "3" type="text" placeholder="" name="">' + description + '</textarea>' +
                      '</div>' +

                      '<div class="form-buttons-right">' +
                          "<button type='submit' class='theme-color-background' id='link-save-button'" +
                                "onClick='editLink(" + content[0]['Properties'][i]['PropertyId'] + ", " + itemId + ", " + userId + ")'>" +
                            "SAVE" +
                          "</button>" +

                          "<button class='theme-color-background edit-data-cancel-right' onClick='openLinksourceEdit(" + content[0]['Properties'][i]['PropertyId'] + ")'>" +
                            "CANCEL" +
                          "</button>" +

                          '<div id="item-link-' + content[0]['Properties'][i]['PropertyId'] + '-spinner-container" class="spinner-container spinner-container-left">' +
                          '<div class="spinner"></div>' +
                          "</div>" +
                          '<div style="clear:both;"></div>' +
                      '</div>' +
                      '<div style="clear:both;"></div>' +
                  '</div>' +
              '</div>' +
            '</li>'
          )
        }
      }
    }
  });

}

function deleteItemData(type, id, itemId, section, userId) {
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
    'type': 'DELETE',
    'url': home_url + '/tp-api/' + type + '/' + id
  },
  function(response) {
    switch (section) {
      case "place":
          loadPlaceData(itemId, userId);
          break;
      case "person":
          loadPersonData(itemId, userId);
          break;
      case "keyword":
          loadKeywordData(itemId, userId);    
          break;
      case "link":
          loadLinkData(itemId, userId);
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

function openLocationEdit(placeId) {
  if (jQuery('#transcribeLock').length) {
    event.preventDefault();
    lockWarning();
    return 0;
  }

  if (jQuery('#location-data-edit-' + placeId).css('display') == 'none') {
    jQuery('#location-data-edit-' + placeId).css('display', 'block');
    jQuery('#location-data-output-display-' + placeId).css('display', 'none');
  }
  else {
    jQuery('#location-data-edit-' + placeId).css('display', 'none');
    jQuery('#location-data-output-display-' + placeId).css('display', 'block');
  }
}

function openPersonEdit(personId) {
  if (jQuery('#transcribeLock').length) {
    event.preventDefault();
    lockWarning();
    return 0;
  }
  if (jQuery('#person-data-edit-' + personId).css('display') == 'none') {
    jQuery('#person-data-edit-' + personId).css('display', 'block');
    jQuery('#person-data-output-display-' + personId).css('display', 'none');
  }
  else {
    jQuery('#person-data-edit-' + personId).css('display', 'none');
    jQuery('#person-data-output-display-' + personId).css('display', 'block');
  }
}

function openLinksourceEdit(propertyId) {
  if (jQuery('#transcribeLock').length) {
    event.preventDefault();
    lockWarning();
    return 0;
  }
  if (jQuery('#link-data-edit-' + propertyId).css('display') == 'none') {
    jQuery('#link-data-edit-' + propertyId).css('display', 'block');
    jQuery('#link-data-output-display-' + propertyId).css('display', 'none');
  }
  else {
    jQuery('#link-data-edit-' + propertyId).css('display', 'none');
    jQuery('#link-data-output-display-' + propertyId).css('display', 'block');
  }
}


function editItemLocation(placeId, itemId, userId) {
  jQuery('#item-location-' + placeId + '-spinner-container').css('display', 'block')
  // Prepare data and send API request
  locationName = jQuery('#location-data-edit-' + placeId + ' .location-input-name-container input').val();
  [latitude, longitude] = jQuery('#location-data-edit-' + placeId + ' .location-input-coordinates-container input').val().split(',');
  if (latitude != null) {
    latitude = latitude.trim();
  } 
  if (longitude != null) {
    longitude = longitude.trim();
  }
  if (isNaN(latitude) || isNaN(longitude)) {
    jQuery('location-data-edit-' + placeId + ' .location-input-coordinates-container span').css('display', 'block');
    jQuery('#item-location-' + placeId + '-spinner-container').css('display', 'none')
    return 0;
  }

  description = jQuery('#location-data-edit-' + placeId + ' .location-input-description-container textarea').val();
  data = {
            Name: locationName,
            Latitude: latitude,
            Longitude: longitude,
            Comment: description
          }
  var dataString= JSON.stringify(data);

  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': home_url + '/tp-api/places/' + placeId,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    loadPlaceData(itemId, userId);
    
    openLocationEdit(placeId);
    jQuery('#item-location-' + placeId + '-spinner-container').css('display', 'none')
  });
}

function editPerson(personId, itemId, userId) {
  jQuery('#item-person-' + personId + '-spinner-container').css('display', 'block')
  
  firstName = jQuery('#person-' + personId + '-firstName-edit').val();
  lastName = jQuery('#person-' + personId + '-lastName-edit').val();
  birthPlace = jQuery('#person-' + personId + '-birthPlace-edit').val();
  birthDate = jQuery('#person-' + personId + '-birthDate-edit').val().split('/');
  deathPlace = jQuery('#person-' + personId + '-deathPlace-edit').val();
  deathDate = jQuery('#person-' + personId + '-deathDate-edit').val().split('/');
  description = jQuery('#person-' + personId + '-description-edit').val();

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
  
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': home_url + '/tp-api/persons/' + personId,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    loadPersonData(itemId, userId);
    openPersonEdit(personId);
    jQuery('#item-person-' + personId + '-spinner-container').css('display', 'none')
  });
}

function editLink(linkId, itemId, userId) {
  jQuery('#item-link-' + linkId + '-spinner-container').css('display', 'block')
  url = jQuery('#link-' + linkId + '-url-input input').val();
  description = jQuery('#link-' + linkId + '-description-input textarea').val();

  if (url != "" && url != null) {
    // Prepare data and send API request
    data = {
      PropertyValue: url,
      PropertyDescription: description,
      PropertyType: "Link"
    }
    var dataString= JSON.stringify(data);
    
    jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
        'type': 'POST',
        'url': home_url + '/tp-api/properties/' + linkId,
        'data': data
    },
    // Check success and create confirmation message
    function(response) {

      loadLinkData(itemId, userId);
      openLinksourceEdit(linkId);
      jQuery('#item-link-' + linkId + '-spinner-container').css('display', 'none')
    });
  }
}

function generateTeamCode() {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < 10; i++ ) {
     result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

function editTeam(teamId) {
  jQuery('#team-' + teamId + '-spinner-container').css('display', 'block')
  name = jQuery('#admin-team-' + teamId + '-name').val();
  shortName = jQuery('#admin-team-' + teamId + '-shortName').val();
  description = jQuery('#admin-team-' + teamId + '-description').val();
  code = jQuery('#admin-team-' + teamId + '-code').val();

  // Prepare data and send API request
  data = {
    Name: name,
    ShortName: shortName,
    Description: description,
    Code: code
  }
  var dataString= JSON.stringify(data);
  
  jQuery.post(home_url + '/wp-content/themes/transcribathon/admin/inc/custom_scripts/send_ajax_api_request.php', {
      'type': 'POST',
      'url': home_url + '/tp-api/teams/' + teamId,
      'data': data
  },
  // Check success and create confirmation message
  function(response) {
    console.log(response);
    jQuery('#team-' + teamId + '-spinner-container').css('display', 'none')
  });
}



function exitTm(pID,cuID,tID,txt){
	"use strict";
	if(confirm(txt)){
		jQuery('div#ismember_list').html("<p class=\"smallloading\"></p>");
		jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'pls-ex-it-tm','pid':pID,'cuid':cuID,'tid':tID}, function(res) {	
			if(res.status === "ok"){
				if(res.success !== "yes"){
					alert("Sorry, this did not work. Please try again\n\n");
				}
				jQuery('div#ismember_list').html(res.content);
				if(res.refresh_caps !== 'no'){
					jQuery('div#isparticipant_list').html(res.refresh_caps);
				}
				jQuery('div#openteams_list').html(res.openteams);
			}else{
				alert("Sorry, an error occured. Please try again\n\n");	
			}
		});
	}
}
function getTeamTabContent(pID,cuID){
	"use strict";
	jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'init-teamtab','pid':pID,'cuid':cuID}, function(res) {
		if(res.status === "ok"){
			jQuery('div#ismember_list').html(res.teamlist);
			jQuery('div#isparticipant_list').html(res.campaignlist);
			jQuery('div#openteams_list').html(res.openteams);
		}else{
			alert("Sorry, an error occured. Please try again\n\n"+JSON.stringify(res));	
		}
  });
}
function chkTmCd(pID,cuID){
	"use strict";
	var cd = jQuery("input#tct-mem-code").val();
	jQuery('form#tct-tmcd-frm').html("<p class=\"smallloading\"></p>");
	jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'chk-tm-cd','pid':pID,'cuid':cuID,'tidc':cd}, function(res) {	
		if(res.status === "ok"){
			if(res.success !== "yes"){
				alert("Sorry, this did not work. Please try again\n\n");
			}
			jQuery('form#tct-tmcd-frm').html(res.content);
			jQuery('form#tct-tmcd-frm div.message').delay( 3000 ).slideUp( 400 );
			if(res.refresh !== 'no'){
				jQuery('div#ismember_list').html(res.refresh);
			}
			if(res.refresh_caps !== 'no'){
				jQuery('div#isparticipant_list').html(res.refresh_caps);
			}
		}else{
			alert("Sorry, an error occured.\n\n");	
		}
	});
}
function openTmCreator(pid,cuid,obj){
	"use strict";
	if(pid !== cuid){
		alert("ups, something went wrong.\nIt appears as if you are not working\nin your own profile...");
	}else{
		if(!jQuery('form#tct-crtrtm-frm').is(':visible')){
			jQuery('input#qcmpgncd').val('');
			jQuery('input#qtmnm').val('');
			jQuery('textarea#qtsdes').val('');
			jQuery('input#qtmcd').val('');
			jQuery('form#tct-crtrtm-frm').slideDown(function(){
				obj.text(obj.attr('data-rel-close'));
			});
		}else{
			jQuery('form#tct-crtrtm-frm').slideUp(function(){
				obj.text(obj.attr('data-rel-open'));
			});
		}	
	}
}
function checkCode(fid,txt){
	"use strict";
	if(jQuery('input#'+fid).val().split(' ').join('').length >7){
		jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'check-code','cd':jQuery('input#'+fid).val()}, function(res) {	
			if(res.allgood === "no"){
				alert(res.message);
				jQuery('input#'+fid).focus();
			}
		});
	}else if(jQuery('input#'+fid).val().split(' ').join('').length >0){
		alert(txt);
	}
}
function tct_generateCode(fid){
	"use strict";
	jQuery('a#'+fid+'-but').fadeTo(1,0,function(){
		jQuery('p#'+fid+'-waiter').css({'position':'absolute','margin-top':'-10px','margin-left':'15px','display':'block'});
		jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'get-code'}, function(res) {	
			jQuery('input#'+fid).val(res.content);
			jQuery('a#'+fid+'-but').fadeTo(1,1);
			jQuery('p#'+fid+'-waiter').hide();

		});
	});	
	jQuery('input#'+fid).keyup(function(e){
    if(e.keyCode == 46 || e.keyCode == 8) {
        jQuery('input#'+fid).val('');
    }
});
}
function joinTeam(pid,cuid,tid){
	"use strict";
	jQuery('div#openteams_list').html("<p class=\"smallloading\"></p>");
	jQuery('div#ismember_list').html("<p class=\"smallloading\"></p>");
	jQuery.post("/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'join-team','pid':pid,'cuid':cuid,'tid':tid}, function(res) {	
		if(res.status === "ok"){
			jQuery('div#openteams_messageholder').hide();
			jQuery('div#openteams_messageholder').html(res.message);
			jQuery('div#openteams_messageholder').show().delay( 3000 ).slideUp( 400,function(){jQuery('div#openteams_messageholder').html('');});
			jQuery('div#ismember_list').html(res.teamlist);
			jQuery('div#openteams_list').html(res.openteams);
		}else{
			alert('sorry, an error occured. Please reload page.');
		}
	});
	
}

function svTeam(pid,cuid){
	"use strict";
	var tmp = jQuery('a#svTmBut').text();
	var sollich = 0;
	var errs=0;
	var errtxt = "";
	if(jQuery('input#qtmnm').val().split(' ').join('') === ""){
		errs++;
		errtxt += "- "+jQuery('input#qtmnm').attr('data-rel-missing');
	}
	if(jQuery('input#qtmshnm').val().split(' ').join('') === ""){
		errs++;
		errtxt += "- "+jQuery('input#qtmshnm').attr('data-rel-missing');
	}
	if(errs > 0){
		alert(errtxt);
		jQuery('p#svtm-waiter').replaceWith("<a class=\"tct-vio-but\" id=\"svTmBut\" onclick=\"svTeam('"+pid+"','"+cuid+"'); return false;\">"+tmp+"</a>");
		sollich = 0;
	}else{
		jQuery('a#svTmBut').replaceWith("<p id=\"svtm-waiter\" class=\"smallloading\"></p>");
		if(jQuery('input#qcmpgncd').val().split(' ').join('') !== ""){
      /*
			jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'check-tmcpgn-cd','cd':jQuery('input#qcmpgncd').val(),'pid':pid}, function(res) {	
				if(res.allright === "ok"){*/
					
					var transport = {};
					transport.q = 'crt-nw-tm';
					transport.qttl = jQuery('input#qtmnm').val();
					transport.qtshtl = jQuery('input#qtmshnm').val();
					transport.pid = pid;
					transport.cuid = cuid;
					transport.tcd = jQuery('input#qtmcd').val();
					transport.ccd = jQuery('input#qcmpgncd').val();
					transport.tdes = jQuery('textarea#qtsdes').val();
					jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",transport, function(res) {	
						if(res.status === "ok"){
							jQuery('div#team-creation-feedback').hide();
							jQuery('div#team-creation-feedback').html(res.message);
							jQuery('div#team-creation-feedback').show().delay( 3000 ).slideUp( 400,function(){jQuery('div#team-creation-feedback').html('');});
							
							if(res.success > 0){
								//reset team-creator-form
								jQuery('p#svtm-waiter').replaceWith("<a class=\"tct-vio-but\" id=\"svTmBut\" onclick=\"svTeam('"+pid+"','"+cuid+"'); return false;\">"+tmp+"</a>");
								jQuery('input#qcmpgncd').val('');
								jQuery('input#qtmnm').val('');
								jQuery('input#qtmcd').val('');
								jQuery('textarea#qtsdes').val('');
								jQuery('form#tct-crtrtm-frm').slideUp(function(){
									jQuery('a#open-tm-crt-but').text(jQuery('a#open-tm-crt-but').attr('data-rel-open'));
								});
								if(res.teamlist !== 'no'){
									jQuery('div#ismember_list').html(res.teamlist);
								}
								if(res.campaignlist !== 'no'){
									jQuery('div#isparticipant_list').html(res.campaignlist);
								}
							}
						}else{
							alert('Sorry, an error occured');
						}
						jQuery('input#'+fid).val(res.content);
						jQuery('a#'+fid+'-but').fadeTo(1,1);
						jQuery('p#'+fid+'-waiter').hide();

					});
					
          
        /*
				}else{
					alert(res.message);
					jQuery('p#svtm-waiter').replaceWith("<a class=\"tct-vio-but\" id=\"svTmBut\" onclick=\"svTeam('"+pid+"','"+cuid+"'); return false;\">"+tmp+"</a>");
					sollich = 0;
				}
      });
      */
		}else{
			sollich = 1;
		}
	}
	if(sollich>0){
		transport = {};
		transport.q = 'crt-nw-tm';
		transport.qttl = jQuery('input#qtmnm').val();
		transport.qtshtl = jQuery('input#qtmshnm').val();
		transport.pid = pid;
		transport.cuid = cuid;
		transport.tcd = jQuery('input#qtmcd').val();
		transport.ccd = jQuery('input#qcmpgncd').val();
		transport.tdes = jQuery('textarea#qtsdes').val();
		jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",transport, function(res) {	
			if(res.status === "ok"){
				jQuery('div#team-creation-feedback').hide();
				jQuery('div#team-creation-feedback').html(res.message);
				jQuery('div#team-creation-feedback').show().delay( 3000 ).slideUp( 400,function(){jQuery('div#team-creation-feedback').html('');});
				if(res.success > 0){
					//reset team-creator-form
					jQuery('p#svtm-waiter').replaceWith("<a class=\"tct-vio-but\" id=\"svTmBut\" onclick=\"svTeam('"+pid+"','"+cuid+"'); return false;\">"+tmp+"</a>");
					jQuery('input#qcmpgncd').val('');
					jQuery('input#qtmnm').val('');
					jQuery('textarea#qtsdes').val('');
					jQuery('form#tct-crtrtm-frm').slideUp(function(){
						jQuery('a#open-tm-crt-but').text(jQuery('a#open-tm-crt-but').attr('data-rel-open'));
					});
					if(res.teamlist !== 'no'){
						jQuery('div#ismember_list').html(res.teamlist);
					}
					if(res.campaignlist !== 'no'){
						jQuery('div#isparticipant_list').html(res.campaignlist);
					}
				}
			}else{
				alert('Sorry, an error occured');
			}
			jQuery('a#'+fid+'-but').fadeTo(1,1);
			jQuery('p#'+fid+'-waiter').hide();

		});
	}
}

function removeTm(pid,cuid,tid){
	"use strict";
	if(confirm(jQuery('a#teamsdeleter').attr('data-rel-realy'))){
		jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'rem-tmfg','pid':pid,'cuid':cuid,'tid':tid}, function(res) {	
			if(res.status === "ok"){
				if(res.message !== "no"){
					alert(res.message);
				}else{
					if(res.teamlist !== 'no'){
						jQuery('div#ismember_list').html(res.teamlist);
					}
					if(res.campaignlist !== 'no'){
						jQuery('div#isparticipant_list').html(res.campaignlist);
					}
					disablePopup();
				}
			}else{
				alert('Sorry, an error occured');
			}
		});
	}
}
// When the user scrolls down 50px from the top of the document, resize the navbar's padding 
//and the logo's font size

window.onscroll = function() {scrollFunction()};
                    
function scrollFunction() {
  if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {
      document.getElementById("_transcribathon_partnerlogo").style.height = "56px";
      document.getElementById("_transcribathon_partnerlogo").style.width = "56px";
      document.getElementById("_transcribathon_partnerlogo").style.marginLeft = "33px";
  } else {
      document.getElementById("_transcribathon_partnerlogo").style.height = "120px";
      document.getElementById("_transcribathon_partnerlogo").style.width = "120px"; 
      document.getElementById("_transcribathon_partnerlogo").style.marginLeft = "0px";                   
      }
}


function chkTeamname(nmfldid){
	"use strict";
	jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'chk-teamname','title':nmfldid.val(),'myself':'new'}, function(res) {	
		if(res.status === "ok"){
			if(res.usable != "ok"){
				alert(res.message);	
				nmfldid.val('').focus();
			}else{
				// do nothing - title is unique
			}
		}else{
			alert('Sorry, an error occured');
		}
	});
}

function checkAbbr(nmfldid){
	"use strict";
	jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'chk-teamshortname','title':nmfldid.val(),'myself':'new'}, function(res) {	
		if(res.status === "ok"){
			if(res.usable != "ok"){
				alert(res.message);	
				nmfldid.val('').focus();
			}else{
				// do nothing - title is unique
			}
		}else{
			alert('Sorry, an error occured');
		}
	});
}

function checkExAbbr(fid,txt,tid){
	"use strict";
	jQuery.post(home_url + "/wp-content/themes/transcribathon/admin/inc/custom_profiletabs/scripts/team-script.php",{'q':'chk-teamshortname','title':jQuery('#'+fid).val(),'myself':tid}, function(res) {	
		if(res.status === "ok"){
			if(res.usable != "ok"){
				alert(res.message);	
				jQuery('#'+fid).val('');
			}else{
				// do nothing - title is unique
			}
		}else{
			alert('Sorry, an error occured');
		}
	});
}

function lockWarning() {
  alert("Someone else is currently editing this document");
}