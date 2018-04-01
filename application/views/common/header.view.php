<!DOCTYPE html>
<html ng-app="windandcrops">
	<head>
		<base href="/">
		<title>Wind & Crops | Know or Predict Weather Conditions for Growing crops</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Faster+One|Quicksand" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo base_url()."application/resources/css/styles.css" ?>" type="text/css">
		<script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
		<script type="text/javascript" src='https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyAvGLDDT9KaxBHmu2TzM9cHlGwPjfVkV4Y'></script>
	</head>
	<body ng-view>