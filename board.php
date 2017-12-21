<html>
<head><title>Message Board</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<body>
<?php
session_start();
//error_reporting(E_ALL);
ini_set('display_errors','On');


  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//  print_r($dbh);
//  $dbh->beginTransaction();
//  $dbh->exec('delete from users where username="smith"');
//  $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
//        or die(print_r($dbh->errorInfo(), true));
//  $dbh->commit();
//
//  $stmt = $dbh->prepare('select * from users');
//  $stmt->execute();
//  print "<pre>";
//  while ($row = $stmt->fetch()) {
//    print_r($row);
//  }
//  print "</pre>";
//} catch (PDOException $e) {
//  print "Error!: " . $e->getMessage() . "<br/>";
//  die();
$username="";
if(isset($_SESSION['user'])) {
    $username = $_SESSION['user'];

//    foreach ($_SESSION as $uname => $pass) {
//        $username = $uname;
//    }
    echo " <h2 align='center'>Hey " . $username . "! </h2>";
    echo "<form action='' method='POST' name='logoutform' > <p align='center'> <button value='LOGOUT' name='logout' >LOGOUT</button></p>";

    if (isset($_POST['logout'])) {
        session_unset();
        header('Location:/project5/login.php');

    }

    echo "</form>";
    echo "<p align='center'>Post a new message!</p>";

    echo "<form action='' method='POST' name='msgpostform' > <p align='center'><input type='text' name='msg' style='width: 300px;' /> </p>";
    echo "<p align='center'><button value='Post message' name='msgpost'>New Post</button> </p>";
    echo "</form>";

    if (isset($_POST['msgpost']) && isset($_POST['msg'])) {
        $message = $_POST['msg'];
        $dbh->beginTransaction();
        $dbh->exec('insert into posts(id,postedby,datetime,message) values ("' . uniqid() . '","' . $username . '","' . date("Y-m-d H:i:s") . '","' . $message . '");')
        or die(print_r($dbh->errorInfo(), true));
        $dbh->commit();


    }

    $replyforms = array();
    $printed_forms=array();
    echo "<div  id='ud2' style='height:500px; border: 1px solid black;margin: auto; background-color: #f1f1c1; width:500px;align-items: ; overflow:scroll;' >";


    function repy_forms($dbh, $username)
    {

        global $replyforms;
        global $printed_forms;
        $sql = "SELECT posts.id,users.username,users.fullname,posts.datetime,posts.replyto,posts.message from posts,users where posts.postedby=users.username ORDER BY posts.datetime DESC ";
        $result = $dbh->query($sql);
        while ($tuple = $result->fetch(PDO::FETCH_ASSOC))
        {
            if(!in_array($tuple['id'].'msg',$printed_forms) )
            {
//           echo "<script>var space=document.createElement('p');var space_value=document.createTextNode('');space.appendChild(space_value);document.getElementById('ud2').appendChild(space);<script>";array_push($printed_forms, $tuple['id'].'msg');
            echo "Id: " . $tuple['id'] . "<br>";
            echo "By: " . $tuple['fullname'] . ' aka ' . $tuple['username'] . "<br>";
            echo "Date&Time : " . $tuple['datetime'] . "<br>";
            if (isset($tuple['replyto'])){
                echo "Reply To : " . $tuple['replyto'] . "<br>";

            }
            echo "Message : " . $tuple['message'] . "<br>";
            echo "<br>";

                echo "<form action='' method='POST' style=' align=right; ' > <p align='center'><input type='text' name=' " . $tuple['id'] . "msg' style='width: 300px;' /> </p>";
                echo "<p align='center'><button value='Post message' name='" . $tuple['id'] . "msgpost' >Reply To</button> </p>";
                echo "<br>";
                echo "</form>";

//                echo $tuple['id'].'msg'."<br>";
            }


            if ( isset($_POST[$tuple['id'] . 'msgpost']) && isset($_POST[$tuple['id'] . 'msg']) && !in_array($tuple['id'] . 'msg',$replyforms)  && !empty($_POST[$tuple['id'] . 'msg']) )
            {
                array_push($replyforms,$tuple['id'] . 'msg');
                $message = $_POST[$tuple['id'] . 'msg'];
                $dbh->beginTransaction();
                $dbh->exec('insert into posts(id,replyto,postedby,datetime,message) values ("' . uniqid() . '","' . $tuple['id'] . '","' . $username . '","' . date("Y-m-d H:i:s") . '","' . $message . '");')
                or die(print_r($dbh->errorInfo(), true));
                $dbh->commit();
                header('Location:/project5/board.php');
//                echo "</div>";

//                echo '<script> document.getElementById("ud2").innerHTML = " "; </script>';

//                unset($printed_forms);
//                $printed_forms=array();

//                repy_forms($dbh, $username);
            }
        }


    }

    repy_forms($dbh, $username);
    echo "</div>";

}
else
    echo"<h2 align='center'>go login</h2>";
?>
</body>
</html>
