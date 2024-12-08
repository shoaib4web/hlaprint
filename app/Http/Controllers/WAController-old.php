<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Models\UserCodes;
use App\Models\WhatsAppModel;
use App\Models\PrintJobsModel;
use App\Models\WhatsAppTextMessagesModel;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;




class WAController extends Controller
{
    public $data = [];
    public $token = 'EAAlAigYje8QBO0ZBKqgK2ysZA3TzlIH6Qs10a3VewHGZCUCKqHVdQ8BfJBBaVqk0jq2ZCHEBFXsTYVlpVIBZAITBMrfcoxNhijwrsftMX4ToeDyN64vdpMnsJsZC7Ap8KKhXybxUZCS5qHgPttxwktc6NiZAaFZCKBgJO4YVKROZBlrTl6Ebj6J9oJZC0LwPNoJiQPH3jCZC3UWxmkdxiPB5WkmWfijZBMK4ZD';

    public function index(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        //Storage::disk('local')->put('example.txt', $data);
       // Storage::disk('public')->put('images.json', $request);
        return $request["hub_challenge"];
    }

    public function indexPost(Request $request)
    {
        $this->data = ($request->all());
        $debug_value = print_r($this->data, true);
        Storage::disk('local')->put('khttki_intial_debug.txt', $debug_value);
        if (!isset($this->data['entry'][0]['changes'][0]['value']['messages'][0]['type'])) {
            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        }
        $this->data['file_type'] = $this->data['entry'][0]['changes'][0]['value']['messages'][0]['type'];
        $this->data['phone'] = explode('/', $this->data['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'])[0];
        if ($this->data['file_type'] == 'interactive') {

            if (isset($this->data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'])) {
                $this->data['message'] =  $this->data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'];
            } elseif (isset($this->data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'])) {
                $this->data['message'] =  $this->data['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'];
            }
            $this->handleInteractive();
            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        }
        if ($this->data['file_type'] == 'text') {
            $this->data['message'] = $this->data['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
            $this->save_text_message_to_database(); // <------------------------------------------------------ TEXT MESSAGE -> DATABASE
            $res = [
                "status" => "200",
            ];

            return response()->json($res);
        } else {
            $id = $this->data['entry'][0]['changes'][0]['value']['messages'][0][$this->data['file_type']]['id'];
            if (isset($this->data['entry'][0]['changes'][0]['value']['messages'][0][$this->data['file_type']]['caption'])) {
                $this->data['message'] = $this->data['entry'][0]['changes'][0]['value']['messages'][0][$this->data['file_type']]['caption'];
            } else {
                $this->data['message'] = "No Caption Provided";
            }
            $this->get_image($id); // <------------------------------------------------------ GET IMAGE
            $res = [
                "status" => "200",
            ];
            return response()->json($res);
        }
    }

    public function save_print_job()
    {
        $model = new PrintJob();
        $model->phone = $this->data['phone'];
        $model->filename = $this->data['filename'];
        $model->code = $this->data['user_code'];
        $model->color = false;
        $model->double_sided = false;
        $model->pages_start = 0;
        $model->page_end = 0;
        $model->status = 'Queued';
        $model->save();
    }
    public function handleInteractive()
    {
        $temp = explode('_', $this->data['message']);
        $debug_value = print_r($temp, true);
        Storage::disk('local')->put('khttki_handler_debug.txt', $debug_value);
        $action = $temp[1];
        $code = $temp[0];



        switch ($action) {
            case ('color'):
                $model = PrintJobsModel::where('phone', '=', $this->data['phone'])->where('code', '=', $code)->first();
                $model->color = true;
                $model->save();
                $this->interactiveFlow('sides', $code);
                break;
            case ('double-sided'):
                $model = PrintJobsModel::where('phone', '=', $this->data['phone'])->where('code', '=', $code)->first();
                $model->double_sided = true;
                $model->save();
                $this->interactiveFlow('payment', $code);
                break;
            case ('single-sided'):
                $this->interactiveFlow('payment', $code);
                break;
            case ('bw'):
                $this->interactiveFlow('sides', $code);
                break;
        }
    }

    public function interactiveFlow($template, $code)
    {
        switch ($template) {
            case ('sides'):

                $message = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => $this->data['phone'],
                    "type" => "interactive",
                    "interactive" => [
                        'type' => 'button',
                        'header' => [
                            'type' => 'text',
                            'text' => 'Select Sides to Print'
                        ],
                        'body' => [
                            'text' => 'Please Select the Sides to Print'
                        ],
                        'footer' => [
                            'text' => 'Your current code is ' . $code
                        ],
                        'action' => [
                            'buttons' => [
                                [
                                    'type' => 'reply',
                                    'reply' => [
                                        'id' => $code . '_single-sided',
                                        'title' => 'Single Sided Print'
                                    ]
                                ],
                                [
                                    'type' => 'reply',
                                    'reply' => [
                                        'id' => $code . '_double-sided',
                                        'title' => 'Double Sided Print'
                                    ]
                                ]
                            ]

                        ]
                    ]
                ];
                break;
            case ('payment'):
                $message = [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => $this->data['phone'],
                    "type" => "text",
                    "text" => [
                        "preview_url" => false,
                        "body" => "Please visit link below for selecting number of pages and checkout\n\n " . url('/') . '/getOptions/' . $code
                    ]
                ];
        }
        $response = Http::withToken($this->token)->POST('https://graph.facebook.com/v16.0/107831068970298/messages/', $message);
        $debug_value = print_r($response->json(), true);
        Storage::disk('local')->put('khttki_2nd_message_debug.txt', $debug_value);
    }

    public function get_image($id)
    {
        $response = Http::withToken($this->token)->get('https://graph.facebook.com/v16.0/' . $id . '/');
        $debug_value = print_r($response, true);
        Storage::disk('local')->put('khttki_getImage_debug.txt', $debug_value);
        // $this->data['file_size'] = $response['file_size'];
        $this->save_file($response['url']);
    }
    public function save_file($url)
    {
        $this->data['ext'] = explode('/', $this->data['entry'][0]['changes'][0]['value']['messages'][0][$this->data['file_type']]['mime_type'])[1];
        try {
            $this->data['dir'] = 'WhatsApp_Files/' . $this->data['phone'];

            //Storing File in Storage/app/
            $file = Http::withToken($this->token)->get($url);
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            if ($this->data['file_type'] == 'document') {
                $this->data['filename'] = explode('/', $this->data['entry'][0]['changes'][0]['value']['messages'][0]['document']['filename'])[0];
                $this->data['filename'] = $this->data['phone'] . '_' . $now->format("H_i_s_u") . '_' . $this->data['filename'];
            } else {
                $this->data['filename'] = $this->data['phone'] . '_' . $now->format("H_i_s_u") . '.' . $this->data['ext'];
            }
            Storage::disk('public')->put($this->data['dir'] . '/' . $this->data['filename'], $file);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Storage::disk('local')->put('error.txt', $error);
        }
        $this->reply_message(); // <------------------------------------------------------ REPLY MESSAGE
    }

    public function save_file_record_to_database()
    {
        try {
            $record = new WhatsAppModel();
            $record->message = $this->data['message'];
            $record->phone = $this->data['phone'];
            $record->file_type = $this->data['file_type'];
            $record->file_name = $this->data['filename'];
            $record->file_path = $this->data['dir'];
            // $record->file_size = $this->data['file_size'];
            $record->file_size = 0;
            $record->user_code = $this->data['user_code'];
            $record->save();
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Storage::disk('local')->put('error.txt', $error);
        }
    }

    public function save_text_message_to_database()
    {
        try {
            $record = new WhatsAppTextMessagesModel();
            $record->phone = $this->data['phone'];
            $record->message = $this->data['message'];
            $record->save();
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Storage::disk('local')->put('error.txt', $error);
        }
        $message = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $this->data['phone'],
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "شكرًا لك على رسالتك ، ولكننا نتوقع ملف مرفق ترغب في طباعته. يرجى مشاركة الملف. \n\nThank you for your message, however we are expecting a file attachment that you would like to print. Please share the file."
            ]
        ];

        try {
            $response = Http::withToken($this->token)->POST('https://graph.facebook.com/v16.0/107831068970298/messages/', $message);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Storage::disk('local')->put('error.txt', $e);
        }
    }

