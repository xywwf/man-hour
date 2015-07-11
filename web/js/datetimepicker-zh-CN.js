/* Chinese initialisation for the jQuery UI date picker plugin. */
/* Written by Cloudream (cloudream@gmail.com). */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([ "../datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}(function( datepicker ) {

datepicker.regional['zh-CN'] = {
	closeText: '关闭',
	prevText: '&#x3C;上月',
	nextText: '下月&#x3E;',
	currentText: '现在',
	monthNames: ['一月','二月','三月','四月','五月','六月',
	'七月','八月','九月','十月','十一月','十二月'],
	monthNamesShort: ['一月','二月','三月','四月','五月','六月',
	'七月','八月','九月','十月','十一月','十二月'],
	dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
	dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
	dayNamesMin: ['日','一','二','三','四','五','六'],
	weekHeader: '周',
	dateFormat: 'yy-mm-dd',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: true,
	yearSuffix: '年',
	closeText: '完成',
	amNames: ['上午', 'AM'],
	pmNames: ['下午', 'PM'],
	timeFormat: 'HH:mm',
	timeSuffix: '',
	timeOnlyTitle: '选择时间',
	timeText: '时间',
	hourText: '时',
	minuteText: '分',
	secondText: '秒',
	millisecText: '毫秒',
	microsecText: '微秒',
	timezoneText: '时区',	
};
datepicker.setDefaults(datepicker.regional['zh-CN']);

return datepicker.regional['zh-CN'];

}));
