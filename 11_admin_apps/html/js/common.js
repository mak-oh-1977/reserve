
//////////////////////////////////////////////////////////////////////////
//
//
//
function Api(mod_cmd, param, ok_cb, ng_cb) {
  //	ShowMsg(msg,"サーバー処理中です。。。", true);
  var p = {};
  p['MOD'] = mod_cmd.split('/')[0];
  p['CMD'] = mod_cmd.split('/')[1];
  p['JOB'] = false;
  p['LOG'] = true;
  p['param'] = param;

  $.ajax({
    url: "/api/" + mod_cmd + "/",
    type: 'post',
    dataType: 'json',
    data: JSON.stringify(p),
    success: function (ret) {

      var ar = eval(ret);
      if (ret["res"] == "TO") {
        window.location.href = "/timeout.php";
      }
      else if (ret["res"] == "OK") {
        if (typeof (ok_cb) == 'function')
          ok_cb(ret);
      }
      else if (ret["res"] == "WARN") {
        confirmDlg("確認", ret["msg"] + "\r\n続行しますか？", ok_cb);
      }
      else if (ret["res"] == "DBERR") {
        dbErrDlg(ret["msg"]);
      }
      else {
        if (typeof (ng_cb) == 'function') {
          ng_cb(ret);
          return;
        }

        var msg = ret["msg"];
        if (ret['info'] != null)
          msg += "\r\n" + ret['info'];
        alertDlg("エラー", msg);
      }
    },

  }
  );

}



//////////////////////////////////////////////////////////////////////////
//
//
//
function dbErrDlg(msg) {

  var dlg = $('<div/>', { title: "エラー" })
    .append($('<pre/>').text(msg).css('font-size', '0.6em'))
    .append($('<div/>', { id: 'msg' })
    );
  $(dlg).appendTo(document.body);

  var btn = $(this);
  $(dlg).dialog({
    autoOpen: true,
    modal: true,
    width: 1200,
    minHeight: 150,
    maxHeight: 800,
    buttons: {
      "OK": function () {
        var dlg = $(this);

        $(dlg).dialog("close");
        $(dlg).remove();

      },
    }
  });
}

//////////////////////////////////////////////////////////////////////////
//
// 確認ダイアログ
//
var alert_ok_cb = null;

function alertDlg(title_txt, msg, ok_cb) {
  $('#alert_dlg #alert_dlg_title').text(title_txt);
  $('#alert_dlg #alert_dlg_msg').text(msg);

  $('#alert_dlg').modal('show');

  alert_ok_cb = ok_cb;
}

$('#alert_dlg #alert_dlg_ok').click(function () {
  $('#alert_dlg').modal('hide');

  if (typeof (alert_ok_cb) == 'function') {
    alert_ok_cb();
  }

  alert_ok_cb = null;
})

var confirm_ok_cb = null;
var confirm_cancel_cb = null;

function confirmDlg(title_txt, msg, ok_cb, cancel_cb) {
  $('#confirm_dlg #confirm_dlg_title').text(title_txt);
  $('#confirm_dlg #confirm_dlg_msg').html(msg);

  $('#confirm_dlg').modal('show');

  confirm_ok_cb = ok_cb;
  confirm_cancel_cb = cancel_cb;
}


$('#confirm_dlg #confirm_dlg_ok').click(function () {
  $('#confirm_dlg').modal('hide');

  if (typeof (confirm_ok_cb) == 'function') {
    confirm_ok_cb();
  }

  confirm_ok_cb = null;
})

$('#confirm_dlg #confirm_dlg_cancel').click(function () {
  $('#confirm_dlg').modal('hide');

  if (typeof (confirm_cancel_cb) == 'function') {
    confirm_cancel_cb();
  }

  confirm_cancel_cb = null;
})


/* ------------------------------
 Loading イメージ表示関数
 引数： msg 画面に表示する文言
 ------------------------------ */
function dispLoading(msg) {
  // 引数なし（メッセージなし）を許容
  if (msg == undefined) {
    msg = "";
  }
  // 画面表示メッセージ
  var dispMsg = "<div class='loadingMsg'>" + msg + "</div>";
  // ローディング画像が表示されていない場合のみ出力
  if ($("#loading").length == 0) {
    $("body").append("<div id='loading'>" + dispMsg + "</div>");

    $('#loading').css({
      'z-index': '1000',
      'display': 'table',
      'width': '100%',
      'height': '100%',
      'position': 'fixed',
      'top': '0',
      'left': '0',
      'background-color': '#fff',
      'opacity': '0.8',
    });

    $('#loading .loadingMsg').css({
      'display': 'table-cell',
      'text-align': 'center',
      'vertical-align': 'middle',
      'padding-top': '140px',
      'background': 'url("/images/running.gif") center center no-repeat',
    });


  }
}

/* ------------------------------
 Loading イメージ削除関数
 ------------------------------ */
function removeLoading() {
  $("#loading").remove();
}


