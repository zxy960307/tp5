<?php
/**
 * 发送邮件类库
 */
namespace phpmailer;
use phpmailer\PHPMailer;
use think\Exception;
class Email
{
    /**
     * 发送文件函数
     * @param $to发送方
     * @param $title主题
     * @param $content内容
     * @return mixed
     */
    public static function send($to,$title,$content)
    {
        date_default_timezone_set('PRC');//set time
        if(empty($to))
        {
            return false;
        }
        try {
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Debugoutput = 'html';
            $mail->Host =config('email.host');
            $mail->Port = 25;
            $mail->SMTPAuth = true;
//Username to use for SMTP authentication
            $mail->Username = config('email.user');
//Password to use for SMTP authentication
            $mail->Password =config('email.password');
//Set who the message is to be sent from
            $mail->setFrom('zhangxingyu960307@163.com', 'zhangxingyu960307');
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
            $mail->addAddress($to, '张兴钰');
//Set the subject line
            $mail->Subject = $title;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
            $mail->msgHTML($content);
            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        }catch(phpmailerException $e)
        {
            return fasle;
        }
    }
}
