//line chart
    var line = document.getElementById('line');
    line.height = 200
    var lineConfig = new Chart(line, {
     type: 'line',
    data: {
          labels: ['data-1', 'data-2', 'data-3',
                    'data-4', 'data-5', 'data-6'],
          datasets: [{
              label: '# of data', // Name the series
              data: [10, 15, 20, 10, 25, 5, 10], // Specify the data values array
              fill: false,
              borderColor: '#2196f3', // Add custom color border (Line)
              backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
              borderWidth: 1 // Specify bar border width
          }]},
         options: {
            responsive: true, // Instruct chart js to respond nicely.
            maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      }

  })

    //line chart
    var line1 = document.getElementById('line1');
    line1.height = 200
    var lineConfig = new Chart(line1, {
     type: 'line',
    data: {
          labels: ['data-1', 'data-2', 'data-3',
                    'data-4', 'data-5', 'data-6'],
          datasets: [{
              label: '# of data', // Name the series
              data: [10, 15, 20, 10, 25, 5, 10], // Specify the data values array
              fill: false,
              borderColor: '#2196f3', // Add custom color border (Line)
              backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
              borderWidth: 1 // Specify bar border width
          }]},
         options: {
            responsive: true, // Instruct chart js to respond nicely.
            maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      }

  })
