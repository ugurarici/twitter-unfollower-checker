<?php

//  twitter unfollower manager

//  redirect user to allow twitter oauth login -> not working now. instead using self generated tokens
//  get users current followers list
//  if it is first time for this user, say "we start checking your unfollowers, check back later to see who unfollowed you"
//  if user had previous data on system, get diff and show unfollowers
//  on unfollowers list, if user follows any of them show an "unfollow" button

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

if(TWAPP_USE_SELF_GENERATED_TOKEN) {
    $access_token = TWAPP_ACCESS_TOKEN;
    $access_token_secret = TWAPP_ACCESS_TOKEN_SECRET;
} else {
    //  requires oauth login
}

$twitter_connection = new TwitterOAuth(TWAPP_CONSUMER_KEY, TWAPP_CONSUMER_SECRET, $access_token, $access_token_secret);

$credentials = $twitter_connection->get("account/verify_credentials");

$followers = $twitter_connection->get("followers/ids", ["screen_name" => $credentials->screen_name]);

$current_followers = $followers->ids;

$previous_followers = null;

if (file_exists("followers.json")){
    $previous_followers = json_decode(file_get_contents("followers.json"), true);
}


if (!is_null($previous_followers)) {

    $unfollowers_ids = array_diff($previous_followers, $current_followers);

    $new_followers_ids = array_diff($current_followers, $previous_followers);

    if(count($unfollowers_ids)>0){
        // echo count($unfollowers_ids) . " kişi seni takipten çıkmış.<hr>";
        echo count($unfollowers_ids) . " account(s) unfollowed you.<hr>";
        $unfollowers = $twitter_connection->get("users/lookup", ["user_id" => implode(",", $unfollowers_ids)]);

        foreach ($unfollowers as $unfollower) {
            echo "<strong>".$unfollower->name."</strong><br>";
            echo "@".$unfollower->screen_name . "<br>";
            // echo $unfollower->following ? "Takip ediyorsun<hr>" : "Takip etmiyorsun<hr>";
            echo $unfollower->following ? "You are following her/him<hr>" : "You are NOT following her/him<hr>";
        }

    } else {
        // echo "VAAAAY Hiç kimse takipten çıkmamış. Böyle devam et!";
        echo "YAAAYY!!! No one unfollowed you.";
    }

    echo "<hr>";

    if(count($new_followers_ids)>0){
        // echo count($new_followers_ids) . " yeni takipçin var!<hr>";
        echo "You have ". count($new_followers_ids) . " new follower(s)!<hr>";
        $new_followers = $twitter_connection->get("users/lookup", ["user_id" => implode(",", $new_followers_ids)]);
        
        foreach ($new_followers as $new_follower) {
            echo "<strong>".$new_follower->name."</strong><br>";
            echo "@".$new_follower->screen_name . "<br>";
            // echo $new_follower->following ? "Takip ediyorsun<hr>" : "Takip etmiyorsun<hr>";
            echo $new_follower->following ? "You are following her/him<hr>" : "You are NOT following her/him<hr>";
        }

    } else {
        // echo "Maalesef hiç yeni takipçin yok :(";
        echo "You don't have any new followers :(";
    }

    // echo "<hr> Mevcut takipçi listesini de kaydediyorum ;)";
    echo "<hr> I saved your updated followers list ;)";

} else {
    // echo "İlk defa gelmişsin. Şu anda " . count($current_followers) . " tane takipçin varmış. Ben bunları not alıyorum.";
    echo "This is your first visit. Now you have " . count($current_followers) . " followers. I'm saving those to check what changed later.";
}

file_put_contents("followers.json", json_encode($current_followers));