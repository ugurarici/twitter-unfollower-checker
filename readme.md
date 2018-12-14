# Twitter Unfollower Checker
A basic app to check twitter unfollowers and new followers.
It needs a twitter app and self generated access token (with secrets) to work.

## Installation
Copy ```config.example.php``` as ```config.php``` and fill needed informatin.
In order to do this you need to create an app on twitter and generate access tokens.

Create an empty ```followers.json``` file on base dir.

Then run ```composer install``` on terminal to install dependencies.

## How to check
Run this script basicly visit index.php from a web browser.

When you run this script first time, it will only show a message telling you "Check back later"
On your next runs, it will take diff with last saved followers list and show you unfollowers and new followers.

## Why?
A friend of mine asked me for a "trusted unfollower checker service" and I said "if you are not runnig your own, you cannot trust any". So I created this in 30 min to show him how free/libre software makes people powerful on their own stuff :)