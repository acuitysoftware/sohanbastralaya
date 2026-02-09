<div class="row">
    @php
        $profit_perctg = 0;
        $total_selling = 0;
        $total_discount = 0;
        $total_profit = 0;
        $total_product_selling_price = 0;
        $discount_perctg = 0;
    @endphp
    @php
        function convert_numbers_to_indian_format($number)
        {
            $formattedAmount = number_format($number);
            $decimal = (string) ($number - floor($number));
            $money = floor($number);
            $length = strlen($money);
            $delimiter = '';
            $money = strrev($money);
            for ($i = 0; $i < $length; $i++) {
                if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                    $delimiter .= ',';
                }
                $delimiter .= $money[$i];
            }

            $formattedAmount = strrev($delimiter);
            $decimal = preg_replace('/0\./i', '.', $decimal);
            $decimal = substr($decimal, 0, 3);

            if ($decimal != '0') {
                $formattedAmount = $formattedAmount . $decimal;
            } else {
                $formattedAmount = $formattedAmount . '.00';
            }
            return $formattedAmount;
        }
    @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" name="date" placeholder="Date From"
                                        wire:model.lazy="dateForm">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" name="date" placeholder="Date To"
                                        wire:model.lazy="dateTo">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" wire:click="resetSearch">All</button>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- <div class="col-xl-3">
                        <div class="row align-items-center justify-content-xl-end mt-xl-0 mt-2">
                            
                                <select class="form-select w-auto me-2">
                                    <option value="" selected>Select Store</option>
                                    <option value="">1</option>
                                    <option value="">2</option>
                                </select>
                                                                       
                        </div>
                    </div> -->
                </div>
                <div wire:loading wire:target="storeUser">
                    <div class="loader_sectin" id="loader_section">
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center"></div>
                    </div>
                </div>
                <div wire:loading wire:target="resetSearch">
                    <div class="loader_sectin" id="loader_section">
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center"></div>
                    </div>
                </div>
                <div wire:loading wire:target="loadMore">
                    <div class="loader_sectin" id="loader_section">
                        <div class="loader_overlay"></div>
                        <div id="loader" class="center"></div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Billing User</th>
                                <th>Customer Phone</th>
                                <th>Customer Email</th>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                @if (Auth::user()->type == 'A')
                                    <th>Profit</th>
                                @endif
                                <th>Discount Amount</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if (count($orders) > 0)
                                @foreach ($orders as $key => $row)
                                    <tr>
                                        <td>{{ $row->order_id }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->user ? $row->user->name : '' }}</td>
                                        <td>{{ $row->customer_phone ? $row->customer_phone : '' }}</td>
                                        <td>{{ $row->customer_email }}</td>
                                        <td>{{ date('d/m/Y h:i', strtotime($row->order_time)) }}</td>
                                        @php
                                            $total_product_selling_price += $row->subtotal;
                                            $profit = 0;
                                            if (isset($row->productDetails)) {
                                                foreach ($row->productDetails as $details) {
                                                    $profit += $details->profit;
                                                }
                                            }

                                            $total_selling +=
                                                $row->subtotal -
                                                (($row->discount_type == 'dis_amt'
                                                    ? $row->discount_amt
                                                    : $row->discount_percent) +
                                                    (float) $row->wallet_discount);

                                            $total_profit += $profit;

                                            $total_discount +=
                                                $row->discount_type == 'dis_amt'
                                                    ? $row->discount_amt
                                                    : $row->discount_percent;

                                            if ($total_profit > 0) {
                                                $profit_perctg = ($total_profit / $total_selling) * 100;
                                            }

                                            if ($total_discount > 0) {
                                                $discount_perctg = ($total_discount / $total_selling) * 100;
                                            }

                                        @endphp
                                        <td>{{ env('CURRENCY', '₹') }}{{ number_format($row->subtotal - (($row->discount_type == 'dis_amt' ? $row->discount_amt : $row->discount_percent) + (float) $row->wallet_discount), 2) }}
                                        </td>

                                        @if (Auth::user()->type == 'A')
                                            <td>{{ env('CURRENCY', '₹') }}{{ number_format($profit, 2) }}</td>
                                        @endif
                                        <td>{{ env('CURRENCY', '₹') }}{{ $row->discount_type == 'dis_amt' ? $row->discount_amt : $row->discount_percent }}
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @if (Auth::user()->type == 'A' || in_array('order-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" title="View" class="action-icon"
                                                    wire:click="viewOrder({{ $row->id }})"><i
                                                        class="mdi mdi-eye"></i></a>
                                            @endif
                                            @if (Auth::user()->type == 'A' || in_array('order-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" class="action-icon" id="warning"
                                                    title="Delete" wire:click="deleteAttempt({{ $row->id }})"><i
                                                        class="mdi mdi-delete"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="text-center">No records available</td>
                                </tr>
                            @endif
                            @php
                                if ($total_profit > 0) {
                                    $profit_perctg = ($total_profit / $total_selling) * 100;
                                }

                                if ($total_discount > 0) {
                                    $discount_perctg = ($total_discount / $total_product_selling_price) * 100;
                                }
                            @endphp
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-end">
                        <table class="table w-auto table-dark">
                            <tbody>

                                <tr>
                                    <th>Total Selling :</th>
                                    <td>Rs {{ number_format($total_selling, 2) }} </td>
                                </tr>
                                @if (Auth::user()->type == 'A')
                                    <tr>
                                        <th>Net Profit:</th>
                                        <td>Rs {{ number_format($total_profit, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Discount:</th>
                                        <td>Rs {{ number_format(@$total_discount, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th> Discount %:</th>
                                        <td> {{ number_format($discount_perctg, 2) }}%</td>
                                    </tr>
                                    <tr>
                                        <th>Profit %:</th>
                                        <td> {{ number_format($profit_perctg, 2) }}%</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->

    <!-- My Modal -->
    <div wire:ignore.self id="subadminOrderDetails" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="my-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Order Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-xl-8">
                            <div
                                class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <button type="button" onclick="PrintDiv()" class="btn btn-primary">Print
                                            Details</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-4">
                            <div
                                class="row gy-2 gx-2 align-items-center justify-content-xl-end justify-content-between">
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary">Whole Seller</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                    <div id="print_data" class="w-100 mt-3 mb-5">
                        <table style="width: 300px; border-spacing: 0px; margin: 0 auto;">
                            <tbody>
                                <tr>
                                    @if ($storeUser == '1')
                                        <td style="text-align: center;">
                                            <p
                                                style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">
                                                {{ $setting->site_title }}</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Madhanmohan Pur
                                                Bazar,</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Ramanagar, kulpi
                                            </p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone:
                                                {{ $setting->contact_no }}</p>
                                            <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email:
                                                {{ $setting->site_mail }}</p>
                                            <p
                                                style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">
                                                Order Details </p>
                                        </td>
                                    @else
                                        <td style="text-align: center;">
                                            <p
                                                style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">
                                                {{ $setting->site_title }}</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">12 No-Naskarer
                                                Chak Bazar,</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;"> Madrasa More,
                                                Kakdwip</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone:
                                                {{ $setting->contact_no }}</p>
                                            <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email:
                                                {{ $setting->site_mail }}</p>
                                            <p
                                                style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">
                                                Order Details </p>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-spacing: 0px;">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size: 12px; color: #000; padding: 10px 0px 0;">Bill
                                                        No : {{ @$viewOrder->order_id }}</td>
                                                    <td
                                                        style="text-align: right; font-size: 12px; color: #000; padding: 10px 0px 0;">
                                                        Date : {{ date('d/m/Y', strtotime(@$viewOrder->order_date)) }}
                                                        <p style="padding: 0px;margin:0px;">
                                                            {{ date('h:i', strtotime(@$viewOrder->order_time)) }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 12px; color: #000; text-transform: uppercase;  border-top: 1px dashed #ccc; padding: 10px 0px 0;     border-bottom: 1px dashed #ccc;"
                                                        colspan="2">
                                                        <p style="padding: 0px;margin:0;">
                                                            {{ @$viewOrder->customer_name }}</p>
                                                        <p style="padding: 0px;margin:0px;">
                                                            @if (@$viewOrder->customer_phone)
                                                                {{ @$viewOrder->customer_phone }}
                                                            @endif
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-spacing: 0px; font-size:12px;">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left; width:50%;">
                                                        <strong>Item Name</strong>
                                                    </td>
                                                    <td style="text-align: center; width:10%;">
                                                        <strong>Qty</strong>
                                                    </td>
                                                    <td style="text-align: center; width:0%;">
                                                    </td>
                                                    <td style="text-align: center; width:20%;">
                                                        <strong>Rate</strong>
                                                    </td>
                                                    <td style="text-align: right; width:20%;">
                                                        <strong>Amount</strong>
                                                    </td>
                                                </tr>
                                                @php
                                                    $sub_total = 0.0;
                                                    $discount = 0.0;
                                                @endphp

                                                @if (isset($viewOrder))
                                                    @foreach (@$viewOrder->productDetails as $key => $product)
                                                        <tr>
                                                            <td style="text-align: left; padding: 5px 0px; ">
                                                                {{ $product->product_name }}
                                                            </td>
                                                            <td style="text-align: center; padding: 5px 0px; ">
                                                                {{ $product->qty }}
                                                            </td>
                                                            <td style="text-align: center; padding: 5px 0px; ">
                                                            </td>
                                                            <td style="text-align: center; padding: 5px 0px; ">
                                                                {{ $product->selling_price }}
                                                            </td>
                                                            <td style="text-align: right; padding: 5px 0px; ">
                                                                {{ number_format($product->selling_price * $product->qty, 2) }}
                                                            </td>
                                                            @php
                                                                $sub_total += $product->selling_price * $product->qty;
                                                                $discount += $product->discount * $product->qty;
                                                            @endphp
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                        colspan="2">
                                                        <strong>Sub Total: </strong>
                                                    </td>
                                                    <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                        colspan="2">
                                                        <!--Sub Total: -->
                                                    </td>
                                                    <td
                                                        style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                                                        {{ number_format(@$sub_total, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                        colspan="2">
                                                        <strong>Discount Amount</strong>
                                                    </td>
                                                    <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                        colspan="2">
                                                    </td>
                                                    <td
                                                        style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                                                        <!-- @if (@$viewOrder->discount_type == 'dis_amt')
{{ @$viewOrder->discount_amt }}
@else
{{ @$viewOrder->perctge_amt }}
@endif   -->
                                                        {{ number_format($discount, 2) }}
                                                    </td>
                                                </tr>
                                                @if (isset($viewOrder) && count($viewOrder->productDetails) > 0)
                                                    @if (@$viewOrder->wallet == 1)
                                                        <tr>
                                                            <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                                colspan="2">
                                                                <strong>Wallet Debit</strong>
                                                            </td>
                                                            <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                                colspan="2">
                                                                <!--Sub Total: -->
                                                            </td>
                                                            <td
                                                                style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                                                                {{ number_format(@$viewOrder->wallet_discount, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                @if (isset($viewOrder) && count($viewOrder->productDetails) > 0)
                                                    @if (@$viewOrder->return_id != '')
                                                        <tr>
                                                            <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                                colspan="2">
                                                                <strong>Return Amount
                                                                    ({{ @$viewOrder->return_id }})</strong>
                                                            </td>
                                                            <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                                colspan="2">
                                                                <!--Sub Total: -->
                                                            </td>
                                                            <td
                                                                style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                                                                {{ number_format(@$viewOrder->return_amt, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="width: 100%; border-spacing: 0px; margin-bottom: 20px;">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size: 15px; color: #000; padding: 7px 0px;  border-bottom: 1px dashed #ccc;"
                                                        colspan="2"> <strong>Total :</strong></td>
                                                    <td
                                                        style="font-size: 15px; color: #000; padding: 7px 0px; text-align: right; border-bottom: 1px dashed #ccc; font-weight: 600; ">
                                                        <!-- @if (isset($viewOrder))
{{ @$viewOrder->total_amount }}
@else
0.00
@endif -->
                                                        {{ number_format($sub_total - ($discount + (float) @$viewOrder->wallet_discount + @$viewOrder->return_amt), 2) }}
                                                    </td>
                                                </tr>
                                                @if (@$viewOrder->due_payments_count > 0)
                                                    <tr>
                                                        <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                            colspan="2"> <strong>Due Amount :</strong></td>
                                                        <td
                                                            style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"><!-- @if (isset($viewOrder))
{{ @$viewOrder->due_amount }}
@else
0.00
@endif -->
                                                            {{ number_format(@$viewOrder->due_amount, 2) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                                            colspan="2"> <strong>Collected Amount :</strong></td>
                                                        <td
                                                            style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"><!-- @if (isset($viewOrder))
{{ @$viewOrder->collected_amount }}
@else
0.00
@endif -->
                                                            {{ number_format(@$viewOrder->collected_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #000; padding: 7px 0px;">
                                        <strong>Note</strong> : We exchange products within 7 days between 10: 30am to
                                        12:30pm from the date of purchase.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #000; padding: 7px 0px; text-align: center;">
                                        Our Store open 7 days a week
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 18px; color: #000; padding: 7px 0px; text-align: center;">
                                        Thank you
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                            id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Code</th>
                                    @if (Auth::user()->type == 'A')
                                        <th>Purchase Price</th>
                                    @endif
                                    <th>Selling Price</th>
                                    <th>Discount</th>
                                    <th>Actual Selling Price</th>
                                    @if (Auth::user()->type == 'A')
                                        <th>Profit</th>
                                        <th>Percentage Profit</th>
                                    @endif
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>&nbsp;</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($viewOrder))
                                    @if (count(@$viewOrder->productDetails))
                                        @foreach (@$viewOrder->productDetails as $orderDetails)
                                            <tr>
                                                <td>{{ @$orderDetails->product_name }}</td>
                                                <td>{{ @$orderDetails->product_code }}</td>
                                                @if (Auth::user()->type == 'A')
                                                    <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->purchase_price }}
                                                    </td>
                                                @endif
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->selling_price }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->discount }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->selling_price - @$orderDetails->discount }}
                                                </td>
                                                @if (Auth::user()->type == 'A')
                                                    <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$orderDetails->profit, 2) }}
                                                    </td>
                                                    <td>{{ @$orderDetails->profit_percentage }}%</td>
                                                @endif
                                                <td>{{ @$orderDetails->qty }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$orderDetails->subtotal - @$orderDetails->total_discount, 2) }}
                                                </td>
                                                <td><button type="button" class="btn btn-warning"
                                                        wire:click="returnOrder({{ $orderDetails->id }})">Return</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if (isset($viewOrder))
                        @if (count(@$viewOrder->returnProducts))

                            <div class="table-responsive">
                                <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                                    id="products-datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="7" style="background-color: #009CEC;">Return Products</th>
                                        </tr>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Code</th>
                                            <th>Selling Price</th>
                                            <th>Discount</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (@$viewOrder->returnProducts as $details)
                                            <tr>
                                                <td>{{ @$details->product_name }}</td>
                                                <td>{{ @$details->product_code }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$details->selling_price }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$details->discount }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$details->selling_price - @$details->discount }}
                                                </td>
                                                <td>{{ @$details->qty }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$details->selling_price * @$details->qty }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- My Modal -->
    <div wire:ignore.self id="returnProduct" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="return-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Return Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form wire:submit.prevent="saveReturnOrder">
                    <input type="hidden" wire:model.defer="return_order_id">
                    <div class="modal-body">
                        <span class="badge badge-outline-danger p-1 font-16 mb-1">Order Id -
                            {{ @$returnOrder->order_id }}</span>
                        <div class="row mt-2">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" placeholder="Product Name"
                                    wire:model.defer="product_name" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Product Code</label>
                                <input type="text" class="form-control" placeholder="Product Code"
                                    wire:model.defer="product_code" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="text" class="form-control" placeholder="Quantity"
                                    wire:model.defer="product_qty">
                                @error('product_qty')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Selling Price</label>
                                <input type="text" class="form-control" placeholder="Selling Price"
                                    wire:model.defer="product_selling_price" readonly>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>



            </div>
        </div>
    </div>

    <script type="text/javascript">
        function PrintDiv() {
            var divToPrint = document.getElementById('print_data');
            var popupWin = window.open('', '_blank', 'width=900,height=650');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();

        }
    </script>
</div><!-- end row -->
