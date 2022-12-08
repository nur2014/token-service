<?php
if (!function_exists('user_id')) {
  function user_id()
  {
    return app('request')->header('accessUserId');
  }
}

if (!function_exists('username')) {
  function username()
  {
    return app('request')->header('accessUsername');
  }
}