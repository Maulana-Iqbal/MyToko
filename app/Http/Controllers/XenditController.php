<?php

namespace App\Http\Controllers;

use App\Models\Xeninvoice;
use Illuminate\Support\Facades\DB;
use Xendit\Xendit;

require '..\vendor/autoload.php';

class XenditController extends Controller
{

    private $apiKey;
    private $callBackToken;
    public function __construct()
    {
        $this->apiKey = 'xnd_development_P4qDfOss0OCpl8RtKrROHjaQYNCk9dN5lSfk+R1l9Wbe+rSiCwZ3jw==';
        $this->callBackToken = 'VVxGHRNEuvV5PcIGRBLqlZRsqT7XO73TAImIpEi4X5gh3gVT';
    }

    public function ewallet()
    {
        Xendit::setApiKey($this->apiKey);
        $params = [
            'reference_id' => 'test-reference-id',
            'currency' => 'IDR',
            'amount' => 1000,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'ID_DANA',
            'channel_properties' => [
                'success_redirect_url' => 'https://dashboard.xendit.co/register/1',
            ],
            'metadata' => [
                'branch_code' => 'tree_branch'
            ]
        ];

        $createEWalletCharge = \Xendit\EWallets::createEWalletCharge($params);
        var_dump($createEWalletCharge);
    }

    public function index()
    {

        Xendit::setApiKey($this->apiKey);

        $params = [
            'external_id' => 'SJB20220321TRX1',
            'payer_email' => 'nengkirahmat@gmail.com',
            'description' => 'Tagihan Transaksi',
            'amount' => 2000000,
            "expiry_date" => "2022-03-21T12:00:00.469Z"
        ];

        $createInvoice = \Xendit\Invoice::create($params);
        var_dump($createInvoice);
    }

    public function saldo()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.xendit.co/balance?account_type=CASH&currency=IDR',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($this->apiKey . ':'),
                'Cookie: nlbi_2182539=XGdoS+D6AU2iZffejjCKbQAAAAA/1sMAz0MoKRnDGXUXI4Gk; incap_ses_7243_2182539=wEgLQdBk/Dn3zX/qSU6EZP3W32IAAAAAmQ1gfWXXOPF9LQxwd1VJGg=='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

    public function createInvoice($trans, $customer, $item, $ongkir, $ppn, $pph,$diskon,$biaya_lain, $successUrl, $failurUrl)
    {
        $result = DB::transaction(function () use ($trans, $customer, $item, $ongkir, $ppn, $pph,$diskon,$biaya_lain, $successUrl, $failurUrl) {

            Xendit::setApiKey($this->apiKey);
            $accept_payment = [];
            if ($trans['amount'] > 10000000) {
                $accept_payment = [
                    "BCA", "BNI", "BRI", "MANDIRI", "PERMATA"
                ];
            }
            $params = [
                'external_id' => $trans['invoice'],
                'amount' => $trans['amount'],
                'description' => $trans['deskription'],
                'invoice_duration' => $trans['duration'],
                'customer' => $customer,
                'customer_notification_preference' => [
                    'invoice_created' => [
                        'whatsapp',
                        'sms',
                        'email'
                    ],
                    'invoice_reminder' => [
                        'whatsapp',
                        'sms',
                        'email'
                    ],
                    'invoice_paid' => [
                        'whatsapp',
                        'sms',
                        'email'
                    ],
                    'invoice_expired' => [
                        'whatsapp',
                        'sms',
                        'email'
                    ]
                ],
                'success_redirect_url' => $successUrl,
                'failure_redirect_url' => $failurUrl,
                'currency' => 'IDR',
                'payment_methods' => $accept_payment,
                'items' => $item,
                'fees' => [
                    [
                        'type' => 'ONGKIR',
                        'value' => $ongkir
                    ],
                    [
                        'type' => 'PPN',
                        'value' => $ppn
                    ],
                    [
                        'type' => 'PPH',
                        'value' => -$pph
                    ],
                    [
                        'type' => 'BIAYA LAIN',
                        'value' => $biaya_lain
                    ],
                    [
                        'type' => 'DISKON',
                        'value' => -$diskon
                    ]
                ]
            ];

            $createInvoice = \Xendit\Invoice::create($params);
            return $createInvoice;
        });
        return $result;
    }

