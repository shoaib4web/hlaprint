<?php







namespace App\Http\Controllers;







use Illuminate\Http\Request;



use Session;



use App\Models\UserCodes;



use App\Models\Shops;



use App\Models\ShopPrintRelation;



use App\Models\PrintJobsModel;



use App\Models\PrintJob;



use App\Models\Transaction;



use App\Models\Invoice;



use App\Models\Color_size;



use Illuminate\Support\Facades\Http;











class UploadController extends Controller



{



    public function uploadview()



    {



        $shop = '0';



        return view('english.upload',compact('shop'));



    }







    public function uploadShop($id)

    {



       



        $shop = Shops::find($id);



        return view('english.upload')->with('shop', $shop->id);



    }



    private function getToken($appSid, $appKey)



    {

        $url = 'https://api.aspose.cloud/connect/token';

        $postData = [



            'grant_type' => 'client_credentials',



            'client_id' => $appSid,



            'client_secret' => $appKey



        ];

        $options = [



            CURLOPT_URL => $url,



            CURLOPT_RETURNTRANSFER => true,



            CURLOPT_POST => true,



            CURLOPT_POSTFIELDS => http_build_query($postData),



            CURLOPT_HTTPHEADER => [



                'Content-Type: application/x-www-form-urlencoded',



                'Accept: application/json'



            ]



        ];

        $curl = curl_init();



        curl_setopt_array($curl, $options);



        $response = curl_exec($curl);



        curl_close($curl);

        $tokenData = json_decode($response, true);

        //  print_r($tokenData);exit;

        return $tokenData['access_token'];



    }



    public function uploadFile($appSid, $appKey, $filePath)

