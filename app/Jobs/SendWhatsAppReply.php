<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SendWhatsAppReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contactId;
    protected $phoneID;
    
    private $token = "EAAlAigYje8QBO59eGxviZCxydg1ulQ3T6gaZCfQlkdOBRZCxLw9NRbcKCcjAZCTVx9Wr57IwSPkYAnkXTrER0FVUZASotyzcZCld0wSFqqL5gT1cZAXjGN9uEsmPXeQRVQYqXgUWwRfS3YLQCvttTJG2fjDgsOZAedzhSNSKNiZAOoM3hvrd7xbAuOus1McjkpGwZA";

    public function __construct($contactId,$phoneID)
    {
        $this->contactId = $contactId;
        $this->phoneID = $phoneID;

    }
    


    public function handle()
    {
      // Check if the message has been responded to
        if (Cache::has('replied_to_' . $this->contactId)) {
            // The message has already been responded to
            return;
        }
        
        
        
        
        $new_message = Cache::get('new_message_for' . $this->contactId);
        
        $total_messages = Cache::get('total_messages_by'.$this->contactId);
        
        $lastMessageTimestamp = Cache::get('last_message_timestamp_' . $this->contactId);
        
        $currentTime = now()->timestamp;
        
        if(($currentTime - $lastMessageTimestamp) >= 3)
        {
             $message = [

            "messaging_product" => "whatsapp",

            "recipient_type" => "individual",

            "to" => $this->contactId,

            "type" => "interactive",

            "interactive" => [

                'type' => 'button',

                
                'body' => [

                  'text' => "إستمر بارسال المزيد من الملفات او اختر جاهز لإتمام الطباعه"

                ],

               

                'action' => [

                  'buttons' => [
                
    [
        'type' => 'reply',
        'reply' => [
            'id' => 'no', // unique ID for the second button
            'title' => 'جاهز' // title for the second button
        ]
    ]
                      
                      ]

                  ]

                ]];
            

            try {
                $response = Http::withToken($this->token)->POST(
                    "https://graph.facebook.com/v16.0/".$this->phoneID."/messages/",
    
                    $message
                );
                
                $e = print_r($response,true);
                Storage::disk("local")->put("SexyText.txt", $e);
    
             
            } catch (\Exception $e) {
                $error = $e->getMessage();
    
                Storage::disk("local")->put("SexyText.txt", $e);
            }
        
        }
        else {
            return;
        }
            

       
       

        // Mark the message as replied
         Cache::put('replied_to_' . $this->contactId, true, 10);
        Cache::put('new_message_for' . $this->contactId, false, 10);
       
}
}
