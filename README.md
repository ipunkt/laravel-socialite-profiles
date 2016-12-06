# Socialite Profiles

[![Total Downloads](https://poser.pugx.org/ipunkt/laravel-socialite-profiles/d/total.svg)](https://packagist.org/packages/ipunkt/laravel-socialite-profiles)
[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-socialite-profiles/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-socialite-profiles)
[![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-socialite-profiles/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-socialite-profiles)
[![License](https://poser.pugx.org/ipunkt/laravel-socialite-profiles/license.svg)](https://packagist.org/packages/ipunkt/laravel-socialite-profiles)

## Introduction

Socialite profiles extends the Laravel Socialite package by allowing multiple social network profiles being attached at the user. Attaching and detaching can be done after being logged in.

## Installation

Just install the package on your authorization server

	composer require ipunkt/laravel-socialite-profiles

and add the Service Provider in your `config/app.php`

	\Ipunkt\Laravel\SocialiteProfiles\DonePMSocialiteProfilesServiceProvider::class,

Then use `@include('socialite-profiles::logins')` in your login blade template to display all possible configured social logins.

Run `php artisan vendor:publish --provider="\Ipunkt\Laravel\SocialiteProfiles\SocialiteProfilesServiceProvider"`

## Configuration

### `redirect-when-user-logged-in`

Setting the redirect path when user logged successfully in.

### `user-model`

Setting model class for your user model.

### `route`

Set your authentication route path. This path will be followed by the social provider.

### `detaching_route`

Set your detaching a social profile route path. This path will be followed by the social provider.


## Usage

Use trait `HasSocialProfiles` in your User model to attach the social profiles.


## License

Socialite Profiles is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
