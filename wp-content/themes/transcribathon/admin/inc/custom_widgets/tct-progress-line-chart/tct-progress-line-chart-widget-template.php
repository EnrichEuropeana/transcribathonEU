<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;


if (isset($instance['tct-progress-line-chart-headline']) && trim($instance['tct-progress-line-chart-headline']) != "") { 
  echo "<h3>".str_replace("\n","<br />",$instance['tct-progress-line-chart-headline'])."</h3>\n"; 
}

 //if(isset($instance['tct-progress-line-chart-figure']) && trim($instance['tct-progress-line-chart-figure']) != ""){ echo "<div class='ct-chart ct-golden-section' id='chart1'>".str_replace("\n","<br />",$instance['tct-progress-line-chart-figure'])."</div>\n"; }
 if( ! empty( $instance['image'] ) ) {
  //$size = empty( $instance['image_size'] ) ? 'full' : $instance['image_size']; // Account for no image size selection
  $attachment = wp_get_attachment_image_src( $instance['image'], $size );
  if( !empty( $attachment ) ) {
      //echo '<div class="ct-chart ct-golden-section" id="chart1"></div>';
  }
}
//function _TCT_progress_line_chart( $atts ) {

  $content = "";

  $content .= '<div>';
  
 // $content .= '<canvas id="myChart" width="400" height="400"></canvas>';

  
 
  
 $chartId = str_replace(" ", "", $instance['tct-progress-line-chart-headline']);
 $content .= '<canvas id="lineChart'.$chartId.'"  width="400" height="400" display="inline"></canvas>';

  $content .= '</div>';

  $content .= "<script>
  
              var ctx = document.getElementById('lineChart".$chartId."');
              var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                  labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                  datasets: [{
                      label: '',
                      lineTension: 0,
                      borderColor: 'rgba(9, 97, 129, 1)',
                      data: [12, 19, 3, 5, 2, 3],
                      backgroundColor: 'rgba(9, 97, 129, 0.4)',
                      borderWidth: 1
                  }]
              },
              options: {
                legend: {
                    onClick: (e) => e.stopPropagation(),
                    labels: {
                      boxWidth: '0px'
                    }
                },
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero: true
                          }
                      }]
                  }
              }
          });
              </script>";
/*$content .= "<style>
  line.ct-point {
  stroke: #0e4c63 !important;
}
path.ct-line {
    stroke: #0e4c63 !important;
}
</style>";
  $content .= '<div class="transcribing-chart" style="display:inline;">';

  //$content .= "<h4 class='theme-color frontpage-top-headline'>TRANSCRIBERS</h4>";
  
 
 $content .= '<div class="ct-chart ct-golden-section" id="chart-'.str_replace(" ", "-", $instance['tct-progress-line-chart-headline']).'"></div>';

  $content .= '</div>';

  $content .= "<script>
    // Initialize a Line chart in the container with the ID chart1
    new Chartist.Line('#chart-".str_replace(" ", "-", $instance['tct-progress-line-chart-headline'])."', {
      labels: [],
      series: [[482, 1177, 1314, 3380, 4495, 5013, 7526, 15584],
      [440, 1324, 2085, 2787, 4217, 7664, 8489, 14175]]
    }
    
    );
  </script>";*/

 
  
  echo $content;

//}

?>


