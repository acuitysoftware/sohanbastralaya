@props(['name' => $name, "target"=> $target])
<button class="btn btn-primary"  wire:loading.attr="disabled"  wire:loading.class="opacity-50" id="submitBtn">
    <span >{{ $name }}</span>
    <span wire:loading wire:target="{{ $target }}" >
        <div class="text-center" >
            <div class="spinner-border text-white spinner-border-sm" role="status">
            </div>
        </div>
    </span>
</button>
