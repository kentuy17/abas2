<!DOCTYPE html> 
<html> 
<head> 
	<title>AVega Business Automation System</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="stylesheet" href="<?php echo HTTP_PATH."assets/bootstrap/css/bootstrap.min.css"; ?>" />

    <!---<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">--->
    <link rel="stylesheet" href="<?php echo HTTP_PATH.'assets/bootstrap-table-master/src/bootstrap-table.css' ?>">
	<script src="<?php echo HTTP_PATH.'assets/jquery/jquery-1.11.1.min.js'; ?>"></script>	
	<script src="<?php echo HTTP_PATH.'assets/jquery/jquery.printPage.js' ?>"></script>
	<script src="<?php echo HTTP_PATH.'assets/bootstrap/js/bootstrap.min.js' ?>"></script>
    <!---<script src="libs/bootstrap-table-master/src/bootstrap-table.js"></script>
    	
    --->

<style>

/*header*/
.navbar-inverse {
  /*  background-color: #339933;*/
    border-color: #E7E7E7;
	color:#FFFFFF; 
}

.navbar-inverse .navbar-brand {
    color: #fff;
}

.jumbotron{ height:500px}
/*.panel-body{ height:250px}*/
.panel-body span{ margin-top:20px}
.row{ margin-top:45px}

#loginForm{ 

width:500px

}


</style>

</head>
<body> 


<nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>          </button>
        <a class="navbar-brand" href="#"><span> <img src="<?php echo HTTP_PATH.'assets/images/Avega Bros Integrated Shipping Corp.png'; ?>" width="35px" align="absmiddle"></span>  AVega Business Automation System</a>        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          
          <ul class="nav navbar-nav navbar-right">

            <li><a class="navbar-brand" href="ABAS"><span class="glyphicon glyphicon-user"></span> Logout</a></li>
          </ul>
        </div>
      </div>
</nav>


    
    