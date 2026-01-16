<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-bordered mb-3">
                    <li class="nav-item">
                        <a href="#home-b1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                            <span class="d-none d-md-block">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact-b1" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                            <span class="d-none d-md-block">Contact</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#social-b1" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="mdi mdi-image-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Social Cover Image</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#calculations-b1" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="mdi mdi-calculator d-md-none d-block"></i>
                            <span class="d-none d-md-block">Calculations</span>
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane show active" id="home-b1">
                        <form wire:submit.prevent="saveTab1">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Site Title</label>
                                <input type="text" class="form-control" placeholder="Site Title" wire:model.defer="site_title">
                                @error('site_title') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Copyright Link</label>
                                <input type="text" class="form-control" placeholder="Copyright Link" wire:model.defer="copyright_link">
                                @error('copyright_link') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Site Mail</label>
                                <input type="text" class="form-control" placeholder="Site Mail" wire:model.defer="site_mail">
                                @error('site_mail') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Site Link</label>
                                <input type="text" class="form-control" placeholder="Site Link" wire:model.defer="site_link">
                                @error('site_link') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-3 col-md-6">

                                <div class="flex">
                                    <label class="form-label">Site Logo</label>
                                    <!-- File Upload -->
                                    <div wire:submit.prevent="save" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                                    data-upload-preview-template="#uploadPreviewTemplate">
                                    
                                    <div class="fallback">
                                        <input name="file" type="file" wire:model.defer="site_logo"/>
                                    </div>

                                    <div class="dz-message needsclick">
                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                        <h3>Upload File</h3>                                                            
                                    </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="dropzone-previews mt-3" id="file-previews"></div>

                                    <!-- file preview template -->
                                    <div class="d-none" id="uploadPreviewTemplate">
                                    <div class="card mt-1 mb-0 shadow-none border">
                                        <div class="p-2">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                                </div>
                                                <div class="col ps-0">
                                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                                    <p class="mb-0" data-dz-size></p>
                                                </div>
                                                <div class="col-auto">
                                                    <!-- Button -->
                                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                                        <i class="dripicons-cross"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                            </div>
                            @if($setting->site_logo)
                            <div class="mb-3 col-md-6" style="background-color: #009CEC; height: 102px; margin-top: 30px;">
                                <img src="{{asset('storage/app/public/'.$setting->site_logo) }}" width="100%" class="mt-2">
                            </div>
                            @endif
                            <div class="mb-3 col-lg-12">
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                        </form>

                    </div>
                    <div class="tab-pane" id="contact-b1">
                        <form wire:submit.prevent="saveTab2">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Contact No</label>
                                <input type="text" class="form-control" placeholder="Contact No" wire:model.defer="contact_no">
                                @error('contact_no') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-lg-12">
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="social-b1">
                        <form wire:submit.prevent="saveTab3">
                        <div class="row">
                            <div class="mb-3 col-md-6">

                                <div class="flex">
                                    <label class="form-label">Site Logo</label>
                                    <!-- File Upload -->
                                    <div action="/" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                                    data-upload-preview-template="#uploadPreviewTemplate">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple wire:model.defer="site_logo"/>
                                    </div>

                                    <div class="dz-message needsclick">
                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                        <h3>Upload File</h3>                                                            
                                    </div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="dropzone-previews mt-3" id="file-previews"></div>

                                    <!-- file preview template -->
                                    <div class="d-none" id="uploadPreviewTemplate">
                                    <div class="card mt-1 mb-0 shadow-none border">
                                        <div class="p-2">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                                                </div>
                                                <div class="col ps-0">
                                                    <a href="javascript:void(0);" class="text-muted fw-bold" data-dz-name></a>
                                                    <p class="mb-0" data-dz-size></p>
                                                </div>
                                                <div class="col-auto">
                                                    <!-- Button -->
                                                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                                                        <i class="dripicons-cross"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                            </div>
                            @if($setting->site_logo)
                            <div class="mb-3 col-md-6" style="background-color: #009CEC; height: 102px; margin-top: 30px;">
                                <img src="{{asset('storage/app/public/'.$setting->site_logo) }}" width="100%" class="mt-2">
                            </div>
                            @endif
                            <div class="mb-3 col-lg-12">
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="calculations-b1">
                        <form wire:submit.prevent="saveTab4">
                        <div class="row">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Purchase Price to get membership card</label>
                                <input type="text" class="form-control" placeholder="Purchase Price to get membership card" wire:model.defer="target_purchase">
                                @error('target_purchase') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Min Price to use wallet</label>
                                <input type="text" class="form-control" placeholder="Min Price to use wallet" wire:model.defer="min_price_wallet">
                                @error('min_price_wallet') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">1 Points is equal to Rs</label>
                                <input type="text" class="form-control" placeholder="1 Points is equal to Rs" wire:model.defer="one_points">
                                @error('one_points') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Points for purchasing Rs 100</label>
                                <input type="text" class="form-control" placeholder="Points for purchasing Rs 100" wire:model.defer="target_points">
                                @error('target_points') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Min Points to use wallet</label>
                                <input type="text" class="form-control" placeholder="Min Points to use wallet" wire:model.defer="min_points_wallet">
                                @error('min_points_wallet') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Min Points in wallet</label>
                                <input type="text" class="form-control" placeholder="Min Points in wallet" wire:model.defer="min_points">
                                @error('min_points') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Card Expires in days</label>
                                <input type="text" class="form-control" placeholder="Card Expires in days" wire:model.defer="expire_days">
                                @error('expire_days') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Min Purchase</label>
                                <input type="text" class="form-control" placeholder="Min Purchase" wire:model.defer="min_purchase">
                                @error('min_purchase') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Min Purchase Points</label>
                                <input type="text" class="form-control" placeholder="Min Purchase Points" wire:model.defer="min_purchase_points">
                                @error('min_purchase_points') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Carry Charge</label>
                                <input type="text" class="form-control" placeholder="Carry Charge" wire:model.defer="carry_charge">
                                @error('carry_charge') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Discount Percentage</label>
                                <input type="text" class="form-control" placeholder="Discount Percentage"wire:model.defer="discount_percentage">
                                @error('discount_percentage') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-lg-12">
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>

                    
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>