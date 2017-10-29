<?php

function moments($seconds)
{
    if($seconds < 60 * 60 * 24 * 30)
    {
        return "within the month";
    }

    return "a while ago";
}


// borrowed the f(n) from the assign02 solution to have a working getPosts
// even through this change was not part of the assign 3 specifications
//
function getPosts()
{
    $posts = [];

    $link = connect();
    $query = 'select firstname, lastname, title, comment, priority, filename, time, id 
              from posts
              order by priority';

    $results = mysqli_query($link, $query);

    while($row = mysqli_fetch_array($results))
    {
        $post = validatePost($row);
        if($post != false)
        {
            $posts[] = $post;
        }
    }

    return $posts;
}


// renamed old getPosts f(n) which was using file method
function getPosts_old()
{
    $posts = [];

    if(file_exists("posts.txt"))
    {
        $lines = file("posts.txt");

        $importantPriority  = [];
        $highPriority       = [];
        $normalPriority     = [];

        foreach($lines as $line)
        {
            $post = validatePost($line);
            if($post != false)
            {
                switch($post['priority'])
                {
                    case 3;
                        $normalPriority[] = $post;
                        break;
                    case 2;
                        $highPriority[] = $post;
                        break;
                    case 1;
                        $importantPriority[] = $post;
                        break;
                }
            }
        }

        $posts = array_merge($importantPriority, $highPriority, $normalPriority);
    }

    return $posts;
}


// borrowed the f(n) from the assign02 solution to have a working searchPosts
// even through this change was not part of the assign 3 specifications
//
function searchPosts($term)
{
    $posts = [];

    $link = connect();
    $query = 'select firstname, lastname, title, comment, priority, filename, time, id  
              from posts
              where comment like "%'.$term.'%"
              order by priority';

    $results = mysqli_query($link, $query);

    while($row = mysqli_fetch_array($results))
    {
        $post = validatePost($row);
        if($post != false)
        {
            $posts[] = $post;
        }
    }

    return $posts;
}


// renamed old searchPost f(n) which was using file method
function searchPosts_old($term)
{
    $posts = [];

    if(file_exists("posts.txt"))
    {
        $lines = file("posts.txt");

        $importantPriority = [];
        $highPriority   = [];
        $normalPriority = [];

        foreach($lines as $line)
        {
            $post = validatePost($line);
            if($post != false && strpos($post['comment'], $term) != false)
            {
                switch($post['priority'])
                {
                    case 3;
                        $normalPriority[] = $post;
                        break;
                    case 2;
                        $highPriority[] = $post;
                        break;
                    case 1;
                        $importantPriority[] = $post;
                        break;
                }
            }
        }

        $posts = array_merge($importantPriority, $highPriority, $normalPriority);
    }

    return $posts;
}


// borrowed the f(n) from the assign02 solution to have a working validatePost
// even through this change was not part of the assign 3 specifications
//
function validatePost($fields)
{
    $valid = [];

    $firstName  = trim($fields[0]);
    $lastName   = trim($fields[1]);
    $title      = trim($fields[2]);
    $comment    = trim($fields[3]);
    $priority   = trim($fields[4]);
    $filename   = trim($fields[5]);
    $time       = trim($fields[6]);
	$id			= trim($fields[7]);

    if($firstName == '' ||
        $lastName == '' ||
        $title    == '' ||
        $comment  == '' ||
        $priority == '' ||
        $filename == '' ||
        $time     == '' ||
		$id       == '')
    {
        $valid = false;
    }
    elseif(!file_exists('uploads/'.$filename))
    {
        $valid = false;
    }
    else
    {
        $valid['firstName'] = $firstName;
        $valid['lastName']  = $lastName;
        $valid['title']     = $title;
        $valid['comment']   = $comment;
        $valid['priority']  = $priority;
        $valid['filename']  = $filename;
        $valid['time']      = $time;
		$valid['id']      = $id;
    }

    return $valid;
}



// renamed old validatePost f(n) which was using file method
function validatePost_old($post)
{
    $valid = [];

    $fields = preg_split("/\|/", $post);

    if(count($fields) == 7)
    {
        $firstName  = trim($fields[0]);
        $lastName   = trim($fields[1]);
        $title      = trim($fields[2]);
        $comment    = trim($fields[3]);
        $priority   = trim($fields[4]);
        $filename   = trim($fields[5]);
        $time       = trim($fields[6]);

        if($firstName == '' ||
            $lastName == '' ||
            $title    == '' ||
            $comment  == '' ||
            $priority == '' ||
            $filename == '' ||
            $time     == '')
        {
            $valid = false;
        }
        elseif(!file_exists('uploads/'.$filename))
        {
            $valid = false;
        }
        else
        {
            $valid['firstName'] = $firstName;
            $valid['lastName']  = $lastName;
            $valid['title']     = $title;
            $valid['comment']   = $comment;
            $valid['priority']  = $priority;
            $valid['filename']  = $filename;
            $valid['time']      = $time;
        }
    }

    return $valid;
}

