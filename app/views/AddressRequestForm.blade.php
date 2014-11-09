<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Address Request Form</title>
    <!-- Bootstrap -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//d79i1fxsrar4t.cloudfront.net/jquery.liveaddress/2.4/jquery.liveaddress.min.js"></script>
   <style>
   	body{
			background-color:#D9D6CF;
		}
		.signin {
		  max-width: 330px;
		  padding: 15px;
		  margin: 0 auto;
		  background-color:#AEAEAE;
		  border:#2C3E50 4px solid;
		}
		#wrapper{
			background-color:#2F4D6B;
			position:relative;
			padding:2px 30px;
			margin:10px -30px 20px -30px;
		}
		#wrapper:before,
		#wrapper:after {
			content:" ";
			border-top:10px solid #2C3E50;
			position:absolute;
			bottom:-10px;
		} 
		#wrapper:before {
			border-left:10px solid transparent;
			left:0;
			}
		#wrapper:after {
			border-right:10px solid transparent;
			right:0;
		}
		div.title{
			text-align:center;
		}
		div.title h2{
			color:#ffffff;
			font-weight:600;
		}
		div.header h1{
			font-weight:800;
			text-align:center;
		}
		a.homeLink{
			text-decoration:none;
			color:#2C3E50;
		}
   </style>
  </head>
  <body>
    <div class="container">
    	<div class="signin">
			<div class="header">
				<h1><a class="homeLink" href="{{URL::to('/')}}">X-Press Cards</a></h1>
			</div>
			<div id="wrapper" class="ribbon title">
				<h2>Address Request</h2>
			</div>
			<p>{{$sender->name}} would like your mailing address in order to send you a postcard and/or a gift! Just fill out the form, and we'll handle the rest</p>
			<p class="mute">Try out <a href="{{URL::to('/')}}">X-Press Cards yourself!</a></p>
			<form role="form" method="post" id="requestForm" action="{{URL::route('addressRequestPost',array($request->id))}}">
				  <div class="form-group">
				    <label for="InputName">Your Name</label>
				    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
				  </div>
				  <div class="form-group">
				  	<label for="InputEmail">Your Email</label>
				  	<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{$responder->email}}">
				  </div>
				  <div class="form-group" style="display:{{$agent?'none':'block'}}"> 
				    <label for="InputAddress">Your Address</label>
				    <textarea class="form-control" id="address" name="address" placeholder="Enter your address"></textarea>
				  </div>
				  <input type="hidden" name="sender" value="{{$sender->id}}"/>
				  <div style="display:{{$agent?'block':'none'}}">
				    <div class="form-group">
				    	<label for="AddressLine1">Street</label>
				        <input autocomplete="on" name="deliveryLine1" type="text" class="form-control" id="AddressLine1" placeholder="Enter Address Line 1" ng-model="address.deliveryLine1" required>
				    </div>
			        <input autocomplete="on" name="lastLine" type="hidden" class="form-control" id="AddressLine2" required>
				    <div class="form-group">
				        <label for="City">City</label>
				        <input autocomplete="on" name="cityName" type="text" class="form-control" id="City" placeholder="Enter City"
				               required>
				    </div>
				    <div class="form-group">
				        <label for="State">State</label>
				        <select autocomplete="on" class="form-control" name="stateAbbreviation" id="State" required>
				            <option value="OH">Ohio</option>
				            <option value="KY">Kentucky</option>
				            <option value="AL">Alabama</option>
				            <option value="AK">Alaska</option>
				            <option value="AZ">Arizona</option>
				            <option value="AR">Arkansas</option>
				            <option value="CA">California</option>
				            <option value="CO">Colorado</option>
				            <option value="CT">Connecticut</option>
				            <option value="DE">Delaware</option>
				            <option value="DC">District Of Columbia</option>
				            <option value="FL">Florida</option>
				            <option value="GA">Georgia</option>
				            <option value="HI">Hawaii</option>
				            <option value="ID">Idaho</option>
				            <option value="IL">Illinois</option>
				            <option value="IN">Indiana</option>
				            <option value="IA">Iowa</option>
				            <option value="KS">Kansas</option>
				            <option value="LA">Louisiana</option>
				            <option value="ME">Maine</option>
				            <option value="MD">Maryland</option>
				            <option value="MA">Massachusetts</option>
				            <option value="MI">Michigan</option>
				            <option value="MN">Minnesota</option>
				            <option value="MS">Mississippi</option>
				            <option value="MO">Missouri</option>
				            <option value="MT">Montana</option>
				            <option value="NE">Nebraska</option>
				            <option value="NV">Nevada</option>
				            <option value="NH">New Hampshire</option>
				            <option value="NJ">New Jersey</option>
				            <option value="NM">New Mexico</option>
				            <option value="NY">New York</option>
				            <option value="NC">North Carolina</option>
				            <option value="ND">North Dakota</option>
				            <option value="OK">Oklahoma</option>
				            <option value="OR">Oregon</option>
				            <option value="PA">Pennsylvania</option>
				            <option value="RI">Rhode Island</option>
				            <option value="SC">South Carolina</option>
				            <option value="SD">South Dakota</option>
				            <option value="TN">Tennessee</option>
				            <option value="TX">Texas</option>
				            <option value="UT">Utah</option>
				            <option value="VT">Vermont</option>
				            <option value="VA">Virginia</option>
				            <option value="WA">Washington</option>
				            <option value="WV">West Virginia</option>
				            <option value="WI">Wisconsin</option>
				            <option value="WY">Wyoming</option>
				        </select>
				    </div>
				    <div class="form-group">
				        <label for="ZipCode">Zip Code</label>
				        <input autocomplete="on" name="zipCode" class="form-control" id="ZipCode" placeholder="Zip Code"
				               required>
				    </div>
				    <input type="hidden" name="plus4Code" id="plus4Code"/>
				</div>	
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
    </div>
    <script>
	    $(document).ready(function(){
	    	var liveaddress = $.LiveAddress({
	    		 key: "{{Config::get('smartystreet.public')}}", 
	    		 debug: true, 
	    		 autoVerify: true, 
	    		 submitVerify:true,
	    		 invalidMessage: "That address is not valid" 
			 });
			 function setFields(event,data,previousHandler){
			 	console.log('address accepted');
			 	console.log(event,data);
			 	var chosen=data.response.chosen;
			 	$('#ZipCode').val(chosen.components.zipcode);
			 	$('#plus4Code').val(chosen.components.plus4_code);
			 	$('#State').val(chosen.components.state_abbreviation);
			 	$('#City').val(chosen.components.city_name);
			 	$('#AddressLine1').val(chosen.delivery_line_1);
			 	$('#addressLine2').val(chosen.last_line);
			 	previousHandler(event,data);
			 }
			 liveaddress.on('AddressAccepted',setFields);
	    });
    </script>
  </body>
</html>