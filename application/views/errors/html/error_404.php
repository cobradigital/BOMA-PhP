<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<!-- purple x moss 2020 -->

<head>
	<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">

	<style>
		body {
			background-color: #95c2de;
		}
		.mainbox {
			background-color: #95c2de;
			margin: auto;
			height: 800px;
			width: 800px;
			position: relative;
		}

		.err {
			color: #ffffff;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 11rem;
			position: absolute;
			left: 20%;
			top: 8%;
		}

		.far {
			position: absolute;
			font-size: 8.5rem;
			left: 43%;
			top: 15%;
			color: #ffffff;
		}

		.err2 {
			color: #ffffff;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 11rem;
			position: absolute;
			left: 68%;
			top: 8%;
		}

		.msg {
			text-align: center;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 1.6rem;
			position: absolute;
			left: 16%;
			top: 43%;
			width: 75%;
		}

		a {
			text-decoration: none;
			color: white;
		}

		a:hover {
			text-decoration: underline;
		}
	</style>
</head>

<body>
	<div class="mainbox">
		<div class="err">4</div>
		<i class="far fa-question-circle fa-spin"></i>
		<div class="err2">4</div>
		<div class="msg">Halaman yang anda tuju tidak ada. Mohon kembali ke halaman sebelumnya.<p>Atau <a href="https://prapermohonan.dpmptsp-dki.com/">Klik Disini</a> untuk kembali ke depan.</p>
		</div>
	</div>
</body>

</html>