@extends('layouts.app')

@section('content')

    <div class="container">
        @include('components.flash-message')

        <form action="{{ route('accounts.activate') }}" method="POST">
            @csrf
            
            @include('components.custom-table', [
                'cols' => [
                    'Địa chỉ e-mail' => 'email',
                    'Tên' => 'name',
                    'checkbox' => 'account_ids[]',
                ],
                'rows' => $pendings, 
                'pagination' => true

            ])

            <div class="button-group">
                <button type="submit" class="btn btn-primary offset-5 mt-3" name="action" value="accept" disabled>
                    <i class="fas fa-save"></i>
                    <span>Kích hoạt</span>
                </button>

            </div>
            
            
        </form>



        
                
    </div>

    <script src="{{ asset('js/tableCheckbox.js') }}"></script>
    <script>
            handleHeadCheckboxClick('thead input:checkbox', 'tbody input:checkbox');
            handleBodyCheckboxClick('thead input:checkbox', 'tbody input:checkbox', function(headSelector, bodySelector, element) {
                if (element.checked) {
 
                    const accountId = $(element).closest('tr').attr('id');
					
                    $(element).val(accountId);
                    $('button[value="accept"]').prop('disabled', false);
                    
                    if ($(bodySelector).not($('input:checkbox:checked')).length === 0) {
                        $(headSelector).prop('checked', true);
                    }
                }
                else{
                    $(headSelector).prop('checked', false);
                    $(element).removeAttr('value');
                    if ($('tbody input:checkbox:checked').length === 0) {
                        $('button[value="accept"]').prop('disabled', true);
                    }
                }
            });
    </script>
    
@endsection


