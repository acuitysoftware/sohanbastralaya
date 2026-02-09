<div class="row">
    <!-- My Modal -->
    <div wire:ignore.self id="orderView" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1"
        role="dialog" aria-labelledby="my-modalLabel" aria-hidden="true" style="z-index: 99999;">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Order Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="row ">
                    <div class="col-xl-8">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" onclick="PrintDiv()" class="btn btn-primary">Print Details</button>                                                
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button> 
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary">Whole Seller</button>                                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}



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
                                                <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$orderDetails->selling_price - @$orderDetails->discount, 2) }}
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
                            {{-- <div style="display: flex;align-items: end;justify-content: flex-end;margin: 15px -7 0;"><button type="submit" id="save_btn" class="btn btn-primary" onclick="PrintReturnDiv()">Print Return </button></div> --}}
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
                                                <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$details->selling_price * @$details->qty, 2) }}
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
    <div wire:ignore.self id="returnProduct" data-backdrop="static" data-keyboard="false" class="modal fade"
        tabindex="-1" role="dialog" aria-labelledby="return-modalLabel" aria-hidden="true" style="z-index: 999999;">
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
    <div id="print_return_data" class="w-100 mt-3 mb-5" style="display: none">
        <table style="width: 300px; border-spacing: 0px; margin: 0 auto;">
            <tbody>
                <tr>
                    @if ($storeUser == '1')
                        <td style="text-align: center;">
                            <p
                                style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">
                                {{ $setting->site_title }}</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Madhanmohan Pur Bazar,</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Ramanagar, kulpi</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone: {{ $setting->contact_no }}
                            </p>
                            <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email: {{ $setting->site_mail }}
                            </p>
                            <p style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">RETURN
                                BILL </p>
                        </td>
                    @else
                        <td style="text-align: center;">
                            <p
                                style="margin: 5px 0px; font-size: 18px; color: #000;font-weight: 600; text-transform: uppercase;">
                                {{ $setting->site_title }}</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">12 No-Naskarer Chak Bazar,</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;"> Madrasa More, Kakdwip</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Pin 743347</p>
                            <p style="margin: 5px 0px; font-size: 16px; color: #000;">Phone:
                                {{ $setting->contact_no }}</p>
                            <p style="margin: 5px 0px; font-size: 15px; color: #000;">Email: {{ $setting->site_mail }}
                            </p>
                            <p style="margin: 5px 0px; font-size: 15px; color: #000; text-transform: uppercase;">RETURN
                                BILL </p>
                        </td>
                    @endif

                </tr>
                <tr>
                    <td>
                        <table style="width: 100%; border-spacing: 0px;">
                            <tbody>
                                <tr>
                                    <td style="font-size: 12px; color: #000; padding: 10px 0px 0;">Bill No :
                                        {{ @$viewOrder->order_id }}</td>
                                    <td style="text-align: right; font-size: 12px; color: #000; padding: 10px 0px 0;">
                                        Date : {{ date('d/m/Y', strtotime(@$viewOrder->order_date)) }}
                                        <p style="padding: 0px;margin:0px;">
                                            {{ date('h:i', strtotime(@$viewOrder->order_time)) }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; color: #000; text-transform: uppercase;  border-top: 1px dashed #ccc; padding: 10px 0px 0;     border-bottom: 1px dashed #ccc;"
                                        colspan="2">
                                        <!-- Sp00021845<br> -->
                                        <p style="padding: 0px;margin:0;">{{ @$viewOrder->customer_name }}</p>
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
                                @if (isset($viewOrder->returnProducts))
                                    @foreach ($viewOrder->returnProducts as $key => $product)
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
                                                {{ $product->selling_price * $product->qty }}
                                            </td>
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
                                        @if (isset($viewOrder))
                                            @if (count($viewOrder->returnProducts))
                                                @php
                                                    $subtotal = 0;
                                                    $discount = 0;
                                                    foreach ($viewOrder->returnProducts as $key => $product) {
                                                        $subtotal += $product->selling_price * $product->qty;
                                                        $discount += $product->discount * $product->qty;
                                                    }

                                                @endphp
                                                {{ $subtotal }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                        colspan="2">
                                        <strong>Discount Amount</strong>
                                    </td>
                                    <td style="text-align: center; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;"
                                        colspan="2">
                                        <!--Sub Total: -->
                                    </td>
                                    <td
                                        style="text-align: right; padding: 7px 0px; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc;">
                                        @if (isset($viewOrder))
                                            @if (count($viewOrder->returnProducts))
                                                {{ @$discount }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>


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
                                        @if (isset($viewOrder))
                                            @if (count($viewOrder->returnProducts))
                                                {{ @$viewOrder->returnProducts()->sum('price') }}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if (@$viewOrder->due_amount > 0)
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
                        <strong>Note</strong> : We exchange products within 7 days between 10: 30am to 12:30pm from the
                        date of purchase.
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
    <!-- my-modal-edit -->
    <div wire:ignore.self class="modal fade" id="productEdit" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myLargeModalLabel">Product Edit</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form wire:submit.prevent="updateProduct">
                    <input type="hidden" wire:model.defer="state.id">
                    <div class="modal-body">

                        <div class="row mt-2 mb-2">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Product Title</label>
                                <input type="text" class="form-control" placeholder="Product Title"
                                    wire:model.defer="state.name">
                                @error('name')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                                @if ('error_name')
                                    <span class="text-danger error">{{ $error_name }}</span>
                                @endif
                            </div>
                            @if (isset($state['product_code']))
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Product Code</label>
                                    <input type="text" class="form-control" placeholder="Product Code"
                                        wire:model.defer="state.product_code">
                                    @error('product_code')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Quantity (Available Quantity:
                                    {{ @$state['current_quantity'] }})</label>
                                <input type="text" class="form-control" placeholder="Quantity"
                                    onkeypress="return number_check(event);" wire:model="state.quantity">
                                @error('quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Quantity Alert</label>
                                <input type="text" class="form-control" placeholder="Quantity Alert"
                                    onkeypress="return number_check(event);"
                                    wire:model.defer="state.default_quantity">
                                @error('default_quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Purchase Price</label>
                                <input type="text" class="form-control" placeholder="Purchase Price"
                                    wire:model.defer="state.purchase_price">
                                @error('purchase_price')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Selling Price</label>
                                <input type="text" class="form-control" placeholder="Selling Price"
                                    wire:model.defer="state.selling_price">
                                @error('selling_price')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (isset($state['id']))
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Item Code</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Item Code"
                                            wire:model.defer="state.bar_code" onkeypress="return number_check(event);"
                                            maxlength="12" style="background: #fff;">
                                        <div class="input-group-append" style="cursor: pointer;">
                                            <span class="input-group-text" id="basic-addon2"
                                                wire:click="generateEditBarCode('{{ json_encode($state) }}')">{{ $state['bar_code'] ? 'View' : 'Generate' }}
                                                Barcode</span>
                                        </div>
                                    </div>
                                    @error('bar_code')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                    @if ('error_bar_code')
                                        <span class="text-danger error">{{ $error_bar_code }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Product Image</label>
                                <input type="file" id="example-fileinput" class="form-control"
                                    wire:model.defer="state.image" accept="image/*">
                            </div>
                            <div class="mb-3 col-md-2">
                                <label class="form-label">Custom Discount</label>
                                <input type="checkbox" wire:model="edit_is_discount"
                                    value="{{ @$edit_is_discount }}">

                            </div>

                            @if (@$edit_is_discount)
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Select Discount Type</label>
                                    <select class="form-control" wire:model.defer="state.discount_type">
                                        <option value="">Select Discount Type</option>
                                        <option value="Flat">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                    @error('discount_type')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Discount </label>
                                    <input type="text" class="form-control" placeholder="Discount"
                                        wire:model.defer="state.discount_amt">
                                    @error('discount_amt')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            @if (isset($state['gallery_image']))
                                <div class="mb-3 col-md-6">
                                    <img src="{{ asset('storage/app/public/product_image/' . $state['gallery_image']) }}"
                                        height="100px">
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <x-admin.button name="Submit" target="updateProduct"></x-admin.button> --}}
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div wire:ignore.self id="productView" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Stock</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <!-- <div class="col-xl-12">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Back</button>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    </div>




                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                            id="products-datatable">
                            <tbody>
                                <tr>
                                    <th>Product Code :</th>
                                    <td>{{ @$viewProduct->product_code }}</td>
                                    <th>Item Code :</th>
                                    <td>{{ @$viewProduct->bar_code }}</td>

                                </tr>
                                <tr>
                                    <th>Product Name :</th>
                                    <td>{{ @$viewProduct->name }}</td>
                                    @if (Auth::user()->type == 'A')
                                        <th>Purchase Price :</th>
                                        <td>{{ env('CURRENCY', '₹') }}{{ @$viewProduct->purchase_price }}</td>
                                    @endif

                                    {{-- <td>Available Quantity</td> --}}
                                </tr>
                                <tr>
                                    <th>Selling Price</th>
                                    <td>{{ env('CURRENCY', '₹') }}{{ @$viewProduct->selling_price }}</td>
                                    <th>Inserted Stock :</th>
                                    <td>{{ @$viewProduct->product_quantities_sum_quantity ?? 0 }}</td>

                                </tr>
                                <tr>
                                    <th>Product Reduce :</th>
                                    <td>{{ @$viewProduct->product_reductions_sum_qty ?? 0 }}</td>
                                    <th>Return Quantity :</th>
                                    <td>{{ @$viewProduct->return_products_quantity_sum_qty ?? 0 }}</td>

                                </tr>
                                <tr>
                                    <th>Available Quantity</th>
                                    <td>{{ @$viewProduct->quantity ?? 0 }}</td>
                                    <th>Sold Quantity :</th>
                                    <td>{{ @$viewProduct->product_orders_sum_qty ?? 0 }}</td>
                                </tr>
                            </tbody>
                            {{-- <tbody>
                                @if (isset($viewProduct))

                                    @php
                                        $avl_qty = 0;
                                        $avl_qty =
                                            @$viewProduct->product_quantities_sum_quantity -
                                            (@$viewProduct->return_products_quantity_sum_qty +
                                                @$viewProduct->product_orders_sum_qty +
                                                @$viewProduct->product_reductions_sum_qty);
                                    @endphp

                                    <tr>
                                        
                                        @if (Auth::user()->type == 'A')
                                            <td>{{ @$viewProduct->purchase_price }}</td>
                                        @endif
                                        <td>{{ @$viewProduct->selling_price }}</td>
                                        <td>{{ @$viewProduct->quantity }}</td>
                                    </tr>

                                @endif
                            </tbody> --}}
                        </table>
                    </div>
                    @if (isset($viewProduct->productQuantities) && count(@$viewProduct->productQuantities) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                                id="products-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="3" style="background-color: #009CEC;">Product Insert History
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Date</th>
                                        <!-- <th>Product Name</th> -->
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_qty = 0;
                                    @endphp
                                    @foreach (@$viewProduct->productQuantities as $key => $productView)
                                        @php
                                            $total_qty += $productView->quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($productView->date)) }}</td>
                                            <!-- <td>{{ @$productView->pro_name }}</td> -->
                                            <td>{{ @$productView->quantity }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="2" style="text-align: right">Total Quantity</th>
                                        <td>{{ $total_qty }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                            id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="4" style="background-color: #009CEC;">Product Return History</th>
                                </tr>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Quantity Return</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (isset($viewProduct->returnProducts) && count(@$viewProduct->returnProducts) > 0)
                                    @php
                                        $total_qty = 0;
                                    @endphp
                                    @foreach (@$viewProduct->returnProducts as $key => $returnProduct)
                                        @php
                                            $total_qty += $returnProduct->qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($returnProduct->date)) }}</td>
                                            {{--  <td><a href="{{ route('product.order', $returnProduct->order_id) }}"
                                                    target="_blank">{{ @$returnProduct->order_id }}</a></td> --}}
                                            <td><a href="javascript:void(0)"
                                                    wire:click.prevent="viewOrders({{ $returnProduct->order_id }},{{ $viewProduct->id }})">{{ @$returnProduct->order_id }}</a>
                                            </td>
                                            <td>{{ @$returnProduct->qty }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3" style="text-align: right">Total Quantity</th>
                                        <td>{{ $total_qty }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" style="text-align: center;">There is no returned product
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                            id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="4" style="background-color: #009CEC;">Product Reduce History</th>
                                </tr>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (isset($viewProduct->productReductions) && count(@$viewProduct->productReductions) > 0)
                                    @php
                                        $total_qty = 0;
                                    @endphp
                                    @foreach (@$viewProduct->productReductions as $key => $productReduction)
                                        @php
                                            $total_qty += $productReduction->qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($productReduction->date)) }}</td>

                                            <td>{{ @$productReduction->qty }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="2" style="text-align: right">Total Quantity</th>
                                        <td>{{ $total_qty }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align: center;">There is no reduce product
                                            quantity</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                            id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5" style="background-color: #009CEC;">Product Selling History</th>
                                </tr>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Quantity Sold</th>
                                    <th>Selling Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($viewProduct->productOrders) && count(@$viewProduct->productOrders) > 0)
                                    @php
                                        $total_qty = 0;
                                    @endphp

                                    @foreach (@$viewProduct->productOrdersByDesc as $key => $order)
                                        @php
                                            $total_qty += $order->qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ date('d/m/Y', strtotime($order->orderDetails->order_date)) }}</td>
                                            {{-- <td><a href="{{ route('product.order', $order->order_id) }}"
                                                    target="_blank">{{ @$order->order_id }}</a></td> --}}
                                            <td><a href="javascript:void(0)"
                                                    wire:click.prevent="viewOrders({{ $order->order_id }},{{ $viewProduct->id }})">{{ @$order->order_id }}</a>
                                            </td>
                                            <td>{{ @$order->qty }}</td>
                                            <td>{{ env('CURRENCY', '₹') }}{{ @$order->selling_price }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3" style="text-align: right">Total Quantity</th>
                                        <td colspan="2">{{ $total_qty }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No record available</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>


                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <style>
        .barcode_main_div img {
            width: 100%;
            height: 60px;
        }

        .barcode_main_div {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 15px auto;
            gap: 10px;
            max-width: 300px;
            width: 100%;
        }

        .barcode_text {
            margin-top: -10px;
        }
    </style>
    <div id="barcode_print_data" class="w-100 mt-3 mb-5" style="display: none;">
        <table style="width: 300px; border-spacing: 0px; margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="text-align: center;">
                        <div class="barcode_main_div"
                            style=" display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
                margin: 15px auto;
                gap: 10px;
                max-width: 300px;
                width: 100%;
        }">
                            @if (isset($bar_code))
                                <label>Sohan Bastralaya</label>
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($bar_code, 'C39') }}"
                                    alt="barcode" style=" width: 100%;
            height: 60px;" />
                                <label>{{ $bar_code }}</label>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- View Modal -->
    <div wire:ignore.self id="barcodeModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog"
            style="    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px #00000054;">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Barcode</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="barcode_main_div">
                        @if (isset($bar_code))
                            <label>Sohan Bastralaya</label>
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($bar_code, 'C39') }}"
                                alt="barcode" />
                            <label class="barcode_text">{{ $bar_code }}</label>
                        @endif
                    </div>





                    <button type="button" onclick="printBarcode()" class="btn btn-primary">Print</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div wire:ignore.self id="editBarcodeModal" class="modal fade" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog"
            style="    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px #00000054;">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Barcode</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="barcode_main_div">
                        @if (isset($edit_bar_code))
                            <label>Sohan Bastralaya</label>
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($edit_bar_code, 'C39') }}"
                                alt="barcode" />
                            <label class="barcode_text">{{ $edit_bar_code }}</label>
                        @endif
                    </div>





                    <button type="button" onclick="printBarcode()" class="btn btn-primary">Print</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- My Modal -->
    <div wire:ignore.self id="orderView" class="modal fade" tabindex="-1" role="dialog"
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
                                        {{-- <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cancel</button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- <div class="col-xl-4">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary">Whole Seller</button>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                                                        Date : {{ date('Y-m-d', strtotime(@$viewOrder->order_date)) }}
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
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->selling_price }}
                                                </td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ @$orderDetails->discount }}</td>
                                                <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$orderDetails->selling_price - @$orderDetails->discount, 2) }}
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
                                                        wire:click.prevent="returnOrder({{ $orderDetails->id }})">Return</button>
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
                            <div style="display: flex;align-items: end;justify-content: flex-end;margin: 15px -7 0;">
                                {{--   <button type="submit" id="save_btn" class="btn btn-primary"
                                    onclick="PrintReturnDiv()">Print Return </button> --}}
                            </div>
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
                                                <td>{{ env('CURRENCY', '₹') }}{{ number_format(@$details->selling_price * @$details->qty, 2) }}
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

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if (Auth::user()->type == 'A' || in_array('product-add', Auth::user()->permissions()->pluck('permission')->toArray()))
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="mb-3 col-md-6" style="position: relative;">
                                <label class="form-label">Product Title</label>
                                <input type="text" class="form-control" placeholder="Product Title"
                                    wire:model="name">
                                <ul id="serch_result" class="product_search"
                                    @if (count($productSearch) == '0') style="display: none" @endif>
                                    @if (count($productSearch) > 0)
                                        @foreach ($productSearch as $value)
                                            <li wire:click.prevent="getProductDetails({{ $value->id }})"><a
                                                    href="javascript:void(0)">{{ $value->name }}
                                                    ({{ $value->product_code }})
                                                </a></li>
                                        @endforeach
                                    @endif
                                </ul>
                                @error('name')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                                @if ('error_name')
                                    <span class="text-danger error">{{ $error_name }}</span>
                                @endif
                            </div>

                            <input type="hidden" wire:model.defer="product_id">

                            @if (isset($product_code))
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Product Code</label>
                                    <input type="text" class="form-control" placeholder="Product Code"
                                        wire:model.defer="product_code" maxlength="6">
                                    @error('product_code')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="text" class="form-control" placeholder="Quantity"
                                    onkeypress="return number_check(event);" wire:model.defer="quantity"
                                    wire:focus="inputFocused">
                                @error('quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Quantity Alert</label>
                                <input type="text" onkeypress="return number_check(event);" class="form-control"
                                    placeholder="Quantity Alert" wire:model.defer="default_quantity"
                                    wire:focus="inputFocused">
                                @error('default_quantity')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Purchase Price</label>
                                <input type="text" class="form-control" placeholder="Purchase Price"
                                    wire:model.defer="purchase_price" wire:focus="inputFocused"
                                    @if (isset($product_id)) readonly="" @endif>
                                @error('purchase_price')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Selling Price</label>
                                <input type="text" class="form-control" placeholder="Selling Price"
                                    wire:model.defer="selling_price" wire:focus="inputFocused"
                                    @if (isset($product_id)) readonly="" @endif>
                                @error('selling_price')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Item Code</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Item Code"
                                        wire:model.defer="bar_code" maxlength="12" style="background: #fff;"
                                        wire:focus="inputFocused" onkeypress="return number_check(event);">
                                    <div class="input-group-append" style="cursor: pointer;">
                                        <span class="input-group-text" id="basic-addon2"
                                            wire:click="generateBarCode('{{ $product_id }}')">{{ $bar_code ? 'View' : 'Generate' }}
                                            Barcode</span>
                                    </div>
                                </div>

                                @error('bar_code')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                                @if ('error_bar_code')
                                    <span class="text-danger error">{{ $error_bar_code }}</span>
                                @endif
                            </div>
                            {{-- @if (isset($bar_code))
                    <div class="mb-3 col-md-6">
                        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($bar_code, 'C39')}}" alt="barcode" />
                    </div>
                    @endif --}}
                            <div class="mb-3 col-md-6">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Product Image</label>
                                    <input type="file" id="example-fileinput" class="form-control"
                                        wire:model.defer="image" wire:focus="inputFocused" accept="image/*">
                                    @error('image')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-md-2">
                                <label class="form-label">Custom Discount</label>
                                <input type="checkbox" wire:model="is_discount" value="{{ $is_discount }}">

                            </div>

                            @if ($is_discount)
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">Select Discount Type</label>
                                    <select class="form-control" wire:model.defer="discount_type">
                                        <option value="">Select Discount Type</option>
                                        <option value="Flat">Flat</option>
                                        <option value="Percentage">Percentage</option>
                                    </select>
                                    @error('discount_type')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Discount </label>
                                    <input type="text" class="form-control" placeholder="Discount"
                                        wire:model.defer="discount_amt">
                                    @error('discount_amt')
                                        <span class="text-danger error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            @if (isset($gallery_image))
                                <div class="mb-3 col-md-6">
                                    <img src="{{ asset('storage/app/public/product_image/' . $gallery_image) }}"
                                        alt="contact-img" title="contact-img" height="70" />
                                </div>
                            @endif

                        </div>

                        <div class="mb-3 text-center">
                            @if ($formSubmit == 1)
                                {{--  <x-admin.button name="Submit" target="save"></x-admin.button> --}}
                                <button type="button" class="btn btn-primary" disabled="">Submit</button>
                            @else
                                {{--  <x-admin.button name="Submit" target="save"></x-admin.button> --}}
                                <button type="submit" class="btn btn-primary"
                                    wire:loading.attr="disabled">Submit</button>
                            @endif
                        </div>
                    </form>
                @endif

                <div class="row mb-2">
                    <div class="col-xl-8">
                        @if (Auth::user()->type == 'A')
                            <form
                                class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <!-- <label for="status-select" class="me-2">Status</label> -->
                                        <select class="form-select" wire:model="storeUser">
                                            <option value="" disabled="">Select Store</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="col-xl-4">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end">
                            <div class="col-auto">
                                <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="inputPassword2"
                                    wire:model="searchName" placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xl-2">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end">
                            <a href="javascript:void(0)" onclickp"PrintBarcode()" class="btn btn-primary">Print Barcode</a>
                        </div>
                    </div> -->
                    <!-- end col-->
                    <!--  <div wire:loading>
                        <div class="loader_sectin" id="loader_section" >
                            <div class="loader_overlay"></div>
                            <div id="loader" class="center" ></div>
                        </div>
                    </div> -->
                    <div wire:loading wire:target="formSubmit">
                        <div class="loader_sectin" id="loader_section">
                            <div class="loader_overlay"></div>
                            <div id="loader" class="center"></div>
                        </div>
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
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                        id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>SL No.</th>
                                <th>Image</th>
                                <th>Products Name</th>
                                <th>Item Code</th>
                                <th>Code</th>
                                <th>Product Qty</th>
                                <th>Qty</th>
                                <th>Selling Price</th>
                                <th>Discount<!--  (Max Discount upto {{ $setting->discount_percentage }}%) --></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($products) > 0)
                                @foreach ($products as $key => $row)
                                    @php
                                        $item = $products->perPage() * ($products->currentPage() - 1) + ($key + 1);
                                    @endphp
                                    <tr>
                                        <td>{{ $item }}</td>
                                        <td>
                                            @if (count($row->galleries))
                                                @foreach ($row->galleries as $imgKey => $gallery)
                                                    @if ($imgKey == 0)
                                                        <a class="example-image-link"
                                                            href="{{ asset('storage/app/public/product_image/' . $row->gallery->gallery_image) }}"
                                                            data-lightbox="example-set{{ $row->id }}"><img
                                                                class="example-image rounded me-3"
                                                                src="{{ asset('storage/app/public/product_image/' . $row->gallery->gallery_image) }}"
                                                                alt="" height="48" width="48" /></a>
                                                    @else
                                                        <a class="example-image-link"
                                                            href="{{ asset('storage/app/public/product_image/' . $gallery->gallery_image) }}"
                                                            data-lightbox="example-set{{ $row->id }}"><img
                                                                class="example-image rounded me-3 d-none"
                                                                src="{{ asset('storage/app/public/product_image/' . $gallery->gallery_image) }}"
                                                                alt="" height="48" width="48" /></a>
                                                    @endif
                                                @endforeach
                                            @else
                                                <img src="{{ asset('public/assets/images/no_image.png') }}"
                                                    alt="contact-img" title="contact-img" class="rounded" />
                                            @endif
                                        </td>
                                        @php
                                            $avl_qty = 0;
                                            $avl_qty =
                                                $row->product_quantities_sum_quantity -
                                                ($row->product_orders_sum_qty + $row->productReductions->sum('qty'));
                                            if ($avl_qty != $row->quantity) {
                                                $row->update(['quantity' => $avl_qty]);
                                            }
                                            /* +$row->return_products_quantity_sum_qty */
                                        @endphp
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->bar_code }}</td>
                                        <td>{{ $row->product_code }}</td>
                                        <td>{{ $avl_qty }}</td>

                                        <td>
                                            <div class="plusminus">
                                                <button class="btn btn-primary" type="button"
                                                    wire:click.prevent="decrementQuantity({{ $row->id }},{{ $key }})"
                                                    style="margin: 0;">-</button>
                                                <input type="text"
                                                    wire:model="cart_quantity.{{ $key }}"> <button
                                                    class="btn btn-primary" type="button"
                                                    wire:click.prevent="incrementQuantity({{ $row->id }},{{ $key }})"
                                                    style="margin: 0;">+</button>
                                            </div>
                                        </td>
                                        <td>{{ env('CURRENCY', '₹') }}{{ $row->selling_price }}</td>
                                        <td style="display: flex;">
                                            @if ($row->is_discount)
                                                @if ($row->discount_type == 'Flat')
                                                    <p>{{ $row->discount_amt }}</p>
                                                @else
                                                    <p>{{ number_format($row->discount_amt) }}%={{ ($row->selling_price * $row->discount_amt) / 100 }}
                                                    </p>
                                                @endif
                                            @else
                                                <p>{{ number_format($setting->discount_percentage) }}%={{ ($row->selling_price * $setting->discount_percentage) / 100 }}
                                                </p>
                                            @endif
                                            <input type="text" wire:model="discount.{{ $key }}"
                                                style="height: 35px; margin-left: 10px;">
                                        </td>
                                        <td style="white-space: nowrap;">
                                            @if (Auth::user()->type == 'A' || in_array('add-to-cart', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" class="action-icon"
                                                    wire:click.prevent="addToCart({{ $row->id }}, {{ $key }})"
                                                    title="Add To Cart"><i class="mdi mdi-shopping-outline"></i></a>
                                            @endif
                                            @if (Auth::user()->type == 'A' || in_array('product-view', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" class="action-icon"
                                                    wire:click.prevent="viewProductData({{ $row->id }})"
                                                    title="View"><i class="mdi mdi-eye"></i></a>
                                            @endif
                                            @if (Auth::user()->type == 'A' || in_array('product-edit', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" class="action-icon"
                                                    wire:click.prevent="editProduct({{ $row->id }})"
                                                    title="Edit"><i class="mdi mdi-square-edit-outline"></i></a>
                                            @endif
                                            @if (Auth::user()->type == 'A' ||
                                                    in_array('product-delete', Auth::user()->permissions()->pluck('permission')->toArray()))
                                                <a href="javascript:void(0);" class="action-icon" id="warning"
                                                    wire:click.prevent="deleteAttempt({{ $row->id }})"
                                                    title="Delete"><i class="mdi mdi-delete"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="text-center">No records available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if ($products->hasMorePages())
                    <button wire:click.prevent.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                {{ $products->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->



    <a href="{{ route('add_to_cart') }}" class="btn btn-primary btn-lg cart-btn " role="button" title=""
        data-toggle="tooltip" data-original-title="cart">
        <i class="uil-shopping-cart-alt" id="cart">
            @if (isset($count_cart_item))
                {{ $count_cart_item }}
            @endif
        </i>
    </a>

    <script type="text/javascript">
        function printBarcode() {
            var divToPrint = document.getElementById('barcode_print_data');
            var popupWin = window.open('', '_blank', 'width=900,height=650');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();

        }
    </script>
    <script type="text/javascript">
        function PrintDiv() {
            var divToPrint = document.getElementById('print_data');
            var popupWin = window.open('', '_blank', 'width=900,height=650');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();

        }

        function PrintReturnDiv() {
            var divToReturnPrint = document.getElementById('print_return_data');
            var popupWin = window.open('', '_blank', 'width=900,height=650');
            popupWin.document.open();
            popupWin.document.write('<html><body onload="window.print()">' + divToReturnPrint.innerHTML + '</html>');
            popupWin.document.close();

        }
    </script>
</div>
