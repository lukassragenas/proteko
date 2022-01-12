<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\Address;
use Webkul\Core\Models\CoreConfig;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Models\ShipmentItem;

/**
 * Class Shipping.
 *
 */
class Shipping
{
    /**
     * Rates
     *
     * @var array
     */
    protected $rates = [];

    /**
     * Collects rate from available shipping methods
     *
     * @return array
     */
    public function collectRates()
    {
        if (! $cart = Cart::getCart()) {
            return false;
        }

        $this->removeAllShippingRates();

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if ($rates = $object->calculate()) {
                if (is_array($rates)) {
                    $this->rates = array_merge($this->rates, $rates);
                } else {
                    $this->rates[] = $rates;
                }
            }
        }

        $this->saveAllShippingRates();

        return [
            'jump_to_section' => 'shipping',
            'shippingMethods' => $this->getGroupedAllShippingRates(),
            'html'            => view('shop::checkout.onepage.shipping', ['shippingRateGroups' => $this->getGroupedAllShippingRates()])->render(),
        ];
    }

    /**
     * Persist shipping rate to database
     *
     * @return void
     */
    public function removeAllShippingRates()
    {
        if (! $cart = Cart::getCart()) {
            return;
        }

        foreach ($cart->shipping_rates()->get() as $rate) {
            $rate->delete();
        }
    }

    /**
     * Persist shipping rate to database
     *
     * @return void
     */
    public function saveAllShippingRates()
    {
        if (! $cart = Cart::getCart()) {
            return;
        }

        $shippingAddress = $cart->shipping_address;

        if ($shippingAddress) {

            foreach ($this->rates as $rate) {
                $rate->cart_address_id = $shippingAddress->id;

                $rate->save();
            }
        }
    }

    /**
     * Returns shipping rates, grouped by shipping method
     *
     * @return void
     */
    public function getGroupedAllShippingRates()
    {
        $rates = [];

        foreach ($this->rates as $rate) {
            if (! isset($rates[$rate->carrier])) {
                $rates[$rate->carrier] = [
                    'carrier_title' => $rate->carrier_title,
                    'rates'         => []
                ];
            }

            $rates[$rate->carrier]['rates'][] = $rate;
        }

        return $rates;
    }

    /**
     * Returns active shipping methods
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $methods = [];

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if (! $object->isAvailable()) {
                continue;
            }

            $methods[] = [
                'method'       => $object->getCode(),
                'method_title' => $object->getTitle(),
                'description'  => $object->getDescription()
            ];
        }

        return $methods;
    }
    public function bookDpdShipment($order)
    {
        app()->dpdShipment;
        app()->dpdShipment->setPredict([
            'channel' => 'email',
            'value' => $order->customer_email,
            'language' => 'EN'
        ]);
        app()->dpdShipment->setGeneralShipmentData([
            'product' => 'CL',
            'mpsCustomerReferenceNumber1' => $order->id
        ]);
        app()->dpdShipment->setSender([
            'name1' => 'Bagisto',
            'street' => CoreConfig::where('code','sales.shipping.origin.contact')->first()->value,
            'country' => CoreConfig::where('code','sales.shipping.origin.country')->first()->value,
            'zipCode' => CoreConfig::where('code','sales.shipping.origin.zipcode')->first()->value,
            'city' => CoreConfig::where('code','sales.shipping.origin.city')->first()->value,
            'email' => 'contact@bagiston.com',
            'phone' => CoreConfig::where('code','sales.shipping.origin.contact')->first()->value,
        ]);
        $to = Address::where('order_id',$order->id)->first();
        app()->dpdShipment->setReceiver([
            'name1' => $order->customer_first_name,
            'name2' => $order->customer_last_name,
            'street' => $to->address1,
            'houseNo' => null,
            'zipCode' => $to->postcode,
            'city' => $to->city,
            'country' => $to->country,
            'contact' => null,
            'phone' => $to->phone,
            'email' => $to->email,
            'comment' => null
        ]);
        $total_qty = 0;
        $total_weight = 0;
        $shipment = new Shipment();
        $shipment->total_qty = $total_qty;
        $shipment->total_weight = $total_weight;
        $shipment->order_id = $order->id;
        $shipment->order_address_id = $to->id;
        $shipment->save();
        foreach($order->items as $item)
        {
            app()->dpdShipment->addParcel([
                'weight' => $item->total_weight,
                'height' => 0, // In centimeters
                'width' => 0,
                'length' => 0 // All parameters need to be given. Enter 0 if you have no value
            ]);
            $total_weight += $item->total_weight;
            $shipment_item = new ShipmentItem();
            $shipment_item->name = $item->name;
            $shipment_item->sku = $item->sku;
            $shipment_item->weight = $item->weight;
            $shipment_item->product_id = $item->product_id;
            $shipment_item->shipment_id = $shipment->id;
            $shipment_item->save();
        }
        app()->dpdShipment->submit();
        $trackinglinks = app()->dpdShipment->getParcelResponses();
        $shipment->tracking_number = json_encode($trackinglinks);
        $shipment->save();
        // header('Content-Type: application/pdf');
        // echo app()->dpdShipment->getLabels();
        return true;
    }
}