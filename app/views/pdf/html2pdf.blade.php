<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		@page { margin: 0px; }
		body { margin: 0px; }
		html{margin:0px;}
		div.backMessage{
			/*font-size:10pt !important;*/
			position: absolute;
			top: {{$cards[0]['card_setting']['message_top']}}px;
			left: {{$cards[0]['card_setting']['message_left']}}px;
			z-index: 10;
			color: black;
			font-family:Helvetica;
			display:inline-block;
			line-height:2;
			font-size:16px;
			padding:0px;
			margin:0px;
			height: {{$cards[0]['card_setting']['message_height']}}px;
			width: {{$cards[0]['card_setting']['message_width']}}px;
			overflow:hidden;
			word-wrap:break-word;
		}
		div.frontMessage{
			position:absolute;
			z-index:10;
			padding:0.5rem;
			color:white;
			font-family:'big_johnregular';
			display:inline-block;
			line-height:1;
  			font-size:22px;
			padding:0px;
			margin:0px;
			width:862px;
			height:562.5px;
			overflow:hidden;
			word-wrap:break-word;
		}
		div.frontMessage p{
			margin:0px;
			padding:0px;
		}
		div.container{
			width: 2750px;
			border: solid 1px black;
			margin:0px;
		}
		div.page{
			margin:0px;
			position:relative;
			height: 1881px;
			border: solid 1px green;
			page-break-after:always;
		}
		div.address{
			top:150px;
			left:550px;
			font-size:13pt;
			position:absolute;
		}
		div.barcode{
			position:absolute;
		}
		div.permit{
			position:absolute;
		}
		.frontImage, div.card{
			width:862px;
			height:562.5px;
			position:absolute;
			z-index:0;
		}
		div.table {
		    display:block;
		}
		div.tr {
		    display:block;
		}
		div.td {
		    float:left;
		    display:inline-block;
		}
		.rotated{
			transform-origin: 50% 50%;
		}
		div.frontImage3,div.card1{
			left:68.7px;
			top:68.7px;
		}
		div.frontImage2,div.card2{
			left:993.6px;
			top:68.7px;
		}
		div.frontImage1,div.card3{
			left:1918.5px;
			top:68.7px;
		}
		div.frontImage6,div.card4{
			left:68.7px;
			top:693.6px;
		}
		div.frontImage5,div.card5{
			left:993.6px;
			top:693.6px;
		}
		div.frontImage4,div.card6{
			left:1918.5px;
			top:693.6px;
		}
		div.frontImage9,div.card7{
			left:68.7px;
			top:1318.5px;
		}
		div.frontImage8,div.card8{
			left:993.6px;
			top:1318.5px;
		}
		div.frontImage7,div.card9{
			left:1918.5px;
			top:1318.5px;
		}
		img.standard{
			z-index:-10;
			width:862px;
			height:562.5px;
		}
		img.cropMarks{
			z-index:100;
			position:absolute;
			top:-60px;
			left:-18px;
			width:900px;
			height:694.5px;
		}
		@font-face {
		  font-family: 'bobregular';
		  src: url("{{URL::asset('assets/fonts/bob-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/bob-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/bob-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/bob-webfont.woff')}}") format("woff"), url("{{URL::asset('assets/fonts/bob-webfont.ttf')}}") format("truetype"), url("{{URL::asset('assets/fonts/bob-webfont.svg#bobregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		@font-face {
		  font-family: 'kilogramregular';
		  src: url("{{URL::asset('assets/fonts/kilogram_kg-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/kilogram_kg-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/kilogram_kg-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/kilogram_kg-webfont.woff')}}") format("woff"), url("{{URL::asset('assets/fonts/kilogram_kg-webfont.ttf')}}") format("truetype"), url("{{URL::asset('assets/fonts/kilogram_kg-webfont.svg#kilogramregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		@font-face {
		  font-family: 'slim_joeregular';
		  src: url("{{URL::asset('assets/fonts/slim_joe-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/slim_joe-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/slim_joe-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/slim_joe-webfont.woff')}}") format("woff"), url("{{URL::asset('assets/fonts/slim_joe-webfont.ttf')}}") format("truetype"), url("{{URL::asset('assets/fonts/slim_joe-webfont.svg#slim_joeregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		@font-face {
		  font-family: 'parisishregular';
		  src: url("{{URL::asset('assets/fonts/parisish-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/parisish-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/parisish-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/parisish-webfont.woff')}}") format("woff"), url("../fonts/parisish-webfont.ttf") format("truetype"), url("{{URL::asset('assets/fonts/parisish-webfont.svg#parisishregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		@font-face {
		  font-family: 'big_johnregular';
		  src: url("{{URL::asset('assets/fonts/big_john-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/big_john-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/big_john-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/big_john-webfont.woff')}}") format("woff"), url("{{URL::asset('assets/fonts/big_john-webfont.ttf')}}") format("truetype"), url("{{URL::asset('assets/fonts/big_john-webfont.svg#big_johnregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		@font-face {
		  font-family: 'reisregular';
		  src: url("{{URL::asset('assets/fonts/reis-webfont.eot')}}");
		  src: url("{{URL::asset('assets/fonts/reis-webfont.eot?#iefix')}}") format("embedded-opentype"), url("{{URL::asset('assets/fonts/reis-webfont.woff2')}}") format("woff2"), url("{{URL::asset('assets/fonts/reis-webfont.woff')}}") format("woff"), url("{{URL::asset('assets/fonts/reis-webfont.ttf')}}") format("truetype"), url("{{URL::asset('assets/fonts/reis-webfont.svg#reisregular')}}") format("svg");
		  font-weight: normal;
		  font-style: normal;
		}
		
		.back-shadow {
		  text-shadow: 3px 3px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
		}
	</style>
</head>
<body>
	<div class="container">
		<?php $i=0; $k=1; $m=0; $flash=array(); ?>
		@foreach($cards as $card)
		<?php $j=0; ?>
			@foreach($card['addresses'] as $address)
				@if($k==1)
		<div class="page">
				<?php $flash=array(); ?>
				@endif
				<?php $m++ ?>
			<div class="card card{{$k}}">
					<div class="backMessage">
						{{$cards[$i]['back_message']}}
					</div>
					<div class="address">
						<p>{{$address['name']}}</p>
						<p>{{$address['delivery_line_1']}}</p>
						<p>{{$address['last_line']}}</p>
					</div>
					<div class="barcode">
					</div>
					<div class="permit">
					</div>
					<img class="standard" src="{{URL::asset('assets/images/pdf/XpressCardsBlank.gif')}}"/>
					<img class="cropMarks" src="{{URL::asset('assets/images/pdf/CropMarks_3-75_5-75.gif')}}"/>
				</div>
				
					<?php array_push($flash,$i);?>		
					@if($k==9)
						<?php $k=1;?>
					</div>
					<div class="page">
						@foreach($flash as $l)
						<div class="frontImage frontImage{{$k}}">
							<div class="frontMessage">{{$cards[$l]['front_message']}}</div>
							<img src="{{$cards[$l]['croppedImage']['url_path']}}" width="100%"/> 
							<img class="cropMarks" src="{{URL::asset('assets/images/pdf/CropMarks_3-75_5-75.gif')}}"/>
						</div>
						<?php $k++; ?>
						@endforeach
						<?php $k=1; ?>
					</div><!--End Page-->
					
					@else
						<?php $k++; ?>
					@endif
					<?php $j++;?> <!--Keep track of total cards-->
		@endforeach
			<?php $i++; ?>
		@endforeach
		@if($m%9!=0)
			<?php $k=1; ?>
			</div>
			<div class="page">
			@foreach($flash as $l)
			<div class="frontImage frontImage{{$k}}">
				<div class="frontMessage">{{$cards[$l]['front_message']}}</div>
				<img src="{{$cards[$l]['croppedImage']['url_path']}}" width="100%"/>
				<img class="cropMarks" src="{{URL::asset('assets/images/pdf/CropMarks_3-75_5-75.gif')}}"/>
			</div>
			<?php $k++; ?>
			@endforeach
		
		@endif
	</div><!--end page-->
	<footer>
	</footer>
</body>
</html>