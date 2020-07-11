@if ($link)
<tr>
    <td><a href="{{ route('short_link', array('id' => $link->short_link)) }}">{{ $link->short_link }}</a></td>
    <td><a href="{!! $link->link !!}">{{ $link->link }}</a></td>
    <td>
        @if($link->active)
            active
        @else
            deactivated
        @endif
    </td>
    @if(Route::currentRouteName() === 'user_link')
        <td><a href="{{ route('edit_link', array('id' => $link->short_link)) }}">edit</a></td>
        <td><a href="{{ route('delete_link', array('id' => $link->short_link)) }}">delete</a></td>
    @endif
    @moderatorAndPath
        @if($link->active)
        <td><a href="{{ route('deactivate_link', array('id' => $link->short_link)) }}">deactivate</a></td>
        @else
        <td><a href="{{ route('activate_link', array('id' => $link->short_link)) }}">activate</a></td>
        @endif
    @endModeratorAndPath
</tr>
@endif