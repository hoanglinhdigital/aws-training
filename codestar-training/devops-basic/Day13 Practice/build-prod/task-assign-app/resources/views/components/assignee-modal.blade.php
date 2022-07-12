<div class="modal fade" id="assignee-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Đối tượng xử lý</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST">
                @csrf

                <div class="form-group-row mb-3">
                    @include('components.input-text', [
						'name' => 'id',
						'label' => 'Mã ĐT',
					])
					@include('components.input-text', [
						'name' => 'assignee_name',
						'label' => 'Tên',
					])

                </div>

                <div class="text-center">
                    <button type="button" id="search-reset-btn" class="btn btn-light">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>



            </form>
        
            @include('components.dynamic-table', [
                'cols' => [
                    'Mã ĐT' => 'id',
                    'Tên' => 'name',
                    '' => 'pattern.tick'
                ],
                'id' => 'assignee-list',
                'rows' => $staff
            ])
            
        </div>

    </div>
    </div>
</div>
