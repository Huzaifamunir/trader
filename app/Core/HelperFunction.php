<?php


namespace App\Core;


class HelperFunction
{

    public static function send_sms($to, $message)
    {

      $api_token = "160064846de61a9c6ea46aa10d18f68ae787421a2d3c2fce1fc112ea44a2";

      $api_secret = "zrtraders1";

      // $to = "92xxxxxxxxxx";

      $from = "ZrTraders";

      // $message = "Testing SMS";

      $url = "http://sms.eziline.com/plain?api_token=".urlencode($api_token)."&api_secret=".urlencode($api_secret)."&to=".$to."&from=".urlencode($from)."&message=".urlencode($message)."";



      $ch  =  curl_init();

      $timeout  =  30;

      curl_setopt ($ch, CURLOPT_URL, $url);

      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

      $response = curl_exec($ch);

      curl_close($ch);



      if(strstr( $response, 'OK : ' ) || strstr($response ,':1')){

        return true;

      }



      return false;

    }
}