    public function getInvoice($id)
    {
        Xendit::setApiKey($this->apiKey);

        $getInvoice = \Xendit\Invoice::retrieve($id);
        return $getInvoice;
    }

    // public function myInvoice($id)
    // {
    //     Xendit::setApiKey('xnd_development_P4qDfOss0OCpl8RtKrROHjaQYNCk9dN5lSfk+R1l9Wbe+rSiCwZ3jw==');

    //     $invoice=Xeninvoice::where('xen_external_id',$id)->first();
    //     $xenId=$invoice->xen_id;
    //     $getInvoice = \Xendit\Invoice::retrieve($xenId);
    //     return $getInvoice;

    // }

    public function getReport()
    {
        Xendit::setApiKey($this->apiKey);

        $params = [
            'type' => 'BALANCE_HISTORY',
        ];
        $generate = \Xendit\Report::generate($params);

        $detail = \Xendit\Report::detail($generate['id']);
        var_dump($detail);
    }


    public function closeInvoice($id)
    {
        $cek = $this->getInvoice($id);
        if ($cek['status'] <> 'EXPIRED') {
            Xendit::setApiKey($this->apiKey);

            $expireInvoice = \Xendit\Invoice::expireInvoice($id);
        }
        return true;
    }

    public function callBackInvoice()
    {

        // Ini akan menjadi Token Verifikasi Callback Anda yang dapat Anda peroleh dari dasbor.
        // Pastikan untuk menjaga kerahasiaan token ini dan tidak mengungkapkannya kepada siapa pun.
        // Token ini akan digunakan untuk melakukan verfikasi pesan callback bahwa pengirim callback tersebut adalah Xendit
        $xenditXCallbackToken = $this->callBackToken;

        // Bagian ini untuk mendapatkan Token callback dari permintaan header,
        // yang kemudian akan dibandingkan dengan token verifikasi callback Xendit
        $reqHeaders = getallheaders();
        $xIncomingCallbackTokenHeader = isset($reqHeaders['X-Callback-Token']) ? $reqHeaders['X-Callback-Token'] : "";

        // Untuk memastikan permintaan datang dari Xendit
        // Anda harus membandingkan token yang masuk sama dengan token verifikasi callback Anda
        // Ini untuk memastikan permintaan datang dari Xendit dan bukan dari pihak ketiga lainnya.

        if ($xIncomingCallbackTokenHeader === $xenditXCallbackToken) {
            // Permintaan masuk diverifikasi berasal dari Xendit

            // Baris ini untuk mendapatkan semua input pesan dalam format JSON teks mentah
            $rawRequestInput = file_get_contents("php://input");
            // Baris ini melakukan format input mentah menjadi array asosiatif
            $arrRequestInput = json_decode($rawRequestInput, true);

            //   print_r($arrRequestInput);

            $_id = $arrRequestInput['id'];
            $_externalId = $arrRequestInput['external_id'];
            $_userId = $arrRequestInput['user_id'];
            $_status = $arrRequestInput['status'];
            $_paidAmount = $arrRequestInput['paid_amount'];
            $_paidAt = $arrRequestInput['paid_at'];
            $_paymentChannel = $arrRequestInput['payment_channel'];
            $_paymentDestination = $arrRequestInput['payment_destination'];

            if ($_status == 'PAID') {
                $xenInvoice = Xeninvoice::where('xen_id', $_id)->update(['xen_status' => 'PAID']);
                return $_id;
            } elseif ($_status == 'EXPIRED') {
                $xenInvoice = Xeninvoice::where('xen_id', $_id)->update(['xen_status' => 'EXPIRED']);
                return $_id;
            }
            // Kamu bisa menggunakan array objek diatas sebagai informasi callback yang dapat digunaka untuk melakukan pengecekan atau aktivas tertentu di aplikasi atau sistem kamu.
        } else {
            // Permintaan bukan dari Xendit, tolak dan buang pesan dengan HTTP status 403
            http_response_code(403);
        }
    }

    public function setCallBack($kode_trans)
    {
        Xendit::setApiKey($this->apiKey);
        $callbackUrlParams = [
            'url' => 'https://9132-110-137-80-61.ngrok.io/confirs/SJB-1-202207-1011'
        ];
        $callbackType = 'invoice';
        $setCallbackUrl = \Xendit\Platform::setCallbackUrl($callbackType, $callbackUrlParams);
        // var_dump($setCallbackUrl);
        return true;
    }
}
