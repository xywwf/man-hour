/**
 * Highcharts-en plugins v0.0.2 (2015-04-19)
 *
 * (c) 2015 HCharts.cn http://www.hcharts.cn
 *
 * Author : John Doe, Blue monkey
 *
 * License: Creative Commons Attribution (CC)
 */

(function(H) {

    ABSOLUTE = H.ABSOLUTE;
    RELATIVE = H.RELATIVE;
    hasSVG = H.hasSVG;
    isTouchDevice = H.isTouchDevice;
    var defaultOptionsZhCn = {
        global: {
            useUTC: false,
            timezoneOffset: 8 * 60, // +8
            canvasToolsURL: '/plugin/highcharts/modules/canvas-tools.js',
            VMLRadialGradientURL: '/plugin/highcharts/gfx/vml-radial-gradient.png'
        },

        tooltip: {
            dateTimeLabelFormats: {
                millisecond: '%A, %b %e, %H:%M:%S.%L',
                second: '%A, %b %e, %H:%M:%S',
                minute: '%A, %b %e, %H:%M',
                hour: '%b %e, %H:%M',
                day: '%Y-%m-%d',
                week: 'Week from %A, %b %e, %Y',
                month: '%m-%Y',
                year: '%Y'
            },
            headerFormat: '<span style="font-size: 10px">{point.key}</span><br/>',
            pointFormat: '{series.name}: <b>{point.y}</b><br/>',

        },
    	colors: [   
         	     '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                 '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                 '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                 '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089' 
             ],
        xAxis: {
            dateTimeLabelFormats: {
                millisecond: '%H:%M:%S.%L',
                second: '%H:%M:%S',
                minute: '%H:%M',
                hour: '%H:%M',
                day: '%Y-%m-%d',
                week: '%e. %b',
                month: '%m-%Y',
                year: '%Y'
            }
        },
		credits: {
			enabled: false
		},
        exporting: {
            filename: 'chart', //use chart title
            url: '/plugin/highcharts/export/download.php',
            buttons: {
                contextButton: {
         			menuItems: [{
        				textKey: 'printChart',
        				onclick: function () {this.print();}
        			}, {
        				separator: true
        			}, {
        				textKey: 'downloadJPEG',
        				onclick: function () {this.exportChart({type: 'image/jpeg'});}
        			}, {
        				textKey: 'downloadPDF',
        				onclick: function () {this.exportChart({type: 'application/pdf'});}
        			}, {
        				separator: true
        			}, {
                        textKey: 'downloadCSV',
                        onclick: function () { this.downloadCSV(); }
                    }, {
                        textKey: 'downloadXLS',
                        onclick: function () { this.downloadXLS(); }
                    }]
                }
            }
        },        
        legend: {
            align: 'left',
            verticalAlign: 'top',
            maxHeight: 60,
            floating: true,
            x: 60,
            y: 40
        },       
    };

    H.setOptions(defaultOptionsZhCn);
}(Highcharts));