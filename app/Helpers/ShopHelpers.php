<?php

use App\Models\Shops;
use App\Models\PriceOptions;
use App\Models\PdfFilesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ShopOption;
use App\Models\PrintJob;

if (!function_exists('getShopOptionIdByShopId')) {
    /**
     * Get the ShopOption ID based on the shop ID.
     *
     * @param  int  $shopId
     * @return int
     */
    function getShopOptionIdByShopId($shopId)
    {
        $shopOption = ShopOption::where('shop_id', $shopId)->first();
        return $shopOption ? $shopOption->id : 0;
    }
}


if (!function_exists('getPagesPrinted')) {
    /**
     * Get the Number of Pages Printed by a shop in current month.
     *
     * @param  int  $shopId
     * @return int
     */
    function getPagesPrinted($shopId)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $printJobs = PrintJob::where('shop_id', $shopId)
                            ->whereMonth('created_at', $currentMonth)
                            ->whereYear('created_at', $currentYear)
                            ->get();

        $totalPagesPrinted = 0;
        foreach($printJobs as $printJob)
        {
            if( ($printJob->pages_start == 1 && $printJob->page_end == 1) && $printJob->total_pages == 1 )
            {
                $totalPagesPrinted += 1;
            }
            else {
                $totalPagesPrinted += $printJob->total_pages;
            }
        }

        return $totalPagesPrinted;
    }
}


if (!function_exists('getThumbnail')) {
    function getThumbnail($printJob_id) {
        // Retrive Thumbnail by ID
       $thumbnail = DB::table('imgs_files')->where('printjob_id', $printJob_id)->first();
        if ($thumbnail) {
            return $thumbnail->filePath; // return the Thumbnail Path
        }

        return null; // Null if no Thumbnail found
    }
}




if (!function_exists('getShopContact')) {
    function getShopContact($shop_id) {
        // Retrive Thumbnail by ID
       $shop = Shops::where('id',$shop_id)->first();
       if($shop)
       {
        return $shop->contact;
       }

        return null; // Null if no Thumbnail found
    }
}

if (!function_exists('invoiceGetPriceOptions')) {
    function invoiceGetPriceOptions($shop_id, $key) {
        $variation = explode("_",$key);
        $pricing = PriceOptions::where('page_size',$variation[0])
                             ->where('color_type',$variation[1])
                             ->where('sidedness',$variation[2])
                             ->where('shop_id', $shop_id)
                             ->first();

        if ($pricing) {
            return $pricing; // return the Thumbnail Path
        }

        return "-"; // Null if no Thumbnail found
    }
}

if (!function_exists('invoiceCalculateSubtotals')) {
    function invoiceCalculateSubtotals($pages, $interval, $price) {
        $count = 0;
        while($pages > 0)
        {
            $pages -= $interval;
            $count++;
        }

        return $count*$price;
    }
}

if (!function_exists('GetShopStatus')) {
    function GetShopStatus($shopId) {
        // Retrieve the shop by ID
        $shop = Shops::find($shopId);

        if ($shop) {
            return $shop->online; // Assuming 'status' is the field in your 'shops' table that stores the shop's status
        }

        return null; // Return null if the shop is not found
    }
}

if (!function_exists('ConvertDate')) {
    function ConvertDate($originalDate) {



// Create a Carbon instance from the original date (assuming it's in your application's timezone)
$carbonDate = Carbon::parse($originalDate);

// Convert the Carbon date to GMT+3 timezone
$carbonDate->setTimezone('Europe/Istanbul'); // Adjust to the appropriate timezone

// Format the date as needed (e.g., in 'Y-m-d H:i:s' format)
$formattedDate = $carbonDate->format('Y-m-d H:i:s');

// $formattedDate now contains the date and time in GMT+3 timezone

return $formattedDate;

    }
}

if (!function_exists('GetFileName')) {
    function GetFileName($printJobId) {
        // Retrieve the file by ID
        $pdf = PdfFilesModel::where('printjob_id', $printJobId)->first();

        if($pdf)
        {
            return $pdf->fileName;
        }
        else
        {
            return null;
        }
    }
}

if (!function_exists('getCurrentUsersShopId')) {
    function getCurrentUsersShopId() {
        // Retrieve the users shop ID
        return Auth::user()->shop_id;
    }
}
