<?php

use Illuminate\Support\Facades\Route;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $client = new Party([
        'name'          => 'Roosevelt Lloyd',
        'phone'         => '(520) 318-9486',
        'custom_fields' => [
            'note'        => 'IDDQD',
            'business id' => '365#GG',
        ],
    ]);

    $customer = new Party([
        'name'          => 'Ashley Medina',
        'address'       => 'The Green Street 12',
        'code'          => '#22663214',
        'custom_fields' => [
            'order number' => '> 654321 <',
        ],
    ]);

    $items = [
        InvoiceItem::make('Service 2')->pricePerUnit(71.96),
        InvoiceItem::make('Service 3')->pricePerUnit(4.56),
        InvoiceItem::make('Service 19')->pricePerUnit(76.37),
        InvoiceItem::make('Service 20')->pricePerUnit(55.80),
    ];

    $notes = [
        'your multiline',
        'additional notes',
        'in regards of delivery or something else',
    ];
    $notes = implode("<br>", $notes);


    $invoice = Invoice::make('receipt')
        ->series('BIG')
        // ability to include translated invoice status
        // in case it was paid
        ->status('PAID')
        ->sequence(667)
        ->serialNumberFormat('{SEQUENCE}/{SERIES}')
        ->seller($client)
        ->buyer($customer)
        ->date(now()->subWeeks(3))
        ->dateFormat('m/d/Y')
        ->payUntilDays(14)
        ->currencySymbol('$')
        ->currencyCode('USD')
        ->currencyFormat('{SYMBOL}{VALUE}')
        ->currencyThousandsSeparator('.')
        ->currencyDecimalPoint(',')
        ->filename($client->name . ' ' . $customer->name)
        ->addItems($items)
        ->notes($notes)
        ->logo(public_path('logo-alta.png'))
        ->save('public');

    return $invoice->stream();
});
