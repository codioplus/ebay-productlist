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
        $req = $request->all();
        if (!$this->validateParams($req)) {
            return ['error' => 'error check params'];
        }
        $params = $this->designParams($req);
        $result =  $this->findItemsAdvanced($params);
        if (isset($result["error"])) {
            return $result;
        }

        return $result;
    }

    public function search(Request $request)
    {
        $req = $request['params'];
        if (!$this->validateParams($req)) {
            return ['error' => 'error check params'];
        }
        $params = $this->designParams($req);
        $result =  $this->findItemsAdvanced($params);
        if (isset($result["error"])) {
            return $result;
        }

        return $result;
    }

    public function designParams($params)
    {
        $newParams = [];
        foreach ($params as $key => $param) {

            if (empty($param)) continue;

            $paramExplode = explode('_', $key);
            if (count($paramExplode) == 2) {
                $paramKey = ucfirst($paramExplode[1]) . ucfirst($paramExplode[0]);
                $paramValue = $param;
            } else if (($key == 'sort') || ($key == 'sorting')) {
                if (($param == 'Price Lowest First') || ($param == 'by_price_asc')) {
                    $paramKey = 'sortOrder';
                    $paramValue = 'PricePlusShippingLowest';
                }
            } else {
                $paramKey = $key;
                $paramValue = $param;
            }

            $newParams[$paramKey] = $paramValue;
        }
        return $newParams;
    }

    public function validateParams($request)
    {
        if (!isset($request['keywords'])) {
            return false;
        }
        return true;
    }
}
