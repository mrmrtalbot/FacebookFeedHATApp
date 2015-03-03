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
$helper = new FacebookRedirectLoginHelper( 'http://hubofallthings.com/HAT-Apps/facebook/src/facebookfeedapp.php' );
 
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
if ( isset( $session ) ) 

{
  $currentdate = date('Y-m-d');
  echo $currentdate;
  // save the session
  $_SESSION['fb_token'] = $session->getToken();
  // create a session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );
  
  // graph api request for user data
  $graphObject = (new FacebookRequest( $session, 'GET', '/me?fields=since.('$currentdate')'))->execute()->getGraphObject()->asArray();
  
  //echo  '<pre>' . print_r( $graphObject, 1 ) . '</pre>';

/*if ($_GET["feed"] == "0")
  { 
    $number_of_feed = 0; 
    echo $_GET["feed"];
  }
elseif ($_GET["feed"] == "all_feed")
  {
    $numberof_feed = count($graphObject['feed']->data);
    echo $_GET["feed"];
  }
  else 
  {
    $number_of_feed == $_GET["number_of_feed"];
    echo $_GET["feed"];
  } */

$number_of_feed_items = count($graphObject['feed']->data);
$feednumber = 0;
//echo $feednumber;
//echo $number_of_feed_items;

$number_of_To_Names = count($graphObject[$feednumber]->data->to->data);
$ToFacebooknumber = 0;


$number_of_With_Names = count($graphObject['from']->data->story_tags->data);
$storytagsFacebooknumber = 0;


$number_of_likes = count($graphObject['from']->data->likes->data);
$LikesFacebooknumber = 0;


$number_of_comments = count($graphObject['from']->data->comments->data);
$CommentsFacebooknumber = 0;


 while ($feednumber < 5)
  {
    if (isset ($graphObject['feed']->data[$feednumber]->from->name))
      {
        $FacebookAccountName = $graphObject['feed']->data[$feednumber]->from->name;     
      }
    /*while ($storytagsFacebooknumber < $number_of_To_Names)
      {
        if (isset ($graphObject['feed']->data[$feednumber]->to->data[0]->name))
          {
            $ToFacebookAccountName = $graphObject['feed']->data[$feednumber]->to->data[0]->name;
          }
        if (isset ($graphObject['feed']->data[$feednumber]->with_tags->data[0]->name))
          {
            $WithFacebookAccountName = $graphObject['feed']->data[$feednumber]->with_tags->data[0]->name;
          }
          $storytagsFacebooknumber++;
      }
      */
    if (isset ($graphObject['feed']->data[$feednumber]->story))
      {
        $FeedStory = $graphObject['feed']->data[$feednumber]->story;
      
      }
    else
    {
      $FeedStory = "N/A";
    }

    /*while ($WithFacebooknumber < $number_of_With_Names)
      {
        if (isset ($graphObject['feed']->data[$feednumber]->story_tags[0]->name))
          {
            $StoryTagsName = $graphObject['feed']->data[$feednumber]->story_tags[0]->name;
          }
        if (isset ($graphObject['feed']->data[$feednumber]->story_tags[0]->type))
          {
            $StoryTagsType = $graphObject['feed']->data[$feednumber]->story_tags[0]->type;
          }
      }
      */
      if (isset ($graphObject['feed']->data[$feednumber]->picture))
      {
        $TaggedPicture = $graphObject['feed']->data[$feednumber]->picture;
        $taglength = count($TaggedPicture);
      }
      if ($taglength > 44) 
      {
        $TaggedPicture = "url too long";
        echo $TaggedPicture;
      }
      if (isset ($graphObject['feed']->data[$feednumber]->link))
      {
        $PictureLink = $graphObject['feed']->data[$feednumber]->link;
        $urllength = count($PictureLink);
      }
      if ($urllength > 44) 
      {
        $PictureLink = "url too long";
      } 

      if (isset ($graphObject['feed']->data[$feednumber]->description))
      {
        $PictureDescription = $graphObject['feed']->data[$feednumber]->description;
      }
      if (isset ($graphObject['feed']->data[$feednumber]->privacy->value))
      {
        $PrivacyType = $graphObject['feed']->data[$feednumber]->privacy->value;
      }
      if (isset ($graphObject['feed']->data[$feednumber]->type))
      {
        $FeedType = $graphObject['feed']->data[$feednumber]->type;
      }  
      if (isset ($graphObject['feed']->data[$feednumber]->status_type))
      {
        $FeedStatusType = $graphObject['feed']->data[$feednumber]->status_type;
      }
      if (isset ($graphObject['feed']->data[$feednumber]->application->name))
      {
        $FeedApplicationName = $graphObject['feed']->data[$feednumber]->application->name;
      }
      else
      {
        $FeedApplicationName = "N/A";
      } 
      /*
      while ($WithFacebooknumber < $number_of_likes)
      {
        if (isset ($graphObject['feed']->data[$feednumber]->likes->data[0]->name))
          {
            $FacebookLikesName = $graphObject['feed']->data[$feednumber]->likes->data[0]->name;
          }
          $LikesFacebooknumber++
      }
      while ($CommentsFacebooknumber < $number_of_comments)
      {
        if (isset ($graphObject['feed']->data[$feednumber]->comments->data[0]->from->name))
          {
            $FeedMessageFromName = $graphObject['feed']->data[$feednumber]->comments->data[0]->from->name;
          }
        if (isset ($graphObject['feed']->data[$feednumber]->comments->data[0]->message))
          {
            $FeedMessage = $graphObject['feed']->data[$feednumber]->comments->data[0]->message;
          }
          $CommentsFacebooknumber++  
      } 
      */
      if (isset ($graphObject['feed']->data[$feednumber]->created_time))
      {
        $CreatedTime = $graphObject['feed']->data[$feednumber]->created_time;
      }
      if (isset ($graphObject['feed']->data[$feednumber]->updated_time))
      {
        $UpdatedTime = $graphObject['feed']->data[$feednumber]->updated_time;
      } 
      if (isset ($graphObject['feed']->data[$feednumber]->comments->like_count))
      {
        $LikeCount = $graphObject['feed']->data[$feednumber]->comments->like_count;
      } 
      else
      {
        $LikeCount = "N/A";
      } 
        $feednumber++;
        //echo $feednumber; 
      

        if (isset ($feedtart))
      {
        $feedstartTimestamp = strtotime($feedstart);
      }  

//echo $feedstart;
//echo $feedstartTimestamp;
//converts time into unix timestamp
      if (isset ($eventEnd))
      {
        $feedupdateTimestamp = strtotime($UpdatedTime);
      }  
      else
      { 
        $eventEndTimestamp = $feedtartTimestamp;
      } 

//works out event time type
$currentdate = date('Y-m-d');
$currentDateTimestamp = strtotime($currentdate);


//Post fields
echo $FacebookAccountName;

$fullName = preg_split('/\s+/', $FacebookAccountName);

$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

   // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => '9afb631d-af23-4289-8688-bc425f861266',
        "timestamp" => $feedstartTimestamp,
        "value" => $fullName,
        "unit" => 12,
        "data_type" => 12,
        "description" => "The HAT owners Facebook Account name"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);

// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Print response data
print_r($responseData);


//Post EventendTime

/* Post fields current timestamp
$post_fields_event = array(
    "device_id" => "2a8ed032-1b2d-4047-b7cd-e3ecb676ccd7",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "timestamp" => $feedstartTimestamp;,
        "value" => $ToFacebookAccountName,
        "unit" => 1,
        "data_type" => 1,
        "description" => "The Facebook Account name of the person the facebook message is to"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
}

// Post fields location name
$post_fields_event = array(
    "device_id" => "eded5cb0-e28a-48f9-9e95-b53eb8916051",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "timestamp" => $feedstartTimestamp;,
        "value" => $WithFacebookAccountName,
        "unit" => 1,
        "data_type" => 1,
        "description" => "Location Name"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
}

*/

// Post fields Event name
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "e53df67b-ccae-4cc0-9d38-ef9365faca28",
        "timestamp" => $feedstartTimestamp,
        "value" => $FeedStory,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Feed Story"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);

/*
// Post fields Start name
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "72494f41-09ce-4017-97a1-d1f88c0f9b78",
        "timestamp" => $feedstartTimestamp,
        "value" => $StoryTagsName,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Event Start Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "aebe7799-d752-460b-bc1e-db1afc7b67cc",
        "timestamp" => $feedstartTimestamp,
        "value" => $StoryTagsType,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Event Start Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
*/

// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "fdce2bb3-20e8-43ce-8fef-28ef68c1894b",
        "timestamp" => $feedstartTimestamp,
        "value" => $TaggedPicture,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Tagged Picture"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "2ffa1fa7-bdf4-4cb2-a541-8aecca254617",
        "timestamp" => $feedstartTimestamp,
        "value" => $PictureLink,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Picture Link"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "3a77aadc-43bb-4523-b377-57435261ace1",
        "timestamp" => $feedstartTimestamp,
        "value" => $PictureDescription,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Picture Description"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "b7130162-b525-4769-91d9-7128049249c3",
        "timestamp" => $feedstartTimestamp,
        "value" => $PrivacyType,
        "unit" => 4,
        "data_type" => 4,
        "description" => "Privacy Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "b1328c92-3ed9-49d6-91f8-8634158b5543",
        "timestamp" => $feedstartTimestamp,
        "value" => $FeedType,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Feed Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "98280c5e-8038-42fb-a44b-a79c900c51a1",
        "timestamp" => $feedstartTimestamp,
        "value" => $FeedStatusType,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Feed Status Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "a1a6ae30-40d0-417d-86e8-5f4ec41d234b",
        "timestamp" => $feedstartTimestamp,
        "value" => $FeedApplicationName,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Feed Application Name"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


/* Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "e614e92a-ec98-48bd-b96c-8b70e28f3573",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "timestamp" => $CurrentTime,
        "value" => $FacebookLikesName,
        "unit" => 4,
        "data_type" => 4,
        "description" => "Event Start Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
}

// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "94a7b5ab-2616-4f31-9e7b-44398ee62b77",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "timestamp" => $CurrentTime,
        "value" => $FeedMessageFromName,
        "unit" => 4,
        "data_type" => 4,
        "description" => "Event Start Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
}

// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "90c5f22e-5586-4dba-ac51-171dfcdf0113",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "timestamp" => $CurrentTime,
        "value" => $FeedMessage,
        "unit" => 4,
        "data_type" => 4,
        "description" => "Event Start Type"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);
}

*/

// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "16bd6de9-0bba-44a1-8203-e8694b02fb6c",
        "timestamp" => $feedstartTimestamp,
        "value" => $LikeCount,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Like Count"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "11d36e95-3289-4097-9bf9-6d656302c097",
        "timestamp" => $feedstartTimestamp,
        "value" => $CreatedTime,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Created Time"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);
echo $response;
// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Decode the response
// Print response data
print_r($responseData);


// Post fields rsvp_status
$post_fields_event = array(
    "device_id" => "3dbb65b3-0f35-47e8-8e32-36c8195c0b6c",

    // Use JSON encode here just to make sure everything is valid json
    "data" => json_encode(array(
        "id" => "d3de7076-28db-49f9-9062-f56230743f36",
        "timestamp" => $feedstartTimestamp,
        "value" => $UpdatedTime,
        "unit" => 12,
        "data_type" => 12,
        "description" => "Updated Time"
    ))
);
// This function builds a valid POST query string
$post_query_string = http_build_query($post_fields_event);

// Setup cURL
$ch = curl_init('http://dp02.hubofallthings.net/api/v1/sensor_data/');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => $post_query_string
));

// Send the request
$response = curl_exec($ch);

// Check for errors
if($response === FALSE){
    die(curl_error($ch));
}

// Decode the response
$responseData = json_decode($response, TRUE);
sleep(10);
}
// Print response data
//print_r($responseData);
$eventsucess = $responseData['response'];
echo $eventsucess;
if ($eventsucess = "sucess" ) {
  echo 'Your Data is being pushed via the HAT API to your HAT';
}
else 
  // print logout url using session and redirect_uri (logout.php page should destroy the session)
  echo '<a href="' . $helper->getLogoutUrl( $session, 'http://hubofallthings.com/app/HAT-Apps/facebook/src' ) . '">Logout</a>';
  
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends', 'user_events', 'publish_stream', 'read_stream' ) ) . '">Login</a>';
}

?>