<tr>
<td class="header">
<a href="https://hokiads.network" style="display: inline-block;">
@if (trim($slot) === 'Hoki Ads')
<img src="{{asset('images/logo/logo.png')}}" class="logo" alt="logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