//////////////////////////////////////////////////////////////////////////
//
//	Getパラメータの値を取得
//
function getParm(key) {
  var arg = new Object;
  url = location.search.substring(1).split('&');

  for (i = 0; url[i]; i++) {
    var k = url[i].split('=');
    if (k[0] == key)
      return k[1];
  }

  return '';
}

//////////////////////////////////////////////////////////////////////////
//
//	Getパラメータの値を取得
//
function isExistParm(key) {
  var arg = new Object;
  url = location.search.substring(1).split('&');

  for (i = 0; url[i]; i++) {
    var k = url[i].split('=');
    if (k[0] == key)
      return true;
  }

  return false;
}

//////////////////////////////////////////////////////////////////////////
//
//	取得した値をDOMへセット
//
function SetToDom(base, vals, overwrite) {
  if (typeof overwrite === 'undefined') overwrite = true;

  $.each(vals, function (index, value) {
    var $objs = $(base).find('[name=' + index + ']');
    if ($objs == null)
      return true;
    $.each($objs, function (ix, $obj) {
      console.log($($obj).prop("tagName"));
      switch ($($obj).prop("tagName")) {
        case 'INPUT':
          if ($($obj).attr('type') == 'checkbox') {
            if (value == 0 || value == false)
              $($obj).prop('checked', false);
            else
              $($obj).prop('checked', true);

          }
          else if ($($obj).attr('type') == 'radio') {
            $($obj).val([value]);
          }
          else {
            if (overwrite == true) {
              $($obj).val(value);
            }
            else {
              //上書きしない
              if ($($obj).val() == '')
                $($obj).val(value);
            }
          }
          break;
        case 'SELECT':
          if (value != null)
            $($obj).val(value);
          break;
        case 'TEXTAREA':
          if (overwrite == true) {
            $($obj).val(value);
          }
          else {
            //上書きしない
            if ($($obj).val() == '')
              $($obj).val(value);
          }
          break;
        case 'SPAN':
          $($obj).text(value);
          break;
        case 'VAR':
          $($obj).html(value);
          break;
        case 'PRE':
          $($obj).html(value);
          break;
        case 'A':
          if (value == '') {
            $($obj).attr('href', '');
          }
          else {
            //全部直すの面倒なので応急処理
            var link = $($obj).attr('href_bak');
            if (link != undefined) {
              $($obj).attr('href', link + value);
            }
            else {
              link = $($obj).attr('href');
              $($obj).attr('href', link + value);
              $($obj).attr('href_bak', link);
            }
          }

          break;
        case 'IMG':

          if (value == 0) {
            $($obj).remove();
          }
          else if (value != 1) {
            var link = $($obj).attr('src');
            $($obj).attr('src', link + value + ".png");
          }
          break;

        case 'TD':
          $($obj).addClass(value);
          break;

        default:
          break;
      }
    });
  });

}

//////////////////////////////////////////////////////////////////////////
//
//	Domの値をクリア
//
function ClearDom(base) {
  $obj = $(base).find(':input, var');
  if ($obj == null)
    return true;
  $.each($($obj), function () {

    console.log($(this).prop("tagName"));
    console.log($(this).attr('type'));
    console.log($(this).attr('name'));

    switch ($(this).prop("tagName")) {
      case 'INPUT':
        if ($(this).attr('type') == 'checkbox')
          $(this).prop('checked', false);
        else if ($(this).attr('type') == 'radio')
          $(this).prop('checked', false);

        else
          $(this).val(null);
        break;
      case 'SELECT':
        $(this).val(null);
        break;
      case 'TEXTAREA':
        $(this).val(null);
        break;
      case 'VAR':
        $(this).text("");
        break;
      case 'SPAN':
        $(this).text("");
        break;
      default:
        break;
    }


  })
}

//////////////////////////////////////////////////////////////////////////
//
//	DOMからName属性を持った値を取得し連想配列に格納
//
function GetFromDom(base) {
  var param = {};

  if (typeof base === 'string' || base instanceof String) {
    $obj = $(base + ' [name]');
  }
  else {
    $obj = $(base).find('[name]');
  }
  $.each($obj, function (index, $value) {

    if ($($value).prop("tagName") == 'VAR' || $($value).prop("tagName") == 'SPAN') {
      param[$($value).attr('name')] = $($value).text();
    }
    else {
      if ($(this).attr('type') == 'checkbox')
        param[$($value).attr('name')] = $($value).prop('checked');
      else if ($(this).attr('type') == 'radio') {
        if ($($value).prop('checked') == true)
          param[$($value).attr('name')] = $($value).val();
      }
      else if ($($value).prop("tagName") == 'A') {
      }
      else {
        param[$($value).attr('name')] = $($value).val();
      }
    }

  });

  return param;
}

//////////////////////////////////////////////////////////////////////////
//
//	DOMからName属性を持った値を取得し連想配列に格納
//
function GetToday() {
  var today = new Date();
  return today.getFullYear() + "-" + ("0" + (today.getMonth() + 1)).slice(-2) + "-" + ("0" + today.getDate()).slice(-2);
}


