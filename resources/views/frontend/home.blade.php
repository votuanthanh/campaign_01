@extends('layouts.frontend')


@section('main')
<div class="header-spacer-small"></div>

<div class="main-header bg-group">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12 col-xs-12">
				<div class="main-header-content">
					<h1>Manage your Friend Groups</h1>
					<p>Welcome to your friends groups! Do you wanna know what your close friends have been up to? Groups
						will let you easily manage your friends and put the into categories so when you enter you’ll only
						see a newsfeed of those friends that you placed inside the group. Just click on the plus button below and start now!
					</p>
				</div>
			</div>
		</div>
	</div>
	<img class="img-bottom" src="/images/group-bottom.png" alt="friends">
</div>
<div class="container" id="app">
	<campaign></campaign>
</div>
@endsection
