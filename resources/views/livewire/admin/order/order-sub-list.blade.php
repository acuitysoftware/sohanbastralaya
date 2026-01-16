<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex align-items-center justify-content-end">
                        <table class="table w-auto table-dark">
                            <tbody>

                                <tr>
                                    <th>Total Selling :</th>
                                    <td>Rs {{$total_selling}} </td>
                                </tr>
                                <tr>
                                    <th>Net Profit:</th>
                                    <td>Rs {{$total_profit}}</td>
                                </tr>
                                <tr>
                                    <th>Total Discount:</th>
                                    <td>Rs {{$total_discount}}</td>
                                </tr>

                                <tr>
                                    <th> Discount %:</th>
                                    <td>{{$discount_percentage}} %</td>
                                </tr>
                                <tr>
                                    <th>Profit %:</th>
                                    <td>{{$profit_percentage}} %</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>