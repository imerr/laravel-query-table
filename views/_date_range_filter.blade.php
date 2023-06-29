@php($formId = $table->fieldName(\imer\QueryTable\QueryTable::FilterFormId))

<div class="input-group">
    <button class="btn btn-outline-secondary" type="submit" form="{{$formId}}">
        <i class="fa fa-search"></i>
    </button>
    <input type="date" form="{{$formId}}" name="{{$fieldNameFrom}}"
           value="{{request()->query($fieldNameFrom)}}" class="form-control">

    <span class="input-group-text"> - </span>
    <input type="date" form="{{$formId}}" name="{{$fieldNameTo}}"
           value="{{request()->query($fieldNameTo)}}" class="form-control">
</div>


