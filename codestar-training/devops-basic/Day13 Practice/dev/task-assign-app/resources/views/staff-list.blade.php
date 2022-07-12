@extends('layouts.app')

@section('content')

    <div class="container">
        
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('success') }}
            </div>
        @endif

        @include('components.staff-info-modal', ['staffId' => null, 'departments' => $departments ?? []])

                



        @include('components.custom-table', [
            'cols' => [
                'Tên' => 'name',
                'Ngày sinh' => 'dob',
                'SĐT' => 'phone',
                'Phòng ban' => 'department',
                'Vị trí' => 'position',
            ],
            'rows' => $staff ?? [], 
            'pagination' => true,

        ])

                




        
                
    </div>


    <script>
        $(document).ready(function() {

            $('.pagination').css('justify-content', 'center');

            $('tr.data-row').click(function() {
       
                const staffId = $(this).attr('id');
                
                let url = $('form').prop('action').split('/').slice(0, -1).join('/');

                $('form').prop('action', `${url}/${staffId}`);
                

                $('#staff-info-modal').modal('show').on('shown.bs.modal', function() {

                    $('#name').val($(`#${staffId} td.name`).html());
                    $('#dob').val($(`#${staffId} td.dob`).html());
                    $('#position').val($(`#${staffId} td.position`).html());
                    $('#phone').val($(`#${staffId} td.phone`).html());

                    $('select#department_id').prop('selectedIndex', -1);

                    const departmentName = $(`#${staffId} td.department`).html().trim();

                    $('#department_id option').each(function() {

                        if ($(this).html().trim() === departmentName) {
                            $(this).prop('selected', true);
                        }
                    });
                    
                    

                });





            });
        })



    </script>
    
@endsection


