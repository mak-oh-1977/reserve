<?php
/*  * Apiクラス(abstract)
 * Aoi は一切の出力を行わない
 *
 * @package Api
 */
mb_language("uni");
mb_internal_encoding("utf-8");
mb_http_input("auto");
mb_http_output("utf-8");

//タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');


const CAPT_DIR = __DIR__ . "/../html/capt/";

include_once(__DIR__ . "/mail.php");
include_once(__DIR__ . "/log.php");
include_once(__DIR__ . "/exception.php");



//////////////////////////////////////////////////////////////////////////
//
//
//
class api
{
  private $DB;
  private $fetch_type;

  protected $user_id;
  protected $userDiv;
  protected $groupID;
  protected $OpCompanyId;
  protected $sess;

  public function __construct($uid, $udiv, $gid, $ocid, $sess)
  {
    $this->open();

    $this->user_id = $uid;
    $this->userDiv = $udiv;
    $this->groupID = $gid;
    $this->OpCompanyId = $ocid;
    $this->sess = $sess;
  }




  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  function open()
  {
    $dsn = sprintf("mysql:dbname=%s;host=%s;charset=utf8",
        getenv('DB_NAME'), getenv('DB_HOST'));
    $user = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $option = array(
      // local ファイルからload可能にする
      PDO::MYSQL_ATTR_LOCAL_INFILE => true,
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );
    try {
      $this->DB = new PDO($dsn, $user, $password, $option);
    } catch (PDOException $e) {
      throw new DbException('Connection failed:' . $e->getMessage());
    }

    $this->fetch_type = PDO::FETCH_ASSOC;

    return $this->DB;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbTrans()
  {
    if ($this->DB->beginTransaction() == FALSE)
      throw new DbException(print_r($this->DB->errorInfo(), TRUE));

    return ['res' => 'OK'];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbSelect($sql, $data = null)
  {
    Log::db($sql . "\n**********************data********************\n" .
      print_r($data, TRUE) . "\n**********************************************\n");

    $stmt = $this->DB->prepare($sql);
    if (!$stmt) {

      throw new DbException(print_r($this->DB->errorInfo(), TRUE));
    }

    if ($stmt->execute($data) == false) {
      throw new DbException(print_r($stmt->errorInfo(), TRUE));
    }
    $rows = array();
    while ($row = $stmt->fetch($this->fetch_type)) {
      array_push($rows, $row);
    }

    return ["res" => "OK", "rows" => $rows];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbOne($sql, $data = null)
  {
    Log::db($sql . "\n**********************data********************\n" .
      print_r($data, TRUE) . "\n**********************************************\n");

    $stmt = $this->DB->prepare($sql);
    if (!$stmt) {

      throw new DbException(print_r($this->DB->errorInfo(), TRUE));
    }

    if ($stmt->execute($data) == false) {

      throw new DbException(print_r($stmt->errorInfo(), TRUE));
    }

    $rows = array();
    $row = $stmt->fetch(PDO::FETCH_NUM);

    return ["res" => "OK", "value" => $row[0]];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbOneRow($sql, $data = null)
  {
    Log::db($sql . "\n**********************data********************\n" .
      print_r($data, TRUE) . "\n**********************************************\n");

    $stmt = $this->DB->prepare($sql);
    if (!$stmt) {

      throw new DbException(print_r($this->DB->errorInfo(), TRUE));
    }

    if ($stmt->execute($data) == false) {

      throw new DbException(print_r($stmt->errorInfo(), TRUE));
    }

    $rows = array();
    $row = $stmt->fetch(PDO::FETCH_NUM);

    return ["res" => "OK", "value" => $row[0]];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbExec($sql, $data = null)
  {
    Log::db($sql . "\n**********************data********************\n" .
      print_r($data, TRUE) . "\n**********************************************\n");

    //		Log::debug(print_r($data, TRUE));

    $stmt = $this->DB->prepare($sql);
    if (!$stmt) {

      throw new DbException(print_r($this->DB->errorInfo(), TRUE));
    }

    if ($stmt->execute($data) == false) {

      throw new DbException(print_r($stmt->errorInfo(), TRUE));
    }

    return ["res" => "OK", "id" => $this->DB->lastInsertId(), "row_num" => $stmt->rowCount()];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function dbCommit()
  {

    if ($this->DB->commit() == FALSE) {
      throw new DbException(print_r($this->DB->errorInfo(), TRUE));
    }

    return ['res' => 'OK'];
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  protected function wklog($sampleid, $wkid, $info = "")
  {
    $sql = "insert into 091t_wk_log (sampleid, wkdatetime, userid, StatusId, message) values(?, now(), ?, ?, ?)";

    if (isset($_SESSION))
      $user = $_SESSION['userID'];
    else
      $user = 'system';


    return $this->dbExec($sql, array($sampleid, $user, $wkid, $info));
  }

  //////////////////////////////////////////////////////////////////////////
  //
  //
  //
  const OP_TYPE_SEL = 0;
  const OP_TYPE_INS = 1;
  const OP_TYPE_UPD = 2;
  const OP_TYPE_DEL = 3;

  protected function OpeLog($type, $desc)
  {
    $sql = "insert into 090t_ope_log (OpeDate, OpeUser, Type, detail) values(now(), ?, ?, ?)";

    if (isset($_SESSION))
      $user = $this->user_id;
    else
      $user = 'system';


    return $this->dbExec($sql, array($user, $type, $desc));
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //  指定IDの機能が使用可能か
  //
  protected function CheckFunction($id)
  {
    if (in_array($id, $_SESSION['function']) == false) {
      throw new AppException("権限がありません。システム管理者にご確認ください。");
    }
    return true;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  // APIに対してJSONをPOST送信し返り値を取得する
  //
  // @param string $url
  // @param array $postParams
  // @return array
  //
  public function callApi($mod, $cmd, $parm)
  {
    log::func("{$mod},{$cmd}:" . print_r($parm, TRUE));

    $p = ['MOD' => $mod, 'CMD' => $cmd, "JOB" => false, 'LOG' => true, 'param' => $parm];

    return $this->sendApi('http://localhost/api.php', $p);
  }

  //////////////////////////////////////////////////////////////////////////
  //
  // APIに対してJSONをPOST送信し返り値を取得する
  //
  // @param string $url
  // @param array $postParams
  // @return array
  //
  protected function sendApi($url, $parm)
  {
    log::func($url . ":" . print_r($parm, TRUE));

    $ch = curl_init($url);

    $postOption = [
      CURLOPT_POST           => true,
      CURLOPT_TIMEOUT        => 60 * 30,
      CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
      CURLOPT_POSTFIELDS     => json_encode($parm, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), //JSON へエンコード処理（'/'をエスケープしない＆日本語をパースしない）
      CURLOPT_RETURNTRANSFER => true,
    ];

    curl_setopt_array($ch, $postOption);

    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch);
    $header  = curl_getinfo($ch);

    curl_close($ch); // リソース開放

    /* errorチェック */
    if ($err !== 0) {
      return ['res' => 'NG', 'msg' => "{$err}:{$errmsg}"];
    }

    if ($header['http_code'] !== 200) {
      if ($errmsg !== '') {
        return ['error' => $errmsg];
      }
      return ['res' => 'NG', 'msg' => "{$err}({$header['http_code']}):{$errmsg}"];
    }

    $res = json_decode($content, true);

    log::debug(print_r($res, TRUE));

    return $res;
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //	ファイル確認
  //
  protected function filecheck($file, $dst, $ext)
  {
    log::func(print_r($file, TRUE) . "->" . $dst);

    if (!empty($file['error'])) {
      switch ($file['error']) {

        case '1':
          $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
          break;
        case '2':
          $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
          break;
        case '3':
          $error = 'The uploaded file was only partially uploaded';
          break;
        case '4':
          $error = 'No file was uploaded.';
          break;

        case '6':
          $error = 'Missing a temporary folder';
          break;
        case '7':
          $error = 'Failed to write file to disk';
          break;
        case '8':
          $error = 'File upload stopped by extension';
          break;
        case '999':
        default:
          $error = 'No error code avaiable';
      }
      $res['msg'] =  $error;
      return $res;
    } elseif (empty($file['tmp_name']) || $file['tmp_name'] == 'none') {
      $res['msg'] =  'No file was uploaded..';
      return $res;
    }

    $tmpname = $file['tmp_name'];
    //拡張子を判定
    $fpath = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fpath != $ext) {
      $res['msg'] = $ext . 'ファイルのみ対応しています。';
      return $res;
    }

    unlink($dst);

    if (!@rename($tmpname, $dst)) {
      $res['res'] = 'NG';
      $res['msg'] = "ファイル移動エラー($tmpname->$dst)";
      return $res;
    }

    $res['res'] = 'OK';
    $res['filename'] = $tmpname;

    return $res;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  // CSVファイルを配列に格納する
  //
  protected function parseCsv($fileName, $col0Skip = TRUE)
  {
    log::func($fileName);

    // ファイル取得
    $file = new SplFileObject($fileName);
    $file->setFlags(SplFileObject::READ_CSV);

    // 全行のINSERTデータ格納用
    $ins_values = array();

    $lineCount = 0;
    // ファイル内のデータループ
    foreach ($file as $key => $line) {
      $lineCount++;

      //var_dump($line);
      if ($line[0] == null && $col0Skip == TRUE) continue;

      // 1行毎のINSERTデータ格納用
      $values = array();

      foreach ($line as $line_key => $str) {

        // INSERT用のデータ作成
        $values[] = mb_convert_encoding(str_replace("'", "", $str), "utf-8", "sjis-win");
      }

      if (!empty($values))
        $ins_values[] = $values;
    }
    log::info(print_r($ins_values, TRUE));

    return $ins_values;
  }

  //////////////////////////////////////////////////////////////////////////
  //
  // Excelファイルを配列に格納する
  //
  protected function readXlsx($readFile, $sheetName)
  {
    // ライブラリファイルの読み込み （パス指定し直す）
    require_once dirname(__FILE__) . '/PHPExcel/IOFactory.php';

    // ファイルの存在チェック
    if (!file_exists($readFile)) {
      throw new AppException("ファイルが見つかりません {$readfile}");
    }

    // xlsxをPHPExcelに食わせる
    $objPExcel = PHPExcel_IOFactory::load($readFile);

    // 配列形式で返す
    $sheet = $objPExcel->getSheetByName($sheetName);
    if ($sheet == null) {
      throw new AppException("シートがありません({$sheetName})");
    }
    return $sheet->toArray(null, true, true, true);
  }


  //////////////////////////////////////////////////////////////////////////
  //
  //  確認キー等に使用する文字列の生成
  //
  function makeRandStr($length)
  {
    $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
      $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
  }
}
