<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailerを配置するパスを自身の環境に合わせて修正
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';



class MailSend
{
  private $from;
  private $fromName;

  public function __construct($f = "", $fn = "")
  {
    if ($f == "") {
      $this->from = "admin@reserve.com";
      $this->fromName = '予約くん';
    } else {
      $this->from = $f;
      $this->fromName = $fn;
    }
  }

  function convertEOL($string, $to = "\n")
  {
    return preg_replace("/\r\n|\r|\n/", $to, $string);
  }

  function is_mail($text)
  {
    if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function Send($to, $cc, $subject, $msg, $bcc = "", $attach = "")
  {
    log::debug("{$to},{$cc},{$bcc}");
    $a = explode(",", $to);
    foreach ($a as $t) {
      if ($this->is_mail(trim($t)) == FALSE) {
        log::error('Invalid Mail address' . $t);
        return FALSE;
      }
    }


    $a = file(__DIR__ . '/../production.txt');
    log::info(print_r($a, TRUE));
    log::info($bcc);
    if ($a == "") {
      $datetime = date("Y-m-d H:i:s");
      $file_name = "/var/log/httpd/mail_log";
      $text = "{$datetime}\n from:$this->from\nto:$to\ncc:$cc\nbcc:$bcc\n[$subject]\n$attach\n\n$msg\n" . PHP_EOL;

      //        if(strpos($_SERVER['SERVER_NAME'], 'localhost') !== FALSE)
      error_log(print_r($text, TRUE), 3, $file_name);
      return TRUE;
    }
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
      //Server settings
      $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = getenv('SMTP_HOST');
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = getenv('AUTH_USER');                // SMTP username
      $mail->Password = getenv('AUTH_PASSWORD');            // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to (ssl:465)
      $mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer'  => false,
          'verify_peer_name'  => false,
          'allow_self_signed' => true
        )
      );            //Recipients
      $mail->setFrom($this->from, mb_encode_mimeheader($this->fromName));

      $a = explode(",", $to);
      foreach ($a as $t) {
        $mail->addAddress(trim($t));     // Add a recipient
      }
      $mail->addReplyTo($this->from, mb_encode_mimeheader($this->fromName));
      // $mail->addCC('cc@example.com');

      if ($bcc != "") {
        $b = explode(",", $bcc);
        foreach ($b as $t) {
          $mail->addBCC(trim($t));     // Add a recipient
        }
      }
      if ($cc != "") {
        $b = explode(",", $cc);
        foreach ($b as $t) {
          $mail->addCC(trim($t));     // Add a recipient
        }
      }
      // $mail->addBCC('bcc@example.com');

      if ($attach != "") {
        $at = explode(",", $attach);
        foreach ($at as $f) {
          if (trim($f) != "") {
            log::debug($f);
            $fa = explode('=', $f);
            $path = $fa[0];
            $name = "";
            if (count($fa) > 1) {
              $name = $fa[1];
            }
            $mail->addAttachment($path, $name);         // Add attachments

          }
        }
      }
      //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      //Content
      $mail->isHTML(false);                                  // Set email format to HTML
      $mail->Subject = mb_encode_mimeheader($subject);
      $mail->Encoding = "7bit";
      $mail->CharSet = 'ISO-2022-JP';
      $mail->Body    = $this->convertEOL(mb_convert_encoding($msg, "JIS", "UTF-8"), "\r\n");



      //            $mail->Body    = $msg;
      //            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      log::info('Message has been sent');
      return TRUE;
    } catch (Exception $e) {
      log::error('Message could not be sent. Mailer Error: ' . print_r($mail->ErrorInfo, TRUE));
      return FALSE;
    }
  }
}
