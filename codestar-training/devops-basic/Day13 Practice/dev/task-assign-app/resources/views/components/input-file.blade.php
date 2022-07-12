<label for="{{$name}}" class= "{{$labelClass ?? 'col-sm-2 col-form-label p-0'}}">{{$label}}</label>
<div class="{{$fileClass ?? 'col-sm-4 d-inline'}}">
    <button type="button" class="btn btn-primary" id="upload-btn" onclick="$('input:file').click();" >
        <i class="fas fa-upload"></i>
        <span> Tải tệp lên </span> 
    </button>
    <input type="file" name="{{$name}}" id="{{$name}}" class="form-control-file d-inline w-50" accept="image/*, .pdf" multiple>
</div>