<div class="row">
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
                        <!-- <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" name="date" placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" name="date" placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger">All</button>
                                </div>
                            </div>


                        </div> -->
                    </div>
                    @if (Auth::user()->type == 'A')
                        <div class="col-xl-3">
                            <div class="row align-items-center justify-content-xl-end mt-xl-0 mt-2">

                                <!-- <label for="status-select" class="me-2">Status</label> -->
                                <select class="form-select w-auto me-2" wire:model="storeUser">
                                    <option value="" disabled="">Select Store</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>

                            </div>
                        </div><!-- end col-->
                    @endif
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




                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Billing User</th>
                                <th>Order Total</th>
                                @if (Auth::user()->type == 'A')
                                    <th>Profit</th>
                                    <th>Profit %</th>
                                @endif
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @foreach ($users as $key => $user)
                                    @php
                                        $order_total = 0;
                                        /* $total_order = 0;
                                    $total_discount = 0;
                                    $purchase_price = 0;
                                    $total_profit = 0;
                                    $profit_percentage = 0;
                                    if($storeUser == 1)
                                    {
                                        if(count($user->orders))
                                        {
                                            foreach($user->orders as $key=>$order)
                                            {
                                               $total_order+=$order->subtotal; 
                                               $total_discount+=($order->qty*$order->discount); 
                                               $purchase_price+=($order->qty*$order->purchase_price);
                                                
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if(count($user->orders_new_db))
                                        {
                                            foreach($user->orders_new_db as $key=>$order)
                                            {
                                               $total_order+=$order->subtotal; 
                                               $total_discount+=($order->qty*$order->discount); 
                                               $purchase_price+=($order->qty*$order->purchase_price); 
                                            }
                                        }
                                    }
                                    $order_total= ($total_order-$total_discount);
                                    $total_profit= ($total_order-$purchase_price); */
                                        if ($user->order_total) {
                                            $profit_percentage = ($user->total_profit * 100) / $user->order_total;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($user->order_total) }}
                                        </td>
                                        @if (Auth::user()->type == 'A')
                                            <td>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($user->total_profit) }}
                                            </td>
                                            <td>{{ convert_numbers_to_indian_format($profit_percentage) }} %</td>
                                        @endif
                                        <td style="white-space: nowrap;">
                                            @if (Auth::user()->type == 'A' ||
                                                    in_array('sub-admin-orders-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="{{ route('sub_admin_orders.view', $user->id) }}"
                                                    class="action-icon"><i class="mdi mdi-eye"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">No records available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->




</div><!-- end row -->
