@inject('repository', '\Ipunkt\Laravel\SocialiteProfiles\Repositories\SocialConnectionsRepository')

<ul class="list-inline">
	@foreach ($repository->allForLogin()->all() as $provider => $data)
		<li><a href="{{ app('url')->forceSchema(config('socialite-profiles.force_schema')) . route('social.login', ['provider' => $provider]) }}" class="btn"><i class="fa fa-{{ $provider }}"></i> {{ $data['title'] or ucwords($provider) }}</a></li>
	@endforeach
</ul>