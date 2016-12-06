<?php
/**
 * @param $except \Illuminate\Database\Eloquent\Collection collection for excluding providers
 */
?>
@inject('repository', '\Ipunkt\Laravel\SocialiteProfiles\Repositories\SocialConnectionsRepository')

<ul class="list-inline">
	@foreach ($repository->allForLogin($except->pluck('provider'))->all() as $provider => $data)
		<li><a href="{{ app('url')->forceSchema(config('socialite-profiles.force_schema')) . route('social.login', ['provider' => $provider]) }}" class="btn"><i class="fa fa-{{ $provider }}"></i> Attach {{ $data['title'] or ucwords($provider) }} profile</a></li>
	@endforeach
</ul>