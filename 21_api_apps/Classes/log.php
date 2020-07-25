<?php
class Log{


    // ログ出力関数 INFOログを出力する
    public static function debug($text){
        $bt = debug_backtrace();
        self::write($text, "DEBUG", $bt[0]['file'], $bt[0]['line']);
    }

    public static function func($text = ""){
        $bt = debug_backtrace();
        self::write($text, "FUNC", $bt[0]['file'], $bt[0]['line'], $bt[1]['function'] . "==================================");
    }

    // ログ出力関数 APIログを出力する
    public static function api($text, $p = NULL){
        $bt = debug_backtrace();
        self::write("##########################################################\n{$text}\n" . print_r($p, TRUE), "API", $bt[0]['file'], $bt[0]['line']);
    }

    public static function info($text){
        $bt = debug_backtrace();
        self::write($text, "INFO", $bt[0]['file'], $bt[0]['line']);
    }

    // ログ出力関数 DBログを出力する
    public static function db($text){
        $bt = debug_backtrace();

        self::write("-----------------------------------------------\r\n" . substr($text, 0, 50), "DB", $bt[1]['file'], $bt[1]['line']);
        self::writeSql("-----------------------------------------------\r\n" . $text, $bt[1]['file'], $bt[1]['line']);
    }

    // ログ出力関数 ERRORログを出力する
    public static function error($text){

        $bt = debug_backtrace();
        self::write($text, "ERROR", $bt[0]['file'], $bt[0]['line']);
    }

    // ログ出力関数 ERRORログを出力する
    public static function error_ex($ex){

        self::write($ex->getLogMessage(), "ERROR", "", "");
    }

    // ログ出力関数 mailログを出力する
    public static function mail($text){
        $datetime = self::getDateTime();
        $date = self::getDate();
        $file_name = "/var/log/httpd/mail_log";
        $text = "{$datetime} [{$log_type}] {$file}({$line}){$text}" . PHP_EOL;

//        if(strpos($_SERVER['SERVER_NAME'], 'localhost') !== FALSE)
        error_log(print_r($text, TRUE), 3, $file_name);
    }

    // /log/log-年月日.log ファイルを出力する
    private static function write($text, $log_type, $file, $line, $fn = ''){

        $datetime = self::getDateTime();
        $date = self::getDate();

        $logfile = "app_" . $_SESSION['userID'] . ".log";
        $file_name = "/var/log/httpd/" . $logfile;

        $file = substr($file, strlen("/var/www/html"));
        $text = "{$datetime} [{$log_type}] {$file}({$line}) {$fn}{$text}" . PHP_EOL . PHP_EOL;

//        if(strpos($_SERVER['SERVER_NAME'], 'localhost') !== FALSE)
        error_log(print_r($text, TRUE), 3, $file_name);

    }

    // /log/log-年月日.log ファイルを出力する
    private static function writeSql($text, $file, $line){

        $datetime = self::getDateTime();
        $date = self::getDate();

        $logfile = "app_" . $_SESSION['userID'] . "_sql.log";
        $file_name = "/var/log/httpd/" . $logfile;

        $file = substr($file, strlen("/var/www/html"));
        $text = "{$datetime} [{$log_type}] {$file}({$line}) {$fn}{$text}" . PHP_EOL . PHP_EOL;

//        if(strpos($_SERVER['SERVER_NAME'], 'localhost') !== FALSE)
        error_log(print_r($text, TRUE), 3, $file_name);

    }


    // 日付を返す(ファイル名用)
    private static function getDate(){
        return date('Ymd');
    }

    // 日時を返す(出力ログ用)
    private static function getDateTime(){
        $datetime = explode(".", microtime(true));
        $date = date('ymdHis', $datetime[0]);
        $time = $datetime[1];

        return "{$date}.{$time}";
    }

}
?>