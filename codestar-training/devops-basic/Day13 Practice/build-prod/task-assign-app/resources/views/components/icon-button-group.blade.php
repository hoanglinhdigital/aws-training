<div class="edit-button-group">
    @foreach ($buttonList as $buttonItem)
        <button type="submit" class="btn btn-light">
            <i class="fa fa-{{ $buttonItem ?? 'question' }}" aria-hidden="true"></i>
        </button>
    @endforeach
</div>
