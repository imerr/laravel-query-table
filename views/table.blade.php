<?php
/** @var \imer\QueryTable\QueryTable $table */
?>
{{-- Enable outputting of forms outside the table and then referencing them via the form attribute --}}
@foreach($table->results as $result)
    @foreach($table->fields as $i => $field)
        {!! $field->renderPreTableContent($result, $i)  !!}
    @endforeach
@endforeach
<form method="GET" action="{{request()->url()}}" id="{{$table->fieldName(\imer\QueryTable\QueryTable::FilterFormId)}}">
    @foreach(request()->query() as $k => $v)
        @if($table->skipFieldFilterForm($k))
            @continue
        @endif
        <input type="hidden" name="{{$k}}" value="{{$v}}">
    @endforeach
</form>
<table class="table">
    <thead>
    <tr>
        @foreach($table->fields as $field)
            <th scope="col">
                {{$field->getName()}}
                @if ($field->key && $field->sortable)
                    @php($dir = ($sortField == $field->key && $sortDirection == \imer\QueryTable\QueryTable::SortAsc) ? \imer\QueryTable\QueryTable::SortDesc : \imer\QueryTable\QueryTable::SortAsc)
                    <a href="{{$table->sortUrl($field->key, $dir)}}"><i
                                class="fa fa-angle-{{$dir == "asc" ? "up" : "down" }}"></i></a>
                @endif
            </th>
        @endforeach
    </tr>
    <tr>
        @foreach($table->fields as $field)
            <td>
                @if($field->filter)
                    {!! $field->filter->render($table, $field) !!}
                @endif
            </td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($table->results as $result)
        <tr>
            @foreach($table->fields as $i => $field)
                <td>{{ $field->renderValue($result, $i) }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
{!! $table->results->appends(request()->query())->links() !!}
