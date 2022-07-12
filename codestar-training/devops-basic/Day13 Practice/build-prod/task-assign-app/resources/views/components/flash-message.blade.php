@if(session('success'))
    <div class="alert alert-info  alert-dismissible">
        <strong>{{session('success')}}!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger  alert-dismissible">
        <strong>{{session('error')}}!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
    </div>
@endif
