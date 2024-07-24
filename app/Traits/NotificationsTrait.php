<?php

namespace App\Traits;

trait NotificationsTrait
{
    public function sendPushNotification()
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
            CURLOPT_POSTFIELDS => '{
                "message": {
                    "token": "cLObSIS4SwKytWuRTNiZBf:APA91bHeCPanOibmOUtq0it1UZYozXx41LQhdybj_3cCu_IG2y6FWoi-2hE_zuN4lZ3AF-c1EcCzZT45U385S4L-bhAgl0PBowwntpbuV2oGH-MVmpsT2NnMhkJDa7Fbk4r9vMky-znQ",
                    "notification": {
                        "body": "Bring me the phone now!!!",
                        "title": "Hey Madam Esther"
                    }
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token['access_token'],
            ),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}
