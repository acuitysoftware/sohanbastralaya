<div class="row">
     <!-- My Modal -->
<div wire:ignore.self id="productImageUpload" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="primary-header-modalLabel">Products</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form wire:submit.prevent="save">
            <input type="hidden" wire:model.defer="state.product_id">
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Product Image</label>
                        <input type="file" id="example-fileinput" class="form-control" accept="image/*" wire:model.defer="state.image">
                        @error('image') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    @if(isset($state['gallery_image']))
                        <div class="mb-3 col-md-6">
                            <img src="{{asset('storage/app/public/product_image/'.$state['gallery_image']) }}" height="100px" >
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Submit</button>
            </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <div class="row mb-2">
                    <div class="col-xl-8">
                        @if(Auth::user()->type=='A')
                        <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <!-- <label for="status-select" class="me-2">Status</label> -->
                                    <select class="form-select" wire:model="storeUser">
                                        <option value="" selected>Select Store</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>
                        </form>   
                        @endif                         
                    </div>
                    <div wire:loading wire:target="storeUser">
                        <div class="loader_sectin" id="loader_section" >
                            <div class="loader_overlay"></div>
                            <div id="loader" class="center" ></div>
                        </div>                
                    </div>
                    <div wire:loading wire:target="resetSearch">
                        <div class="loader_sectin" id="loader_section" >
                            <div class="loader_overlay"></div>
                            <div id="loader" class="center" ></div>
                        </div> 
                    </div>
                    <div wire:loading wire:target="loadMore">
                        <div class="loader_sectin" id="loader_section" >
                            <div class="loader_overlay"></div>
                            <div id="loader" class="center" ></div>
                        </div> 
                    </div>
                    <div class="col-xl-4">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-end">
                            <div class="col-auto">
                                <label for="inputPassword2" class="visually-hidden">Search</label>
                                <input type="search" class="form-control" id="inputPassword2" wire:model="searchName" placeholder="Search...">
                            </div>                                                
                        </div>
                    </div><!-- end col-->
                </div>



                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap"
                        id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>SL No.</th>
                                <th>Image</th>
                                <th>Products Name</th>
                                <th>Code</th>
                                <th>Product Qty</th>
                                <th>Selling Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(count($products)>0)
                        	@foreach($products as $key=>$row)
                           @php
                                    $item = ($products->perPage() * ($products->currentPage() - 1)) + ($key+ 1);
                                 @endphp
                                    <tr>
                                        <td>{{ $item }}</td>
                                <td>
                                    @if(isset($row->gallery))
                                    <a data-fancybox="gallery" href="{{asset('storage/app/public/product_image/'.$row->gallery->gallery_image) }}">
                                        <img src="{{asset('storage/app/public/product_image/'.$row->gallery->gallery_image) }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    </a>
                                    @else
                                        <img src="{{asset('public/assets/images/no_image.png') }}" alt="contact-img"
                                            title="contact-img" class="rounded me-3" height="48" />
                                    @endif
                                </td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->product_code}}</td>
                                <td>{{$row->quantity}}</td>                                                    
                                <td>{{$row->selling_price}}</td>
                                <td style="white-space: nowrap;">
                                    <a href="javascript:void(0);" class="action-icon" wire:click="editProductImage({{$row->id}})" title="Upload Image"><i class="mdi mdi-square-edit-outline"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                            	<td colspan="7" class="text-center">No records available</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
               {{--  @if($products->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                 {{ $products->links() }}
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->

   
</div>