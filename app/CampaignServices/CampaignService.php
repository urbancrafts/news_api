<?php

namespace App\CampaignServices;

use Validator;
use App\Models\Campaign;
use App\Models\CampaignBanner;
use App\CampaignServices\DateHandler;
use Illuminate\Support\Facades\Storage;

class CampaignService {

    

    public function __construct(Campaign $campaign, DateHandler $date){
       $this->campaign = $campaign;
       $this->date = $date;
    }


public function createCampaign($request){

    $validator = Validator::make($request->all(), ['name' => 'required|string',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'daily_budget' => 'required',
        'banners' => 'array',
        'banners.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:12288',
    ]);
    if ($validator->fails()) {
        $errors = array();
        $validationErrors = json_decode(json_encode($validator->errors()), true);
        foreach ($validationErrors as $key => $error) {
            $errors[] = $error[0];
        }

        return response()->json(['status' => 'Error', 'message' => implode(',', $errors)], 200);
    }

    
    //count days between from_date to to_date
    $count_days = $this->date->time_diff($request->from_date, $request->to_date)->days;

    $sum = $request->daily_budget * ($count_days + 1);

    $create_campaign = $this->campaign->create([
        'name' => $request->name,
        'from_date' => $request->from_date,
        'to_date' => $request->to_date,
        'daily_budget' => $request->daily_budget,
        'total_budget' => $sum
    ]);

    
     

    if($request->hasFile('banners')){
            
            
            foreach($request->file('banners') as $file){
          
         //get filename with the extension
            $fileNameWithExt = $file->getClientOriginalName();
            //get just filename
           $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $file->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = str_replace(' ', '_', $filename).'_'.time().'.'.$extension;
            
            $path = $file->storeAs('public/campaign_img', $fileNameToStore);
        
            
            $dir = asset('storage/campaign_img');
            

            //  $getServerImg3[] = $dir3.'/'.$fileNameToStore_3;
             //array_push($getServerImg3, $file);
            // $create_product->product_images()->create([
            // 'image_url' => $getServerImg3
            // ]);
        
            $create_campaign->banner()->create([
                'img_url' => $dir.'/'.$fileNameToStore
            ]);
        
         }
            }else{
            $fileNameToStore = "";
            }

    return response()->json(['status' => 'ok', 'message' => 'Created successful', 'data' => $create_campaign], 200);
}


public function updateCampaign($request, $id){

    $validator = Validator::make($request->all(), ['name' => 'required|string',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'daily_budget' => 'required',
    ]);
    if ($validator->fails()) {
        $errors = array();
        $validationErrors = json_decode(json_encode($validator->errors()), true);
        foreach ($validationErrors as $key => $error) {
            $errors[] = $error[0];
        }

        return response()->json(['status' => 'Error', 'message' => implode(',', $errors)], 200);
    }


    $select_campaign = $this->campaign->whereId($id);
    
    //count days between from_date to to_date
    $count_days = $this->date->time_diff($request->from_date, $request->to_date)->days;

    $sum = $request->daily_budget * ($count_days + 1);

    $update_campaign = $select_campaign->update([
        'name' => $request->name,
        'from_date' => $request->from_date,
        'to_date' => $request->to_date,
        'daily_budget' => $request->daily_budget,
        'total_budget' => $sum
    ]);

    

    return response()->json(['status' => 'ok', 'message' => 'Updated successful', 'data' => $select_campaign->first()], 200);
}

public function fetchCampaignData(){
$data = $this->campaign->orderBy('updated_at', 'DESC')->whereCurrency('USD')->with('banner')->paginate(10);
return response()->json(['status' => 'ok', 'message' => 'Fetched successful', 'data' => $this->output($data)], 200);
}

public function fetchCampaignSignleData($data_id){
    $data = $this->campaign->whereId($data_id)->first();
    return response()->json(['status' => 'ok', 'message' => 'Fetched successful', 'data' => $this->outPutSingle($data)], 200);
    }


private function output($data)
    {
        $items = [];
        foreach ($data as $item) {
            
            $items[] = [
                'id' => $item->id,
                'name' => $item->name,
                'fromDate' => $item->from_date,
                'toDate' => $item->to_date,
                'currency' => $item->currency,
                'dailyBudget' => $item->daily_budget,
                'totalBudget' => $item->total_budget,
                'createdAt' => $item->updated_at,
                'imageBanners' => (!count($item->banner) > 0 ) ? null : $this->bannerOut($item->banner)
            ];
        }
        return $items;
    }


    private function outPutSingle($item)
    {
        
        $items = [];

            if($item){
            $items[] = [
                'id' => $item->id,
                'name' => $item->name,
                'fromDate' => $item->from_date,
                'toDate' => $item->to_date,
                'currency' => $item->currency,
                'dailyBudget' => $item->daily_budget,
                'totalBudget' => $item->total_budget,
                'createdAt' => $item->updated_at,
                'imageBanners' => (count($item->banner()->get()) > 0 ) ? $this->bannerOut($item->banner()->get()) : null
            ];
            return $items;
        }else{
            return $items;
        }
        
        
    }


public function bannerOut($data){
    $items = [];
     if(count($data) > 0){
    foreach ($data as $item) {
        
        $items[] = [
            'id' => $item->id,
            'imageUrl' => $item->img_url
        ];
    }
}
    return $items;
}    




}
