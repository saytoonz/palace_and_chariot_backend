<?php

namespace App\Traits;

trait NotificationsTrait
{
    public function sendTokenPushNotification($userToken, $title, $body)
    {

        return $this->sendPush('{
                "message": {
                    "token": "' . $userToken . '",
                    "notification": {
                        "title": "' . $title . '",
                        "body": "' . $body . '"
                    }
                }
            }');
    }

    public function sendTopicPushNotification($topic, $title, $body, $data = '')
    {

        return $this->sendPush('{
                "message": {
                    "topic": "' . $topic . '",
                    "notification": {
                        "title": "' . $title . '",
                        "body": "' . $body . '"
                    }
                }
            }');
    }

    function sendPush($data)
    {
        $credentialsFilePath =   storage_path('app/fcm.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/palace-and-chariots-198b5/messages:send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token['access_token'],
            ),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}
