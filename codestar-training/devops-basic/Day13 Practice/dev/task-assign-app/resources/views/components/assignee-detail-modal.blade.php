<div class="modal fade" id="assignee-detail-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Chi tiết đối tượng xử lý</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        
            @include('components.dynamic-table', [
                'cols' => [
                    'Người giao việc' => 'assigner',
                    'Người đánh giá' => 'evaluator',
                    'Đối tượng xử lý' => 'assignee',
                    'Hình thức xử lý' => 'process_method',
                    'Lịch sử chuyển tiếp' => 'history',
                    'Trạng thái' => 'status'
                ],
                'id' => 'assignee-detail-table',
                'rows' => []
            ])
            
        </div>

    </div>
    </div>
</div>
