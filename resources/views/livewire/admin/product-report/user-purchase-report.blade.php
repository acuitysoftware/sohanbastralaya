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



                <form wire:submit.prevent="save">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label class="form-label">Note</label>
                            <input type="text" wire:model.defer="note" class="form-control" placeholder="Note">
                            @error('note')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label">Date</label>
                            <input class="form-control" wire:model.defer="date" type="date" name="date"
                                placeholder="Date From">
                            @error('date')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label">Purchase Price</label>
                            <input type="text" wire:model.defer="purchase_price" class="form-control"
                                placeholder="Purchase Price">
                            @error('purchase_price')
                                <span class="text-danger error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-danger mt-4 ">Submit</button>
                        </div>


                    </div>
                </form>



                <div class="row mb-2">
                    <div class="col-xl-9">
                        <div class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" wire:model.lazy="dateForm"
                                        placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <input class="form-control" type="date" wire:model.lazy="dateTo"
                                        placeholder="Date From">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-danger" wire:click="resetSearch">All</button>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-xl-3">

                    </div><!-- end col-->
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
                                <th>SL No.</th>
                                <th>Note</th>
                                <th>Date</th>
                                <th>Purchase Price</th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody>
                            @if (count($reports) > 0)
                                @foreach ($reports as $key => $row)
                                    @php
                                        $item = $reports->perPage() * ($reports->currentPage() - 1) + ($key + 1);
                                    @endphp
                                    <tr>
                                        <td>{{ $item }}</td>
                                        <td>{{ $row->note }}</td>
                                        <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                                        <td>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($row->purchase_price) }}</td>
                                        <td style="white-space: nowrap;">
                                            <a href="javascript:void(0);" class="action-icon"
                                                wire:click="edit({{ $row->id }})"><i
                                                    class="mdi mdi-square-edit-outline"></i></a>
                                            <a href="javascript:void(0);" class="action-icon" id="warning"
                                                wire:click="deleteAttempt({{ $row->id }})"><i
                                                    class="mdi mdi-delete"></i></a>
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
                {{-- @if ($reports->hasMorePages())
                    <button wire:click.prevent="loadMore" class="btn btn-primary">Load more</button>
                @endif --}}
                {{ $reports->links() }}

                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-end">

                        <table class="table w-auto table-dark">
                            <tbody>

                                <tr>
                                    <th>Total Purchase Amount : </th>
                                    <th>{{ env('CURRENCY', '₹') }}{{ convert_numbers_to_indian_format($reports->sum('purchase_price')) }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
    <!-- Purchase Modal -->
    <div wire:ignore.self id="editPurchaseReport" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel">Purchase Amount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form wire:submit.prevent="updateReport">
                    <div class="modal-body">
                        <div class="row mt-2 mb-2">
                            <input type="hidden" wire:model.defer="report_id">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Note</label>
                                <input type="text" wire:model.defer="edit_note" class="form-control"
                                    placeholder="Note">
                                @error('edit_note')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" wire:model.defer="edit_date" class="form-control"
                                    placeholder="Enter Name">
                                @error('edit_date')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label class="form-label">Purchase Price</label>
                                <input type="text" wire:model.defer="edit_purchase_price" class="form-control"
                                    placeholder="Purchase Price">
                                @error('edit_purchase_price')
                                    <span class="text-danger error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>


            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- end row -->
