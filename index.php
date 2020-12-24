<?php

include 'includes/session.inc.php';
include 'includes/model.inc.php';

if (!isset($_SESSION['user_logged_in'])) {
    header('location: login.html');
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $query = mysqli_query($conn, $sql);
	$user_details  = mysqli_fetch_array($query, MYSQLI_ASSOC);
	$sql = "SELECT * FROM messages WHERE (sender or recever ) = '$user_id' ORDER BY id DESC LIMIT 1";
	$query = mysqli_query($conn, $sql);
	$result = mysqli_num_rows($query);
	if ($result != 0) {
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		if ($row['sender'] == $user_id) {
			$_GET['contact'] = $row['recever'];
		} else {
			$_GET['contact'] = $row['sender'];
		}
	}
}

if (isset($_POST['submit'])) {
    $message = $_POST['message'];
    $contact = $_GET['contact'];
    date_default_timezone_set("Asia/Dhaka");
    $datetime = date("d M Y h:ia", strtotime("now"));
    $sql = "INSERT INTO messages (sender, recever, message, datetime) VALUES ('$user_id', '$contact', '$message', '$datetime')";
    mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Chat App</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-chat100">
				<div class="row p-t-15 p-r-15 p-b-15 p-l-2 w-full bg text-white m-0 header">
					<div class="col-4">
						Header
					</div>
					<div class="col-8">
						<?php
						if (isset($_GET['contact'])) {
							$contact = $_GET['contact'];
							$sql = "SELECT username FROM users WHERE id = '$contact'";
							$query = mysqli_query($conn, $sql);
							$result = mysqli_num_rows($query);
							if ($result != 0) {
								$contact_name = mysqli_fetch_array($query, MYSQLI_ASSOC);
								echo $contact_name['username'];
							}
						}
                        ?>
                        <a class="refresh" href="?contact=<?php echo $contact; ?>" style="position: absolute; right: 10px; color: #ffffffdd">
                            Refresh
                        </a>
					</div>
				</div>
				<div class="row w-full h-full m-0">
					<div class="col-4 p-l-15 p-t-15 w-full bg-light p-0">
						<!-- Side Bar -->
						<div class="search-form p-2">
							<form action="">
								<input type="search" class="p-2 pl-4 w-100" placeholder="Find Users">
							</form>
						</div>
						<div class="chat-list">
                            <?php 
                            $sql = "SELECT * FROM users";
                            $query = mysqli_query($conn, $sql);
                            while ($users = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                                $chat_item = $users['id'];
                                if ($user_details['id'] != $users['id']) {
                                    $sql_get_msg = "SELECT * FROM messages WHERE (sender = '$user_id' and recever = '$chat_item') or (sender = '$chat_item' and recever = '$user_id') ORDER BY id DESC LIMIT 1";
                                    $query_get_msg = mysqli_query($conn, $sql_get_msg);
                                    $result = mysqli_num_rows($query_get_msg);
                                    if ($result != 0) {
                                        $msg = mysqli_fetch_array($query_get_msg, MYSQLI_ASSOC); 
                                    } else {
                                        $msg['message'] = '<i>Say hello to this contact.</i>';
                                    }
                                    if (empty($users['iamge'])) {
                                        $users['image'] = 'img-01.png';
                                    }
                                    echo '<a href="?contact='.$users['id'].'">';
                                    if (isset($_GET['contact']) and ($_GET['contact'] == $users['id'])) {
                                        echo '    <div class="chat-item active">';
                                    } else {
                                        echo '    <div class="chat-item">';
                                    }
                                    echo '        <div class="media">';
                                    echo '            <div class="user-image">';
                                    echo '                <img src="images/'.$users['image'].'" alt="">';
                                    echo '            </div>';
                                    echo '            <div class="message-holder">';
                                    echo '                <div class="user-name">';
                                    echo '                    <p>'.$users['username'].'</p>';
                                    echo '                </div>';
									echo '                <div class="last-msg">';
									echo '                <p>'.$msg['message'].'</p>';
                                    echo '                </div>';
                                    echo '            </div>';
                                    echo '        </div>';
                                    echo '    </div>';
									echo '</a>';
                                }
                            }
                            ?>
							<!-- <a href="">
								<div class="chat-item active">
									<div class="media">
										<div class="user-image">
											<img src="images/avr.jpg" alt="">
										</div>
										<div class="message-holder">
											<div class="user-name">
												<p>Harry Potter</p>
											</div>
											<div class="last-msg">
												<p>Last message goes here</p>
											</div>
										</div>
									</div>
								</div>
							</a> -->
						</div>
						<p class="profile w-100">
                            <a href="">
								<?php echo $user_details['username']; ?>
							</a>
							<a href="utils/logout.util.php" style="position: absolute; right: 30px;">Logout</a>
                        </p>
					</div>
					<div class="col-8 w-full p-0">
						<div class="h-80">
							<div class="w-100 p-3">
								<div class="conversation">
									<?php
									if (isset($_GET['contact'])) {
										$sql = "SELECT * FROM (SELECT * FROM messages WHERE ((sender='$user_id' or recever ='$user_id') and (sender='$contact' or recever ='$contact')) order by id desc limit 6) sub order by id asc";
										$query = mysqli_query($conn, $sql);
										$result = mysqli_num_rows($query);
										if ($result == 0) {
											echo '<br><br><br><center><h5 class="text-muted">No messages to show!</h5></center>';
										}
										while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
											if ($row['recever'] == $user_id) {
												echo '<div class="text-container text-container-1 w-100">';
											} else {
												echo '<div class="text-container text-container-2 w-100">';
											}
											echo '<div class="text-holder">';
											echo '    <label for="">'.$row['message'].'</label>';
											echo '    <p class="date">'.$row['datetime'].'</p>';
											echo '</div>';
											echo '</div>';
										}  
									} else {
										echo '<br><br><br><center><h5 class="text-muted">No messages to show!</h5></center>';
									}                            
                                    ?>
									<!-- <div class="text-container text-container-1 w-100">
										<div class="text-holder">
											<label for="">Hello World!</label>
											<p class="date">
												20 Dec 2020 8:06pm
											</p>
										</div>
									</div>
									<div class="text-container text-container-2">
										<div class="text-holder">
											<label for="">Replying Hello World!</label>
											<p class="date">
												20 Dec 2020 8:06pm
											</p>
										</div>
									</div> -->
									<div id="show-up"></div>
								</div>
							</div>
						</div>
						<div class="bg-light w-full chat-form border-l">
							<form action="?contact=<?php echo $contact ?>" method="POST">
								<textarea name="message" class="box p-t-10 p-r-10 p-b-10 p-l-10" placeholder="Write Message" autofocus></textarea>
								<button name="submit">Send</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>