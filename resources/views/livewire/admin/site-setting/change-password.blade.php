<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="save">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Old Password</label>
                        <input type="text" class="form-control" placeholder="Old Password" wire:model="old_password">
                        @error('old_password') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">New Password</label>
                        <input type="text" class="form-control" placeholder="New Password" wire:model="password">
                        @error('password') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="text" class="form-control" placeholder="Confirm Password" wire:model="password_confirmation">
                        @error('password_confirmation') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>

                </div>

                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>