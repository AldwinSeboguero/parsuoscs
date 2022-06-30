<template>
<div class="ma-0 pb-0">
  <apexchart width="300" type="donut" :options="chartOptions" :series="series"></apexchart>
</div>
</template>

<script>
import { defineComponent } from '@vue/composition-api'


export default defineComponent({
    props:{
        datas: Array,
        pendingR: Array ,
    },
    data: function() {
      console.log(this.pendingR)
    return {
   series: this.datas,
          chartOptions: {

              stroke: {
        // show: false,
        curve: 'smooth',
        lineCap: 'butt',
        width:8
    },

states: {
  hover: {
   filter: {
      type: 'none'
    }
  }
},
    colors: [ '#2E7D32','rgba(255,180,0,1)'],
    legend: {
      fontSize: '14px',
      fontFamily: 'Helvetica, Arial',
      fontWeight: 400,
      itemMargin: {
        // vertical: 10
      },
      formatter: function(seriesName, opts) {
        return '<div class="legend-info">'  + '<span>1' + opts.w.globals.series[opts.seriesIndex] + '</span>' + '<span>' + seriesName + '</span>'+ '</div>'
      }
    },
    tooltip: {
          enabled: true,
          
          style: {
            fontSize: '20px',
            fontFamily: undefined
          },
          y: {
                  formatter: function(value) {
                    return Number(value).toLocaleString();
                  }
                }
       },
    dataLabels: {
      enabled: false,
    },
    labels: ['Approved','Pending'],
legend: {
    show: false
  },
     plotOptions: {
          pie: {
            
            donut: {
        
              labels: {
               
                show: true,
                name: {
                  show: true,
                  fontSize: '32px',
                  fontFamily: 'Roboto,sans-serif',
                  color: '#dfsda',
                  offsetY: 24
                  
                },
                total: {
                  show: true,
                  fontFamily: 'Roboto,sans-serif',
                  label: 'Total',
                  color: '#424242',
                  fontSize: '18px',
                  fontWeight:50,
                  offsetY: 16,
                  formatter: function (w) {
                    return Number(w.globals.seriesTotals.reduce((a, b) => {
                      return a + b
                    }, 0)).toLocaleString() 
                  }
                },
                value: {
                  show: true,
                  fontSize: '32px',
                  fontWeight: 600,
                  fontFamily: 'Helvetica, Arial, sans-serif',
                  color: "#424242",
                  fontFamily: 'Roboto,sans-serif',
                  offsetY: -20,
                  formatter: function (val) {
                    return Number(val).toLocaleString() 
                  }
                },
                
              }
            }
          }
        },
          
  }
    }
  },
    setup() {
        
    },
})
</script>
