<?php
namespace App\Services;

use JOSE_JWT;

class Query
{
  const HOST = 'https://e.nlrs.ru/graphql';

  public static function getToken($user_id) {
    global $DB;

    $moduleinstance = $DB->get_record('nlrsbook_shelf', array('user_id' => $user_id), '*', IGNORE_MISSING );
    if ($moduleinstance->token) {        
      return $moduleinstance->token;
    } else {
        $row = new stdClass();
        $row->user_id = $user_id;
        $row->token = 'fewfwefwefwewef';
        $row->datetime = '1';
        $DB->insert_record('nlrsbook_shelf', $row);
        return $getToken;
    }
  }

  public static function checkToken($user_id) {
      $query = 'mutation {
        eduCheckIfLinkedNlrsAccountExistsAndGetToken(
          input: { 
              orgId: 1, 
              userIdInEduPlatform: "'.$user_id.'" 
          }
        ) {
          token
        }
      }';

      $data = array ('query' => $query);
      $data = http_build_query($data);

      $options = array(
        'http' => array(
          'method'  => 'POST',  
          'content' => $data
        )
      );

      $context  = stream_context_create($options);
      $getContents = file_get_contents(sprintf(self::HOST), false, $context);
      $json = json_decode($getContents, true);
      if ($getContents === FALSE) { }
      return $json['data']['eduCheckIfLinkedNlrsAccountExistsAndGetToken']['token'];
  }

  public static function createAccount($user_id) {
      $query = 'mutation {
        eduCreateNewNlrsAccount(
          input: {
            orgId: 1
            userIdInEduPlatform: "'.$user_id.'"
          }
        ) {
          token
        }
      }';

      $data = array ('query' => $query);
      $data = http_build_query($data);

      $options = array(
        'http' => array(
          'method'  => 'POST',  
          'content' => $data
        )
      );

      $context  = stream_context_create($options);
      $getContents = file_get_contents(sprintf(self::HOST), false, $context);
      $json = json_decode($getContents, true);
      if ($getContents === FALSE) { }
      return $json['data']['eduCreateNewNlrsAccount']['token'];
  }

  public static function generateServerApiRequestSignatureBase64() {
      $privateKeyString = file_get_contents('edu_org_1_private_key.pem'); // TODO: вынести в конфиг
      $jwt = new JOSE_JWT(['orgId' => 1, 'userIdInEduPlatform' => "2"]);
      $jws = $jwt->sign($privateKeyString, 'RS256');
      $signatureBase64 = self::base64url_encode($jws->signature);

      return $signatureBase64;
  }

  protected static function base64url_encode( $data ) {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  public static function getShelf($page, $first, $token) 
  {
      $query = 'query { 
          books(
              first: '.$first.'
              page: '.$page.'
              prefiltering: { onlyFromShelf: true }
            ) {
              paginatorInfo {
                total
                currentPage
                hasMorePages
                perPage
              }
              data {
                id
                title
                cover_thumb_url
              }
            }
        }';

      $data = array ('query' => $query);
      $data = http_build_query($data);

      $options = array(
        'http' => array(
          'header'  => sprintf("Authorization: Bearer %s", $token),
          'method'  => 'POST',  
          'content' => $data
        )
      );

      $context  = stream_context_create($options);
      $getContents = file_get_contents(sprintf(self::HOST), false, $context);
      $json = json_decode($getContents, true);
      if ($getContents === FALSE) { }
      return $json['data']['books'];
  }

  public static function addBookToShelf($book_id, $token) {
      $query = 'mutation {
        addBookToShelf(bookId: '.$book_id.') {
          title
          is_on_shelf
        }
      }';

      $data = array ('query' => $query);
      $data = http_build_query($data);

      $options = array(
        'http' => array(
          'header'  => sprintf("Authorization: Bearer %s", $token),
          'method'  => 'POST',  
          'content' => $data
        )
      );

      $context  = stream_context_create($options);
      $getContents = file_get_contents(sprintf(self::HOST), false, $context);
      $json = json_decode($getContents, true);
      if ($getContents === FALSE) { }
      return $json;
  }

  public static function removeBookToShelf($book_id, $token) {
      $query = 'mutation {
        removeBookFromShelf(bookId: '.$book_id.') {
          title
          is_on_shelf
        }
      }';

      $data = array ('query' => $query);
      $data = http_build_query($data);

      $options = array(
        'http' => array(
          'header'  => sprintf("Authorization: Bearer %s", $token),
          'method'  => 'POST',  
          'content' => $data
        )
      );

      $context  = stream_context_create($options);
      $getContents = file_get_contents(sprintf(self::HOST), false, $context);
      $json = json_decode($getContents, true);
      if ($getContents === FALSE) { }
      return $json;
  }
}