<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row mb-2">
                    <div class="col-xl-9">
                        <!-- <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <input class="form-control" type="text"  placeholder="Search Phone">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <input class="form-control" type="text"  placeholder="Search Card">
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
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Order Details</h4>

                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Selling Price:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($total_selling_price) }}</p>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Purchase Price:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($total_purchase_price) }}</p>
                                        <p class="mb-2"><span class="fw-bold me-2">Net Profit:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($total_profit) }}</p>

                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Product Details</h4>

                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Available Selling
                                                Price:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($product_selling_price) }}</p>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Available Purchase
                                                Price:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($product_purchase_price) }}</p>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title mb-3">Total Inserted Product</h4>

                                <ul class="list-unstyled mb-0">
                                    <li>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Selling Price:</span>{{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($inseted_product_selling_price) }}</p>
                                        <p class="mb-2"><span class="fw-bold me-2">Total Purchase Price:</span> {{env('CURRENCY','₹')}}{{ convert_numbers_to_indian_format($inseted_product_purchase_price) }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="mb-3 text-end">
                        <button type="submit" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
