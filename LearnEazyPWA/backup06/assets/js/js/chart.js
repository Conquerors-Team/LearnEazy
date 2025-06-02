let data = {
  datasets: [{
    backgroundColor: ["#e76d63", "#8dc5c0","#6ab945"],
    data: [100,40,20]
  }],
  
  // labels: [
  //   'total',
  //   'available',
  //   'required'
  // ]
}

/*
let options =
  title: {
    display: true,
    text: 'Disk Usage'
  }
}
*/

//var ctx = document.getElementById('chart').getContext('2d');

var myDoughnutChart = new Chart('chart', {
  type: 'doughnut',
  data: data,
  //options: options
})

