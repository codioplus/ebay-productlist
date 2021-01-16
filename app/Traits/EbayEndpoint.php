<?php

namespace App\Traits;

use App\Models\ProductsModel;
use Illuminate\Support\Facades\Http;

trait EbayEndpoint
{
    public function findItemsAdvanced($params)
    {
        $main_url = $this->ebayfindItemsAdvancedApiUrl();
        $url = $this->addParams($params, $main_url);
        $response = Http::get(rtrim($url, "&"))->json();
        if ($response['findItemsAdvancedResponse'][0]['ack'][0] == "Failure") {
            return ['error' => 'Api Error'];
        }

        if ($response['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'] == "0") {
            return ['error' => 'no products'];
        }

        $data = new ProductsModel($response['findItemsAdvancedResponse'][0]['searchResult'][0]['item']);


        $result_api = $data->data;
        return  $result_api->toArray();
    }

    public function ebayfindItemsAdvancedApiUrl()
    {
        $url = config('app.ebay_endpoint') . "?";
        $url = $url . "OPERATION-NAME=findItemsAdvanced&";
        $url = $url . "SERVICE-VERSION=1.13.0&";
        $url = $url . "SECURITY-APPNAME=" . config('app.ebay_app_id') . "&";
        $url = $url . "RESPONSE-DATA-FORMAT=JSON&";
        $url = $url . "REST-PAYLOAD&";
        $url = $url . "outputSelector(0)=PictureURLLarge&";
        $url = $url . "outputSelector(1)=PictureURLSuperSize&";
        $url = $url . "outputSelector(2)=GalleryInfo&";
        $url = $url . "paginationInput.entriesPerPage=21&";
        return $url;
    }

    public function addParams($params, $url)
    {
        $itemFilters = ['MaxPrice', "MinPrice"];
        $count = 0;

        foreach ($params as $key => $value) {
            if (!in_array($key, $itemFilters) && $value) {
                $url = $url . str_replace("_", ".", $key) . "=" . urlencode($value) . "&";
            }

            if (in_array($key, $itemFilters) && $value) {
                $url = $url . "itemFilter($count).name=" . $key . "&";
                $url = $url . "itemFilter($count).value=" . $value . "&";
                $count++;
            }
        }


        return $url;
    }
}