    public function reply_message()
    {
        //تم استلام طلبك وهنا رمزك ( $code ) للمضي قدمًا. من فضلك لا تشارك هذا الرمز مع أي شخص.\n\n
        $code = rand(1000, 9999);
        $this->data['user_code']  = $code;
        $message = [
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $this->data['phone'],
            "type" => "text",
            "text" => [
                "preview_url" => true,
                "body" => "تم استلام ملفك. اتبع الرابط أدناه للحصول على خيارات الطباعة والدفع.\n\n Your file has been received. Follow the Link below for printing options and payment. \n\n " . url('/') . '/getOptions/' . $code
            ]
        ];

        // $message = [
        //     "messaging_product" => "whatsapp",
        //     "recipient_type" => "individual",
        //     "to" => $this->data['phone'],
        //     "type" => "interactive",
        //     "interactive" => [
        //         'type' => 'list',
        //         'header' => [
        //           'type' => 'text',
        //           'text' => 'We have Recieved your File'
        //         ],
        //         'body' => [
        //           'text' => 'Please Select the option that applies to you, your transaction code is '.$code
        //         ],
        //         'footer' => [
        //           'text' => 'Please keep this code private'
        //         ],
        //         'action' => [
        //           'button' => 'Options',
        //           'sections' => [
        //             [
        //               'title' => 'Print Options',
        //               'rows' => [
        //                 [
        //                   'id' =>  $code.'_color',
        //                   'title' => 'Color',
        //                   'description' => 'Select for Color Print'
        //                 ],

        //                 [
        //                       'id' => $code.'_bw',
        //                       'title' => 'B/W',
        //                       'description' => 'Select for B/W Print'
        //                 ]

        //             ]
        //                 ],
        //                 [
        //                     'title' => 'Support',
        //                     'rows' => [
        //                         [
        //                             'id' => 'support',
        //                             'title' => 'Support',
        //                             'description' => 'Select for Support'
        //                         ],
        //                 ]
        //         ]
        //       ]
        // ]
        //                     ]];





        //save code in UserCodes table
        $uc = new UserCodes();
        $uc->phone = $this->data['phone'];
        $uc->code = $this->data['user_code'];
        $uc->status = false;
        $uc->expiry = date('Y-m-d', strtotime(' +1 day'));
        $uc->save();

        $this->save_file_record_to_database(); // <------------------------------------------------------ DATABASE RECORD
        $this->save_print_job();

        $convert_request = new ConvertController();
        $convert_request->convert($this->data['filename'], $this->data['phone'], $this->data['user_code']);

        try {
            $response = Http::withToken($this->token)->POST('https://graph.facebook.com/v16.0/107831068970298/messages/', $message);
            $debug_value = print_r($response->json(), true);
            Storage::disk('local')->put('khttki_message_debug.txt', $debug_value);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Storage::disk('local')->put('error.txt', $e);
        }
    }
}
