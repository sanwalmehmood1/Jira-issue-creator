<?php
/**
 * Plugin Name:Wp jira issue creator
 * Plugin URI: https://adcure.co/
 * Description: This plugin creates jira issue.
 * Version: 1.0
 * Author: Sanwal Mehmood
 * Author Email:sanwalsain1@gmail.com
 * Author URI: https://adcure.co/
 * Text Domain: AdCure
 * Domain Path: /languages
 */

require plugin_dir_path(__FILE__).'vendor/autoload.php';
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\JiraException;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function postJiraIssues($data)
{
    Unirest\Request::auth('sanwalsain@adcure.co', 'kjixcZLzqMaPlVcOE6PT6E0F');

    $headers = array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    );

    $yourSubject=$data["your-subject"];
    $yourMessage=$data["your-message"];
    $body = <<<REQUESTBODY
{
    "fields": {
       "project":
       {
           "key": "KV95",
           "id": "10000"
       },
     
       "summary": "$yourSubject.",  
       "description": {
          "type": "doc",
          "version": 1,
          "content": [
            {
              "type": "paragraph",
              "content": [
                {
                  "type": "text",
                  "text": "$yourMessage"
                }
              ]
            }
          ]
        },
       "issuetype": {
          "name": "Bug",
          "id": "10009"
       }
   }
}
REQUESTBODY;

    $response = Unirest\Request::post(
        'https://kitchen-vibes-try.atlassian.net/rest/api/3/issue',
        $headers,
        $body
    );
//    $response = Unirest\Request::get(
//        'https://kitchen-vibes-try.atlassian.net/rest/api/3/project',
//        $headers
//    );



    return print_r($response);

}
function wpcf7_before_send_mail_function( $contact_form, $abort, $submission )
{

    $data = $submission->get_posted_data();


    $return=postJiraIssues($data);

    return $contact_form;

}
add_filter( 'wpcf7_before_send_mail', 'wpcf7_before_send_mail_function', 10, 3 );
