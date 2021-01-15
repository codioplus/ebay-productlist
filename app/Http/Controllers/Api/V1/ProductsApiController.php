<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\EbayEndpoint;
use Illuminate\Http\Request;

/**
 * @group Front page
 *
 * APIs for front page
 */
class ProductsApiController extends Controller
{
    use EbayEndpoint;
    public function index(Request $request)
    {
        $keywords =  $request->get('keywords');
        $price_max = $request->get('price_max');
        $price_min = $request->get('price_min');
        $sorting = $request->get('sorting');

        if (!$keywords) {
            return ['error' => 'no keywords'];
        }

        $request = array(
            'params' =>
            array(
                'keywords' => urlencode($keywords),
                'MaxPrice' => $price_max,
                'MinPrice' => $price_min,
                'sort' => $sorting,
            ),
        );
        $result =  $this->findItemsAdvanced($request['params']);
        if (isset($result["error"])) {
            return $result;
        }
        $return = $this->sortResults($result, $request['params']['sort'], 'by_price_asc');
        return $return;
    }

    public function search(Request $request)
    {
        $result =  $this->findItemsAdvanced($request['params']);
        if (isset($result["error"])) {
            return $result;
        }
        $return = $this->sortResults($result, $request['params']['sort'], 'Price Lowest First');
        return $return;
    }


    public function sortResults($data, $req, $sort)
    {
        if ($req == $sort) {
            uasort($data, function ($item, $compare) {
                return $item['price'] >= $compare['price'];
            });
            return array_values($data);
        }
        return $data;
    }
}
