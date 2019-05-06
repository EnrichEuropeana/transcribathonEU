function switchTab(event, tabName) {
    
    var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  event.currentTarget.className += " active";
  /*
    //jQuery('#panel-right-content').html(tab);
    jQuery('#editor-tab').css('display', 'none');
    jQuery('#equilize-tab').css('display', 'none');
    jQuery('#info-tab').css('display', 'none');
    jQuery('#tags-tab').css('display', 'none');
    jQuery('#query-tab').css('display', 'none');

    jQuery('#' + tab + '-tab').css('display', 'block');
    */
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

    
}