<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	<div class="row mb-2" style="background-color: #009CEC;">
                    <div class="col-xl-9">
	            		<h4 class="customer_view_title" id="primary-header-modalLabel">Orders of {{@$customer_details[0]->customer_name}}</h4>
	            	</div>
	            	<div class="col-xl-3 mt-2">
	            		<a href="{{route('customer.index')}}?perNo={{$perNo}}" type="button" class="btn btn-danger" data-bs-dismiss="modal" style="float: right;">Back</a > 
	            	</div>
	            </div>
          
                <div class="table-responsive">
                    <table class="table table-bordered table-centered w-100 dt-responsive nowrap" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Bill Amount</th>
                            </tr>
                        </thead>
                        @php
                            $total =0;
                        @endphp
                        <tbody>
                            @if(count($customer_details)>0)
                            @foreach($customer_details as $key=>$row)
                            @php
                            $total+=$row->subtotal;
                        @endphp
                           
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                <td>{{$row->order_id}}</td>
                                <td>{{date('d/m/Y',strtotime(@$row->order_date)) }} </td>
                                <td>{{$row->subtotal}}</td>
                                
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td colspan="3">Total</td>
                                <td colspan="1">{{number_format($total,2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    

function PrintReturnDiv() {
    var divToReturnPrint = document.getElementById('print_return_data');
    var popupWin = window.open('', '_blank', 'width=900,height=650');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToReturnPrint.innerHTML + '</html>');
    popupWin.document.close();

}
</script>
</div>