function filterPost($post)
{
    $author     = trim($post['firstName']) . ' ' . trim($post['lastName']);
    $title      = trim($post['title']);
    $comment    = trim($post['comment']);
    $priority   = trim($post['priority']);
    $filename   = trim($post['filename']);
    $postedTime = trim($post['time']);
	$id 		= trim($post['id']);

    $filteredPost['author']     = ucwords(strtolower($author));
    $filteredPost['moment']     = moments(time() - $postedTime);
    $filteredPost['title']      = trim($title);
    $filteredPost['comment']    = trim($comment);
    $filteredPost['priority']   = trim($priority);
    $filteredPost['filename']   = trim($filename);
    $filteredPost['postedTime'] = date('l F \t\h\e dS, Y', $postedTime);
    $filteredPost['searchResultsPostedTime'] = date('M d, \'y', $postedTime);
	$filteredPost['id'] = $id;

    return $filteredPost;
}

function validateFields($input)
{
    $valid = [];

    $firstName  = trim($input['firstName']);
    $lastName   = trim($input['lastName']);
    $title      = trim($input['title']);
    $comment    = trim($input['comment']);
    $priority   = trim($input['priority']);

    if($firstName == '' ||
        $lastName == '' ||
        $title    == '' ||
        $comment  == '' ||
        $priority == '' )
    {
        $valid = false;
    }
    elseif(!preg_match("/^[A-Z]+$/i", $firstName) || !preg_match("/^[A-Z]+$/i", $lastName) || !preg_match("/^[A-Z]+$/i", $title))
    {
        $valid = false;
    }
    elseif(preg_match("/<|>/", $comment))
    {
        $valid = false;
    }
    elseif(!preg_match("/^[0-9]{1}$/i", $priority))
    {
        $valid = false;
    }
    else
    {
        $valid['firstName'] = $firstName;
        $valid['lastName'] = $lastName;
        $valid['title'] = $title;
        $valid['comment'] = $comment;
        $valid['priority'] = $priority;
    }

    return $valid;
}

function isValidFile($fileInfo)
{
    if($fileInfo['type'] == 'image/jpeg')
    {
        return true;
    }

    return false;
}

function isValidSearchTerm($term)
{
    if(preg_match("/^[A-Z]+$/i", $term))
    {
        return true;
    }

    return false;
}

function insertPost_tbd($data)
{
    // md5 is a hashing function http://php.net/manual/en/function.md5.php
    $fileName = md5(time().$data['firstName'].$data['lastName']) . '.jpg';

    move_uploaded_file($data['file'], 'uploads/'.$fileName);

    $line = PHP_EOL;
    $line .= $data['firstName'] . '|';
    $line .= $data['lastName']  . '|';
    $line .= $data['title']     . '|';
    $line .= $data['comment']   . '|';
    $line .= $data['priority']  . '|';
    $line .= $fileName          . '|';
    $line .= time();

    $fp = fopen('posts.txt', 'a+');
    fwrite($fp, $line);
    fclose($fp);
}

function checkSignUp($data)
{
    $valid = false;

    // if any of the fields are missing, return an error
    if(trim($data['firstName']) == '' ||
        trim($data['lastName']) == '' ||
        trim($data['password'])  == '' ||
        trim($data['phoneNumber'])    == '' ||
        trim($data['dob']) == '')
    {
        $valid = "All inputs are required.";
    }
    elseif(!preg_match("/^[A-Z]+$/i", trim($data['firstName'])))
    {
        $valid = 'First Name needs to be alphabetical only.';
    }
    elseif(!preg_match("/^[A-Z]+$/i", trim($data['lastName'])))
    {
        $valid = 'Last Name needs to be alphabetical only';
    }
    elseif(!preg_match("/^.*([0-9]+.*[A-Z])|([A-Z]+.*[0-9]+).*$/i", trim($data['password'])))
    {
        $valid = 'Password must contain at least a number and a letter.';
    }
    elseif(!preg_match("/^((\([0-9]{3}\))|([0-9]{3}))?( |-)?[0-9]{3}( |-)?[0-9]{4}$/", trim($data['phoneNumber'])))
    {
        $valid = 'Phone Number must be in the format of (000) 000 0000.';
    }
    elseif(!preg_match("/^(JAN|FEB|MAR|APR|MAY|JUN|JUL|AUG|SEP|OCT|NOV|DEC)-[0-9]{2}-[0-9]{4}$/i", trim($data['dob'])))
    {
        $valid = 'Date of Birth must be in the format of MMM-DD-YYYY.';
    }
    else
    {
        $valid = true;
    }

    return $valid;
}


