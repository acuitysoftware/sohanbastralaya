<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

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
                <div class="row">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" wire:model="searchName" type="text"
                                        placeholder="Search">
                                </div>
                            </div>
                        </div>
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
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Total Qty</th>
                                <th>Available Qty</th>
                                <th>Return Qty</th>
                                <th>Qty Sold</th>
                                <th>Selling Price</th>
                                @if (Auth::user()->type == 'A')
                                    <th>Purchase Price</th>
                                @endif
                                <th>Available Selling Price</th>
                                @if (Auth::user()->type == 'A')
                                    <th>Available Purchase Price</th>
                                @endif
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if (count($products) > 0)
                                @foreach ($products as $key => $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td>
                                            @if (isset($row->gallery))
                                                <a data-fancybox="gallery"
                                                    href="{{ asset('storage/app/public/product_image/' . $row->gallery->gallery_image) }}">
                                                    <img src="{{ asset('storage/app/public/product_image/' . $row->gallery->gallery_image) }}"
                                                        alt="contact-img" title="contact-img" class="rounded me-3"
                                                        height="48" />
                                                </a>
                                            @else
                                                <img src="{{ asset('public/assets/images/no_image.png') }}"
                                                    alt="contact-img" title="contact-img" class="rounded me-3"
                                                    height="48" />
                                            @endif
                                        </td>
                                        @php
                                            $avl_qty = 0;
                                            $avl_qty =
                                                $row->product_quantities_sum_quantity -
                                                ($row->product_orders_sum_qty + $row->productReductions->sum('qty'));

                                        @endphp
                                        <td>{{ $row->product_code }}</td>
                                        <td>{{ $row->product_quantities_sum_quantity - $row->productReductions->sum('qty') }}
                                        </td>
                                        <td>{{ $avl_qty }}</td>
                                        <td>{{ $row->return_products_quantity_sum_qty ?? 0 }}</td>
                                        <td>{{ $row->product_orders_sum_qty ?? 0 }}</td>
                                        <td>{{ env('CURRENCY', '₹') }}{{ $row->selling_price }}</td>

                                        @if (Auth::user()->type == 'A')
                                            <td>{{ env('CURRENCY', '₹') }}{{ $row->purchase_price }}</td>
                                        @endif
                                        <td>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($row->selling_price * $avl_qty) }}
                                        </td>
                                        @if (Auth::user()->type == 'A')
                                            <td>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($row->purchase_price * $avl_qty) }}
                                            </td>
                                        @endif
                                        <td style="white-space: nowrap;"><a href="javascript:void(0);"
                                                class="action-icon" wire:click="viewOrder({{ $row->id }})"><i
                                                    class="mdi mdi-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" class="text-center">No records available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if ($products->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                {{ $products->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <!-- My Modal -->
    <div wire:ignore.self id="stockInserted" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title">Stock Orders of {{ @$product->name }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">



                    {{--   <div class="col-xl-12">
                        <form class="row align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <button type="button" class="btn btn-secondary mb-1">Back</button>
                            </div>
                        </form>                            
                    </div> --}}


                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Order Date</th>
                                    <th>Quantity</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if (count($orderList) > 0)
                                    @foreach ($orderList as $key => $row)
                                        <tr>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ $row->customer->customer_name }}</td>
                                            <td>{{ date('d/m/Y', strtotime($row->customer->order_date)) }}</td>
                                            <td>{{ $row->qty }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No records available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>


                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- end row -->
