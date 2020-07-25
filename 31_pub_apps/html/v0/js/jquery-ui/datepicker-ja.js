jQuery(function($){
	$.datepicker.regional['ja'] = {
		closeText: '閉じる',
		prevText: '&#x3C;前',
		nextText: '次&#x3E;',
		currentText: '今日',
		monthNames: ['1月','2月','3月','4月','5月','6月',
		'7月','8月','9月','10月','11月','12月'],
		monthNamesShort: ['1月','2月','3月','4月','5月','6月',
		'7月','8月','9月','10月','11月','12月'],
		dayNames: ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
		dayNamesShort: ['日','月','火','水','木','金','土'],
		dayNamesMin: ['日','月','火','水','木','金','土'],
		weekHeader: '週',
		dateFormat: 'yy/mm/dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '年'};
	$.datepicker.setDefaults($.datepicker.regional['ja']);
});
function convert_wareki(y){
	var tmp;
	if (y > 2018) {      //令和
		tmp = y - 2018;
		tmp = 'R' + tmp;
		return tmp;
	}else if (y > 1988) {//平成
		tmp = y - 1988;
		tmp = 'H' + tmp;
		return tmp;
	}else if (y > 1925) {//昭和
		tmp = y - 1925;
		tmp = 'S' + tmp;
		return tmp;
	}else if (y > 1911) {//大正
		tmp = y - 1911;
		tmp = 'T' + tmp;
		return tmp;
	}else if (y > 1867) {//明治
		tmp = y - 1867;
		tmp = 'M' + tmp;
		return tmp;
	}else{               //該当なし
		return '';
	}
}