//////////////////////////////////////////////////////////////////////////
//
//  全角カナを半角カナに変換
//
function ZenkakuToHankaku(mae) {
  let zen = new Array(
    'ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク', 'ケ', 'コ'
    , 'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', 'チ', 'ツ', 'テ', 'ト'
    , 'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ'
    , 'マ', 'ミ', 'ム', 'メ', 'モ', 'ヤ', 'ヰ', 'ユ', 'ヱ', 'ヨ'
    , 'ラ', 'リ', 'ル', 'レ', 'ロ', 'ワ', 'ヲ', 'ン'
    , 'ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ', 'ゼ', 'ゾ'
    , 'ダ', 'ヂ', 'ヅ', 'デ', 'ド', 'バ', 'ビ', 'ブ', 'ベ', 'ボ'
    , 'パ', 'ピ', 'プ', 'ペ', 'ポ'
    , 'ァ', 'ィ', 'ゥ', 'ェ', 'ォ', 'ャ', 'ュ', 'ョ', 'ッ'
    , '゛', '°', '、', '。', '「', '」', 'ー', '・'
  );

  let han = new Array(
    'ｱ', 'ｲ', 'ｳ', 'ｴ', 'ｵ', 'ｶ', 'ｷ', 'ｸ', 'ｹ', 'ｺ'
    , 'ｻ', 'ｼ', 'ｽ', 'ｾ', 'ｿ', 'ﾀ', 'ﾁ', 'ﾂ', 'ﾃ', 'ﾄ'
    , 'ﾅ', 'ﾆ', 'ﾇ', 'ﾈ', 'ﾉ', 'ﾊ', 'ﾋ', 'ﾌ', 'ﾍ', 'ﾎ'
    , 'ﾏ', 'ﾐ', 'ﾑ', 'ﾒ', 'ﾓ', 'ﾔ', 'ｲ', 'ﾕ', 'ｴ', 'ﾖ'
    , 'ﾗ', 'ﾘ', 'ﾙ', 'ﾚ', 'ﾛ', 'ﾜ', 'ｦ', 'ﾝ'
    , 'ｶﾞ', 'ｷﾞ', 'ｸﾞ', 'ｹﾞ', 'ｺﾞ', 'ｻﾞ', 'ｼﾞ', 'ｽﾞ', 'ｾﾞ', 'ｿﾞ'
    , 'ﾀﾞ', 'ﾁﾞ', 'ﾂﾞ', 'ﾃﾞ', 'ﾄﾞ', 'ﾊﾞ', 'ﾋﾞ', 'ﾌﾞ', 'ﾍﾞ', 'ﾎﾞ'
    , 'ﾊﾟ', 'ﾋﾟ', 'ﾌﾟ', 'ﾍﾟ', 'ﾎﾟ'
    , 'ｧ', 'ｨ', 'ｩ', 'ｪ', 'ｫ', 'ｬ', 'ｭ', 'ｮ', 'ｯ'
    , 'ﾞ', 'ﾟ', '､', '｡', '｢', '｣', 'ｰ', '･'
  );

  let ato = "";

  for (let i = 0; i < mae.length; i++) {
    let maechar = mae.charAt(i);
    let zenindex = zen.indexOf(maechar);
    if (zenindex >= 0) {
      maechar = han[zenindex];
    }
    ato += maechar;
  }

  return ato;
}


function SetSelect(item, values) {
  $(item).empty();
  for (var i = 0; i < values.length; i++) {
    var r = values[i];
    $o = $('<option>').text(r['name']).val(r['id']);
    $(item).append($o);
  }

}


$(document).ready(function () {

  var v = JSON.parse(localStorage.getItem('customize-v'));
  if (v == null) {
    v = {};
    v['text-sm'] = '';
    v['body'] = '';
    v['main_header'] = 'navbar-light navbar-white';
    v['sidebar'] = 'sidebar-dark-primary';
    localStorage.setItem('customize-v', JSON.stringify(v));

  }
  $('body').addClass(v['text-sm']);
  $('body').addClass(v['body']);
  $('.main-header').addClass(v['main_header']);
  $('.main-sidebar').addClass(v['sidebar']);
  $('.brand-link').addClass(v['logo']);

  if (localStorage.getItem('is_open') == "0")
    $('body').addClass('sidebar-collapse');


  var item = localStorage.getItem('menu-expand');
  var m = JSON.parse(item);
  for (let i in m) {
    console.log(m[i]);
    $('div.sidebar li.menu-item').eq(m[i]).addClass('menu-open');
  }
});

$(window).on('beforeunload', function () {

  if (localStorage.getItem('setting') == "true") {

    localStorage.setItem('setting', false);
    Api('912_user', 'user_save', { customize: localStorage.getItem('customize-v') },
      function (ret) {
      }
    );
  }


});

$("a.nav-link[data-widget=pushmenu]").click(function () {
  localStorage.setItem('is_open', $('body').hasClass('sidebar-collapse') ? "1" : "0");
});

$('div.sidebar li ul a.nav-link').click(function () {
  var a = [];
  $.map($('div.sidebar li.menu-open'), function (e, i) {
    a.push($(e).index());
    console.log($(e).index());
  });
  localStorage.setItem('menu-expand', JSON.stringify(a));
})