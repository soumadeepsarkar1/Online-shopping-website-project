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
		<title>Cart</title>
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
				<form class="loginForm" name="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
				<form class="signupForm" name="signupForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
										<a href="Ukhome.php?state=loggedout">Log out</a>
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
		<div id="flexcart">
			<div id="cartitems">
				<?php
					$sql="select Uid from usersinfo where Email='".$_SESSION["loginname"]."'";
					$result = $conn->query($sql);
					if($result->num_rows>0)
					{
						$row3=$result->fetch_assoc();
						$sql="select CustId,ProId, Qty from cart where CustId=".$row3["Uid"];
						$result=$conn->query($sql);
						if($result->num_rows>0)
						{
							while($row=$result->fetch_assoc())
							{
								$result1=$conn->query("select ProductName,ImgPath,DisPrice,Price from proinfo where Pid=".$row["ProId"]);
								$row1=$result1->fetch_assoc();
								echo 
								"<div id=\"individualitem\">
									<div id=\"individualitemimage\">
									<img src=\"".$row1["ImgPath"]."\">
									</div>
									<div id=\"individualitemdesc\">
										<div id=\"cartitemname\">
										".$row1["ProductName"]."
										</div>
										<div id=\"citemcost\">
											<span id=\"citemdiscprice\">
												₹".$row1["DisPrice"]."
											</span>
											<span id=\"citemprice\">
												₹".$row1["Price"]."
											</span>
										</div>
										<div id=\"numbercontrols\">
											<span id=\"plus\" onclick=\"cart(".$row["ProId"].",1,".$row["CustId"].",1)\">
												+
											</span>
											<span id=\"number\">
												".$row["Qty"]."
											</span>
											<span id=\"minus\" onclick=\"cart(".$row["ProId"].",-1,".$row["CustId"].",1)\">
												-
											</span>
											<span id=\"delete\" onclick=\"cart(".$row["ProId"].",0,".$row["CustId"].",1)\">
												<img id=\"deleteimg\" src=\"Images/bin.png\">
											</span>
										</div>
									</div>
								</div>";
							}
						}
						else
							echo "<div id=\"cartemptymsg\">
									Your cart is empty
								</div>";
						$result1=$conn->query("select SUM(Qty) as total from cart where CustId=".$row3["Uid"]) or die($conn->error); 
						$row1=$result1->fetch_assoc();
						$result1=$conn->query("select ProId,Qty from cart where CustId=".$row3["Uid"]);
						if($result1->num_rows>0)
						{	$sumprice=0;$sumdisprice=0;
							while($row2=$result1->fetch_assoc())
							{
								$result=$conn->query("select DisPrice,Price from proinfo where pid=".$row2["ProId"]);
								$row3=$result->fetch_assoc();
								$sumprice=$sumprice+$row3["Price"]*$row2["Qty"];
								$sumdisprice=$sumdisprice+$row3["DisPrice"]*$row2["Qty"];
							}
							echo "</div>
							<div id=\"totalplaceorder\">
								<div id=\"pricehead\">
									Price Details:
								</div>
								<table>
									<tr><td>Price (".$row1["total"];
									if($row1["total"]==1)
										echo " item";
									else
										echo " items";
									echo ")</td><td>₹".$sumdisprice."</td></tr>
									<tr><td>Delivery charge</td><td>₹40</td></tr>
									<tr id=\"tpayable\"><td>Total Payable</td><td>₹".($sumdisprice+40)."</td></tr>
								</table>
								<div id=\"saved\">
									You will save ₹".($sumprice-$sumdisprice+40)." on this order
								</div>
								<form>
									<input name=\"address\" type=\"text\" placeholder=\"Enter address\" required>
									<div id=\"pmethod\">Payment Method: Cash on delivery</div>
									<button name=\"order\" type=\"submit\">
										Place Order
									</button>
								</form>
							</div>";
						}
					}
				?>
		</div>
		<script src="script1.js"></script>
	</body>
</html>
		