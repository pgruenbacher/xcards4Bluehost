<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>X-Press Cards Receipt</title>
<style type="text/css">
<!--
body {
  font-family:Tahoma;
}

img {
  border:0;
  width:20%;
}

#page {
  width:800px;
  margin:0 auto;
  padding:15px;

}

#logo {
  float:left;
  margin:0;
}

#address {
  height:181px;
  margin-left:250px; 
}

table {
  width:100%;
}

td {
padding:5px;
}

tr.odd {
  background:#e1ffe1;
}
-->
</style>
</head>
	<body>
		<div id="page">
		  <div id="logo">
		    <a href=""></a>
		  </div><!--end logo-->
		  
		  <div id="address">
		
		    <p>X-Press Cards<br />
		    <a href="mailto:info@x-presscards.com">info@x-presscards.com</a>
		    <br /><br />
		    Transaction #{{$order->reference}}<br />
		    Created on {{$order->created_at}}<br />
		    </p>
		  </div><!--end address-->
		
		  <div id="content">
		    <p>
		      <strong>{{$user->user}}</strong><br />
		      Name: {{$user->first}}, {{$user->last}}<br />
		      Email: {{$user->email}}<br />
		      Payment Type: Credit Card    </p>
		    <hr>
		    <table>
		      <tr><td><strong>Description</strong></td><td><strong>Qty</strong></td><td><strong>Unit Price</strong></td><td><strong>Amount</strong></td></tr>
		      <tr class="odd"><td>Credit Purchase</td><td>{{$order->credits}}</td><td>{{$pricing->price}}</td><td> ${{($charge->amount)/100}}</td></tr>
		
		    </table>
		    <hr>
		    <p>
		      Thank you for your order!  This transaction will appear on your billing statement as "X-Press Cards".<br />
		      If you have any questions, please feel free to contact us at <a href="info@x-presscards.com">info@x-presscards.com</a>.
		    </p>
		
		    <hr>
		    <p>
		      <center><small>This communication is for the exclusive use of the addressee and may contain proprietary, confidential or privileged information. If you are not the intended recipient any use, copying, disclosure, dissemination or distribution is strictly prohibited.
		      <br /><br />
		      &copy; X-Press Cards All Rights Reserved
		      </small></center>
		    </p>
		  </div><!--end content-->
		</div><!--end page-->
	</body>
</html>