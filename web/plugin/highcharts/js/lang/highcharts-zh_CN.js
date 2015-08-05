/**
 * Highcharts-zh_CN plugins v0.0.2 (2015-04-19)
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

        lang: {
            contextButtonTitle: '图表导出菜单',
            decimalPoint: '.',
            downloadJPEG: "下载JPEG图片",
            downloadPDF: "下载PDF文件",
            downloadPNG: "下载PNG文件",
            downloadSVG: "下载SVG文件",
            downloadCSV: '下载CSV文件',
            downloadXLS: '下载XLS文件',
            drillUpText: "返回 {series.name}",
            loading: '加载中...',
            months: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            noData: "没有数据",
            numericSymbols: ['k', 'M', 'G', 'T', 'P', 'E'],
            printChart: "打印图表",
            resetZoom: '重置缩放比例',
            resetZoomTitle: '重置为原始大小',
            shortMonths: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            thousandsSep: ',',
            weekdays: ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'],
        },

        global: {
            useUTC: false,
            timezoneOffset: 8 * 60, // +8
            canvasToolsURL: '/plugin/highcharts/modules/canvas-tools.js',
            VMLRadialGradientURL: '/plugin/highcharts/gfx/vml-radial-gradient.png'
        },

        title: {
            text: '图表'
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
            filename: '图表', //use chart title
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