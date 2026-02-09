<script type="text/javascript">
/*window.onscroll = function (ev) {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
        window.livewire.emit('loadMore');
    }
};*/

window.addEventListener('show-user-add-form', event => {
            $('#addUserModal').modal('show');
        });
window.addEventListener('view-barcode', event => {
            $('#barcodeModal').modal('show');
        });
window.addEventListener('view-due-amount', event => {
            $('#viewDueAmountModal').modal('show');
        });
window.addEventListener('edit-view-barcode', event => {
            $('#editBarcodeModal').modal('show');
        });
window.addEventListener('show-user-access-menu', event => {
            $('#userAccessMenu').modal('show');
        });

window.addEventListener('show-product-image-form', event => {
            $('#productImageUpload').modal('show');
        });
window.addEventListener('show-product-edit-form', event => {
            $('#productEdit').modal('show');
        });
window.addEventListener('show-product-view-form', event => {
            $('#productView').modal('show');
        });
window.addEventListener('hide-product-image-form', event => {
            $('#productImageUpload').modal('hide');
        });
window.addEventListener('show-order-view-form', event => {
            $('#orderView').modal('show');
        });
window.addEventListener('show-return-product-form', event => {
            $('#returnProduct').modal('show');
        });
window.addEventListener('show-subadmin-orders', event => {
            $('#subadminOrders').modal('show');
        });
window.addEventListener('show-subadmin-order-details', event => {
            $('#subadminOrderDetails').modal('show');
        });
window.addEventListener('show-product-order-form', event => {
            $('#productOrder').modal('show');
        });
window.addEventListener('show-membership-details', event => {
            $('#showMembership').modal('show');
        });
window.addEventListener('show-membership-card-edit', event => {
            $('#editMembershipCard').modal('show');
        });

window.addEventListener('show-stock-report', event => {
            $('#stockReport').modal('show');
        });
window.addEventListener('show-stock-inserted', event => {
            $('#stockInserted').modal('show');
        });
window.addEventListener('show-edit-purchase-report', event => {
            $('#editPurchaseReport').modal('show');
        });
window.addEventListener('show-customer-details', event => {
            $('#showCustomerDetails').modal('show');
        });


    //Advertisements//
   /* (adsbygoogle = window.adsbygoogle || []).push({});*/
    
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
    //   "positionClass": "toast-bottom-center",
      "preventDuplicates": false,
      "onclick": null,
     "showDuration": "300",
     "hideDuration": "1000",
      "timeOut": "9000",
     "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif


    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif


    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif


    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    window.addEventListener('modal-open', event  => {
            $('#delete_confirm_modal').modal('show');
    });

    
    window.addEventListener('toastr', event  => {
            alertMsg(event.detail.msg,event.detail.type);
    });

    function alertMsg($msg,$type){
        switch($type){
            case 'success':
                toastr.success($msg);
                break;
            case 'info':
                toastr.info($msg);
                break;
            case 'warning':
                toastr.warning($msg);
                break;
            case 'error':
                toastr.error($msg);
                break;
        }
    }

    const SwalModal = (icon, title, html) => {
        Swal.fire({
            icon,
            title,
            html
        })
    }

    const SwalConfirm = (icon, title, html, confirmButtonText, method, params, callback) => {
        Swal.fire({
            icon,
            title,
            html,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText,
            reverseButtons: true,
        }).then(result => {
            if (result.value) {
                return livewire.emit(method, params)
            }

            if (callback) {
                return livewire.emit(callback)
            }
        })
    }

    document.addEventListener('DOMContentLoaded', () => { 
        this.livewire.on('swal:modal', data => {
                SwalModal(data.type, data.title, data.text)
        })

        this.livewire.on('swal:confirm', data => {
            SwalConfirm(data.type, data.title, data.text, data.confirmText, data.method, data.params, data.callback)
        })

    })

$('.modalClose').click(function(){
        location.reload();
    })

function number_check(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
    }
function decimal_number_check(event) {
        var charCode = (event.which) ? event.which : event.keyCode
        if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                    $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
            return false;
        else
            return true;
    }
</script>