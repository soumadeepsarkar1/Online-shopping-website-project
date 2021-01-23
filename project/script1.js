var loginModal=document.getElementById("loginModal");
var loginButton=document.getElementById("loginButton");
var signupButton=document.getElementById("signupButton");
var signupModal=document.getElementById("signupModal");
var moreButton=document.getElementById("moreButton");
var loginCloseButton=document.getElementById("loginCloseButton");
var signupCloseButton=document.getElementById("signupCloseButton");
var addtocartlogin=document.getElementById("addcart");
loginButton.onclick=function(){loginModal.style.display="block";}
loginCloseButton.onclick=function(){loginModal.style.display="none";}
signupButton.onclick=function(){signupModal.style.display="block"}
signupCloseButton.onclick=function(){signupModal.style.display="none";}
function addcartlogin()
{
	loginModal.style.display="block";
}
function cart(pid,todo,uid,showcart)//if showcart==1 then show cart else display item added to cart message
{
	var xhttp=new XMLHttpRequest();
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			if(showcart==0 && this.responseText=="Success")
			{
				document.getElementById("Addsuccessmsg").style.display="block";
				setTimeout(function(){document.getElementById("Addsuccessmsg").style.display="none";},2000);
			}
			else if(showcart==1)
			{
				document.getElementById("flexcart").innerHTML=this.responseText;
			}
		}
	};
	xhttp.open("GET","cartmanager.php?pid="+pid+"&todo="+todo+"&uid="+uid+"&showcart="+showcart,true);
	xhttp.send();
}
function ordersuccess()
{
	document.getElementById("ordersuccessmsg").style.display="block";
	setTimeout(function(){document.getElementById("ordersuccessmsg").style.display="none";},2000);
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) 
{
	if (event.target == signupModal) 
	{
	signupModal.style.display = "none";
	}
	else if (event.target == loginModal) 
	{
	loginModal.style.display = "none";
	}
}
window.onscroll = function() {myFunction()};
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;
function myFunction() {
	if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}