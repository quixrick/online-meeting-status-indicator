<?php



$header = <<<EOF


<html>

<head>

<meta http-equiv="refresh" content="30">

<title>Online Status Indicator</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>

.html, body {

	height: 100%;
	width: 100%;
	margin: 0;
	top: 50%;

}

body {
	display: block;
}

.status_text1 {
	display: inline-block;
	margin: auto;
	/* border: 1px solid #0000ff; */
	width: 85%;
	font-size: 10vw;
	text-align: center;
	/* line-width: 40vw; */
}

.status_text {
  display: flex;
  justify-content: center;
  font-size: 10vw;
  align-items: center;
  /* height: 200px; */
  height: 100%;
  /* border: 3px solid green; */
}


.statuses_active .statuses_default .statuses_other {
	display: block;
}



</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>


EOF;
