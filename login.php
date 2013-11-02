<?php
  /*
   Licensed to the Apache Software Foundation (ASF) under one
   or more contributor license agreements.  See the NOTICE file
   distributed with this work for additional information
   regarding copyright ownership.  The ASF licenses this file
   to you under the Apache License, Version 2.0 (the
   "License"); you may not use this file except in compliance
   with the License.  You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing,
   software distributed under the License is distributed on an
   "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
   KIND, either express or implied.  See the License for the
   specific language governing permissions and limitations
   under the License.
   */

  require_once("includes.php");

  function logout()
  {
      global $snoopy, $apiPath, $_SESSION;
      if ($_SESSION['wikisession']) {
          $snoopy->cookies = $_SESSION['wikisession'];

          $request_vars = array('action' => 'logout', 'format' => 'php');
          if (!$snoopy->submit($apiPath, $request_vars))
              die("Snoopy error: {$snoopy->error}");
      }
      $loginok = 0;
      unset($_SESSION['siduser']);
      unset($_SESSION['wikisession']);
      unset($_SESSION['sidip']);
      unset($_SESSION['admin']);
      unset($_SESSION['sidsrv']);
  }

  function login($username, $password)
  {
      global $tbl_prefix, $apiPath, $snoopy, $use_wiki, $url, $_SESSION;
      logout();
      $db = openDB();
      $qry = $db->prepare("SELECT username, password, admin FROM " . $tbl_prefix . "users WHERE LOWER(username) = ?");
      $qry->execute(array(strtolower($username)));
      $num = $qry->rowCount();
      $result = false;
      $_SESSION['admin'] = false;
      if ($num == 1) {
          $res = $qry->fetch();

          if (crypt($password, $res->password) == $res->password) {
            $_SESSION['siduser'] = $res->username;
            $_SESSION['sidip'] = $_SERVER["REMOTE_ADDR"];
            $_SESSION['sidsrv'] = $url;
            if ($res->admin == 1)
                $_SESSION['admin'] = true;
           $result = true;
          }
      } 


      $db = null;
      return $result;
  }


  if ($_GET['action'] == 'logout') {
      logout();
      header(infoMsgHeader("Logout OK"));
  } else {
      if (isset($_POST['username']) && isset($_POST['password'])) {
          if (login($_POST['username'], $_POST['password']))
              header(infoMsgHeader("Login OK"));
          else
              header(errorMsgHeader("Login fehlgeschlagen"));
      }
  }
?>