@isset($id)
    <div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
@else 
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
@endisset
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ $title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            
        
            @yield('modal-body')
            
        </div>

    </div>
    </div>
</div>
