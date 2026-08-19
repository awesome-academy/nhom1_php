@extends(auth()->check() ? 'layouts.user-app' : 'layouts.user-guest')

@section('content')
    @include('user.home.welcome-banner-caffe')
    @include('user.home.about-banner')
@endsection
