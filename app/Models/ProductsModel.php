<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{

    public $data;
    public function __construct($products)
    {
        $this->data = $this->products($products);
    }

    public function products($products)
    {
        return collect($products)->map(function ($product) {
            return collect($product)->merge([
                'provider' => 'ebay',
                'item_id' => $product['itemId'][0],
                'click_out_link' => $product['viewItemURL'][0],
                'main_photo_url' => $this->getPhotoUrl($product),
                'price' => (isset($product['sellingStatus'][0]['currentPrice'][0]['__value__']) ? $product['sellingStatus'][0]['currentPrice'][0]['__value__'] : "N/A"),
                'price_currency' => (isset($product['sellingStatus'][0]['currentPrice'][0]['@currencyId']) ? $product['sellingStatus'][0]['currentPrice'][0]['@currencyId'] : ""),
                'shipping_price' => $this->shipmentInfo($product),
                'title' => $product['title'][0],
                'valid_until' => (isset($product['listingInfo'][0]['endTime'][0]) ? Carbon::parse($product['listingInfo'][0]['endTime'][0])->format('M d, Y') : '"N/A"'),
            ])->only([
                'provider', 'item_id', 'click_out_link',  'price', 'price_currency', 'shipping_price', 'title', 'main_photo_url', 'valid_until'

            ])->toArray();
        });
    }
    public function getPhotoUrl($product)
    {
        if (isset($product['pictureURLLarge'][0])) {
            $response_iter = $product['pictureURLLarge'][0];
        } else if (isset($product['galleryURL'][0])) {
            $response_iter = $product['galleryURL'][0];
        } else {
            $response_iter = '';
        }
        return $response_iter;
    }

    public function shipmentInfo($product)
    {
        $shippingInfo =  isset($product['shippingInfo'][0]['shippingServiceCost']) ? $product['shippingInfo'][0]['shippingServiceCost'][0]['__value__'] : null;
        if (!isset($shippingInfo)) {
            $response_iter = "N/A";
        } else if ($shippingInfo == "0.0") {
            $response_iter = "Free";
        } else {
            $response_iter = $shippingInfo;
        }
        return $response_iter;
    }
}
