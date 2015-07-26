<?php

namespace Service\FourPush;

class FourPush
{

    public static function notify($title = 'title', $message = 'message')
    {
        $key = 'GBFLdTq9KEkpMEsujrVaSCY1A_q2vGPPIw3JkTN0DXRPLdAxbxxetw';

        $longMessage = $message;

        $data = array
        (
            "user_credentials" => $key,
            "notification[message]" => $message,
            "notification[long_message]" => $longMessage,
            "notification[title]" => $title,
            //"notification[long_message_preview]" => "Message Preview",
            "notification[message_level]" => "0",
            "notification[silent]" => "0",
            //"notification[action_loc_key]" => "Google",
            //"notification[run_command]" => "http://www.google.com/",
            "notification[sound]" => "5.caf",
        );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, "https://www.appnotifications.com/account/notifications.xml" );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_exec( $ch );
        curl_close( $ch );

    }

}