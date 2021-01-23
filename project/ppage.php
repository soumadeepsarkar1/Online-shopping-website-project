<?php
	session_start();
?>
<!DOCTYPE html>
<?php
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="prodb";
	$conn=new mysqli($servername,$username,$password,$dbname);
	if($conn->connect_error){
	die("Connection failed:".$conn->connect_error);}
?>
<html>
	<head>
		<title>Ukart</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="temp.css">
	</head>
	<body>
		<?php
			$conn=new mysqli($servername,$username,$password,$dbname);
			if($conn->connect_error){
			die("Connection failed:".$conn->connect_error);}
			$email = $phone = $Epassword=$Cpassword=$name=$password="";
			$passworderr=0;$aregistered=0;$rs=0;$ls=0;$loginerr=0;
			if ($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$email = test_input($_POST["email"]);
				if($_POST["Submit"]=="Login")
				{
					$password=test_input($_POST["pwd"]);
					$sql="select Email from usersinfo where Email='".$email."' AND Password='".$password."'";
					$result = $conn->query($sql);
					if($result->num_rows==0)
						$loginerr=1;
					else
					{
						$_SESSION["loginname"]=$email;
						$ls=1;
					}
				}
				else if($_POST["Submit"]=="Signup")
				{
					$phone = test_input($_POST["phone"]);
					$Epassword = test_input($_POST["Epwd"]);
					$Cpassword= test_input($_POST["Cpwd"]);
					$name=test_input($_POST["name"]);
					$sql="select Email from usersinfo where Email='".$email."'";
					$result=$conn->query($sql);
					if($result->num_rows>0)
						$aregistered=1;
					else if($Epassword!=$Cpassword)
						$passworderr=1;
					else if($Epassword!="")
					{
						$sql="insert into usersinfo (Email,Password,Phone,Name) values ('".$email."','".$Cpassword."','".$phone."','".$name."')";
						if ($conn->query($sql) === TRUE)
						{
							$rs=1;
							$_SESSION["loginname"]=$email;
						}
						else
							echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}
			}
			function test_input($data)
			{
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
			if(isset($_GET["state"]))
			{
				if($_GET["state"]=="loggedout")
				{
					session_unset();
					session_destroy();
				}
			}
		?>
		<div class="loginModal" id="loginModal"<?php if($aregistered==1||$loginerr==1){echo " style=\"display:block\"";}?>>
			<div class="loginModalContent">
				<div class="modalHeader">
					<div class="modalHeaderText" <?php if($aregistered==1){echo " style=\"display:none\"";}?>>
						Login to your account
					</div>
					<?php if($aregistered==1){echo "<div id=\"areg\">You are already registered. Try logging in.</div>";}?>
					<span class="closebutton" id="loginCloseButton">&times;</span>
				</div>
				<form class="loginForm" name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?pid=".$_GET['pid']);?>">
					<input type="text" name="email"<?php if($aregistered==1){echo "value=\"".$email."\"";}?> placeholder="Email" required>
					<input type="password" name="pwd" placeholder="Password" required>
					<?php if($loginerr==1){echo"<div id=\"lerr\">Invalid login credentials</div>";
					$loginerr==0;}?>
					<button type="submit" name="Submit" value="Login">Login</button>
				</form>
			</div>
		</div>
		<div class="signupModal" id="signupModal"<?php if($passworderr==1){echo " style=\"display:block\"";}?>>
			<div class="signupModalContent">
				<div class="modalHeader">
					<div class="modalHeaderText">
						Create a new account
					</div>
					<span class="closebutton" id="signupCloseButton">&times;</span>
				</div>
				<form class="signupForm" name="signupForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?pid=".$_GET['pid']);?>">
					<input type="email" name="email" value="<?php if($passworderr==1){ echo($email);}?>" placeholder="Enter Email" required>
					<input type="text" name="name" value="<?php if($passworderr==1){ echo($name);}?>" placeholder="Enter your Name" required>
					<input type="password" name="Epwd" placeholder="Enter Password" required>
					<input type="password" name="Cpwd" placeholder="Confirm Password" required>
					<?php if($passworderr==1){echo "<div id=\"perr\">Passwords did not match</div>";}?>
					<input type="text" name="phone" value="<?php if($passworderr==1){ echo($phone);$passworderr=0;}?>" placeholder="Phone Number" required>
					<button type="submit" name="Submit" value="Signup">Signup</button>
				</form>
			</div>
		</div>
		<header>
			<table>
				<tr class="navigate">
					<td class="left"></td>
					<td class="center"><a href="Ukhome.php"><img class="logo" src="Images/UkartLogo.png" alt "Ukart Logo"></a></td>
					<td class="right">
						<span class="navigate">
							<ul>
								<li id="moreDropdown">
									<div id="moreButton">
										More <i class="downarrow"></i>
									</div>
									<div class="dropdowncontent">
										<a href="">Send Feedback</a>
										<a href="">About Us</a>
									</div>
								</li>
								<li id="signupButton" <?php if(isset($_SESSION["loginname"])){echo"style=\"display:none\"";}?>>Signup</li>
								<li id="loginButton" <?php if(isset($_SESSION["loginname"])){echo"style=\"display:none\"";}?>>Login</li>
								<li id="usernamedropdown" <?php if(isset($_SESSION["loginname"])){echo"style=\"display:block\">".$_SESSION["loginname"];}?><i class="downarrow"></i>
									<div class="dropdowncontent">
										<a href="cart.php">View Cart<img class="cartimage" src="Images/cart.png"></a>
										<a href="ppage.php?state=loggedout&pid=<?php echo $_GET['pid'];?>">Log out</a>
									</div>
								</li>
							</ul>
						</span>
					</td>
				<tr>
			</table>
		</header>
		<nav class="flex_container" id="navbar">
			<div id="Mobilesdropdown">
				<div id="Mobilesdropdownbutton">
					Mobiles<i class="downarrow"></i>
				</div>
				<div class="dropdowncontent">
					<ul>
						<li><a href="productspage.php?title=Mi+Smartphones">Mi</a></li><li><a href="productspage.php?title=Realme+Smartphones">Realme</a></li><li><a href="productspage.php?title=Samsung+Smartphones">Samsung</a></li><li><a href="productspage.php?title=OPPO+Smartphones">OPPO</a></li><li><a href="productspage.php?title=Apple+Smartphones">Apple</a></li>
					</ul>
				</div>
			</div>
			<div id="EAdropdown">
				<div id="EAdropdownbutton">
					Electronics &amp Accessories<i class="downarrow"></i>
				</div>
				<div class="dropdowncontent">
					<ul>
						<li><a href="productspage.php?title=Television">Television</a></li><li><li><a href="productspage.php?title=Laptops">Laptops</a></li><a href="productspage.php?titleWashing+Machines=">Washing Machines</a></li><li><a href="productspage.php?title=Air+Conditioners">Air Conditioners</a></li><li><a href="productspage.php?title=Sound+Systems">Sound Systems</a></li><li><a href="productspage.php?title=Headphones+and+Earphones">Headphones &amp Earphones</a></li>
					</ul>
				</div>
			</div>
			<div id="Mendropdown">
				<div id="Mendropdownbutton">
					Men<i class="downarrow"></i>
				</div>
				<div class="dropdowncontent">
					<ul>
						<li><a href="productspage.php?title=Men's+Top+Wear">Top Wear</a></li><li><a href="productspage.php?title=Men's+Bottom+Wear">Bottom Wear</a></li><li><a href="productspage.php?title=Men's+Footwear">Footwear</a></li><li><a href="productspage.php?title=Men's+Watches">Watches</a></li><li><a href="productspage.php?title=Men's+Grooming">Grooming</a></li>
					</ul>
				</div>
			</div>
			<div id="Womendropdown">
				<div id="Womendropdownbutton">
					Women<i class="downarrow"></i>
				</div>
				<div class="dropdowncontent">
					<ul>
						<li><a href="productspage.php?title=Western+Wear">Western Wear</a></li><li><a href="productspage.php?title=Ethnic+Wear">Ethnic Wear</a></li><li><a href="productspage.php?title=Footwear">Footwear</a></li><li><a href="productspage.php?title=Jewellery">Jewellery</a></li><li><a href="productspage.php?title=Ladies+Watches">Watches</a></li>
					</ul>
				</div>
			</div>
			<div id="Booksdropdown">
				<div id="Booksdropdownbutton">
					Books<i class="downarrow"></i>
				</div>
				<div class="dropdowncontent">
					<ul>
						<li><a href="productspage.php?title=Entrance+Exams+Books">Entrance Exams</a></li><li><a href="productspage.php?title=Acamedics+Books">Academics</a></li><li><a href="productspage.php?title=Literature+and+Fiction">Literature &amp Fiction</a></li><li><a href="productspage.php?title=Self-Help+Books">Self-Help</a></li><li><a href="productspage.php?title=Business Books">Business</a></li>
					</ul>
				</div>
			</div>
			<div id="Offers">
				<a href="">Offers Zone</a>
			</div>
		</nav>
		<div id="productdetails">
			<?php
				$sql="select ProductName,ImgPath,Price,DisPrice from proinfo where Pid=".$_GET['pid'];
				$result = $conn->query($sql);
				if($result->num_rows>0)
				{
					$row = $result->fetch_assoc();
					echo "
					<div id=\"imgcart\">
						<div id=\"productimage\">
							<img src=\"".$row["ImgPath"]."\">
						</div>
						<div id=\"addcart\"";
							if(isset($_SESSION["loginname"]))
							{
								$sql="select Uid from usersinfo where Email='".$_SESSION["loginname"]."'";
								$result1=$conn->query($sql);
								$row1=$result1->fetch_assoc();
								echo " onclick=\"cart(".$_GET['pid'].",1,".$row1["Uid"].",0)\"";
							}
							else
								echo" onclick=\"addcartlogin()\"";
							echo">Add to Cart
						</div>
					</div>
					<div id=\"pdetails\">
						<div id=\"pname\">
							".$row["ProductName"]."
						</div>
						<div id=\"citemcost\">
							<span id=\"citemdiscprice\">
								₹".$row["DisPrice"]."
							</span>
							<span id=\"citemprice\">
								₹".$row["Price"]."
							</span>
						</div>
						<div id=\"pspecs\">
							<div id=\"spechead\">
								Product Details:
							</div>
							<iframe src=\"Ds/".$_GET['pid'].".htm\">
							</iframe>
						</div>
					</div>";
				}
			?>
		</div>
		<div id="Addsuccessmsg">
			Product added to cart
		</div>
		<script src="script1.js"></script>
	</body>
</html>