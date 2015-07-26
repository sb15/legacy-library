<?php

namespace Notification;

class Email
{

    public static $encoding = 'windows-1251';

    public static $from = null;
    public static $fromName = null;

    public static function init($from, $fromName)
    {
        self::$from = $from;
        self::$fromName = $fromName;
    }

    /**
     * @param $to
     * @param $from
     * @param $subject
     * @param $content
     * @throw \Exception
     */
    public static function send($to, $subject, $content, $attachments = array())
    {
        if (!self::$from || !self::$fromName) {
            throw new \Exception('Define sender');
        }

        $to = @iconv('UTF-8', self::$encoding, $to);
        $from = @iconv('UTF-8', self::$encoding, self::$from);
        $fromName = @iconv('UTF-8', self::$encoding, self::$fromName);
        $subject = @iconv('UTF-8', self::$encoding, $subject);
        $content = @iconv('UTF-8', self::$encoding, $content);

        $mail = new EmailFix(self::$encoding);
        $transport = new \Zend_Mail_Transport_Sendmail('-f' . $from);
        $mail->setDefaultTransport($transport);

        $mail->setBodyHtml($content);
        $mail->setHeaderEncoding(\Zend_Mime::ENCODING_QUOTEDPRINTABLE);
        $mail->setFrom($from, $fromName);

        foreach ($attachments as $cid => $attachment) {
            if (is_file($attachment)) {
                $image = file_get_contents($attachment);
                $at = $mail->createAttachment($image, "image/jpg; name=\"{$cid}.jpg\"");
                $at->id = $cid;
            }
        }

        $mail->addTo($to);
        $mail->setSubject($subject);
        $mail->setType(\Zend_Mime::MULTIPART_RELATED);

        $mail->send();

    }

}



