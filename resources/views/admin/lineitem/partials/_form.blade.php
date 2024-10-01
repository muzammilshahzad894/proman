<form action="{{ $url }}" method="POST" role="form" class="ajax-submission">
    @csrf
    <div class="form-group">
        <label class="field-required">Title</label>
        <input 
            type="text" 
            name="title" 
            class="form-control input-half" 
            value="{{ old('title') ? old('title') : (isset($lineitem) ? $lineitem->title : '') }}">
    </div>

    <div class="form-group">
        <label class="field-required">Type</label>
        <div class="radio">
            <label>
                <input
                    type="radio" 
                    name="type" 
                    id="input" 
                    value="Fixed" 
                    checked
                /> Fixed
            </label>
        </div>

        <div class="radio">
            <label>
                <input 
                    type="radio" 
                    name="type" 
                    id="input" 
                    value="Percentage"
                    @if(old('type') ? old('type') == 'Percentage' : (isset($lineitem) ? $lineitem->type == 'Percentage' : false)) checked @endif
                /> Percentage
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="field-required">If it is percentage, Apply to</label>
        <div class="radio">
            <label>
                <input 
                    type="radio" 
                    name="percentage_apply_type" 
                    id="input" 
                    value="Base" 
                    checked
                /> Base
            </label>
        </div>
        <div class="radio">
            <label>
                <input 
                    type="radio" 
                    name="percentage_apply_type" 
                    id="input" 
                    value="Sum"
                    @if(old('percentage_apply_type') ? old('percentage_apply_type') == 'Sum' : (isset($lineitem) ? $lineitem->percentage_apply_type == 'Sum' : false)) checked @endif
                /> Sum
            </label>
        </div>
    </div>
    <div class="form-group">
        <label>Value </label>
        <input 
            type="text" 
            class="form-control input-half" 
            name="value"
            value="{{ old('value') ? old('value') : (isset($lineitem) ? $lineitem->value : '') }}"
        />
    </div>

    <div class="form-group input-3rd">
        <label class="field-required">Display Order</label>
        <input 
            type="text" 
            name="display_order" 
            onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
            class="form-control" 
            value="{{ old('display_order') ? old('display_order') : (isset($lineitem) ? $lineitem->display_order : '') }}"
        />
    </div>

    <button type="submit" class="btn btn-success">{{ $submitBtnText }} </button>
</form>