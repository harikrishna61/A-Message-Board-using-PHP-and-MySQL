<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
<style>
h5{
text-align:center; 

}
#ld1{
    text-align:center;
    border: 1px solid black;
	background-color: #f1f1c1
}


</style>




<body >
<br>
<br>
<br>

<div  class ="container" style="margin: auto;height:250px;  width:350px;" id="ld1">
<br>
<h5>LOGIN</h5>
<form action="" method="POST">
<p>USER NAME : <input type="text" name="userid" ></input></p>
<br>
<p>PASSWORD : <input type ="password" name="pswd" ></input></p>
<br>	
    <button type="submit"  name="submit">LOGIN</button>


<?php

session_start();

$conn = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_POST['userid']) && isset($_POST['pswd']) && !empty($_POST['userid']) && !empty($_POST['pswd']))
{
	$user=$_POST['userid'];
	$pass=$_POST['pswd'];
	$sql1 = "select *,count(*) as cnt from users where username='$user'";

	$result1	= $conn -> query($sql1);
    $getrows=$result1->fetch(PDO::FETCH_ASSOC);
	$no=$getrows['cnt'];
		if($no==0)
		{
			echo "USER DOEsNT EXIST";
//            header('Location:login.php');

        }
		else
		{


			$result2 = $conn -> query($sql1);
		while($tuple1= $result2-> fetch(PDO::FETCH_ASSOC))
	    {
			$dbusername = $tuple1['username'];
			$dbpassword = $tuple1['password'] ;
//			$str =substr($dbpassword,0,4);
//			echo "$str";
//            echo md5($pass)." hari ".$dbpassword;
			if($user==$dbusername && md5($pass)==$dbpassword)
			{
				//$_SESSION['uname'] = $username;

                echo "logged in successfully :P";
                $_SESSION['user']=$user;

				header('Location:/project5/board.php');

			}
			else
			{
				echo "incorrect password";
//                header('Location:login.php');

            }
		}
		}

		
	
}
else
{
	echo "dont leave the fields blank";
//    header('Location:login.php');

}

?>
</form>
</div>

</body>
</html>

