@props(['url'])
<tr>
<td class="header" style="text-align: center; padding: 25px 0;">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
<img src="{{ isset($message) ? $message->embed(public_path('images/renmote-biru.png')) : asset('images/renmote-biru.png') }}" alt="Renmote Logo" style="height: 38px; max-height: 38px; width: auto; display: block; margin: 0 auto;">
</a>
</td>
</tr>