function connect()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'messageBoard';

    $link = mysqli_connect($host, $username, $password, $database);

    return $link;
}



function saveSignUp($data)
{
	$link = connect();
	
	$query = 'insert into logins(phoneNumber, password, firstname, lastname,dob)  values(
		"'.$data['phoneNumber'].'",
		"'.$data['password'].'",
		"'.$data['firstName'].'",
		"'.$data['lastName'].'",
		"'.$data['dob'].'"	
		)';

    mysqli_query($link, $query);		
	
}



function checkLogin($loginID, $loginPW)
{
    $valid = false;
	
	$link = connect();

	$phoneNumber = preg_replace("/[^0-9,.]/", "",$loginID);

	$query = 'select * from logins where phonenumber = "'.$phoneNumber.'" and password="'.$loginPW.'";';
 
    $results = mysqli_query($link, $query);	
		
	if ($row = mysqli_fetch_array($results))
	{
		$valid = [];
		$valid['firstname'] = $row['firstname'];
		$valid['lastname'] = $row['lastname'];

		if ($row['admin'] === '1')
		{
			$valid['admin'] = true;
		}
		else 
		{
			$valid['admin'] = false;
		}		

	}
	
    return $valid;
}

function getUsersData()
{
	$resultsData = [];
	
	$link = connect();
	$query = 'select * from logins';
	
	$results = mysqli_query($link, $query);	
	
	while($row = mysqli_fetch_array($results))
    {
		$data=[];
		$firstName  = trim($row['firstname']);
		$lastName  = trim($row['lastname']);
		$dob  = trim($row['dob']);
        $data['firstName'] = $firstName;
		$data['lastName'] = $lastName;
		if ($row['admin'] === '1')
		{
			$data['dob'] =  '';
		}
		else
		{
			$data['dob'] =   date('M d, Y', strtotime($dob));
		}
		$resultsData[] = $data;
    }

    return $resultsData;
	
}


function deletePost($postID)
{	
	$link = connect();
	$query = 'select count(*) as reccount from posts where id ='.$postID;
	$results = mysqli_query($link, $query);	
	$row = mysqli_fetch_array($results);
	
	if ($row['reccount'] > 0)
	{
		$query = 'delete from posts where id ='.$postID;
		$results = mysqli_query($link, $query);	
		return $results;		
	}
	else
	{
		return false;
	}

}


function getPost($postID)
{
    $postData = [];

    $link = connect();
    $query = 'select firstname, lastname, title, comment, priority, filename, time, id from posts  where id ='.$postID;

    $results = mysqli_query($link, $query);

    if ($row = mysqli_fetch_array($results))
    {
        $post = validatePost($row);
        if($post != false)
        {
            $postData = $post;
        }
    }
    return $postData;	
	
}

function updatePost($data, $postID)
{
/*
echo $postID.'<br/>';
foreach($data as $d1)
{
	echo $d1.'<br/>';
}

// posts(firstname, lastname, title, comment, priority, filename, time)
*/

    // md5 is a hashing function http://php.net/manual/en/function.md5.php
    $fileName = md5(time().$data['firstName'].$data['lastName']) . '.jpg';

    move_uploaded_file($data['file'], 'uploads/'.$fileName);

    $link = connect();
    $query = 'update posts set               
                  firstname = "'.$data['firstName'].'", 
                  lastname = "'.$data['lastName'].'", 
                  title = "'.$data['title'].'", 
                  comment ="'.$data['comment'].'", 
                  priority ="'.$data['priority'].'", 
                  filename="'.$fileName.'", 
                  time = "'.time().'"
			  where id ='.$postID;
//echo $query.'<br/>';
    mysqli_query($link, $query);	

}



function filterSignUp($data)
{
    $filteredData = [];

    $filteredData['phoneNumber'] = trim(preg_replace('/-| /', '', $data['phoneNumber']));
    $filteredData['password']   = trim($data['password']);
    $filteredData['firstName']  = trim($data['firstName']);
    $filteredData['lastName']   = trim($data['lastName']);
    $filteredData['dob']        = trim($data['dob']);

    return $filteredData;
}



function insertPost($data)
{
    // md5 is a hashing function http://php.net/manual/en/function.md5.php
    $fileName = md5(time().$data['firstName'].$data['lastName']) . '.jpg';

    move_uploaded_file($data['file'], 'uploads/'.$fileName);

	$link = connect();	
	
	$query = 'insert into posts(firstname, lastname, title, comment, priority, filename, time)';
	$query = $query.'values("'.$data['firstName'].'", "'.$data['lastName'].'", "'.$data['title'];
	$query = $query.'", "'.$data['comment'].'", '.$data['priority'].', "'.$fileName.'", "'.time().'")';

	// echo $query;

    $results = mysqli_query($link, $query);			
	
}