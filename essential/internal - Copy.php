<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    /* Admin panel functions */
	
	function logImageUpload($fileName, $bikeId) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $q = $db->prepare("INSERT INTO `images` (`fileName`, `bikeId`) VALUES (?, ?)");
        $q->bind_param('ss', $fileName, $bikeId);
        $q->execute();
	}
	
	function deleteBike($bikeId) {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $q = $db->prepare("DELETE FROM `registeredbikes` WHERE `registeredbikes`.`id` = ?");
        $q->bind_param('s', $bikeId);
        $q->execute();
	}
	
	function checkIsUserAdmin($adminName, $key) {
		$key = str_replace(" ","",$key);
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $q = $db->prepare("SELECT admin FROM `users` WHERE `username` = ? AND `sessionKey` = ?");
        $q->bind_param('ss', $adminName, $key);
        $q->execute();

		$res = $q->get_result();

		if($res = $res->fetch_array()) {
			if($res['admin'] == 1 && $key != "")
			return true;
		}

		return false;
	}
    /* Admin panel functions */

	function generateSessionKey($len = 25)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_*";
		$ret = "";
		
		for($i = 0; $i < $len; $i++)
		{
			$ret .= $chars[rand(0, strlen($chars)-1)];
		}
		
		return $ret;
	}
	
	

	function generateLogKey($len = 5)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$ret = "";
		
		for($i = 0; $i < $len; $i++)
		{
			$ret .= $chars[rand(0, strlen($chars)-1)];
		}
		
		return $ret;
	}
	

    function time_ago($timestamp)
    {
        $etime = time() - $timestamp;
    
        if ($etime < 1)
        {
            return 'just now';
        }
    
        $a = array(12 * 30 * 24 * 60 * 60  =>  'year', 30 * 24 * 60 * 60 => 'month', 24 * 60 * 60 => 'day', 60 * 60 => 'hour', 60 => 'minute', 1 => 'second');
    
        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

    function time_until($time, $until){
        $diff2 = 0;
        $seconds = 0;
        $minutes = 0;
        $hours = 0;
        $days = 0;
        if($until > $time){
            $diff = abs($until - $time);
            while ($diff2 < $diff){
                $diff2++;
                $seconds++;
                if($seconds > 59){
                    $seconds =0;
                    $minutes++;
                }
                if($minutes > 59){
                    $minutes = 0;
                    $hours++;
                }
                if($hours > 23){
                    $hours = 0;
                    $days++;
                }
            }
        }
        return json_encode(["days"=>$days, "hours"=>$hours, "minutes"=>$minutes, "seconds" => $seconds]);
        
    }
	
	function confirmSessionKey($username, $key)
	{
		$key = str_replace(" ","",$key);
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$q = $db->prepare("SELECT active FROM users WHERE username = ? AND sessionKey = ? LIMIT 1;");
		$q->bind_param('ss', $username, $key);
		$q->execute();
		
		$res = $q->get_result();
		
		if($res = $res->fetch_array())
		{
			if((int)$res['active'] == 1 && $key != "") {
				return true;
			} // Checks if banned
		}

		return false;
	}
	
	function getLoginDetails()
	{
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId']))
		{
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);
			
			if($loggedIn == true)
			{
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$a = $db->prepare("SELECT id, username, loginKey, tycoon FROM users WHERE username = ? LIMIT 1;");
				$a->bind_param('s', $_COOKIE['user_name']);
				$a->execute();
				
				$res = $a->get_result();
				
				if($res = $res->fetch_array()) {
					return "res=1&userName=" . $res['username'] . "&userIDX=" . $res['id'] . "&tycoon=" . $res['tycoon'] . "&tycoonTV=0&loginKey=" . $res['loginKey'];
				}
			}
		}
		
		return "res=999";
	}
	
	function getUserID()
	{
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId']))
		{
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);
			
			if($loggedIn == true)
			{
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$q = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1;");
				$q->bind_param('s', $_COOKIE['user_name']);
				$q->execute();
				
				$res = $q->get_result();
				
				if($res = $res->fetch_array())
				{
					$st = rand();
					return $res['id'];
				}
			}
		}
		
		return "res=999";
	}


	function updateBikeStatusStolen($bikeId, $comments, $idx)
	{
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId']))
		{
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);
			
			if($loggedIn == true)
			{
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $q = $db->prepare("UPDATE registeredbikes SET `stolen` = '1', `comments` = ? WHERE `id` = ? AND ownerId = ?");
                $q->bind_param('sss', $comments, $bikeId, $idx);
                $q->execute();

				
				$res = $q->get_result();
			}
		}
		
		return "res=999";
	}
	
	function updateBikeStatusRecovered($bikeId, $comments, $idx)
	{
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId']))
		{
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);
			
			if($loggedIn == true)
			{
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                $q = $db->prepare("UPDATE registeredbikes SET `stolen` = '0', `comments` = ? WHERE `id` = ? AND ownerId = ?");
                $q->bind_param('sss', $comments, $bikeId, $idx);
                $q->execute();

				
				$res = $q->get_result();
			}
		}
		
		return "res=999";
	}
	
	function getBikeImage($bikeId)
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM images WHERE bikeId = ? LIMIT 1;");
            $q->bind_param('s', $bikeId);
            $q->execute();

            $res = $q->get_result();

            // Check if there is a result
            if ($row = $res->fetch_array()) {
                $st = rand();
                return $row['fileName'];
            }
        }
    }

    return "res=999&bikeid=" . $bikeId;
}

	
	function getMostRecentBike($ownerId) // This function will return the id that has been given to the bike that the user has just registered. The reason this is needed is so that the logImageUpload function knows which bike to associate the image to.
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM registeredbikes WHERE ownerId = ? ORDER BY id DESC LIMIT 1;"); //This query will sort the list of bikes by descending order and select the one which was most recently inserted by the user
            $q->bind_param('s', $ownerId);
            $q->execute();

            $res = $q->get_result();

            if ($row = $res->fetch_array()) {
                $st = rand();
                return $row['id'];
            }
        }
    }

    return "res=999";
}
    function getUserBikes($ownerId)
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM registeredbikes WHERE ownerId = ?;");
            $q->bind_param('s', $ownerId);
            $q->execute();

            $res = $q->get_result();

            $bikes = array(); // Initialize an array to store bike data

            while ($row = $res->fetch_array()) {
                // Add each bike to the array
                $bikes[] = $row;
            }

            // Return the array of bikes
            return $bikes;
        }
    }

    return "res=999";
}

   function getAllBikes()
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM registeredbikes;");
            //$q->bind_param('s', $ownerId);
            $q->execute();

            $res = $q->get_result();

            $bikes = array(); // Initialize an array to store bike data

            while ($row = $res->fetch_array()) {
                // Add each bike to the array
                $bikes[] = $row;
            }

            // Return the array of bikes
            return $bikes;
        }
    }

    return "res=999";
}

   function getBikeDetails($ownerId, $bikeId)
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM registeredbikes WHERE ownerId = ? AND id = ?;");
            $q->bind_param('ss', $ownerId, $bikeId);
            $q->execute();

            $res = $q->get_result();

            $bikes = array(); // Initialize an array to store bike data

            while ($row = $res->fetch_array()) {
                // Add each bike to the array
                $bikes[] = $row;
            }

            // Return the array of bikes
            return $bikes;
        }
    }

    return "res=999";
}

