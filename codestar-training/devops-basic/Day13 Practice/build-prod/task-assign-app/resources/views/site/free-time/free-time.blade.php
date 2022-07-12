@extends('layouts.app')

@section('content')
    <div class="container">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Tìm kiếm kế hoạch/thời gian rảnh</legend>
            @include('components.flash-message')
            <div class="row">

                <div class="col-md-12">
                    <div class="row mb-4 ml-0">
                        <form action="{{route('free-time.search')}}" class="w-100" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="form-group-row mb-3">
                                        @include('components.select', [
                                            'name' => 'type',
                                            'label' => 'Loại',
                                            'options' => [
                                                ['id' => 0, 'name' => 'Kế hoạch thực hiện'],
                                                ['id' => 1, 'name' => 'Thời gian rảnh'],
                                            ],
                                            'checked' => old('type')
                                        ])
                                        @include('components.input-number', [
                                            'name' => 'free_time',
                                            'label' => 'Rảnh từ (ngày)',
                                        ])
                                    </div>
                                    <div class="form-group-row mb-3">
                                        @include('components.input-date', [
                                            'name' => 'from_date',
                                            'label' => 'Từ ngày',
                                            'type' => 'date',
                                        ])
                                    </div>
                                    <div class="form-group-row mb-3">
                                        @include('components.input-date', [
                                            'name' => 'to_date',
                                            'label' => 'Đến ngày',
                                            'type' => 'date',
                                        ])
                                    </div>
                                    <div class="form-group-row mb-3">
                                        @include('components.searchable-input-text', [
                                            'name' => 'staff_id',
                                            'label' => 'Đối tượng',
                                            'options' => $staffs ?? []
                                        ])
                                        <input type="hidden" name="hidden_staff_id" id="hidden_staff_id" value="{{ session('hidden_staff_id') }}">
                                    </div>
                                    <div class="form-group-row mt-5">
                                        @include('components.button-group', [
                                             'buttons' => [
                                                 ['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search']
                                             ]
                                         ])
                                        <a href="{{route('free-time.list')}}"> <i class="fas fa-sync-alt"></i> Reset</a>
                                    </div>
                                </div>


                        </form>
                    </div>
                </div>

            </div>

        </fieldset>
        
        @include('components.dynamic-table', [
            'cols' => [
                'Đối tượng' => 'name',
                'Phòng ban' => 'department',
                'Tổng manday' => 'total_mandays',
                'Tổng giờ' => 'total_hours',
                ],
            'rows' => $assignees ?? [],
            'pagination' => true
        ])

        <div class="text-center mt-5">
            {{-- TODO: implement workplan detail --}}
            <a href="#" class="btn btn-primary" id="workplan-detail"><i class="fas fa-info-circle"></i> Xem chi tiết kế hoạch</a>
        </div>

    </div>

    <script src="{{ asset('js/job-crud/jobFormInput.js') }}"></script>
    <script>

        $(document).ready(function() {

            $('#workplan-detail').hide();
            $('ul.pagination').css('justify-content', 'center');

            setSelectedValue('#staff_id', $('#hidden_staff_id').val());

            $('#staff_id').change(function() {
                $('#hidden_staff_id').val($(this).val());
            });
            

        });
    </script>

@endsection