<?php
//start session
session_start();

// Include config file and twitter PHP Library by Abraham Williams (abraham@abrah.am)
include_once("config.php");
include_once("inc/twitteroauth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login with Twitter using PHP by CodexWorld</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<section class="container-fluid">
	<header class="OuserHeader clearfix">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<h2>Logo</h2>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<div class="form-group has-feedback col-lg-3 col-md-3 col-sm-3" style="margin-top:23px;">
				  <div class="input-group">
					    <!-- <span class="input-group-addon" style="background:none;"><i class="glyphicon glyphicon-search"></i></span>
					    <input type="text" class="form-control" id="inputGroupSuccess1" aria-describedby="inputGroupSuccess1Status"> -->
					    <!-- new -->
					    <div class="Demo">
				          <form action="https://twitter.com/search" method="get">
				            <input type="hidden" name="mode" value="users">
				            <div class="Typeahead Typeahead--twitterUsers">
				              <div class="u-posRelative">
				                <input class="Typeahead-hint" type="text" tabindex="-1" readonly>
				                <input class="Typeahead-input" id="demo-input" type="text" name="q" placeholder="Search Twitter users...">
				                <img class="Typeahead-spinner" src="img/spinner.gif">
				              </div>
				              <div class="Typeahead-menu"></div>
				            </div>
				            <button class="u-hidden" type="submit" style="display:none;">blah</button>
				          </form>
				        </div>
     			 </div>
     <!--  <div class="sticky-footer-push"></div> -->


    <script id="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
        <img class="ProfileCard-avatar" src="{{profile_image_url_https}}">

        <div class="ProfileCard-details">
          <div class="ProfileCard-realName">{{name}}</div>
          <div class="ProfileCard-screenName">@{{screen_name}}</div>
          <div class="ProfileCard-description">{{description}}</div>
        </div>

        <div class="ProfileCard-stats">
          <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Tweets:</span> {{statuses_count}}</div>
          <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Following:</span> {{friends_count}}</div>
          <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Followers:</span> {{followers_count}}</div>
        </div>
      </div>
    </script>
    <script id="empty-template" type="text/x-handlebars-template">
      <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
    </script>

					    <!-- new -->
				  </div>

			<h2 class="pull-right" style="padding-right: 24px;"><big>O</big><small>Users</small></h2>
		</div>

	</header>
	<aside class="col-lg-3 col-md-3 col-sm-3 col-xs-4" style="background: #EDB9B9;height: 90vh;border-radius: 4px;">
		<h4>Top Tweets</h4>

	</aside>
	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
		<!-- link -->
			<div class="bs-example clearfix">
				<ul class="nav nav-pills" role="tablist">
					<li role="presentation">
						<a href="#">Mood Chart</a>
					</li>
					<li role="presentation">
						<a href="#">Links</a>
					</li>
					<li role="presentation">
						<a href="#">Tweets</a>
					</li>
				</ul> 
			</div>
		<!-- link -->
		<div class="clearfix tweets_container">
			<?php
				if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified') 
				{
					//Retrive variables
					$screen_name 		= $_SESSION['request_vars']['screen_name'];
					$twitter_id			= $_SESSION['request_vars']['user_id'];
					$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
					$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
				
					//Show welcome message
					echo '<div class="welcome_txt">Welcome <strong>'.$screen_name.'</strong> (Twitter ID : '.$twitter_id.'). <a href="logout.php?logout">Logout</a>!</div>';
					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
					
					//If user wants to tweet using form.
					if(isset($_POST["updateme"])) 
					{
						//Post text to twitter
						$my_update = $connection->post('statuses/update', array('status' => $_POST["updateme"]));
						die('<script type="text/javascript">window.top.location="index.php"</script>'); //redirect back to index.php
					}
					
					//show tweet form
					echo '<div class="tweet_box">';
					echo '<form method="post" action="index.php"><table width="200" border="0" cellpadding="3">';
					echo '<tr>';
					echo '<td><textarea name="updateme" cols="60" rows="4"></textarea></td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td><input type="submit" value="Tweet" /></td>';
					echo '</tr></table></form>';
					echo '</div>';
					
					//Get latest tweets
					$my_tweets = $connection->get('statuses/user_timeline', array('screen_name' => $screen_name, 'count' => 15));
					
					echo '<div class="tweet_list"><strong>Latest Tweets : </strong>';
					echo '<ul>';
					foreach ($my_tweets  as $my_tweet) {
						echo '<li>'.$my_tweet->text.' <br />-<i>'.$my_tweet->created_at.'</i></li>';
					}
					echo '</ul></div>';

						
				}else{
					//Display login button
					echo '<a href="process.php"><img src="images/sign-in-with-twitter.png" width="151" height="24" border="0" /></a>';
				}
			?>  
		</div>
	</div>
</section>
	<script src="js/handlebars.js"></script>
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/jquery.xdomainrequest.min.js"></script>
    <script src="js/typeahead.bundle.js"></script>
    <script src="js/main.js"></script>
</body>
</html>