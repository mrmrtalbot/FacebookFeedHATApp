<?php
ini_set('display_errors', 1);
 
// Facebook PHP SDK v4.0.8
 
// path of these files have changes
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
 
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
 
// other files remain the same
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );
require_once('Facebook/GraphUser.php');
 
// path of these files have changes
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
 
use FacebookEntities\AccessToken;
use FacebookEntities\SignedRequest;
 
// other files remain the same
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
 
// start session
session_start();
 
// init app with app id and secret
FacebookSession::setDefaultApplication( '1483942438522701','d188ca5467347a410eb967d6bff396d6' );
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://hubofallthings.com/HAT-Events/HAT-Facebookevents/src/index.php' );
 
// see if a existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );
  
  // validate the access_token to make sure it's still valid
  try {
    if ( !$session->validate() ) {
      $session = null;
    }
  } catch ( Exception $e ) {
    // catch any exceptions
    $session = null;
  }
}  
 
if ( !isset( $session ) || $session === null ) {
  // no session exists
  
  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
    // handle this better in production code
    print_r( $ex );
  } catch( Exception $ex ) {
    // When validation fails or other local issues
    // handle this better in production code
    print_r( $ex );
  }
  
}
 
// see if we have a session
if ( isset( $session ) ) {
  
  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );
  
  // graph api request for user data
  $graphObject = (new FacebookRequest( $session, 'GET', '/me?fields=events{name,description,start_time,end_time,location,venue,attending}'))->execute()->getGraphObject()->asArray();
  
  echo  '<pre>' . print_r( $graphObject, 1 ) . '</pre>';

/*if ($_GET["events"] == "0")
  { 
    $number_of_events = 0; 
    echo $_GET["events"];
  }
elseif ($_GET["events"] == "all_events")
  {
    $numberof_events = count($graphObject['events']->data);
    echo $_GET["events"];
  }
  else 
  {
    $number_of_events == $_GET["number_of_events"];
    echo $_GET["events"];
  } */

$number_of_events = count($graphObject['events']->data);

if (isset (($graphObject['events']->data))
      {
        $number_of_events = count($graphObject['events']->data);
      }
      else {
        $eventName = "N/A";
//$number_of_events = 1;
$eventnumber = 0;
//$number_of_people = count($graphObject['events']->data[$eventnumber]->attending->data);
$number_of_people = 0;
$peoplenumber = 0;
//echo $eventnumber;
//echo $number_of_events;

 while ($eventnumber < $number_of_events)
  {
    if (isset ($graphObject['events']->data[$eventnumber]->name))
      {
        $eventName = $graphObject['events']->data[$eventnumber]->name;
      }
      else {
        $eventName = "N/A";
      }
    if (isset ($graphObject['events']->data[$eventnumber]->description))
      {
        $eventDescription = $graphObject['events']->data[$eventnumber]->description;
      }
      else { 
        $eventDescription = "N/A";
      }
    if (isset ($graphObject['events']->data[$eventnumber]->start_time))
      {
        $eventStart = $graphObject['events']->data[$eventnumber]->start_time;
      }
    if (isset ($graphObject['events']->data[$eventnumber]->end_time))
      {
        $eventEnd = $graphObject['events']->data[$eventnumber]->end_time;
      }
    if (isset ($graphObject['events']->data[$eventnumber]->venue->city))
      {
        $city = $graphObject['events']->data[$eventnumber]->venue->city;
      }
      if (isset ($graphObject['events']->data[$eventnumber]->venue->country))
      {
        $country = $graphObject['events']->data[$eventnumber]->venue->country;
      }
      if (isset ($graphObject['events']->data[$eventnumber]->venue->latitude))
      {
        $latitude = $graphObject['events']->data[$eventnumber]->venue->latitude;
      }
      else
      { $latitude = "";} 

      if (isset ($graphObject['events']->data[$eventnumber]->venue->longitude))
      {
        $longitude = $graphObject['events']->data[$eventnumber]->venue->longitude;
      }
      else
      { $longitude = "";} 
      if (isset ($graphObject['events']->data[$eventnumber]->venue->street))
      {
        $street = $graphObject['events']->data[$eventnumber]->venue->street;
      }
      if (isset ($graphObject['events']->data[$eventnumber]->venue->zip))
      {
        $zip = $graphObject['events']->data[$eventnumber]->venue->zip;
      }
      
      //while ( $peoplenumber < $number_of_people) 

      //{
        if (isset ($graphObject['events']->data[$eventnumber]->attending->data[0]->name))
          {
            $people = $graphObject['events']->data[$eventnumber]->attending->data[0]->name;
          }
      //}
      //$peoplenumber++

      if (isset ($graphObject['events']->data[$eventnumber]->location))
      {
        $locationName = $graphObject['events']->data[$eventnumber]->location;
      }  
      else
      { $locationName = "";} 
      
        $eventnumber++;
        //echo $eventnumber; 
        $height_from_sea_level = "";
        $indoor_information = "";
        $orientation = "";


        //splits full name from people into first and last name 
        $fullName = preg_split('/\s+/', $people);
        //echo $fullName[0];
        //echo "<br />";
        //echo $fullName[1];

        if (isset ($eventStart))
      {
        $eventStartTimestamp = strtotime($eventStart);
      }  


//converts time into unix timestamp
      if (isset ($eventStart))
      {
        $eventStartTimestamp = strtotime($eventStartTimestamp);
      }  
      else
      { $eventEndTimestamp = "1411856700";} 
      if (isset ($eventEnd))
      {
        $eventEndTimestamp = strtotime($eventEnd);
      }  
      else
      { $eventEndTimestamp = "1411856700";} 

//works out event time type
$currentdate = date('Y-m-d H:i:s');
$currentDateTimestamp = strtotime($currentdate);

if ( $currentDateTimestamp <= $eventStartTimestamp)
{
  $status = 2;
}
else {
  $status = 3;
}


  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://hubofallthings.com/app/HAT-Events/HAT-Facebookevents/src' ) . '">Logout</a>';
  
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends', 'user_events' ) ) . '">Login</a>';
}

?>