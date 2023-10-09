<?php

namespace App\Http\Helpers;
use App\Http\Resources\CountryResource;
use App\Models\Config;
use App\Models\Country;
use App\Models\Follow;
use App\Models\GiftLog;
use App\Models\OfficialMessage;
use App\Models\Pack;
use App\Models\PackLog;
use App\Models\Room;
use App\Models\User;
use App\Models\UserVip;
use App\Models\Vip;
use App\Models\Ware;
use App\Traits\HelperTraits\AdminTrait;
use App\Traits\HelperTraits\AttributesTrait;
use App\Traits\HelperTraits\CalcsTrait;
use App\Traits\HelperTraits\FilterTrait;
use App\Traits\HelperTraits\InfoTrait;
use App\Traits\HelperTraits\MoneyTrait;
use App\Traits\HelperTraits\RoomTrait;
use App\Traits\HelperTraits\ZegoTrait;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Twilio\Rest\Client as TwilioClint;

class Common{

  

    public static function apiResponse(bool $success,$message,$data = null,$statusCode = null,$paginates = null){

        if ($success == false && $statusCode == null){
            $statusCode = 422;
        }

        if ($success == true && $statusCode == null){
            $statusCode = 200;
        }

   
        return response ()->json (
            [
                'success'   => $success,
                'message'   => __ ($message),

                'data'      => $data,

                'extra_data'=> [
                    'storage_base_url'=>'https://lychee.binary-tm.com/storage/app/public/',
                ],


                'paginates' =>$paginates
            ],
            $statusCode
        );
    }


    public static function  getPaginates($collection)
    {
        return [
            'per_page' => $collection->perPage(),
            'path' => $collection->path(),
            'total' => $collection->total(),
            'current_page' => $collection->currentPage(),
            'next_page_url' => $collection->nextPageUrl(),
            'previous_page_url' => $collection->previousPageUrl(),
            'last_page' => $collection->lastPage(),
            'has_more_pages' => $collection->hasMorePages(),
            'from' => $collection->firstItem(),
            'to' => $collection->lastItem(),
        ];
    }





    public static function upload($folder,$file){
//        $file->store('/',$folder);
//        $fileName = $file->hashName();
        $extension = $file->getClientOriginalExtension(); // Get the file extension
        $fileName = Str::random(10).'.'.$extension; // Generate a random filename and append the extension
        $file->storeAs($folder.DIRECTORY_SEPARATOR,$fileName); // Store the file with the generated filename
        return $folder.DIRECTORY_SEPARATOR.$fileName;
    }



    public static function paginate($req,$data){
        if ($req->pp){
            return static::getPaginates ($data);
        }
        return null;
    }