function getBikeOwner($bikeId)
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            $q = $db->prepare("SELECT * FROM registeredbikes WHERE id = ?;");
            $q->bind_param('s', $bikeId);
            $q->execute();

            $res = $q->get_result();

            if ($res !== false) {
                $row = $res->fetch_array();

                if ($row !== false) {
                    // Assuming ownerId is an actual column in the registeredbikes table
                    return $row['ownerId'];
                } else {
                    // Handle the case when no row is found
                    die("No matching row found for bike ID: $bikeId");
                }
            } else {
                // Handle the case when the query fails
                die("Query execution failed: " . $q->error);
            }
        } else {
            // Handle the case when the user is not logged in
            die("User not logged in.");
        }
    }

    // Handle the case when user_name or sessionId is not set
    die("User data not set.");
}


function getBikeDetailsAdmin($bikeId)
{
    if (isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
        $loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

        if ($loggedIn == true) {
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $q = $db->prepare("SELECT * FROM registeredbikes WHERE id = ?;");
            $q->bind_param('s', $bikeId);
            $q->execute();

            $res = $q->get_result();

            $bikes = array(); // Initialize an array to store bike data

            while ($row = $res->fetch_array()) {
                // Add each bike to the array
                $bikes[] = $row;
            }

            // Return the array of bikes
            return $bikes;
        }
    }

    return "res=999";
}




	function userGetData($username) {
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

			if($loggedIn == true) {
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$q = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1;");
				$q->bind_param('s', $username);
				$q->execute();
				
				$res = $q->get_result();

				if($res = $res->fetch_array())
				return json_encode(["responseCode" => 1, "user" => ["idx" => intval($res["id"]), "userDef" => strval($res["def"]), "level" => intval($res["level"]), "tycoon" => intval($res["tycoon"]), "lastLog" => "1970-01-01 00:00:00", "dateJoined" => "1970-01-01 00:00:00", "petIds" => null]]);
				else return json_encode(["responseCode" => 999, "message" => "user data not found"]);
			}
		}

		return json_encode(["responseCode" => 999, "message" => "Hahahaha"]);
	}

	function userData($username) {
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

			if($loggedIn == true) {
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$q = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1;");
				$q->bind_param('s', $username);
				$q->execute();
				
				$res = $q->get_result();

				if($res = $res->fetch_array())
				return "ill add this later";
			}
		}

		return "res=999";
	}

	function getAllUserInfo($userIDX) {
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

			if($loggedIn == true) {
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$q = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1;");
				$q->bind_param('s', $userIDX);
				$q->execute();
				
				$res = $q->get_result();

				if($res = $res->fetch_array())
				return $res;
				else return json_encode(["responseCode" => 999, "message" => "user data not found"]);
			}
		}

		return json_encode(["responseCode" => 999, "message" => "err"]);
	}

	function getAllUserInfoByName($userName) {
		if(isset($_COOKIE['user_name']) && isset($_COOKIE['sessionId'])) {
			$loggedIn = confirmSessionKey($_COOKIE['user_name'], $_COOKIE['sessionId']);

			if($loggedIn == true) {
				$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$q = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1;");
				$q->bind_param('s', $userName);
				$q->execute();
				
				$res = $q->get_result();

				if($res = $res->fetch_array())
				return $res;
				else return json_encode(["responseCode" => 999, "message" => "user data not found"]);
			}
		}

		return json_encode(["responseCode" => 999, "message" => "err"]);
	}
?>