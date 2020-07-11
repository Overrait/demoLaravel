<td>{{ $user->email }}</td>
<td>
@foreach($user->roles as $role)
    {{ $role->name }}
    @unless ($loop->last)
    ,<br />
    @endunless
@endforeach
</td>
<td>
    <form method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <button type="submit" class="btn btn-primary">
            @if($user->hasRole('moderator'))
                delete role moderator
            @else
                add role moderator
            @endif
        </button>
    </form>
</td>