@php($formId = $table->fieldName(\imer\QueryTable\QueryTable::FilterFormId))

<div class="input-group">
    <button class="btn btn-outline-secondary" type="submit" form="{{$formId}}">
        <i class="fa fa-search"></i>
    </button>
    <select form="{{$formId}}" name="{{$fieldName}}" class="form-control">
        <option> - </option>
        @foreach($values as $key => $value)
            <option value="{{$key}}"
                @if((string)$key === request()->query($fieldName, $defaultFilter))
                        selected="selected"
                @endif
            >{{$value}}</option>
        @endforeach
    </select>
</div>

