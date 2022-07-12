@extends('components.modal', [
    'title' => 'File',
    'id' => 'file-modal'
])

@section('modal-body')
    @include('components.dynamic-table', [
        'cols' => [
            'Tên file' => '',
        ],
        'id' => 'files',
        'rows' => []
    ])

    <div id="file-content" style="display: none">
        <iframe src="" frameborder="0" style="width: 100%"></iframe>
    </div>
@endsection