    public static function send_firebase_notification($tokens, $title, $body,$icon = '',$data = [],$action = '', $type = '', $id = '', $notification_type = 'user_notification')
    {

        #API access key from Google API's Console
        if (!defined('API_ACCESS_KEY'))
            define('API_ACCESS_KEY', 'AAAA50BR6kU:APA91bFKjV8CCKrmAPUnTQx1uepRBQ5LoLT258NLo24p1Io8U1RAhYTMrUxMJZQmPKDxmBhm_VkNJaYLoy_vRno0XVQZI60qFuQhKh6rmXhEpFAeJOKjuD_4wVa3Ekr4d5fKLoZciPeo');

        #prep the bundle
        $msg = array(
            "android_channel_id"=>"high_importance_channel",
            'body'=> $body,
            'title'=> $title,
            "sound"=> "default",
            "notification_count"=>1,
            "visibility"=>"PUBLIC",
            "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
        );

        $fields = array(
            'registration_ids'    => $tokens,
//            'data'                => $data,
            "notification" => $msg,
            "priority"=> "high",
            "content_available"=>true,
            "direct_boot_ok"=> true,
            "apns"=>[
                "payload"=>[
                    "aps"=>[
                        "mutable-content"=>1
                    ]
                ],
                "fcm_options"=> [
                    "image"=>"https://foo.bar/pizza-monster.png"
                ]
            ],
        );

        // echo json_encode( $fields );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


  

//     public static function sendSMS($phone,$message){
//         $account_sid = Common::getConf ('twilio_sid');
//         $auth_token = Common::getConf ('twilio_api_key');
//         $twilio_number = Common::getConf ('twilio_from');
//         $twilio_service = Common::getConf ('twilio_service');
//         try {
//             // $client = new TwilioClint($account_sid, $auth_token);
//             if ($twilio_service){
//                 $arr = [
// //                    'from' => $twilio_number,
//                     "messagingServiceSid" => $twilio_service,
//                     'body' => $message
//                 ];
//             }else{
//                 $arr = [
//                     'from' => $twilio_number,
//                     'body' => $message
//                 ];
//             }
//             // return $client->messages->create(
//             // // Where to send a text message (your cell phone?)
//             //     $phone,
//             //     $arr
//             // );
//         }catch (\Exception $exception){

//         }

//     }

    public static function sendOfficialMessage($user_id,$title = '',$content = '',$type = 1){
        // OfficialMessage::query ()->create (
        //     [
        //         'title'=>$title,
        //         'user_id'=>$user_id,
        //         'content'=>$content,
        //         'img'=>'',
        //         'type'=>$type
        //     ]
        // );
    }

    // public static function fireBaseFactory(){
    //     return (new Factory)
    //         ->withServiceAccount(public_path ('firebase_credentials.json'))
    //         ->withDatabaseUri('https://yay-chat-c2333-default-rtdb.firebaseio.com');
    // }

    // public static function fireBaseDatabase($path,$obj,$type = 'set'){
    //     $factory = self::fireBaseFactory ();
    //     $database = $factory->createDatabase();
    //     if ($type == 'set'){
    //         $database->getReference($path) ->set($obj);
    //     }else{
    //         return $database->getReference($path)->getSnapshot()->getValue();
    //     }

    // }

    // public static function handelFirebase($request,$type = 'follow'){
    //     $f_add = 0;
    //     $fr_add = 0;
    //     $vi_add = 0;
    //     $id = (integer)$request->user_id ;
    //     $snap = self::fireBaseDatabase ($id,'','get');
    //     $followers_count = @(integer)$snap['followers']?:0;
    //     $followings_count = @(integer)$snap['followings']?:0;
    //     $friends_count = @(integer)$snap['friends']?:0;
    //     $visitors_count = @(integer)$snap['visitors']?:0;
    //     $path = $id;

    //     if ($type == 'follow'){
    //         if (in_array ($request->user_id,$request->user ()->followers_ids()->toArray())){
    //             $fr_add = 1;
    //         }
    //         $f_add = 1;
    //     }elseif($type == 'visit'){
    //         $vi_add = 1;
    //     }

    //     $obj = [
    //         'followers'=>$followers_count + $f_add,
    //         'followings'=>$followings_count,
    //         'friends'=>$friends_count + $fr_add,
    //         'visitors'=>$visitors_count + $vi_add
    //     ];

    //     self::fireBaseDatabase ($path,$obj);





    //     $f_add = 0;
    //     $fr_add = 0;
    //     $vi_add = 0;
    //     $id = (integer)$request->user()->id ;
    //     $snap = self::fireBaseDatabase ($id,'','get');
    //     $followers_count = @(integer)$snap['followers']?:0;
    //     $followings_count = @(integer)$snap['followings']?:0;
    //     $friends_count = @(integer)$snap['friends']?:0;
    //     $visitors_count = @(integer)$snap['visitors']?:0;
    //     $path = $id;

    //     if ($type == 'follow'){
    //         if (in_array ($request->user_id,$request->user ()->followers_ids()->toArray())){
    //             $fr_add = 1;
    //         }
    //         $f_add = 1;
    //     }
    //     $obj = [
    //         'followers'=>$followers_count,
    //         'followings'=>$followings_count + $f_add,
    //         'friends'=>$friends_count + $fr_add,
    //         'visitors'=>$visitors_count + $vi_add
    //     ];

    //     self::fireBaseDatabase ($path,$obj);
    // }

  
   

}