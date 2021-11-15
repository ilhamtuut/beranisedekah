<tr>
<td class="header">
<a href="#" style="display: inline-block;">
@if (trim($slot) === 'Berani Sedekah')
<img src="{{asset('images/logo.png')}}" class="logo" alt="logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
