<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js'></script>
<?php
//echo '<pre>';
//print_r($chart_data);
// dd($chart_data);
?>
<script>
    var ctx = document.getElementById("batch_report_graph").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
        @foreach( $chart_data->students_data as $student)
        "{{$student->name}}",
        @endforeach
        ],
        datasets: [

        @foreach( $chart_data->questions_users as $question_id => $users)
        {
            label: 'Q.No-{{$loop->iteration}}',
            @foreach( $chart_data->students_data as $student)
            <?php
            $result = \App\QuizResult::where('quiz_id', $chart_data->exam->id)->where('user_id', $student->id)->latest('created_at')->first();
            if ( $result ) {
                $correct_answer_questions = (array)json_decode($result->correct_answer_questions);
                $wrong_answer_questions = (array)json_decode($result->wrong_answer_questions);
                $not_answered_questions = (array)json_decode($result->not_answered_questions);
                if( in_array($question_id, $student->correct_answer_questions)) {
                    $color = '#45c490'; // Correct
                } elseif ( in_array($question_id, $wrong_answer_questions)) {
                    $color = '#ff0000'; // Wrong
                } else {
                    $color = '#D3D3D3'; // Skipped
                }
            } else {
                $color = '#D3D3D3'; // Not attended
            }
            ?>
            @endforeach
            backgroundColor: "{{$color}}",
            data: [
                @foreach( $chart_data->students_data as $student) 1, @endforeach
            ],
        },

        @endforeach
        ],
    },
options: {
    tooltips: {
      displayColors: true,
      callbacks:{
        mode: 'x',
      },
    },
    scales: {
      xAxes: [{
        stacked: true,
        gridLines: {
          display: false,
        }
      }],
      yAxes: [{
        stacked: true,
        ticks: {
          beginAtZero: true,
        },
        type: 'linear',
      }]
    },
        responsive: true,
        maintainAspectRatio: false,
        legend: { position: 'bottom' },
    }
});
  </script>