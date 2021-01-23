<?php
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="prodb";
	$conn=new mysqli($servername,$username,$password,$dbname);
	if($conn->connect_error){
	die("Connection failed:".$conn->connect_error);}
	function showcart($conn)
	{
		$sql="select CustId,ProId,Qty from cart where CustId=".$_REQUEST["uid"];
		$result=$conn->query($sql);
		if($result->num_rows>0)
		{
			echo "<div id=\"cartitems\">";
			while($row=$result->fetch_assoc())
			{
				$result1=$conn->query("select ProductName,ImgPath,DisPrice,Price from proinfo where Pid=".$row["ProId"]);
				$row1=$result1->fetch_assoc();
				echo "
				<div id=\"individualitem\">
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
			
			$result1=$conn->query("select SUM(Qty) as total from cart where CustId=".$_REQUEST["uid"]) or die($conn->error); 
			$row1=$result1->fetch_assoc();
			$result1=$conn->query("select ProId,Qty from cart where CustId=".$_REQUEST["uid"]);
			if($result1->num_rows>0)
			{	$sumprice=0;$sumdisprice=0;
				while($row2=$result1->fetch_assoc())
				{
					$result=$conn->query("select DisPrice,Price from proinfo where pid=".$row2["ProId"]);
					$row3=$result->fetch_assoc();
					$sumprice=$sumprice+$row3["Price"]*$row2["Qty"];
					$sumdisprice=$sumdisprice+$row3["DisPrice"]*$row2["Qty"];
				}
				echo "</div>";
				echo "
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
						You saved ₹".($sumprice-$sumdisprice+40)." on this order
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
		else
			echo "
				<div id=\"cartitems\">
				<div id=\"cartemptymsg\">
					Your cart is empty
				</div>
				</div>";
		
	}
	if($_REQUEST["todo"]==1)//todo=1 means add, todo=-1 means reduce by 1, todo=0 means remove totally
	{
		$sql="select CustId from cart where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
		$result=$conn->query($sql);
		if($result->num_rows>0)
			$sql="update cart set Qty=Qty+1 where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
		else
			$sql="insert into cart (CustId,ProId,Qty) values (".$_REQUEST["uid"].",".$_REQUEST["pid"].",1)";
		if($conn->query($sql)==TRUE)
		{
			if($_REQUEST["showcart"]==0)
				echo "Success";
			else
			{
				showcart($conn);
			}
		}
		else
			echo "Error".$conn->error;	
	}
	elseif($_REQUEST["todo"]==-1)
	{
		$sql="update cart set Qty=Qty-1 where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
		if($conn->query($sql)==TRUE)
		{
			$sql="select Qty from cart where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
			$result=$conn->query($sql);
			if($result->num_rows>0)
			{
				$rows1=$result->fetch_assoc();
				if($rows1["Qty"]==0)
				{
					$sql="delete from cart where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
					if($conn->query($sql)==TRUE)
					{
						showcart($conn);
					}
					else
						echo "Error".$conn->error;
				}
				else
					showcart($conn);
			}
		}
		else
			echo "Error".$conn->error;
	}
	else
	{
		$sql="delete from cart where CustId=".$_REQUEST["uid"]." AND ProId=".$_REQUEST["pid"];
		if($conn->query($sql)==TRUE)
		{
			showcart($conn);
		}
		else
			echo "Error".$conn->error;
	}
?>