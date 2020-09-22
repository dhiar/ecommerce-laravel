<?php
namespace App\Helpers;

class PaginationFormat 
{
    public static function commit($paginator, $response) {
      $arrPaginator =	$paginator->toArray();
      unset($arrPaginator["data"],$response['meta']);
      $response = array_merge($response , $arrPaginator);
      return response()->json($response);
    }
}

?>