    {

        

        $url = 'https://api.aspose.cloud/v3.0/pdf/storage/file/' . basename($filePath);

        $headers = [



            'Authorization: Bearer ' . $this->getToken($appSid, $appKey),



        ];

        $fileData = file_get_contents($filePath);

        $options = [



            CURLOPT_URL => $url,



            CURLOPT_RETURNTRANSFER => true,



            CURLOPT_HTTPHEADER => $headers,



            CURLOPT_POSTFIELDS => $fileData,



        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        curl_close($curl);

        //    print_r($response);exit;



        return json_decode($response, true)['Uploaded'][0];



    }















    public function updateTotalPages($print_job){



        



        $check_code = PrintJob::where('filename', $print_job->filename)->first();



            if($check_code){



                // $files = json_decode($check_code->filename);



                // print_r($files);



                $all_pages='0';



                // $nameArray = [];



                // foreach($files as $f){



                    $convert_request = new ConvertController();



                    $name =  $convert_request->convert($check_code->filename, $check_code->phone, $check_code->code);



                    



                //     $updated_code = PrintJob::where('filename', $print_job->filename)->first();



                //    print_r($print_job->filename); echo $name;exit;



                   $fileNameParts = explode('.', $print_job->filename);



                   $exactFileName= $fileNameParts[3];



                   $exactFileName = explode('/', $exactFileName);



                   $exactFileName = end($exactFileName);



                   $exactFileName = $exactFileName.'.pdf'; 
                   
                   $appSid='cdc7500e-a452-4389-ac6e-5dd134d87428';

                   $appKey='ab70885c5908c1e87a4760fb2df25cb7';
                   


                    // $update_image = PrintJob::where('code', $print_job->code)->first();







                    $uploadedFileName = $this->uploadFile($appSid, $appKey, $name);

                    // echo $uploadedFileName;

                    

                    // https://api.aspose.cloud/v3.0/storage/file/{folder}/{filename}

                    $url = 'https://api.aspose.cloud/v3.0/pdf/' . $uploadedFileName . '/pages';



                    $response = Http::withHeaders([



                        'Accept' => 'application/json',



                        'Authorization' => 'Bearer ' . $this->getToken($appSid, $appKey),



                    ])->get($url);





                   

                    $pages = $response->json();

                    // print_r($pages);exit;

                    $pageNumber = $pages['Pages']['List'];



                    



                    $total_pages = count($pageNumber);



                    $all_pages+=$total_pages;



                 PrintJob::where('filename', $print_job->filename)->update(['filename'=>$name]);



                 PrintJob::where('filename', $name)->update(['total_pages'=>$all_pages]);







            }



       



       



    }







    public function upload(Request $request)



    {

        $printJobIds=[];



        $amountToPay=0;

        if($request->hasFile('file') && $request->shop_id)



        {



            $file = $request->file('file');



            $code = rand(1000,9999);



            $files = $request->file('file');



            $phone = "000000000000";



            $uc = new UserCodes();



            $uc->phone = $phone;



            $uc->code = $code;



            $uc->status = false;



            $uc->expiry = date('Y-m-d', strtotime(' +1 day'));



            $uc->save();



            



            if (!\File::isDirectory('storage/app/public/WhatsApp_Files/'. $phone)) {



                \File::makeDirectory('storage/app/public/WhatsApp_Files/'. $phone, 0755, true, true);



            }



            $file_get=[];



            // Move the uploaded file to a desired location



            $i=0;



            foreach($files as $f){



                $fileName = time() . '_' . $f->getClientOriginalName();



                session()->put('FileName', $fileName);



                session()->put('userCode', $code);



                



                $f->move('storage/app/public/WhatsApp_Files/'. $phone, $fileName);



                array_push($file_get,$fileName);



                



                $file = asset('storage/app/public/WhatsApp_Files/'. $phone.'/'.$fileName);



               



                // echo $file;exit;



                $total_pages=0;



                $print_job = new PrintJob();



                $print_job->shop_id = 0;



                $print_job->phone = $phone;



                $print_job->code = $code;



                $print_job->copies = $request->copies.$i;



                $print_job->color = $request->color.$i;



                $print_job->double_sided = $request->sides.$i == 'two' ? 'long-edge' : null;



                $print_job->pages_start = $request->pages_start.$i ? $request->pages_start.$i : null;



                $print_job->page_end = $request->page_end.$i ? $request->page_end.$i : null;



                $print_job->copies = $request->copies.$i;



                $print_job->type = $request->type;



                $print_job->status = 'Queued';



                $print_job->filename = $file;



                $print_job->total_pages = $total_pages;



                $res=$print_job->save();   


                array_push($printJobIds,$print_job->id);



                $this->updateTotalPages($print_job);



                $update_pages = PrintJob::where('id',$print_job->id)->first();



                // echo $update_pages;



                $price = $this->calculateTotals($print_job);



                $price = $price*$update_pages->total_pages;  



                $amountToPay+= $price;



                if($amountToPay < 1){



                    $amountToPay =1;



                }



                



            }



           



                // dev code to save transaction pending 



           



                $transaction = new Transaction;



                $transaction->print_job_id = json_encode($printJobIds);



                $transaction->invoice_id = 0;



                $transaction->amount = $amountToPay;



                $transaction->currency = 'SAR';



                $transaction->type = $request->type;



                $transaction->status = 0;



                $transaction->date=date('Y-m-d H:i:s');



                $transaction->created_at=date('Y-m-d H:i:s');



                $transaction->save();



            



            // dev code to save transaction pending 



        



            if($request->type =='online'){







                $update_transaction =Transaction::where('print_job_id',json_encode($printJobIds))->first();



                // print_r($update_transaction);exit;



                



                $payement = new PaymentController(); 



               



                $trans = $payement->initiatePayment($amountToPay, $update_transaction->id,'english');



    



                $redirect_url = $trans['redirect_url'];



                $trans_ref = $trans['tran_ref'];



                $update_transaction->trans_id = $trans_ref;



                    $update_transaction->update();



                if( $redirect_url){



                    return redirect( $redirect_url);



                }else{



                    return redirect()->route('englishProcessing')->with('message', 'Your print has been submitted');



                }







            }else{



                $type =  'cash';



                $this->updateTransaction(json_encode($printJobIds),$request->price);



                $modalCode ='<div class="modal confirmModal  successModal" id="cashOrder" >



                                <div class="container" style="margin-top:50px;!important">



                                <div class="close" onclick="successModal()">



                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"



                                            fill="none">



                                            <path



                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"



                                                fill="#00448E" />



                                        </svg>



                                </div> <img src="http://hlaprint.synet.com.pk/public/assets/img/lineIcon.png" alt="" style="width: 9.2rem; height: 9.2rem;">



                                        <p class="infoP">please visit the counter to pay and collect your print</p>



                                    <button onclick="successModal()" type="">Ok</button>



                            </div>';



                session()->put('modal_code', $modalCode);



                // Redirect back



                return redirect()->back();



                // return redirect()->route('englishSuccess')->with(['message', 'Proceed to Counter to pay and Collect your print']);



            }



           



        }



       elseif ($request->hasFile('file')) {

            $file = $request->file('file');



            $code = rand(1000,9999);



            $files = $request->file('file');



            $phone = "000000000000";



            $uc = new UserCodes();



            $uc->phone = $phone;



            $uc->code = $code;



            $uc->status = false;



            $uc->expiry = date('Y-m-d', strtotime(' +1 day'));



            $uc->save();

            if (!\File::isDirectory('storage/app/public/WhatsApp_Files/'. $phone)) {



                \File::makeDirectory('storage/app/public/WhatsApp_Files/'. $phone, 0755, true, true);



            }



            $file_get=[];



            // Move the uploaded file to a desired location



            $i=0;



            foreach($files as $f){



                $fileName = time() . '_' . $f->getClientOriginalName();



                session()->put('FileName', $fileName);



                session()->put('userCode', $code);



                $f->move('storage/app/public/WhatsApp_Files/'. $phone, $fileName);



                array_push($file_get,$fileName);



                $file = asset('storage/app/public/WhatsApp_Files/'. $phone.'/'.$fileName);



                // echo $file;exit;



                $total_pages=0;



                $print_job = new PrintJob();



                $print_job->shop_id = 0;



                $print_job->phone = $phone;



                $print_job->code = $code;



                $print_job->copies = $request->copies.$i;



                $print_job->color = $request->color.$i;



                $print_job->double_sided = $request->sides.$i == 'two' ? 'long-edge' : null;



                $print_job->pages_start = $request->pages_start.$i ? $request->pages_start.$i : null;



                $print_job->page_end = $request->page_end.$i ? $request->page_end.$i : null;



                $print_job->copies = $request->copies.$i;



                $print_job->type = $request->type;



                $print_job->status = 'Queued';



                $print_job->filename = $file;



                $print_job->total_pages = $total_pages;



                $res=$print_job->save();   


                array_push($printJobIds,$print_job->id);



                $this->updateTotalPages($print_job);



                $update_pages = PrintJob::where('id',$print_job->id)->first();



                // echo $update_pages;



                $price = $this->calculateTotals($print_job);



                $price = $price*$update_pages->total_pages;  



                $amountToPay+= $price;



                if($amountToPay<1){



                    $amountToPay=1;



                }



                



            }



                // dev code to save transaction pending 



           



                $transaction = new Transaction;



                $transaction->print_job_id = json_encode($printJobIds);



                $transaction->invoice_id = 0;

                $transaction->amount = $amountToPay;



                $transaction->currency = 'SAR';



                $transaction->type = $request->type;



                $transaction->status = 'Queued';



                $transaction->date=date('Y-m-d H:i:s');



                $transaction->created_at=date('Y-m-d H:i:s');



                $transaction->save();


            if($request->type =='online'){

                $update_transaction =Transaction::where('print_job_id',json_encode($printJobIds))->first();



                // print_r($update_transaction);exit;

                $this->updateTransaction(json_encode($printJobIds),$amountToPay);

                $payement = new PaymentController(); 



                // echo $amountToPay;exit;



                $trans = $payement->initiatePayment($amountToPay, $update_transaction->id,'english');

                

              



                $redirect_url = $trans['redirect_url'];



                $trans_ref = $trans['tran_ref'];



                $update_transaction->trans_id = $trans_ref;



                    $update_transaction->update();



                if( $redirect_url){



                    return redirect( $redirect_url);



                }else{



                    return redirect()->route('englishProcessing')->with('message', 'Your print has been submitted');



                }







            }else{



                $type =  'cash';



                $this->updateTransaction(json_encode($printJobIds),$amountToPay);



                $modalCode ='<div class="modal confirmModal  successModal" id="cashOrder" >



                                <div class="container" style="margin-top:50px;!important">



                                <div class="close" onclick="successModal()">



                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"



                                            fill="none">



                                            <path



                                                d="M5.82397 3.63201L12 9.80801L18.144 3.66401C18.2797 3.51956 18.4432 3.404 18.6246 3.32427C18.8061 3.24454 19.0018 3.20228 19.2 3.20001C19.6243 3.20001 20.0313 3.36858 20.3313 3.66864C20.6314 3.9687 20.8 4.37567 20.8 4.80001C20.8037 4.99618 20.7673 5.19103 20.693 5.37262C20.6187 5.55421 20.5081 5.71871 20.368 5.85601L14.144 12L20.368 18.224C20.6317 18.482 20.7863 18.8314 20.8 19.2C20.8 19.6244 20.6314 20.0313 20.3313 20.3314C20.0313 20.6314 19.6243 20.8 19.2 20.8C18.9961 20.8085 18.7926 20.7744 18.6026 20.7001C18.4125 20.6257 18.24 20.5126 18.096 20.368L12 14.192L5.83997 20.352C5.70478 20.4916 5.54327 20.6031 5.36477 20.68C5.18627 20.7569 4.99431 20.7977 4.79997 20.8C4.37562 20.8 3.96865 20.6314 3.6686 20.3314C3.36854 20.0313 3.19997 19.6244 3.19997 19.2C3.19624 19.0038 3.23263 18.809 3.30692 18.6274C3.38121 18.4458 3.49182 18.2813 3.63197 18.144L9.85597 12L3.63197 5.77601C3.36826 5.51803 3.21363 5.16867 3.19997 4.80001C3.19997 4.37567 3.36854 3.9687 3.6686 3.66864C3.96865 3.36858 4.37562 3.20001 4.79997 3.20001C5.18397 3.20481 5.55197 3.36001 5.82397 3.63201Z"



                                                fill="#00448E" />



                                        </svg>



                                </div> <img src="http://hlaprint.synet.com.pk/public/assets/img/lineIcon.png" alt="" style="width: 9.2rem; height: 9.2rem;">



                                        <p class="infoP">please visit the counter to pay and collect your print</p>



                                    <button onclick="successModal()" type="">Ok</button>



                            </div>';



                session()->put('modal_code', $modalCode);
                // // Redirect back
                // echo'here';exit;
                return redirect()->back();

                


            }



        }







        return 'No file was uploaded.';



    }



    public function calculateTotals($printJobId)



    {



        $printJob = PrintJob::where('id', $printJobId->id)->first();



        if(($printJob) && ($printJob->shop_id)){



            $color_amount = Color_size::where('shop_id', $printJob->shop_id)->first();



        }else{



            $color_amount = Color_size::where('shop_id', '0')->first();



       



        }




         if ($printJob->color == false && $printJob->page_sides == 'one') {



                



                $amount = $color_amount->black_and_white_amount + $color_amount->one_side;







            } elseif ($printJob->color == false && $printJob->page_sides == 'two') {







                $amount = $color_amount->black_and_white_amount + $color_amount->two_side;







            } elseif ($printJob->color == true && $printJob->page_sides == 'one') {



                



                $amount = $color_amount->black_and_white_amount + $color_amount->one_Side;







            }else {   



                $amount = $color_amount->color_page_amount + $color_amount->two_side;



            }



            if($printJob->copies =='0'){



                $printJob->copies=1;



            }



        $total = $printJob->copies  * $amount;



        return $total;



    }



    public function updateTransaction($printJobIds, $total_amount)



    {

        //   print_r($printJobIds);exit;

        $transactions =Transaction::where('print_job_id',$printJobIds)->get();


        if ($transactions->isEmpty()) {



            return 'Not transaction found';



        } else {



            foreach ($transactions as $trans) {


                $trans_print_job_id = $printJobIds;
                $trans->amount = $total_amount;
                $trans->status ='Queued';


                $trans_update = $trans->update();





                if ($trans_update) {



                    Invoice::where('trans_id', $trans->id)->delete();



                    $invoice = new Invoice();



                    $invoice->trans_id = $trans->id; // Assuming a user association



                    $invoice->invoice_number = 'INV-' . date('YmdHis');



                    $invoice->date = now();



                    $invoice->amount =$total_amount;







                    $invoice->save();







                    // Associate the transaction with the invoice



                    $trans->invoice_id = $invoice->id;

                    



                    $trans->save();



                } else {



                    return back()->with('No invoice generated');



                }



            }



        }



    }







}



