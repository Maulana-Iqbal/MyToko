<?php
namespace App\Http\Controllers;

use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
        {
            $this->middleware('auth');
        }
        
    public function index()
    {
        $push_notifications = PushNotification::latest()->get();
        return view('notifIndex', compact('push_notifications'));
    }
    public function bulksend(Request $req){
        $comment = new PushNotification();
        $comment->title = $req->input('title');
        $comment->body = $req->input('body');
        $comment->img = $req->input('img');
        $comment->save();

        $SERVER_API_KEY = 'AAAAzynQzAQ:APA91bFD1ThQWPt8-PzE0Ifeuhd-cAcPJwmfeUu9Xa5uqsBH4D4H3IAG31OoM48ZzYPvP9zqf6qWCtMSZzSNpN4uNNHWFShFBxWF3pQHuyewj_iun5iBnaMOzSb7emkbTjfJhE8ySi5C';

        $token_1 = 'frtiwtX4RRONp_JpU-6pXl:APA91bFjUChGY1J-TQ1I1xV4PLOlNvplv8-KpVluw5SXTvf7J53veDtG7HJAvcQ9Z0sO1Ss2iYhuOTQoFr3_3fq6L10cdomMsOHyqQJHK-GrElYgtywM5Kih0qOW9M93J0_dDyifSrlL';

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => $comment,

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        return redirect()->back()->with('success', 'Notification Send successfully');

     }    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotification $pushNotification)
    {
        //
    }

    public function saveToken(Request $request)
        {
           User::find(auth()->user()->id)->update(['userToken'=>$request->token]);
            return response()->json(['token saved successfully.']);
        }
    
        /**
         * Write code on Method
         *
         * @return response()
         */
        public function sendNotification(Request $request)
        {
            //firebaseToken berisi seluruh user yang memiliki device_token. jadi notifnya akan dikirmkan ke semua user
            //jika kalian ingin mengirim notif ke user tertentu batasi query dibawah ini, bisa berdasarkan id atau kondisi tertentu
            
            $firebaseToken = User::whereNotNull('userToken')->pluck('userToken')->all();    
            $SERVER_API_KEY = 'AAAAzynQzAQ:APA91bFD1ThQWPt8-PzE0Ifeuhd-cAcPJwmfeUu9Xa5uqsBH4D4H3IAG31OoM48ZzYPvP9zqf6qWCtMSZzSNpN4uNNHWFShFBxWF3pQHuyewj_iun5iBnaMOzSb7emkbTjfJhE8ySi5C';
            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    // "title" => $request->title,
                    // "body" => $request->body,
                    "title" => 'ini title',
                    "body" => 'ini body',
                    "icon" => 'https://cdn.pixabay.com/photo/2016/05/24/16/48/mountains-1412683_960_720.png',
                    "content_available" => true,
                    "priority" => "high",
                ]
            ];
            $dataString = json_encode($data);
    
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
    
            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    
            $response = curl_exec($ch);
    
            dd($response);
        }
}
