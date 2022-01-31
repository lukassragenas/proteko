<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        {{-- meta tags --}}
        {{-- <meta charset="UTF-8"> --}}
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <link rel="stylesheet" href="{{ bagisto_asset('css/bliss.css') }}">
        
         {{-- <link href="{{ public_path('css/pdblissf.css') }}" rel="stylesheet"> --}}
        <meta http-equiv="Cache-control" content="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


        <style type="text/css"> 
            body { font-family: DejaVu Sans, sans-serif; }
        </style>

        <style>
             table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            thead {
                text-align: left !important;
                border-bottom: 2px solid #000;
            }

            body {
                width: 100% !important;
            }

            .heading-last p {
                margin-right: -50px !important;
            }

            p {
                font-size: 9px;
            }

            .border {
                border: 2px solid #000 !important;
            }

            .border-y {
                border-top: 2px solid #000 !important;
                border-bottom: 2px solid #000 !important;
            }

            .m-0 {
                margin: 0 !important;
            }

            .p-0 {
                padding: 0 !important;
            }

            .text-center {
                text-align: center;
            }

            .width-50 {
                width: 50px;
            }
            .details-info {
                font-weight: 100 !important;
                font-size: 9px;
            }

            .table-2 {
                margin-top: 50px;
            }

            .product-info th, .font-pvm {
                text-transform: uppercase;
                font-size: 11px;
            }
            
            .sub-info, .footer-info {
                font-size: 9px !important;
            }

            .footer-info {
                margin-top: 50px;
            }

            .mt-50 {
                margin-top: 20px;
            }

        </style>
    </head>

    <body>

        
        <table class="table-auto" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th>         
                    <img class="logo" src="{{ Storage::url(core()->getConfigData('sales.invoice_setttings.invoice_slip_design.logo')) }}" width="150px"/>
                </th>
                 <th>
                     {{-- <p>{{ core()->getConfigData('sales.shipping.origin.store_name') ? core()->getConfigData('sales.shipping.origin.store_name') : '' }} {{ core()->getConfigData('sales.shipping.origin.address1') ? core()->getConfigData('sales.shipping.origin.address1') : '' }}<br>
                    {{ core()->getConfigData('sales.shipping.origin.zipcode') ? core()->getConfigData('sales.shipping.origin.zipcode') : '' }}
                    {{ core()->getConfigData('sales.shipping.origin.city') ? core()->getConfigData('sales.shipping.origin.city') : '' }}
                    {{ core()->getConfigData('sales.shipping.origin.country') ?  core()->country_name(core()->getConfigData('sales.shipping.origin.country')) : '' }}<br>
                    @if (core()->getConfigData('sales.shipping.origin.contact'))
                        {{ core()->getConfigData('sales.shipping.origin.contact') }}
                    @endif --}}
                    <p>UAB "PROTEKO" Pelesos g. 1<br>
                        LT-02161 Vilnius, Lietuva<br>
                        Tel.: +370 5 2311171
                    </p>

                    <p>Im. kodas: 124661662<br>
                        PVM mokėtojo kodas:<br>
                        LT246616610</p>
                </th>
                <th class="heading-last"><p>Luminor Bank AB<br>
                    A/S: LT824010042404001510<br>
                    Banko kodas: 40100</p>
                    <p>AB SEB bankas<br>
                        A/S: LT327044060001317364<br>
                        Banko kodas: 70440</p>
                    </th>
                    <th><img src="{{ asset('/storage/images/images/creditinfo-logo.png') }}" alt="" width="90px" style="margin-left: 30px;"></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong><p class="font-pvm">PVM SĄSKAITA FAKTŪRA<br>
                    SERIJA AGRM NR.: {{ $invoice->increment_id ?? $invoice->id }}</p></strong></td>
                <td class="border text-center"><strong><p class="font-pvm">Data<br>
                    <span class="value">{{ core()->formatDate($invoice->created_at, 'Y-m-d') }}</p></strong></span></td>
                <td class="border text-center"><strong><p class="font-pvm">Suma<br>
                    @foreach ($invoice->items as $item)
                        {!! core()->formatBasePrice($invoice->base_grand_total, true) !!}
                    @endforeach</strong></p>
                </td>
                <td class="border text-center"><strong><p class="font-pvm">Apmokėti iki<br>
                    {{ $invoice->created_at->format('Y-m-d') }}<br></p></strong>
                </td>
              </tr>
              <tr>
                <td><strong><p class="font-pvm">PIRKĖJAS:</strong></p>
                    <div class="details-info">
                    @if ($invoice->order->billing_address)
                        {{ $invoice->order->billing_address->company_name ?? '' }}<br>
                        {{ $invoice->order->billing_address->name }}<br>
                        Adr. {{ $invoice->order->billing_address->address1 }}
                        {{ $invoice->order->billing_address->postcode . ' ' .$invoice->order->billing_address->city }}
                        {{ $invoice->order->billing_address->state }}
                        {{ core()->country_name($invoice->order->billing_address->country) }}<br>
                        Tel: {{ $invoice->order->billing_address->phone }}<br>
                @endif
            </div>
                </td>
                <td>
                    <strong><p class="font-pvm">PRISTATYMO ADRESAS:</strong></p>
                    <div class="details-info">
                @if ($invoice->order->shipping_address)
                        {{ $invoice->order->shipping_address->address1 }}
                        {{ $invoice->order->shipping_address->postcode . ' ' . $invoice->order->shipping_address->city }}
                        {{ $invoice->order->shipping_address->state }}
                        {{ core()->country_name($invoice->order->shipping_address->country) }}
                @endif
            </div>
                </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>

          @php
            $total_weight = '';
          @endphp
          
          <table class="table-auto table-2" cellpadding="0" cellspacing="0">
            <thead>
              <tr class="product-info">
                <th class="border-y">         
                    Barkodas
                </th>
                 <th class="border-y">
                    Kodas
                </th>
                <th class="border-y"><p>Aprašymas</p>
                    </th>
                    <th class="border-y">Matas</th>
                    <th class="border-y">Kiekis</th>
                    <th class="border-y">Kaina</th>
                    <th class="border-y">Suma</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                    @foreach ($invoice->items as $item)
                    <td class="border-y"></td>
                    <td class="border-y"><p>{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</p></td>
                    <td class="border-y"><p>{{ $item->name }}</p></td>
                    <td class="border-y"><p>VNT</p></td>
                    <td class="border-y"><p>{{ $item->qty }}</td>
                    <td class="border-y"><p>{!! core()->formatBasePrice($item->base_price, true) !!}</p></td>
                    <td class="border-y"><p>{!! core()->formatBasePrice($item->base_total, true) !!}</p></td>
                <td></td>
                <td></td>
               
                @php
          
                $product_id_item = $item->getTypeInstance()->getOrderedItem($item)->sku;
                $product_weight = DB::table('product_flat')->where('sku', $product_id_item)->first()->weight;
                $product_weight = number_format($product_weight, 3, '.', '');
                // $product_weight = strtok(DB::table('product_flat')->where('sku', $product_id_item)->first()->weight, '.');
                $total_weight = 1 + $product_weight;
                @endphp
                @endforeach
              </tr>
             
              
              <tr>
                  <td><strong><p class="font-pvm">Svoris:</p></strong><span style="font-size: 10px;">{{$product_weight}} kg </span></td>
                @foreach ($invoice->items as $item)
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="sub-info"><strong><p class="font-pvm">VISO BE PVM:<br>PVM 21 %:<br>VISO SU PVM:<br>Apmokėti iki:</p></strong></span></td>
                <td><strong><p class="font-pvm"><span class="sub-info">{!! core()->formatBasePrice($item->base_total, true) !!}</span><br>
                    <span class="sub-info">{!! core()->formatBasePrice($invoice->base_tax_amount, true) !!}</span><br>
                    <span class="sub-info">{!! core()->formatBasePrice($item->base_total + $item->base_tax_amount, true) !!}</span><br>
                    @php
                        $total_price = $item->base_total + $item->base_tax_amount;
                        $total_price_formatted =  strtok($total_price, '.');
                        $total_price_cent = substr(core()->formatBasePrice($item->base_total + $item->base_tax_amount, true), -2);
                    @endphp
                    <span class="sub-info">{{ $invoice->created_at->format('Y-m-d') }}</span></strong></p>

                   

                </td>
            <td></td>
            <td></td>
            @endforeach
          </tr>
            </tbody>
          </table>

          <div class="footer-info">

<?php

function skaicius_zodziais( $skaicius ) {
	
	// neskaiciuosim neigiamu ir itin dideliu skaiciu (iki milijardu)
	if ( $skaicius < 0 || strlen( $skaicius ) > 9 ) return;
	
	if ( $skaicius == 0 ) return 'nulis';

	$vienetai = array( '', 'vienas', 'du', 'trys', 'keturi', 'penki', 'šeši', 'septyni', 'aštuoni', 'devyni' );

	$niolikai = array( '', 'vienuolika', 'dvylika', 'trylika', 'keturiolika', 'penkiolika', 'šešiolika', 'septyniolika', 'aštuoniolika', 'devyniolika' );

	$desimtys = array( '', 'dešimt', 'dvidešimt', 'trisdešimt', 'keturiasdešimt', 'penkiasdešimt', 'šešiasdešimt', 'septyniasdešimt', 'aštuoniasdešimt', 'devyniasdešimt' );

	$pavadinimas = array(
		array( 'milijonas', 'milijonai', 'milijonų' ),
		array( 'tūkstantis', 'tūkstančiai', 'tūkstančių' ),
	);

	$skaicius = sprintf( '%09d', $skaicius ); // iki milijardu 10^9 (milijardu neskaiciuosim)
	$skaicius = str_split( $skaicius, 3 ); // kertam kas tris simbolius

	$zodziais = array();

	foreach ( $skaicius as $i => $tripletas ) {

		// resetinam linksni
		$linksnis = 0;

		// pridedam simtu pavadinima, jei pirmas tripleto skaitmuo > 0
		if ( $tripletas[0] > 0 ) {
			$zodziais[] = $vienetai[ $tripletas[0] ];
			$zodziais[] = ( $tripletas[0] > 1 ) ? 'šimtai' : 'šimtas';
		}

		// du paskutiniai tripleto skaiciai
		$du = substr( $tripletas, 1 );

		// pacekinam nioliktus skaicius
		if ( $du > 10 && $du < 20 ) { 
			$zodziais[] = $niolikai[ $du[1] ];
			$linksnis = 2;
		} else {

			// pacekinam desimtis
			if ( $du[0] > 0 ) {
				$zodziais[] = $desimtys[ $du[0] ];
			}

			// pridedam vienetus
			if ( $du[1] > 0 ) {
				$zodziais[] = $vienetai[ $du[1] ];
				$linksnis = ( $du[1] > 1 ) ? 1 : 0;
			} else {
				$linksnis = 2;
			}

		}

		// pridedam pavadinima isskyrus paskutiniam ir nuliniams tripletams
		if ( $i < count( $pavadinimas ) && $tripletas != '000' ) {
			$zodziais[] = $pavadinimas[ $i ][ $linksnis ];
		}

	}

	return implode( ' ', $zodziais );
}


function valiutos_galune( $number, $saknis = 'eur' ) {
	
	if ( $number < 0 || strlen( $number ) > 9 ) return;

	if ( $number == 0 ) {
		return $saknis . 'ų';
	}

	$last = substr( $number, -1 );
	$du = substr( $number, -2, 2 );

	if ( ($du > 10) && ($du < 20) ) {
		return $saknis . 'ų';
	} else {
		if ( $last == 0 ) {
			return $saknis . 'ų';
		} elseif ( $last == 1 ) {
			return $saknis . 'as';
		} else {
			return $saknis . 'ai';
		}
	}

}
$final_price = skaicius_zodziais($total_price_formatted);
?>
          <div class="mt-50">
           <p> <strong>SUMA ŽODŽIAIS: </strong>
             {{$final_price}} EUR 
                @if ($total_price_cent > 0)
                    {{$total_price_cent}} ct
                @endif </p>
          </div>

          <div class="mt-50">
            <i>Informuojame Jus, kad UAB „Proteko” teikia informaciją apie Jūsų finansinius įsipareigojimus ir jų vykdymą Kreditų biurui</i> <img src="{{ asset('/storage/images/images/creditinfo-logo-2.png') }}" width="100px" style="margin-top: 25px; margin-left: 10px;" alt="">
          </div>

            <div class="mt-50">
                Išrašė:&nbsp; &nbsp; &nbsp; <span style="border-bottom: 1px dotted #000;">El. parduotuvės administratorė Viktorija Gladkaja</span><span style="margin-left: 150px">Gavo:&nbsp; &nbsp; &nbsp; <span style="border-bottom: 1px dotted #000;">  {{ $invoice->order->billing_address->name }}</span></span>
            <div style="margin-left: 90px; margin-top: -2px;"><span style="font-size:7px !important">(pareigos, vardas, pavardė, parašas)</span></div>
           
        
            <div style="margin-left: 500px; margin-top: -5px;"><span style="font-size:7px !important">(pareigos, vardas, pavardė, parašas)</span></div>
        </div>


            <div class="mt-50">
                Prekes išdavė: &nbsp; &nbsp; &nbsp; ..................................................................................................
                <div style="margin-left: 150px; margin-top: -2px;"><span style="font-size:7px !important">(pareigos, vardas, pavardė, parašas)</span></div>

            </div>


          

        </div>


        {{-- <div class="header">
            <div class="" style="display: flex; position:relative;justify-content: space-evenly;">
                <div>
                    <div class="merchant-details">
                        @if (core()->getConfigData('sales.invoice_setttings.invoice_slip_design.logo'))
                    <div class="image">
                        <img class="logo" src="{{ Storage::url(core()->getConfigData('sales.invoice_setttings.invoice_slip_design.logo')) }}"/>
                    </div>
                @endif
                    </div>
                </div>
                
                <div class="merchant-details">
                    <div><span class="text-sm merchant-details-title">{{ core()->getConfigData('sales.shipping.origin.store_name') ? core()->getConfigData('sales.shipping.origin.store_name') : '' }}</span></div>
                    <div>{{ core()->getConfigData('sales.shipping.origin.address1') ? core()->getConfigData('sales.shipping.origin.address1') : '' }}</div>
                    <div>
                        <span>{{ core()->getConfigData('sales.shipping.origin.zipcode') ? core()->getConfigData('sales.shipping.origin.zipcode') : '' }}</span>
                        <span>{{ core()->getConfigData('sales.shipping.origin.city') ? core()->getConfigData('sales.shipping.origin.city') : '' }}</span></div>
                    <div>{{ core()->getConfigData('sales.shipping.origin.state') ? core()->getConfigData('sales.shipping.origin.state') : '' }}</div>
                    <div>{{ core()->getConfigData('sales.shipping.origin.country') ?  core()->country_name(core()->getConfigData('sales.shipping.origin.country')) : '' }}</div>
                </div>
                <div class="merchant-details">
                    @if (core()->getConfigData('sales.shipping.origin.contact'))
                        <div><span class="merchant-details-title">{{ __('admin::app.admin.system.contact-number') }}:</span> {{ core()->getConfigData('sales.shipping.origin.contact') }}</div>
                    @endif
                    @if (core()->getConfigData('sales.shipping.origin.vat_number'))
                        <div><span class="merchant-details-title">{{ __('admin::app.admin.system.vat-number') }}:</span> {{ core()->getConfigData('sales.shipping.origin.vat_number') }}</div>
                    @endif
                    @if (core()->getConfigData('sales.shipping.origin.bank_details'))
                        <div><span class="merchant-details-title">{{ __('admin::app.admin.system.bank-details') }}:</span> {{ core()->getConfigData('sales.shipping.origin.bank_details') }}</div>
                    @endif
                </div>
            </div>

            <div class="invoice-summary">
                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.invoice-id') }} -</span>
                    <span class="value">#{{ $invoice->increment_id ?? $invoice->id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.date') }} -</span>
                    <span class="value">{{ core()->formatDate($invoice->created_at, 'd-m-Y') }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-id') }} -</span>
                    <span class="value">#{{ $invoice->order->increment_id }}</span>
                </div>

                <div class="row">
                    <span class="label">{{ __('admin::app.sales.invoices.order-date') }} -</span>
                    <span class="value">{{ $invoice->created_at->format('d-m-Y') }}</span>
                </div>

                @if ($invoice->hasPaymentTerm())
                    <div class="row">
                        <span class="label">{{ __('admin::app.admin.system.payment-terms') }} -</span>
                        <span class="value">{{ $invoice->getFormattedPaymentTerm() }}</span>
                    </div>
                @endif

                <div class="table address">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ __('admin::app.sales.invoices.bill-to') }}</th>
                                @if ($invoice->order->shipping_address)
                                    <th>{{ __('admin::app.sales.invoices.ship-to') }}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @if ($invoice->order->billing_address)
                                    <td>
                                        <p>{{ $invoice->order->billing_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->billing_address->name }}</p>
                                        <p>{{ $invoice->order->billing_address->address1 }}</p>
                                        <p>{{ $invoice->order->billing_address->postcode . ' ' .$invoice->order->billing_address->city }} </p>
                                        <p>{{ $invoice->order->billing_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->billing_address->country) }}</p>
                                        {{ __('shop::app.checkout.onepage.contact') }} : {{ $invoice->order->billing_address->phone }}
                                    </td>
                                @endif

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        <p>{{ $invoice->order->shipping_address->company_name ?? '' }}</p>
                                        <p>{{ $invoice->order->shipping_address->name }}</p>
                                        <p>{{ $invoice->order->shipping_address->address1 }}</p>
                                        <p>{{ $invoice->order->shipping_address->postcode . ' ' . $invoice->order->shipping_address->city }}</p>
                                        <p>{{ $invoice->order->shipping_address->state }}</p>
                                        <p>{{ core()->country_name($invoice->order->shipping_address->country) }}</p>
                                        {{ __('shop::app.checkout.onepage.contact') }} : {{ $invoice->order->shipping_address->phone }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table payment-shipment">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ __('admin::app.sales.orders.payment-method') }}</th>

                                @if ($invoice->order->shipping_address)
                                    <th>{{ __('admin::app.sales.orders.shipping-method') }}</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    {{ core()->getConfigData('sales.paymentmethods.' . $invoice->order->payment->method . '.title') }}

                                    @php $additionalDetails = \Webkul\Payment\Payment::getAdditionalDetails($invoice->order->payment->method); @endphp

                                    @if (! empty($additionalDetails))
                                        <div>
                                            <label class="label">{{ $additionalDetails['title'] }}:</label>
                                            <p class="value">{{ $additionalDetails['value'] }}</p>
                                        </div>
                                    @endif
                                </td>

                                @if ($invoice->order->shipping_address)
                                    <td>
                                        {{ $invoice->order->shipping_title }}
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table items">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('admin::app.sales.orders.SKU') }}</th>
                                <th>{{ __('admin::app.sales.orders.product-name') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.price') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.qty') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.subtotal') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.tax-amount') }}</th>
                                <th class="text-center">{{ __('admin::app.sales.orders.grand-total') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td>{{ $item->getTypeInstance()->getOrderedItem($item)->sku }}</td>
                                    <td>
                                        {{ $item->name }}

                                        @if (isset($item->additional['attributes']))
                                            <div class="item-options">

                                                @foreach ($item->additional['attributes'] as $attribute)
                                                    <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                                @endforeach

                                            </div>
                                        @endif
                                    </td>
                                    <td>{!! core()->formatBasePrice($item->base_price, true) !!}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total, true) !!}</td>
                                    <td class="text-center">{!! core()->formatBasePrice($item->base_tax_amount, true) !!}</td>
                                    <td class="text-center">{!! core()->formatBasePrice($item->base_total + $item->base_tax_amount, true) !!}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


                <table class="sale-summary">
                    <tr>
                        <td>{{ __('admin::app.sales.orders.subtotal') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_sub_total, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.shipping-handling') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_shipping_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.tax') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_tax_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td>{{ __('admin::app.sales.orders.discount') }}</td>
                        <td>-</td>
                        <td>{!! core()->formatBasePrice($invoice->base_discount_amount, true) !!}</td>
                    </tr>

                    <tr>
                        <td><strong>{{ __('admin::app.sales.orders.grand-total') }}</strong></td>
                        <td><strong>-</strong></td>
                        <td><strong>{!! core()->formatBasePrice($invoice->base_grand_total, true) !!}</strong></td>
                    </tr>
                </table>

            </div>
        </div> --}}
    </body>
</html>
