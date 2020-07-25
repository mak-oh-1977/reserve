
//////////////////////////////////////////////////////////////////////////
//
//
//
function DbAccess(tgt, param, ok_cb, ng_cb) {
  //	ShowMsg(msg,"サーバー処理中です。。。", true);

  $.post(
    tgt,
    JSON.stringify(param),
    function (ret) {
      var ar = eval(ret);
      if (ret["res"] == "OK") {
        //				ShowMsg(msg, "完了しました");
        if (typeof (ok_cb) == 'function')
          ok_cb(ret);
      }
      else {
        //				ShowMsg(msg,"");
        if (typeof (ng_cb) == 'function') {
          ng_cb(ret);
          return;
        }

        alertDlg("エラー", ret["msg"] + "<br>" + ret['info']);
      }
    },
    "json"
  );

}

//////////////////////////////////////////////////////////////////////////
//
//
//
function Api(mod, cmd, param, ok_cb, ng_cb) {
  //	ShowMsg(msg,"サーバー処理中です。。。", true);
  var p = {};
  p['MOD'] = mod;
  p['CMD'] = cmd;
  p['param'] = param;

  $.ajax({
    url: "/api.php",
    type: 'post',
    dataType: 'json',
    data: JSON.stringify(p),
    success: function (ret) {

      var ar = eval(ret);
      if (ret["res"] == "TO") {
        window.location.href = "/timeout.php";
      }
      else if (ret["res"] == "OK") {
        //				ShowMsg(msg, "完了しました");
        if (typeof (ok_cb) == 'function')
          ok_cb(ret);
      }
      else {
        //				ShowMsg(msg,"");
        if (typeof (ng_cb) == 'function') {
          ng_cb(ret);
          return;
        }

        alertDlg("エラー", ret["msg"] + "\r\n" + ret['info']);
      }
    },

  }
  );

}

function ApiLongTime(mod, cmd, param, ok_cb, ng_cb) {
  //	ShowMsg(msg,"サーバー処理中です。。。", true);
  var p = {};
  p['MOD'] = mod;
  p['CMD'] = cmd;
  p['param'] = param;

  dispLoading("処理中・・・");


  $.post(
    "/api.php",
    JSON.stringify(p),
    function (ret) {
      removeLoading();

      var ar = eval(ret);
      if (ret["res"] == "TO") {
        window.location.href = "/timeout.php";
      }
      else if (ret["res"] == "OK") {
        //				ShowMsg(msg, "完了しました");
        if (typeof (ok_cb) == 'function')
          ok_cb(ret);
      }
      else {
        //				ShowMsg(msg,"");
        if (typeof (ng_cb) == 'function') {
          ng_cb(ret);
          return;
        }

        alertDlg("エラー", ret["msg"] + "<br>" + ret['info']);
      }
    },
    "json"
  );

}


//////////////////////////////////////////////////////////////////////////
//
//
//
function ApiFileUp(mod, cmd, fileElement, ok_cb, ng_cb) {
  //	ShowMsg(msg,"サーバー処理中です。。。", true);
  var p = {};
  p['MOD'] = mod;
  p['CMD'] = cmd;

  dispLoading("処理中・・・");

  $.ajaxFileUpload({
    url: "/api.php",
    secureuri: false,
    fileElementIds: fileElement,
    dataType: 'json',
    data: p,
    success:
      function (ret) {
        removeLoading();

        var ar = eval(ret);
        if (ret["res"] == "TO") {
          window.location.href = "/timeout.php";
        }
        else if (ret["res"] == "OK") {
          //				ShowMsg(msg, "完了しました");
          if (typeof (ok_cb) == 'function')
            ok_cb(ret);
        }
        else {
          //				ShowMsg(msg,"");
          if (typeof (ng_cb) == 'function') {
            ng_cb(ret);
            return;
          }

          alertDlg("エラー", ret["msg"] + "<br>" + ret['info']);
        }
      },
    error: function (ret) {
      removeLoading();

      if (typeof (ng_cb) == 'function') {
        ng_cb(ret);
        return;
      }
    }
  });

}

//////////////////////////////////////////////////////////////////////////
//
//
//
function confirmDlg(title_txt, msg, ok_cb, cancel_cb) {
  var dlg = $('<div/>', { title: title_txt })
    .append($('<p/>').html(msg))
    .append($('<div/>', { id: 'msg' })
    );
  $(dlg).appendTo(document.body);

  var btn = $(this);
  $(dlg).dialog({
    autoOpen: true,
    modal: true,
    width: 400,
    minHeight: 150,
    maxHeight: 500,
    buttons: {
      "OK": function () {
        var dlg = $(this);

        $(dlg).dialog("close");
        $(dlg).remove();

        ok_cb();
      },
      "キャンセル": function () {
        $(this).dialog("close");
        $(this).remove();

        if (typeof (cancel_cb) == 'function') {
          cancel_cb();
        }
      }
    }
  });
}

//////////////////////////////////////////////////////////////////////////
//
//
//
function alertDlg(title_txt, msg, ok_cb) {
  var dlg = $('<div/>', { title: title_txt })
    .append($('<pre/>').text(msg))
    .append($('<div/>', { id: 'msg' })
    );
  $(dlg).appendTo(document.body);

  var btn = $(this);
  $(dlg).dialog({
    autoOpen: true,
    modal: true,
    width: 500,
    minHeight: 150,
    maxHeight: 500,
    buttons: {
      "OK": function () {
        var dlg = $(this);

        $(dlg).dialog("close");
        $(dlg).remove();

        if (typeof (ok_cb) == 'function') {
          ok_cb();
        }
      },
    }
  });
}

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
//	取得した値をDOMへセット
//
function SetToDom(base, vals) {
  $.each(vals, function (index, value) {
    var $obj = $(base).find('[name=' + index + ']');
    if ($obj == null)
      return true;
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
          $($obj).val(value);
        }
        break;
      case 'SELECT':
        $($obj).val(value);
        break;
      case 'TEXTAREA':
        $($obj).val(value);
        break;
      case 'SPAN':
        $($obj).text(value);
        break;
      case 'VAR':
        $($obj).html(value);
        break;
      default:
        break;
    }
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
  return today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();
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