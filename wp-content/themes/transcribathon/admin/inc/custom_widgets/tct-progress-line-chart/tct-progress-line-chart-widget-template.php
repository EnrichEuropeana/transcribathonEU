<?php
global $wpdb;
$myid = uniqid(rand()).date('YmdHis');
$base = 0;

if(isset($instance['tct-progress-line-chart-headline']) && trim($instance['tct-progress-line-chart-headline']) != ""){ echo "<h3>".str_replace("\n","<br />",$instance['tct-progress-line-chart-headline'])."</h3>\n"; }
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

  
 
 $content .= '<canvas id="myChart'.str_replace(" ", $instance['tct-progress-line-chart-headline']).'"  width="400" height="400" display="inline"></canvas>';

  $content .= '</div>';

  $content .= "<script>
  
              var ctx = document.getElementById('myChart');
              var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        data: [20, 50, 100, 75, 25, 0],
                        label: 'Left dataset',
            
                        // This binds the dataset to the left y axis
                        yAxisID: 'left-y-axis'
                    }, {
                        data: [0.1, 0.5, 1.0, 2.0, 1.5, 0],
                        label: 'Right dataset',
            
                        // This binds the dataset to the right y axis
                        yAxisID: 'right-y-axis'
                    }],
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
                },
                options: {
                    scales: {
                        yAxes: [{
                            id: 'left-y-axis',
                            type: 'linear',
                            position: 'left'
                        }, {
                            id: 'right-y-axis',
                            type: 'linear',
                            position: 'right'
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


