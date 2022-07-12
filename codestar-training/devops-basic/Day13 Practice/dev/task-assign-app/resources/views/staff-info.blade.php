@extends('layouts.app')


@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Thông tin cá nhân
                <a class="btn btn-link float-right" id="edit-btn" style="color: black"><i class="fas fa-edit"></i></a>
            </div>

            @if(session()->has('success'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ session()->get('success') }}
                </div>
            @endif

            <div class="card-body">
                <form method="POST" action="{{ route('staff_info.update', ['id' => Auth::user()->staff_id]) }}">
                    @csrf
                    <input id="gender-hidden" type="hidden" class="form-control" name="gender" value="{{ $staffInfo->gender ?? old('gender') }}" readonly>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Tên</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $staffInfo->name ?? old('name') }}" autocomplete="name" autofocus readonly>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>



                    <div class="form-group row">
                        <label for="dob" class="col-md-4 col-form-label text-md-right"> Ngày sinh </label>
                        <div class="col-md-6">
                            <input id="dob" type="text" class="form-control" name="dob" value="{{ $staffInfo->date_of_birth ?? old('dob') }}" readonly>
                            
                        </div>

                    </div>

                    <div class="form-group row">
                        <label for="gender-option" class="col-md-4 col-form-label text-md-right">Giới tính</label>
                        

                        
                        <div class="col-md-6 d-flex" id="gender-option">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender-male" value="male" disabled>
                                <label class="form-check-label" for="gender-male">Nam</label>
                            </div>
                            
                            <div class="form-check form-check-inline ml-3">
                                <input class="form-check-input" type="radio" name="gender" id="gender-female" value="female" disabled>
                                <label class="form-check-label" for="gender-female">Nữ</label>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-md-4 col-form-label text-md-right"> Địa chỉ </label>

                        <div class="col-md-6">
                            <input id="address" type="text" class="form-control" name="address" value="{{ $staffInfo->address ?? old('address') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-md-4 col-form-label text-md-right"> SĐT </label>

                        <div class="col-md-6">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $staffInfo->phone ?? old('phone') }}" readonly>
                        
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                        
                            @enderror
                        </div>

                    </div>

                    






                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" style="display: none">
                                Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        const gender = $('#gender-hidden').val();
        $(`.form-check-input[value="${gender}"]`).prop('checked', true);

        $('#edit-btn').click(function() {
            $('input').removeAttr('readonly');

            $('#dob').prop('type', 'date');


            $(`.form-check-input[value="male"]`).prop('disabled', false);
            $(`.form-check-input[value="female"]`).prop('disabled', false);


            $('button:submit').show();


        });

    });

</script>
    
@endsection