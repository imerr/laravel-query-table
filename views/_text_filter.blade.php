@php($formId = $table->fieldName(\imer\QueryTable\QueryTable::FilterFormId))

<div class="input-group">
    <button class="btn btn-outline-secondary" type="submit" form="{{$formId}}">
        <i class="fa fa-search"></i>
    </button>
    <input type="text" form="{{$formId}}" name="{{$fieldName}}"
           value="{{request()->query($fieldName)}}" class="form-control">
</div>


