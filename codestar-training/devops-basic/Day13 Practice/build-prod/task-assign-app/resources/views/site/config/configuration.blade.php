@extends('layouts.app')

@section('content')
    <div class="container">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Cấu hình giao việc</legend>
            
            @include('components.flash-message')
            
            <form action="{{route('config.update')}}" class="offset-4" method="POST">
                @csrf

                @foreach ($configurations as $config)
    
                    <div class="form-group-row mb-3">
                        @include('components.select-yes-no', [
                            'name' => $config->field, 
                            'label' => $config->note,
                            'checked' => $config->value,
                        ])
                    </div>

                @endforeach
            
                <button type="submit" class="btn btn-primary offset-2">
                    <i class="fas fa-save"></i>
                    <span>Lưu</span>
                </button>

            </form>
            
        </fieldset>
    </div>
@endsection