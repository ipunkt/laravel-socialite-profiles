<?php
/**
 * @param $provider string Provider to detach
 * @param $label string, optional Button Label
 */
?>
<form class="form-inline" action="{{ route('social.detach', ['provider' => $provider]) }}" method="post">{{ csrf_field() }}{{ method_field('DELETE') }}
	<input type="submit" value="{{ isset($label) ? $label : 'Detach Profile' }}" class="btn btn-xs btn-link pull-right">
</form>