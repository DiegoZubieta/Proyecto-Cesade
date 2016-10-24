<?php
  include("conexion.php");
  session_start(); //iniciamos la sesion
  if(!isset($_SESSION["id_usuario"])){ //si el usuario no esta activo entonces me regresa al login para iniciar sesion
    header("Location: login.php");

  }
  if($_SESSION['tipo_usuario']=="Psicologa")
        header("Location: psicologia.php");







  if(!empty($_POST)){        //aqui entrara cuando agreguemos un nuevo paciente ya que se enviara por metodo post a la misma pagina y jalaremos todos sus datos
    include("conexion.php");
    //LECTURA DE DATOS DEL METODO POST
    $n= $_POST['nombre'];
    $aP= $_POST['aP'];
    $aM= $_POST['aM'];
    $fn= $_POST['fechaNacimiento'];
    $sexo= $_POST['sexo'];
    $colonia= $_POST['colonia'];
    $entidad=$_POST['entidad'];
    $municipio= $_POST['municipio'];
    $domicilio=$_POST['domicilio'];
    $telefono= $_POST['telefono'];
    $tutor=$_POST['tutor'];
    $parentesco=$_POST['parentesco'];
    $grupo=$_POST['grupoEtnico'];
    

    //primero verificar si no existe algo con ese nombre fecha de nacimiento y telefono
    //este query funciona para seleccionar todos los clientes que tengan el nombre, apellidos, fecha de Nacimiento y teleofno exactamente igual
    $query="SELECT * FROM cliente WHERE nombre='$n' AND apellidoPaterno='$aP' AND apellidoMaterno='$aM' AND fechaNacimiento='$fn' AND telefono='$telefono'";
    $resultado=mysqli_query($conexion,$query); //se ejecuta ese query
    $verificar=$resultado->num_rows; //contamos si la ejecucion del query obtuvo resultados si es 0 significa que no existe un paciente registrado entonces podemos registrarlo
    if($verificar>0){ //significa que encontro alguien ya registrado con ese nombre porque fue 0 entonces mandamos mensaje de error
        $error="Error paciente ya registrado";
    }
    else{ //significa que no encontro alguien registrado entonces proseguiremos a registrarlo
        $time=time();     // iniciamos una variable de tiempo
        $fechaActual=date("Y-m-d",$time);    //jalamos la fecha actual del servidor
//        VALIDACION DE EDAD YA SEA ADULTO O BEBE CON MESES
        $y=substr($fechaActual, 0,-6);   // de la fecha obtenida aqui solo tendremos el a;o
        $m=substr($fechaActual,5,-3); //de a fecha obtenida aqui solo tendremos el mes
        $d=substr($fechaActual,8);    //de la fecha obtenida aqui solo tendremos el dia

        $yn=substr($fn, 0,-6);   //del campo de fecha de nacimiento que llenaron aqui tendremos su a;o
        $mn=substr($fn,5,-3); //del campo de fecha de nacimiento que llenaron aqui tendremos su mes
        $dn=substr($fn,8);  // del campo de fecha de nacimiento que llenaron aqui tendremos el dia
        $edad=$y-$yn;     //restamos los dos a;os obtenido
        if($edad==0){   //si la edad restada es ==0 singifica que es un bebe con menos de 1 a;o
            $edad=$m-$mn; //como el a;o no nos sirve de nada pasamos a restar los meses
            if($edad==0)    //si la resta del mes actual con el mes de nacimiento es 0 entonces el bebe lleva dias de nacido nadamas
                $edad=$d-$dn." dia(s)";   //como el mes tampoco nos sirvio proseguiremos a restar los dias para saber cuantos dias lleva
            else
                $edad=$m-$mn." mes(es)";  //el else de si la resta del mes aqui da mas que 0 entonces sabemos que el bebe tiene meses
        }
        else{  //else de que si la resta de a;o no da 0 entonces tiene de 1 a;o en adelante
            if($m<$mn)   //si el mes actual es menor que el mes de su nacimiento entonces no ha cumplido a;os
                $edad--;  //le resto 1 a su edad porque no ha cumplido a;os
                if($m==$mn){  //si estan en el mes de su cumplea;os entonces valiamos los dias
                    if($d<$dn)  //si el dia actual es menor al dia de su cunplea;os entonces no ha cumplido a;os
                        $edad--;   //le restamos 1 a su edad porque aun no cumple a;os
                }
        }
//      FINALIZA LA VALIDACION DE EDAD
    if($edad>18){
       $tutor="";
        $parentesco="";
    }

    //definimos el query que es insertar a la base de datos
    $query="INSERT INTO cliente VALUES(NULL,'$n','$aP','$aM','$sexo','$fn','$colonia','$municipio','$telefono',CURRENT_TIMESTAMP,'$edad','no','$tutor','$parentesco','$domicilio','$grupo','$entidad')";
    $resultado=mysqli_query($conexion,$query); //ejecutamos la consulta

//    COMO AGREGAMOS UN NUEVO ENTONCES Y EL ID DEL CLIENTE ES AUTOINCREMENTAL TENGO QUE OBTENER SU ID ENTONCES LO VUELVO A CONSULTAR PARA JALAR EL ID DEL CLIENTE
    $query="SELECT * FROM cliente WHERE nombre='$n' AND apellidoPaterno='$aP' AND apellidoMaterno='$aM' AND fechaNacimiento='$fn' AND telefono='$telefono'";
    $resultado=mysqli_query($conexion,$query); //ejecuto el query de la base de datos
    $r=$resultado->fetch_assoc(); //almaceno todo lo leido del cliente
    $id=$r['matricula']; //guardo su id que en este caso es matricula


//    COMO AGREGUE UN NUEVO CLIENTE TENGO QUE CREAR SU EXPEDIENTE AUNQUE ESTE VACIO ENTONCES PROSEGUIMOS A CREARLO
    //ESTE query servira para crear el expediente del cliente
    $query="INSERT INTO expediente (idExpediente,idCliente,FechaInicial) VALUES (NULL,'$id',CURRENT_TIMESTAMP)";

    $resultado=mysqli_query($conexion,$query); //ejecutamos el query creando su expediente vacio que luego podremos modificar
      $error="Paciente registrado correctamente"; //mandaremos mensaje de que el paciente se registro correctamente


  }
  }  //AQUI SE ACABA LO DEL REGISTRO DEL CLIENTE O PACIENTE

?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="stylesheet" href="sidebar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="js/bootstrap.js">
    <link rel="stylesheet" href="jss/bootstrap.min.js">
    <script src="js/sorttable.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
        <script type="text/javascript" src="js/dynamicoptionlist.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
    <script src="js/footable.js"></script>
    <title>Cesystem: Control de Expedientes</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
    <script type="text/javascript" src="dynamicoptionlist.js"></script>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/js/bootstrap.min"></script>
    <script>       //para que no permita letras en el campo telefono
			function numeros(e){
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = " 0123456789";
                especiales = [8,37,39,46];
                tecla_especial = false
                for(var i in especiales){
                    if(key == especiales[i]){
                        tecla_especial = true;
                        break;
                    }
                }
                if(letras.indexOf(tecla)==-1 && !tecla_especial)
                    return false;
            }
            function Solo_Texto(e) { //para que no permita numeros en los campos
    			var code;
    			if (!e) var e = window.event;
    			if (e.keyCode) code = e.keyCode;
    			else if (e.which) code = e.which;
    			var character = String.fromCharCode(code);
    			var AllowRegex  = /^[\ba-zA-Z\s- áéíóúÁÉÚÍÓñÑ]$/;
    			if (AllowRegex.test(character)) return true;
    			return false;
			}

		</script>
		<script>
			function menoresEdad(){
				var fecha=document.getElementById('fechaNacimiento').value;
          		var d=new Date();
          		var n=d.getFullYear();  //a;o del servidor
          		var a=fecha.substring(0,4);  //a;o del detectado por fecha de nacimiento

          		if((n-a)<18){
          			document.getElementById('menorEdad').style.display="block";
          		}
          else
          			document.getElementById('menorEdad').style.display="none";

			}
		</script>
		<script>
			$('#fechaNacimiento').datepicker(); //script para firefox para seleccionar fecha
				document.getElemenById('fechaNacimiento').getAttribute("type");
				return datepicker;
		</script>
    <script>
            webshims.setOptions('forms-ext',{types:'date'});  //script para firefox para seleccionar fechas
            webshims.polyfill('forms forms-ext');
    </script>
    <script src="/js/bootstrap.min"></script>
        <script>
      $('#fechaNacimiento').datepicker(); //script para firefox para seleccionar fecha
        document.getElemenById('fechaNacimiento').getAttribute("type");
        return datepicker;
    </script>
        <script>
            webshims.setOptions('forms-ext',{types:'date'});  //script para firefox para seleccionar fechas
            webshims.polyfill('forms forms-ext');
        </script>
  <head>
  <body onLoad="initDynamicOptionLists()">

    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #333333;">
      <div class="container-fluid">
          <div id="desplegarMenu" style="float:left; margin-top:1.5%; margin-right:2%;">
              <center><a href="#" class="btn btn-success" id="menu-toggle"><img src="imagenes/menu.png"></a></center>
          </div>
          <div class="navbar-header">
              <h1><a class="navbar-brand" href="index.php" style="font-size: 40px; color: #FFF500; font-family: NewJuneBold" >Cesystem</a></h1>
          </div>
          <a href="logout.php"><img src="imagenes/logout.png" style="float:right; margin-top:1.5%; margin-right:5%;"></a>
          <img src="imagenes/perfil.png" style="background-color:white; float:right; margin-top:1.5%; margin-right:1%;">
          <p style="color: white; float: right; margin-top:1.5%; margin-right:1%;">Bienvenido <?php echo $_SESSION['nombre'];?></p>
      </div>
    </nav>
    <!-- TERMINA EL NAV BAR osea e titulo de arriba de cada pagina -->






<!-- WRAPPER SERA TODO EL CONTENDIO DEBAJO DEL HEADER DE LA PAGINA -->
    <div id="wrapper">
      <!-- SIDE BAR -->
      <div id="sidebar-wrapper" style="position:fixed;">
        <center>
        <ul class="sidebar-nav" style="margin-top: 55%;">
          <?php
            if($_SESSION['tipo_usuario']=="Administrador"){
          ?>
          <div class="dropdown">
          <a href="index.php"><button class="dropbtn" style="background-color:#FFF500; color:#333333;"><img src="imagenes/clientes.png" style="float:left; margin-top:-5%; margin-right:-8%; overflow:hidden">Clientes</button></a>
          <div class="dropdown-content">
            <a href="index.php" style="background-color:#FFF500; color:#333333;">Actuales</a>
            <a href="historicos.php">Históricos</a>
          </div>
          </div>
          <?php
              } //cierra el if del administrador
              else{
          ?>
          <li class="selected" style="overflow:hidden;"><img src="imagenes/clientes.png" style="float:left; margin-top:3%; margin-left: 6%; margin-right:-5%; overflow:hidden"><a href="#" class="selected">Clientes</a></li>
          <?php
              }
          ?>
          <?php
            if($_SESSION['tipo_usuario']=="Administrador" || $_SESSION['tipo_usuario']=="Doctor"){
          ?>
          <li><img src="imagenes/consulta.png" style="float:left; margin-top:3%; margin-left:5%; margin-right:-3%; overflow:hidden"><a href="consulta.php">Consulta</a></li>
          <?php
            }
            if($_SESSION['tipo_usuario']=="Administrador"){
          ?>
          <div class="dropdown">
          <a href="estudios.php"><button class="dropbtn" style="overflow:hidden;"><img src="imagenes/labs.png" style="float:left; margin-top:-5%; margin-right:8%; overflow:hidden">Laboratorio</button></a>
          <div class="dropdown-content">
            <a href="estudios.php">Realizar Estudio</a>
            <a href="adminLaboratorios.php">Administrar Estudios</a>
          </div>
          </div>
          <?php
              } //cierra el if del administrador
              else if($_SESSION['tipo_usuario']=="Doctor" || $_SESSION['tipo_usuario']=="Enfermera"){
          ?>
            <div class="dropdown">
              <a href="estudios.php"><button class="dropbtn" style="overflow:hidden;"><img src="imagenes/labs.png" style="float:left; margin-top:-5%; margin-right:8%; overflow:hidden">Laboratorio</button></a>
              <div class="dropdown-content">
                <a href="estudios.php">Realizar Estudio</a>
                <a href="consultarEstudios.php">Consultar Estudios</a>
              </div>
            </div>
          <?php
              }
          ?>
          <?php
            if($_SESSION['tipo_usuario']=="Admin"){
          ?>
          <div class="dropdown">
          <a href="vacunas.php"><button class="dropbtn" style="overflow:hidden;"><img src="imagenes/vacunacion.png" style="float:left; margin-top:-5%; margin-right:-8%; overflow:hidden">Vacunación</button></a>
          <div class="dropdown-content">
            <a href="vacunas.php">Aplicar Vacuna</a>
            <a href="adminVacunas.php">Administrar Vacunas</a>
          </div>
          </div>
          <?php
              } //cierra el if del administrador
              else if($_SESSION['tipo_usuario']=="OTRO"){
          ?>
            <li><img src="imagenes/vacunación.png" style="float:left; margin-top:3%; margin-right:1%; overflow:hidden"><a href="vacunas.php">Vacunación</a></li>
          <?php
              }
          ?>
					<?php
							if($_SESSION['tipo_usuario']=="Psicologa"){
					?>
          <li><img src="imagenes/psicologia.png" style="float:left; margin-top:3%; margin-left:6%; margin-right:-3%; overflow:hidden"><a href="psicologia.php">Psicología</a></li>
					<?php
							}
					?>
          <?php
              if($_SESSION['tipo_usuario']=="Administrador"){
          ?>
          <div class="dropdown">
          <a href="usuarios.php"><button class="dropbtn"><img src="imagenes/users.png" style="float:left; margin-top:-6%; margin-right:1%; overflow:hidden">Usuarios</button></a>
          <div class="dropdown-content">
            <a href="usuarios.php">Administrar Usuarios</a>
            <a href="bloqueados.php">Bloqueados</a>
          </div>
          </div>
          <?php
            }
              if($_SESSION['tipo_usuario']=="Admin" || $_SESSION['tipo_usuario']=="Enfe"){
          ?>
          <div class="dropdown">
          <a href="adeudos.php"><button class="dropbtn">Pagos</button></a>
          <div class="dropdown-content">
            <a href="adeudos.php">Adeudos</a>
            <a href="pagados.php">Pagados</a>
          </div>
          </div>
          <?php
            }
          ?>
          <style>
              .dropbtn {
                  padding: 16px;
                  font-size: 16px;
                  border: none;
                  cursor: pointer;
                  width: 100%;
                  background-color: #333333;
                  color: #FFF500;
                  font-family:NewJuneBold;
              }
              /* The container <div> - needed to position the dropdown content */
              .dropdown {
                  position: relative;
                  width: 250px;
                  background-color: #FFF500;
                  display: inline-block;
                  color: #333333;
              }
              /* Dropdown Content (Hidden by Default) */
              .dropdown-content {
                  display: none;
                  position: absolute;
                  background-color: #333333;
                  min-width: 160px;
                  color:#333333;
                  z-index: 1;
                  opacity: 0.9;
                  font-family: NewJuneBold;
                  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                  width: 100%;
              }
              /* Links inside the dropdown */
              .dropdown-content a {
                  color:#FFF500;
                  padding: 12px 16px;
                  text-decoration: none;
                  display: block;
              }
      /* Change color of dropdown links on hover */
              .dropdown-content a:hover {background-color: #FFF500; color: #333333;}

      /* Show the dropdown menu on hover */
              .dropdown:hover .dropdown-content {
                  display: block;
              }

              /* Change the background color of the dropdown button when the dropdown content is shown */
              .dropdown:hover .dropbtn {
                  background-color: #FFF500;
                  color: #333333;
              }
          </style>
        </ul>
      </div>
      <!-- TERMINA EL MENU LATERAL -->



      <!--El page-content-wrapper es todo el contenido excepto el sidebar-->
      <div id="page-content-wrapper" style="margin-top: 90px;">
          <div id="container-fluid">
              <div class="modal fade" id="mregistro" role="dialog">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <center><h3>Registro de Paciente</h3></center>
                          </div>
                          <div class="modal-body">
                              <form id="fregistro" method="POST" style="overflow:hidden;">
                                  <input type="text" name="nombre" id="nombre" placeholder="Nombre" REQUIRED maxlength="25" onkeypress="return Solo_Texto(event)">
                                  <input type="text" name="aP" id="aP" placeholder="Apellido Paterno" REQUIRED maxlength="25"onkeypress="return Solo_Texto(event)">
                                  <input type="text" name="aM" id="aM" placeholder="Apellido Materno" REQUIRED maxlength="25"onkeypress="return Solo_Texto(event)"><br><br>
                                  <input type="date" name="fechaNacimiento" id="fechaNacimiento"  placeholder="Fecha de Nacimiento" REQUIRED onchange="menoresEdad()" max="2016-10-21">
                                  <span id="sex">Sexo:</span> <input type="radio" name="sexo" id="sexo" value="Masculino"> Masculino
                                  <input type="radio" name="sexo" id="sexo" value="Femenino"> Femenino
      <!--<select name="sexo" id="sexo" REQUIRED>
          <option value="A">--SELECCIONE SU OPCION--</option>
          <option value="Femenino">Femenino</option>
          <option value="Masculino">Masculino</option>
      </select>  -->
                                  <br><br>
                                  <input type="text" name="colonia" id="colonia"  placeholder="Colonia" REQUIRED maxlength="30">
                                  <input type="text" name="grupoEtnico" id="grupoEtnico"  placeholder="Grupo Étnico" REQUIRED maxlength="30" onkeypress="return Solo_Texto(event)">
                                  <br><br>
                                  Estado: <select name="entidad" id="entidad" REQUIRED>
      <!--Aquí se va a poner el script para manejar los estados y municipios-->
      <script type="text/javascript">
        var locacion = new DynamicOptionList();
        locacion.addDependentFields("entidad","municipio");
        locacion.forValue("Aguascalientes").addOptions("Aguascalientes", "Asientos", "Calvillo","Cosío","Jesús María",
        "Pabellón de Arteaga","Rincón de Romos","San José de Gracia","Tepezalá", "El Llano","San Francisco de los Romo"
                );
        locacion.forValue("Baja California").addOptions('Ensenada','Mexicali','Tecate','Tijuana','Playas de Rosarito'
            );
        locacion.forValue("Baja California Sur").addOptions('Comondú', 'Mulegé','La Paz','Los Cabos', 'Loreto'
                );
        locacion.forValue("Campeche").addOptions( 'Calkiní', 'Campeche',  'Carmen',  'Champotón',  'Hecelchakán',
        'Hopelchén','Palizada','Tenabo','Escárcega','Calakmul','Candelaria'
                );
        locacion.forValue("Coahuila").addOptions('Abasolo','Acuña','Allende','Arteaga','Candela','Castaños',
        'Cuatro Ciénegas','Escobedo','Francisco I. Madero','Frontera','General Cepeda','Guerrero','Hidalgo','Jiménez',
        'Juárez','Lamadrid','Matamoros','Monclova','Morelos','Múzquiz','Nadadores','Nava','Ocampo','Parras','Piedras Negras',
        'Progreso','Ramos Arizpe','Sabinas','Sacramento','Saltillo','San Buenaventura','San Juan de Sabinas',
        'San Pedro','Sierra Mojada','Torreón','Viesca','Villa Unión','Zaragoza'
                );
        locacion.forValue("Colima").addOptions('Armería','Colima','Comala','Coquimatlán','Cuauhtémoc',
        'Ixtlahuacán','Manzanillo', 'Minatitlán','Tecomán','Villa de Álvarez'
                );
        locacion.forValue("Chiapas").addOptions('Acacoyagua', 'Acala', 'Acapetahua', 'Altamirano', 'Amatán',
        'Amatenango de la Frontera','Amatenango del Valle','Angel Albino Corzo','Arriaga','Bejucal de Ocampo',
        'Bella Vista','Berriozábal','Bochil','El Bosque','Cacahoatán','Catazajá','Cintalapa','Coapilla',
        'Comitán de Domínguez','La Concordia','Copainalá','Chalchihuitán','Chamula','Chanal', 'Chapultenango',
        'Chenalhó','Chiapa de Corzo','Chiapilla','Chicoasén','Chicomuselo','Chilón','Escuintla','Francisco León',
        'Frontera Comalapa','Frontera Hidalgo','La Grandeza','Huehuetán','Huixtán','Huitiupán','Huixtla',
        'La Independencia','Ixhuatán', 'Ixtacomitán','Ixtapa','Ixtapangajoya','Jiquipilas','Jitotol','Juárez',
        'Larráinzar','La Libertad','Mapastepec','Las Margaritas','Mazapa de Madero','Mazatán','Metapa','Mitontic',
        'Motozintla','Nicolás Ruíz','Ocosingo','Ocotepec','Ocozocoautla de Espinosa', 'Ostuacán',
        'Osumacinta','Oxchuc','Palenque','Pantelhó', 'Pantepec','Pichucalco', 'Pijijiapan','El Porvenir',
        'Villa Comaltitlán','Pueblo Nuevo Solistahuacán','Rayón','Reforma','Las Rosas', 'Sabanilla','Salto de Agua',
        'San Cristóbal de las Casas','San Fernando','Siltepec', 'Simojovel','Sitalá','Socoltenango','Solosuchiapa',
        'Soyaló','Suchiapa','Suchiate', 'Sunuapa','Tapachula', 'Tapalapa','Tapilula', 'Tecpatán','Tenejapa','Teopisca',
        'Tila', 'Tonalá','Totolapa','La Trinitaria','Tumbalá','Tuxtla Gutiérrez','Tuxtla Chico', 'Tuzantán','Tzimol',
        'Unión Juárez','Venustiano Carranza','Villa Corzo','Villaflores', 'Yajalón','San Lucas','Zinacantán',
        'San Juan Cancuc', 'Aldama', 'Benemérito de las Américas','Maravilla Tenejapa','Marqués de Comillas',
        'Montecristo de Guerrero', 'San Andrés Duraznal', 'Santiago el Pinar'
                );
        locacion.forValue("Chihuahua").addOptions(  'Ahumada','Aldama','Allende','Aquiles Serdán','Ascensión','Bachíniva',
        'Balleza','Batopilas','Bocoyna','Buenaventura','Camargo','Carichí','Casas Grandes','Coronado','Coyame del Sotol',
        'La Cruz','Cuauhtémoc','Cusihuiriachi','Chihuahua','Chínipas', 'Delicias','Dr. Belisario Domínguez','Galeana',
        'Santa Isabel','Gómez Farías', 'Gran Morelos','Guachochi','Guadalupe', 'Guadalupe y Calvo','Guazapares',
        'Guerrero','Hidalgo del Parral', 'Huejotitán','Ignacio Zaragoza','Janos', 'Jiménez', 'Juárez', 'Julimes',
        'López','Madera','Maguarichi','Manuel Benavides','Matachí','Matamoros', 'Meoqui', 'Morelos','Moris',
        'Namiquipa','Nonoava','Nuevo Casas Grandes','Ocampo','Ojinaga', 'Praxedis G. Guerrero', 'Riva Palacio',
        'Rosales','Rosario','San Francisco de Borja','San Francisco de Conchos', 'San Francisco del Oro',
        'Santa Bárbara','Satevó','Saucillo','Temósachic','El Tule', 'Urique','Uruachi', 'Valle de Zaragoza'
        );
        locacion.forValue("Distrito Federal").addOptions('Azcapotzalco', 'Coyoacán',  'Cuajimalpa de Morelos',
        'Gustavo A. Madero', 'Iztacalco',  'Iztapalapa', 'La Magdalena Contreras', 'Milpa Alta', 'Álvaro Obregón',
        'Tláhuac',  'Tlalpan',  'Xochimilco',  'Benito Juárez',  'Cuauhtémoc', 'Miguel Hidalgo',  'Venustiano Carranza'
        );
        locacion.forValue("Durango").addOptions('Canatlán', 'Canelas', 'Coneto de Comonfort', 'Cuencamé', 'Durango',
        'General Simón Bolívar', 'Gómez Palacio', 'Guadalupe Victoria', 'Guanaceví', 'Hidalgo', 'Indé', 'Lerdo',
        'Mapimí', 'Mezquital', 'Nazas', 'Nombre de Dios', 'Ocampo', 'El Oro','Otáez', 'Pánuco de Coronado',
        'Peñón Blanco', 'Poanas', 'Pueblo Nuevo', 'Rodeo', 'San Bernardo','San Dimas', 'San Juan de Guadalupe',
        'San Juan del Río', 'San Luis del Cordero', 'San Pedro del Gallo', 'Santa Clara', 'Santiago Papasquiaro',
        'Súchil', 'Tamazula', 'Tepehuanes', 'Tlahualilo', 'Topia', 'Vicente Guerrero', 'Nuevo Ideal'
        );
        locacion.forValue("Guanajuato").addOptions('Abasolo','Acámbaro', 'San Miguel de Allende', 'Apaseo el Alto',
        'Apaseo el Grande', 'Atarjea','Celaya', 'Manuel Doblado', 'Comonfort', 'Coroneo', 'Cortazar', 'Cuerámaro',
        'Doctor Mora', 'Dolores Hidalgo Cuna de la Independencia Nacional', 'Guanajuato','Huanímaro', 'Irapuato',
        'Jaral del Progreso', 'Jerécuaro', 'León', 'Moroleón', 'Ocampo','Pénjamo', 'Pueblo Nuevo', 'Purísima del Rincón',
        'Romita', 'Salamanca', 'Salvatierra', 'San Diego de la Unión', 'San Felipe', 'San Francisco del Rincón',
        'San José Iturbide', 'San Luis de la Paz', 'Santa Catarina', 'Santa Cruz de Juventino Rosas',
        'Santiago Maravatío', 'Silao de la Victoria', 'Tarandacuao', 'Tarimoro', 'Tierra Blanca', 'Uriangato',
        'Valle de Santiago', 'Victoria','Villagrán', 'Xichú', 'Yuriria'
        );
        locacion.forValue("Guerrero").addOptions('Acapulco', 'Ahuacuotzingo', 'Ajuchitlán del Progreso',
        'Alcozauca de Guerrero', 'Alpoyeca', 'Apaxtla', 'Arcelia', 'Atenango del Río', 'Atlamajalcingo del Monte',
        'Atlixtac', 'Atoyac de Álvarez', 'Ayutla de los Libres', 'Azoyú', 'Benito Juárez', 'Buenavista de Cuéllar',
        'Coahuayutla de José María Izazaga', 'Cocula', 'Copala', 'Copalillo', 'Copanatoyac', 'Coyuca de Benítez',
        'Coyuca de Catalán', 'Cuajinicuilapa', 'Cualác', 'Cuautepec','Cuetzala del Progreso', 'Cutzamala de Pinzón',
        'Chilapa de Álvarez', 'Chilpancingo de los Bravo', 'Florencio Villarreal', 'General Canuto A. Neri',
        'General Heliodoro Castillo', 'Huamuxtitlán', 'Huitzuco de los Figueroa', 'Iguala de la Independencia',
        'Igualapa', 'Ixcateopan de Cuauhtémoc', 'Zihuatanejo de Azueta', 'Juan R. Escudero', 'Leonardo Bravo',
        'Malinaltepec', 'Mártir de Cuilapan', 'Metlatónoc',  'Mochitlán', 'Olinalá', 'Ometepec',
        'Pedro Ascencio Alquisiras', 'Petatlán', 'Pilcaya', 'Pungarabato', 'Quechultenango', 'San Luis Acatlán',
        'San Marcos', 'San Miguel Totolapan', 'Taxco de Alarcón','Tecoanapa', 'Técpan de Galeana', 'Teloloapan',
        'Tepecoacuilco de Trujano', 'Tetipac',  'Tixtla de Guerrero', 'Tlacoachistlahuaca', 'Tlacoapa', 'Tlalchapa',
        'Tlalixtaquilla de Maldonado', 'Tlapa de Comonfort', 'Tlapehuala', 'La Unión de Isidoro Montes de Oca',
        'Xalpatláhuac', 'Xochihuehuetlán','Xochistlahuaca','Zapotitlán Tablas', 'Zirándaro', 'Zitlala', 'Eduardo Neri',
        'Acatepec', 'Marquelia', 'Cochoapa el Grande', 'José Joaquín de Herrera', 'Juchitán', 'Iliatenco'
        );
        locacion.forValue("Hidalgo").addOptions('Acatlán', 'Acaxochitlán', 'Actopan', 'Agua Blanca de Iturbide',
        'Ajacuba', 'Alfajayucan', 'Almoloya', 'Apan', 'El Arenal', 'Atitalaquia', 'Atlapexco', 'Atotonilco el Grande',
        'Atotonilco de Tula', 'Calnali', 'Cardonal', 'Cuautepec de Hinojosa', 'Chapantongo', 'Chapulhuacán',
        'Chilcuautla', 'Eloxochitlán', 'Emiliano Zapata', 'Epazoyucan', 'Francisco I. Madero', 'Huasca de Ocampo',
        'Huautla', 'Huazalingo', 'Huehuetla', 'Huejutla de Reyes', 'Huichapan', 'Ixmiquilpan', 'Jacala de Ledezma',
        'Jaltocán', 'Juárez Hidalgo', 'Lolotla', 'Metepec', 'San Agustín Metzquititlán', 'Metztitlán',
        'Mineral del Chico', 'Mineral del Monte', 'La Misión', 'Mixquiahuala de Juárez', 'Molango de Escamilla',
        'Nicolás Flores', 'Nopala de Villagrán', 'Omitlán de Juárez', 'San Felipe Orizatlán', 'Pacula', 'Pachuca',
        'Pisaflores','Progreso de Obregón', 'Mineral de la Reforma', 'San Agustín Tlaxiaca', 'San Bartolo Tutotepec',
        'San Salvador', 'Santiago de Anaya', 'Santiago Tulantepec de Lugo Guerrero',
        'Singuilucan', 'Tasquillo', 'Tecozautla', 'Tenango de Doria', 'Tepeapulco', 'Tepehuacán de Guerrero',
        'Tepeji del Río de Ocampo', 'Tepetitlán', 'Tetepango', 'Villa de Tezontepec', 'Tezontepec de Aldama',
        'Tianguistengo', 'Tizayuca', 'Tlahuelilpan', 'Tlahuiltepa', 'Tlanalapa', 'Tlanchinol', 'Tlaxcoapan',
        'Tolcayuca', 'Tula de Allende', 'Tulancingo de Bravo', 'Xochiatipan', 'Xochicoatlán', 'Yahualica',
        'Zacualtipán de Ángeles', 'Zapotlán de Juárez', 'Zempoala', 'Zimapán'
        );
        locacion.forValue("Jalisco").addOptions('Acatic','Acatlán de Juárez','Ahualulco de Mercado','Amacueca','Amatitán',
        'Ameca','San Juanito de Escobedo','Arandas','El Arenal','Atemajac de Brizuela','Atengo','Atenguillo',
        'Atotonilco el Alto','Atoyac','Autlán de Navarro','Ayotlán','Ayutla','La Barca','Bolaños','Cabo Corrientes',
        'Casimiro Castillo','Cihuatlán','Zapotlán el Grande','Cocula','Colotlán','Concepción de Buenos Aires',
        'Cuautitlán de García Barragán','Cuautla','Cuquío','Chapala','Chimaltitán','Chiquilistlán','Degollado','Ejutla',
        'Encarnación de Díaz','Etzatlán','El Grullo','Guachinango','Guadalajara','Hostotipaquillo','Huejúcar',
        'Huejuquilla el Alto','La Huerta','Ixtlahuacán de los Membrillos','Ixtlahuacán del Río','Jalostotitlán',
        'Jamay','Jesús María','Jilotlán de los Dolores','Jocotepec','Juanacatlán','Juchitlán','Lagos de Moreno',
        'El Limón','Magdalena','Santa María del Oro','La Manzanilla de la Paz','Mascota','Mazamitla','Mexticacán',
        'Mezquitic','Mixtlán','Ocotlán','Ojuelos de Jalisco','Pihuamo','Poncitlán','Puerto Vallarta',
        'Villa Purificación','Quitupan','El Salto','San Cristóbal de la Barranca','San Diego de Alejandría',
        'San Juan de los Lagos','San Julián','San Marcos','San Martín de Bolaños','San Martín Hidalgo',
        'San Miguel el Alto','Gómez Farías','San Sebastián del Oeste','Santa María de los Ángeles','Sayula','Tala',
        'Talpa de Allende','Tamazula de Gordiano','Tapalpa','Tecalitlán','Tecolotlán','Techaluta de Montenegro',
        'Tenamaxtlán','Teocaltiche','Teocuitatlán de Corona','Tepatitlán de Morelos','Tequila','Teuchitlán',
        'Tizapán el Alto','Tlajomulco de Zúñiga','San Pedro Tlaquepaque','Tolimán','Tomatlán','Tonalá','Tonaya',
        'Tonila','Totatiche','Tototlán','Tuxcacuesco','Tuxcueca','Tuxpan','Unión de San Antonio','Unión de Tula',
        'Valle de Guadalupe','Valle de Juárez','San Gabriel','Villa Corona','Villa Guerrero','Villa Hidalgo',
        'Cañadas de Obregón','Yahualica de González Gallo','Zacoalco de Torres','Zapopan','Zapotiltic',
        'Zapotitlán de Vadillo','Zapotlán del Rey','Zapotlanejo','San Ignacio Cerro Gordo'
        );
        locacion.forValue("México").addOptions('Acambay de Ruíz Castañeda','Acolman','Aculco','Almoloya de Alquisiras',
        'Almoloya de Juárez','Almoloya del Río','Amanalco','Amatepec','Amecameca','Apaxco','Atenco','Atizapán',
        'Atizapán de Zaragoza','Atlacomulco','Atlautla','Axapusco','Ayapango','Calimaya','Capulhuac',
        'Coacalco de Berriozábal','Coatepec Harinas','Cocotitlán','Coyotepec','Cuautitlán','Chalco','Chapa de Mota',
        'Chapultepec','Chiautla','Chicoloapan','Chiconcuac','Chimalhuacán','Donato Guerra','Ecatepec de Morelos',
        'Ecatzingo','Huehuetoca','Hueypoxtla','Huixquilucan','Isidro Fabela','Ixtapaluca','Ixtapan de la Sal',
        'Ixtapan del Oro','Ixtlahuaca','Xalatlaco','Jaltenco','Jilotepec','Jilotzingo','Jiquipilco','Jocotitlán',
        'Joquicingo','Juchitepec','Lerma','Malinalco','Melchor Ocampo','Metepec','Mexicaltzingo','Morelos',
        'Naucalpan de Juárez','Nezahualcóyotl','Nextlalpan','Nicolás Romero','Nopaltepec','Ocoyoacac','Ocuilan',
        'El Oro','Otumba','Otzoloapan','Otzolotepec','Ozumba','Papalotla','La Paz','Polotitlán',
        'Rayón','San Antonio la Isla','San Felipe del Progreso','San Martín de las Pirámides',
        'San Mateo Atenco','San Simón de Guerrero','Santo Tomás','Soyaniquilpan de Juárez','Sultepec',
        'Tecámac','Tejupilco','Temamatla','Temascalapa','Temascalcingo','Temascaltepec',
        'Temoaya','Tenancingo','Tenango del Aire','Tenango del Valle','Teoloyucan','Teotihuacán',
        'Tepetlaoxtoc','Tepetlixpa','Tepotzotlán','Tequixquiac','Texcaltitlán','Texcalyacac','Texcoco',
        'Tezoyuca','Tianguistenco','Timilpan','Tlalmanalco','Tlalnepantla de Baz','Tlatlaya','Toluca',
        'Tonatico','Tultepec','Tultitlán','Valle de Bravo','Villa de Allende','Villa del Carbón',
        'Villa Guerrero','Villa Victoria','Xonacatlán','Zacazonapan','Zacualpan','Zinacantepec','Zumpahuacán',
        'Zumpango','Cuautitlán Izcalli','Valle de Chalco Solidaridad','Luvianos','San José del Rincón','Tonanitla'
            );
        locacion.forValue("Michoacán").addOptions('Acuitzio','Aguililla','Álvaro Obregón','Angamacutiro','Angangueo',
        'Apatzingán','Aporo','Aquila','Ario','Arteaga','Briseñas','Buenavista','Carácuaro','Coahuayana',
        'Coalcomán de Vázquez Pallares','Coeneo','Contepec','Copándaro','Cotija','Cuitzeo','Charapan','Charo','\
        Chavinda','Cherán','Chilchota','Chinicuila','Chucándiro','Churintzio','Churumuco','Ecuandureo','Epitacio Huerta',
        'Erongarícuaro','Gabriel Zamora','Hidalgo','La Huacana','Huandacareo','Huaniqueo','Huetamo','Huiramba',
        'Indaparapeo','Irimbo','Ixtlán','Jacona','Jiménez','Jiquilpan','Juárez','Jungapeo','Lagunillas','Madero',
        'Maravatío','Marcos Castellanos','Lázaro Cárdenas','Morelia','Morelos','Múgica','Nahuatzen','Nocupétaro',
        'Nuevo Parangaricutiro','Nuevo Urecho','Numarán','Ocampo','Pajacuarán','Panindícuaro','Parácuaro','Paracho',
        'Pátzcuaro','Penjamillo','Peribán','La Piedad','Purépero','Puruándiro','Queréndaro','Quiroga',
        'Cojumatlán de Régules','Los Reyes','Sahuayo','San Lucas','Santa Ana Maya','Salvador Escalante',
        'Senguio','Susupuato','Tacámbaro','Tancítaro','Tangamandapio','Tangancícuaro','Tanhuato','Taretan',
        'Tarímbaro','Tepalcatepec','Tingambato','Tingüindín','Tiquicheo de Nicolás Romero','Tlalpujahua','Tlazazalca',
        'Tocumbo','Tumbiscatío','Turicato','Tuxpan','Tuzantla','Tzintzuntzan','Tzitzio','Uruapan','Venustiano Carranza',
        'Villamar','Vista Hermosa','Yurécuaro','Zacapu','Zamora','Zináparo','Zinapécuaro','Ziracuaretiro',
        'Zitácuaro','José Sixto Verduzco'
            );
        locacion.forValue("Morelos").addOptions('Amacuzac','Atlatlahucan','Axochiapan','Ayala','Coatlán del Río',
        'Cuautla','Cuernavaca','Emiliano Zapata','Huitzilac','Jantetelco','Jiutepec',
        'Jojutla','Jonacatepec','Mazatepec','Miacatlán','Ocuituco','Puente de Ixtla',
        'Temixco','Tepalcingo','Tepoztlán','Tetecala','Tetela del Volcán','Tlalnepantla',
        'Tlaltizapán de Zapata','Tlaquiltenango','Tlayacapan','Totolapan','Xochitepec',
        'Yautepec','Yecapixtla','Zacatepec','Zacualpan de Amilpas','Temoac'
            );
        locacion.forValue("Nayarit").addOptions('Acaponeta', 'Ahuacatlán', 'Amatlán de Cañas', 'Compostela', 'Huajicori',
        'Ixtlán del Río', 'Jala','Xalisco', 'Del Nayar', 'Rosamorada', 'Ruíz',
        'San Blas', 'San Pedro Lagunillas', 'Santa María del Oro', 'Santiago Ixcuintla',
        'Tecuala', 'Tepic', 'Tuxpan', 'La Yesca', 'Bahía de Banderas'
        );
        locacion.forValue("Nuevo León").addOptions('Abasolo', 'Agualeguas', 'Los Aldamas', 'Allende', 'Anáhuac',
        'Apodaca', 'Aramberri', 'Bustamante', 'Cadereyta Jiménez', 'El Carmen', 'Cerralvo', 'Ciénega de Flores',
        'China', 'Doctor Arroyo', 'Doctor Coss', 'Doctor González', 'Galeana', 'García', 'San Pedro Garza García',
        'General Bravo', 'General Escobedo', 'General Terán', 'General Treviño', 'General Zaragoza', 'General Zuazua',
        'Guadalupe', 'Los Herreras', 'Higueras', 'Hualahuises', 'Iturbide', 'Juárez', 'Lampazos de Naranjo',
        'Linares', 'Marín', 'Melchor Ocampo', 'Mier y Noriega', 'Mina', 'Montemorelos', 'Monterrey', 'Parás',
        'Pesquería', 'Los Ramones', 'Rayones', 'Sabinas Hidalgo', 'Salinas Victoria', 'San Nicolás de los Garza',
        'Hidalgo', 'Santa Catarina', 'Santiago', 'Vallecillo', 'Villaldama'
         );

        locacion.forValue("Oaxaca").addOptions('Abejones','Acatlán de Pérez Figueroa','Asunción Cacalotepec','Asunción Cuyotepeji','Asunción Ixtaltepec','Asunción Nochixtlán','Asunción Ocotlán','Asunción Tlacolulita','Ayotzintepec','El Barrio de la Soledad',
'Calihualá','Candelaria Loxicha','Ciénega de Zimatlán','Ciudad Ixtepec','Coatecas Altas','Coicoyán de las Flores','La Compañía','Concepción Buenavista','Concepción Pápalo','Constancia del Rosario',
'Cosolapa','Cosoltepec','Cuilápam de Guerrero','Cuyamecalco Villa de Zaragoza','Chahuites','Chalcatongo de Hidalgo','Chiquihuitlán de Benito Juárez','Heroica Ciudad de Ejutla de Crespo','Eloxochitlán de Flores Magón','El Espinal',
'Tamazulápam del Espíritu Santo','Fresnillo de Trujano','Guadalupe Etla','Guadalupe de Ramírez','Guelatao de Juárez','Guevea de Humboldt','Mesones Hidalgo','Villa Hidalgo','Heroica Ciudad de Huajuapan de León','Huautepec',
'Huautla de Jiménez','Ixtlán de Juárez','Heroica Ciudad de Juchitán de Zaragoza','Loma Bonita','Magdalena Apasco','Magdalena Jaltepec','Santa Magdalena Jicotlán','Magdalena Mixtepec','Magdalena Ocotlán','Magdalena Peñasco',
'Magdalena Teitipac','Magdalena Tequisistlán','Magdalena Tlacotepec','Magdalena Zahuatlán','Mariscala de Juárez','Mártires de Tacubaya','Matías Romero Avendaño','Mazatlán Villa de Flores','Miahuatlán de Porfirio Díaz','Mixistlán de la Reforma',
'Monjas','Natividad','Nazareno Etla','Nejapa de Madero','Ixpantepec Nieves','Santiago Niltepec','Oaxaca de Juárez','Ocotlán de Morelos','La Pe','Pinotepa de Don Luis',
'Pluma Hidalgo','SanJosé del Progreso','Putla Villa de Guerrero','Santa Catarina Quioquitani','Reforma de Pineda','La Reforma','Reyes Etla','Rojas de Cuauhtémoc','Salina Cruz','SanAgustín Amatengo',
'SanAgustín Atenango','SanAgustín Chayuco','SanAgustín de las Juntas','SanAgustín Etla','SanAgustín Loxicha','SanAgustín Tlacotepec','SanAgustín Yatareni','SanAndrés Cabecera Nueva','SanAndrés Dinicuiti','SanAndrés Huaxpaltepec',
'SanAndrés Huayápam','SanAndrés Ixtlahuaca','SanAndrés Lagunas','SanAndrés Nuxiño','SanAndrés Paxtlán','SanAndrés Sinaxtla','SanAndrés Solaga','SanAndrés Teotilálpam','SanAndrés Tepetlapa','SanAndrés Yaá',
'SanAndrés Zabache','SanAndrés Zautla','SanAntonino Castillo Velasco','SanAntonino el Alto','SanAntonino Monte Verde','SanAntonio Acutla','SanAntonio de la Cal','SanAntonio Huitepec','SanAntonio Nanahuatípam','SanAntonio Sinicahua',
'SanAntonio Tepetlapa','SanBaltazar Chichicápam','SanBaltazar Loxicha','SanBaltazar Yatzachi el Bajo','SanBartolo Coyotepec','SanBartolomé Ayautla','SanBartolomé Loxicha','SanBartolomé Quialana','SanBartolomé Yucuañe','SanBartolomé Zoogocho',
'SanBartolo Soyaltepec','SanBartolo Yautepec','SanBernardo Mixtepec','SanBlas Atempa','SanCarlos Yautepec','SanCristóbal Amatlán','SanCristóbal Amoltepec','SanCristóbal Lachirioag','SanCristóbal Suchixtlahuaca','SanDionisio del Mar',
'SanDionisio Ocotepec','SanDionisio Ocotlán','SanEsteban Atatlahuca','SanFelipe Jalapa de Díaz','SanFelipe Tejalápam','SanFelipe Usila','SanFrancisco Cahuacuá','SanFrancisco Cajonos','SanFrancisco Chapulapa','SanFrancisco Chindúa',
'SanFrancisco del Mar','SanFrancisco Huehuetlán','SanFrancisco Ixhuatán','SanFrancisco Jaltepetongo','SanFrancisco Lachigoló','SanFrancisco Logueche','SanFrancisco Nuxaño','SanFrancisco Ozolotepec','SanFrancisco Sola','SanFrancisco Telixtlahuaca',
'SanFrancisco Teopan','SanFrancisco Tlapancingo','SanGabriel Mixtepec','SanIldefonso Amatlán','SanIldefonso Sola','SanIldefonso Villa Alta','SanJacinto Amilpas','SanJacinto Tlacotepec','SanJerónimo Coatlán','SanJerónimo Silacayoapilla',
'SanJerónimo Sosola','SanJerónimo Taviche','SanJerónimo Tecóatl','SanJorge Nuchita','SanJosé Ayuquila','SanJosé Chiltepec','SanJosé del Peñasco','SanJosé Estancia Grande','SanJosé Independencia','SanJosé Lachiguiri',
'SanJosé Tenango','SanJuan Achiutla','SanJuan Atepec','Ánimas Trujano','SanJuan Bautista Atatlahuca','SanJuan Bautista Coixtlahuaca','SanJuan Bautista Cuicatlán','SanJuan Bautista Guelache','SanJuan Bautista Jayacatlán','SanJuan Bautista Lo de Soto',
'SanJuan Bautista Suchitepec','SanJuan Bautista Tlacoatzintepec','SanJuan Bautista Tlachichilco','SanJuan Bautista Tuxtepec','SanJuan Cacahuatepec','SanJuan Cieneguilla','SanJuan Coatzóspam','SanJuan Colorado','SanJuan Comaltepec','SanJuan Cotzocón',
'SanJuan Chicomezúchil','SanJuan Chilateca','SanJuan del Estado','SanJuan del Río','SanJuan Diuxi','SanJuan Evangelista Analco','SanJuan Guelavía','SanJuan Guichicovi','SanJuan Ihualtepec','SanJuan Juquila Mixes',
'SanJuan Juquila Vijanos','SanJuan Lachao','SanJuan Lachigalla','SanJuan Lajarcia','SanJuan Lalana','SanJuan de los Cués','SanJuan Mazatlán','SanJuan Mixtepec','SanJuan Mixtepec','SanJuan Ñumí','SanJuan Ozolotepec','SanJuan Petlapa','SanJuan Quiahije','SanJuan Quiotepec','SanJuan Sayultepec','SanJuan Tabaá','SanJuan Tamazola','SanJuan Teita','SanJuan Teitipac','SanJuan Tepeuxila',
'SanJuan Teposcolula','SanJuan Yaeé','SanJuan Yatzona','SanJuan Yucuita','SanLorenzo','SanLorenzo Albarradas','SanLorenzo Cacaotepec','SanLorenzo Cuaunecuiltitla','SanLorenzo Texmelúcan','SanLorenzo Victoria',
'SanLucas Camotlán','SanLucas Ojitlán','SanLucas Quiaviní','SanLucas Zoquiápam','SanLuis Amatlán','SanMarcial Ozolotepec','SanMarcos Arteaga','SanMartín de los Cansecos','SanMartín Huamelúlpam','SanMartín Itunyoso',
'SanMartín Lachilá','SanMartín Peras','SanMartín Tilcajete','SanMartín Toxpalan','SanMartín Zacatepec','SanMateo Cajonos','Capulálpam de Méndez','SanMateo del Mar','SanMateo Yoloxochitlán','SanMateo Etlatongo',
'SanMateo Nejápam','SanMateo Peñasco','SanMateo Piñas','SanMateo Río Hondo','SanMateo Sindihui','SanMateo Tlapiltepec','SanMelchor Betaza','SanMiguel Achiutla','SanMiguel Ahuehuetitlán','SanMiguel Aloápam',
'SanMiguel Amatitlán','SanMiguel Amatlán','SanMiguel Coatlán','SanMiguel Chicahua','SanMiguel Chimalapa','SanMiguel del Puerto','SanMiguel del Río','SanMiguel Ejutla','SanMiguel el Grande','SanMiguel Huautla',
'SanMiguel Mixtepec','SanMiguel Panixtlahuaca','SanMiguel Peras','SanMiguel Piedras','SanMiguel Quetzaltepec','SanMiguel Santa Flor','Villa Sola de Vega','SanMiguel Soyaltepec','SanMiguel Suchixtepec','Villa Talea de Castro',
'SanMiguel Tecomatlán','SanMiguel Tenango','SanMiguel Tequixtepec','SanMiguel Tilquiápam','SanMiguel Tlacamama','SanMiguel Tlacotepec','SanMiguel Tulancingo','SanMiguel Yotao','SanNicolás','SanNicolás Hidalgo',
'SanPablo Coatlán','SanPablo Cuatro Venados','SanPablo Etla','SanPablo Huitzo','SanPablo Huixtepec','SanPablo Macuiltianguis','SanPablo Tijaltepec','SanPablo Villa de Mitla','SanPablo Yaganiza','SanPedro Amuzgos',
'SanPedro Apóstol','SanPedro Atoyac','SanPedro Cajonos','SanPedro Coxcaltepec Cántaros','SanPedro Comitancillo','SanPedro el Alto','SanPedro Huamelula','SanPedro Huilotepec','SanPedro Ixcatlán','SanPedro Ixtlahuaca',
'SanPedro Jaltepetongo','SanPedro Jicayán','SanPedro Jocotipac','SanPedro Juchatengo','SanPedro Mártir','SanPedro Mártir Quiechapa','SanPedro Mártir Yucuxaco','SanPedro Mixtepec','SanPedro Mixtepec','SanPedro Molinos',
'SanPedro Nopala','SanPedro Ocopetatillo','SanPedro Ocotepec','SanPedro Pochutla','SanPedro Quiatoni','SanPedro Sochiápam','SanPedro Tapanatepec','SanPedro Taviche','SanPedro Teozacoalco','SanPedro Teutila',
'SanPedro Tidaá','SanPedro Topiltepec','SanPedro Totolápam','Villa de Tututepec de Melchor Ocampo','SanPedro Yaneri','SanPedro Yólox','SanPedro y San Pablo Ayutla','Villa de Etla','SanPedro y San Pablo Teposcolula','SanPedro y San Pablo Tequixtepec',
'SanPedro Yucunama','SanRaymundo Jalpan','SanSebastián Abasolo','SanSebastián Coatlán','SanSebastián Ixcapa','SanSebastián Nicananduta','SanSebastián Río Hondo','SanSebastián Tecomaxtlahuaca','SanSebastián Teitipac','SanSebastián Tutla',
'SanSimón Almolongas','SanSimón Zahuatlán','SantaAna','SantaAna Ateixtlahuaca','SantaAna Cuauhtémoc','SantaAna del Valle','SantaAna Tavela','SantaAna Tlapacoyan','SantaAna Yareni','SantaAna Zegache',
'SantaCatalina Quierí','SantaCatarina Cuixtla','SantaCatarina Ixtepeji','SantaCatarina Juquila','SantaCatarina Lachatao','SantaCatarina Loxicha','SantaCatarina Mechoacán','SantaCatarina Minas','SantaCatarina Quiané','SantaCatarina Tayata',
'SantaCatarina Ticuá','SantaCatarina Yosonotú','SantaCatarina Zapoquila','SantaCruz Acatepec','SantaCruz Amilpas','SantaCruz de Bravo','SantaCruz Itundujia','SantaCruz Mixtepec','SantaCruz Nundaco','SantaCruz Papalutla',
'SantaCruz Tacache de Mina','SantaCruz Tacahua','SantaCruz Tayata','SantaCruz Xitla','SantaCruz Xoxocotlán','SantaCruz Zenzontepec','SantaGertrudis','SantaInés del Monte','SantaInés Yatzeche','SantaLucía del Camino',
'SantaLucía Miahuatlán','SantaLucía Monteverde','SantaLucía Ocotlán','SantaMaría Alotepec','SantaMaría Apazco','SantaMaría la Asunción','Heroica Ciudad de Tlaxiaco','Ayoquezco de Aldama','SantaMaría Atzompa','SantaMaría Camotlán',
'SantaMaría Colotepec','SantaMaría Cortijo','SantaMaría Coyotepec','SantaMaría Chachoápam','Villa de Chilapa de Díaz','SantaMaría Chilchotla','SantaMaría Chimalapa','SantaMaría del Rosario','SantaMaría del Tule','SantaMaría Ecatepec',
'SantaMaría Guelacé','SantaMaría Guienagati','SantaMaría Huatulco','SantaMaría Huazolotitlán','SantaMaría Ipalapa','SantaMaría Ixcatlán','SantaMaría Jacatepec','SantaMaría Jalapa del Marqués','SantaMaría Jaltianguis','SantaMaría Lachixío',
'SantaMaría Mixtequilla','SantaMaría Nativitas','SantaMaría Nduayaco','SantaMaría Ozolotepec','SantaMaría Pápalo','SantaMaría Peñoles','SantaMaría Petapa','SantaMaría Quiegolani','SantaMaría Sola','SantaMaría Tataltepec',
'SantaMaría Tecomavaca','SantaMaría Temaxcalapa','SantaMaría Temaxcaltepec','SantaMaría Teopoxco','SantaMaría Tepantlali','SantaMaría Texcatitlán','SantaMaría Tlahuitoltepec','SantaMaría Tlalixtac','SantaMaría Tonameca','SantaMaría Totolapilla',
'SantaMaría Xadani','SantaMaría Yalina','SantaMaría Yavesía','SantaMaría Yolotepec','SantaMaría Yosoyúa','SantaMaría Yucuhiti','SantaMaría Zacatepec','SantaMaría Zaniza','SantaMaría Zoquitlán','Santiago Amoltepec',
'Santiago Apoala','Santiago Apóstol','Santiago Astata','Santiago Atitlán','Santiago Ayuquililla','Santiago Cacaloxtepec','Santiago Camotlán','Santiago Comaltepec','Santiago Chazumba','Santiago Choápam',
'Santiago del Río','Santiago Huajolotitlán','Santiago Huauclilla','Santiago Ihuitlán Plumas','Santiago Ixcuintepec','Santiago Ixtayutla','Santiago Jamiltepec','Santiago Jocotepec','Santiago Juxtlahuaca','Santiago Lachiguiri',
'Santiago Lalopa','Santiago Laollaga','Santiago Laxopa','Santiago Llano Grande','Santiago Matatlán','Santiago Miltepec','Santiago Minas','Santiago Nacaltepec','Santiago Nejapilla','Santiago Nundiche',
'Santiago Nuyoó','Santiago Pinotepa Nacional','Santiago Suchilquitongo','Santiago Tamazola','Santiago Tapextla','Villa Tejúpam de la Unión','Santiago Tenango','Santiago Tepetlapa','Santiago Tetepec','Santiago Texcalcingo',
'Santiago Textitlán','Santiago Tilantongo','Santiago Tillo','Santiago Tlazoyaltepec','Santiago Xanica','Santiago Xiacuí','Santiago Yaitepec','Santiago Yaveo','Santiago Yolomécatl','Santiago Yosondúa',
'Santiago Yucuyachi','Santiago Zacatepec','Santiago Zoochila','Nuevo Zoquiápam','Santo Domingo Ingenio','Santo Domingo Albarradas','Santo Domingo Armenta','Santo Domingo Chihuitán','Santo Domingo de Morelos','Santo Domingo Ixcatlán',
'Santo Domingo Nuxaá','Santo Domingo Ozolotepec','Santo Domingo Petapa','Santo Domingo Roayaga','Santo Domingo Tehuantepec',
'Santo Domingo Teojomulco','Santo Domingo Tepuxtepec','Santo Domingo Tlatayápam','Santo Domingo Tomaltepec','Santo Domingo Tonalá',
'Santo Domingo Tonaltepec','Santo Domingo Xagacía','Santo Domingo Yanhuitlán','Santo Domingo Yodohino','Santo Domingo Zanatepec',
'Santos Reyes Nopala','Santos Reyes Pápalo','Santos Reyes Tepejillo','Santos Reyes Yucuná','Santo Tomás Jalieza',
'Santo Tomás Mazaltepec','Santo Tomás Ocotepec','Santo Tomás Tamazulapan','SanVicente Coatlán','SanVicente Lachixío',
'SanVicente Nuñú','Silacayoápam','Sitio de Xitlapehua','Soledad Etla','Villa de Tamazulápam del Progreso',
'Tanetze de Zaragoza','Taniche','Tataltepec de Valdés','Teococuilco de Marcos Pérez','Teotitlán de Flores Magón',
'Teotitlán del Valle','Teotongo','Tepelmeme Villa de Morelos','Heroica Villa Tezoatlán','SanJerónimo Tlacochahuaya',
'Tlacolula de Matamoros','Tlacotepec Plumas','Tlalixtac de Cabrera','Totontepec Villa de Morelos','Trinidad Zaachila',
'La Trinidad Vista Hermosa','Unión Hidalgo','Valerio Trujano','SanJuan Bautista Valle Nacional','Villa Díaz Ordaz',
'Yaxe','Magdalena Yodocono de Porfirio Díaz','Yogana','Yutanduchi de Guerrero','Villa de Zaachila',
'SanMateo Yucutindoo','Zapotitlán Lagunas','Zapotitlán Palmas','SantaInés de Zaragoza','Zimatlán de Álvarez'
        );
        locacion.forValue("Puebla").addOptions('Acajete','Acateno','Acatlán','Acatzingo','Acteopan','Ahuacatlán','Ahuatlán','Ahuazotepec','Ahuehuetitla','Ajalpan',
'Albino Zertuche','Aljojuca','Altepexi','Amixtlán','Amozoc','Aquixtla','Atempan','Atexcal','Atlixco','Atoyatempan',
'Atzala','Atzitzihuacán','Atzitzintla','Axutla','Ayotoxco de Guerrero','Calpan','Caltepec','Camocuautla','Caxhuacan','Coatepec',
'Coatzingo','Cohetzala','Cohuecan','Coronango','Coxcatlán','Coyomeapan','Coyotepec','Cuapiaxtla de Madero','Cuautempan','Cuautinchán',
'Cuautlancingo','Cuayuca de Andrade','Cuetzalan del Progreso','Cuyoaco','Chalchicomula de Sesma','Chapulco','Chiautla','Chiautzingo','Chiconcuautla','Chichiquila',
'Chietla','Chigmecatitlán','Chignahuapan','Chignautla','Chila','Chila de la Sal','Honey','Chilchotla','Chinantla','Domingo Arenas',
'Eloxochitlán','Epatlán','Esperanza','Francisco Z. Mena','General Felipe Ángeles','Guadalupe','Guadalupe Victoria','Hermenegildo Galeana','Huaquechula','Huatlatlauca',
'Huauchinango','Huehuetla','Huehuetlán el Chico','Huejotzingo','Hueyapan','Hueytamalco','Hueytlalpan','Huitzilan de Serdán','Huitziltepec','Atlequizayan',
'Ixcamilpa de Guerrero','Ixcaquixtla','Ixtacamaxtitlán','Ixtepec','Izúcar de Matamoros','Jalpan','Jolalpan','Jonotla','Jopala','Juan C. Bonilla',
'Juan Galindo','Juan N. Méndez','Lafragua','Libres','La Magdalena Tlatlauquitepec','Mazapiltepec de Juárez','Mixtla','Molcaxac','Cañada Morelos','Naupan',
'Nauzontla','Nealtican','Nicolás Bravo','Nopalucan','Ocotepec','Ocoyucan','Olintla','Oriental','Pahuatlán','Palmar de Bravo',
'Pantepec','Petlalcingo','Piaxtla','Puebla','Quecholac','Quimixtlán','Rafael Lara Grajales','Los Reyes de Juárez','San Andrés Cholula','San Antonio Cañada',
'San Diego la Mesa Tochimiltzingo','San Felipe Teotlalcingo','San Felipe Tepatlán','San Gabriel Chilac','San Gregorio Atzompa','San Jerónimo Tecuanipan',
'San Jerónimo Xayacatlán','San José Chiapa','San José Miahuatlán','San Juan Atenco',
'San Juan Atzompa','San Martín Texmelucan','San Martín Totoltepec','San Matías Tlalancaleca','San Miguel Ixitlán','San Miguel Xoxtla','San Nicolás Buenos Aires','San Nicolás de los Ranchos','San Pablo Anicano','San Pedro Cholula',
'San Pedro Yeloixtlahuaca','San Salvador el Seco','San Salvador el Verde','San Salvador Huixcolotla','San Sebastián Tlacotepec','Santa Catarina Tlaltempan','Santa Inés Ahuatempan','Santa Isabel Cholula','Santiago Miahuatlán','Huehuetlán el Grande',
'Santo Tomás Hueyotlipan','Soltepec','Tecali de Herrera','Tecamachalco','Tecomatlán','Tehuacán','Tehuitzingo','Tenampulco','Teopantlán','Teotlalco',
'Tepanco de López','Tepango de Rodríguez','Tepatlaxco de Hidalgo','Tepeaca','Tepemaxalco','Tepeojuma','Tepetzintla','Tepexco','Tepexi de Rodríguez','Tepeyahualco',
'Tepeyahualco de Cuauhtémoc','Tetela de Ocampo','Teteles de Avila Castillo','Teziutlán','Tianguismanalco','Tilapa','Tlacotepec de Benito Juárez','Tlacuilotepec','Tlachichuca','Tlahuapan',
'Tlaltenango','Tlanepantla','Tlaola','Tlapacoya','Tlapanalá','Tlatlauquitepec','Tlaxco','Tochimilco','Tochtepec','Totoltepec de Guerrero',
'Tulcingo','Tuzamapan de Galeana','Tzicatlacoyan','Venustiano Carranza','Vicente Guerrero','Xayacatlán de Bravo','Xicotepec','Xicotlán','Xiutetelco','Xochiapulco',
'Xochiltepec','Xochitlán de Vicente Suárez','Xochitlán Todos Santos','Yaonáhuac','Yehualtepec','Zacapala','Zacapoaxtla','Zacatlán','Zapotitlán','Zapotitlán de Méndez','Zaragoza','Zautla','Zihuateutla','Zinacatepec','Zongozotla','Zoquiapan','Zoquitlán'
);
        locacion.forValue("Querétaro").addOptions('Amealco','Pinal de Amoles','Arroyo Seco','Cadereyta de Montes','Colón',
        'Corregidora','Ezequiel Montes','Huimilpan','Jalpan de Serra','Landa de Matamoros',
        'El Marqués','Pedro Escobedo','Peñamiller','Querétaro','San Joaquín',
        'San Juan del Río','Tequisquiapan','Tolimán'
             );
        locacion.forValue("Quintana Roo").addOptions('Cozumel','Felipe Carrillo Puerto','Isla Mujeres','Othón P. Blanco',
        'Benito Juárez','José María Morelos','Lázaro Cárdenas','Solidaridad','Tulum','Bacalar'
            );
        locacion.forValue("San Luis Potosí").addOptions('Ahualulco','Alaquines','Aquismón','Armadillo de los Infante',
        'Cárdenas','Catorce','Cedral','Cerritos','Cerro de San Pedro','Ciudad del Maíz',
        'Ciudad Fernández','Tancanhuitz','Ciudad Valles','Coxcatlán','Charcas',
        'Ebano','Guadalcázar','Huehuetlán','Lagunillas','Matehuala',
        'Mexquitic de Carmona','Moctezuma','Rayón','Rioverde','Salinas',
        'San Antonio','San Ciro de Acosta','San Luis Potosí','San Martín Chalchicuautla','San Nicolás Tolentino',
        'Santa Catarina','Santa María del Río','Santo Domingo','San Vicente Tancuayalab','Soledad de Graciano Sánchez',
        'Tamasopo','Tamazunchale','Tampacán','Tampamolón Corona','Tamuín',
        'Tanlajás','Tanquián de Escobedo','Tierra Nueva','Vanegas','Venado',
        'Villa de Arriaga','Villa de Guadalupe','Villa de la Paz','Villa de Ramos','Villa de Reyes',
        'Villa Hidalgo','Villa Juárez','Axtla de Terrazas','Xilitla','Zaragoza','Villa de Arista','Matlapa','El Naranjo'
        );
        locacion.forValue("Sinaloa").addOptions('Ahome','Angostura','Badiraguato','Concordia','Cosalá',
        'Culiacán','Choix','Elota','Escuinapa','El Fuerte',
        'Guasave','Mazatlán','Mocorito','Rosario','Salvador Alvarado',
        'San Ignacio','Sinaloa','Navolato'
            );
        locacion.forValue("Sonora").addOptions('Aconchi','Agua Prieta','Alamos','Altar','Arivechi',
        'Arizpe','Atil','Bacadéhuachi','Bacanora','Bacerac',
        'Bacoachi','Bácum','Banámichi','Baviácora','Bavispe',
        'Benjamín Hill','Caborca','Cajeme','Cananea','Carbó',
        'La Colorada','Cucurpe','Cumpas','Divisaderos','Empalme',
        'Etchojoa','Fronteras','Granados','Guaymas','Hermosillo',
        'Huachinera','Huásabas','Huatabampo','Huépac','Imuris',
        'Magdalena','Mazatán','Moctezuma','Naco','Nácori Chico',
        'Nacozari de García','Navojoa','Nogales','Onavas','Opodepe',
        'Oquitoa','Pitiquito','Puerto Peñasco','Quiriego','Rayón',
        'Rosario','Sahuaripa','San Felipe de Jesús','San Javier','San Luis Río Colorado',
        'San Miguel de Horcasitas','San Pedro de la Cueva','Santa Ana','Santa Cruz','Sáric',
        'Soyopa','Suaqui Grande','Tepache','Trincheras','Tubutama',
        'Ures','Villa Hidalgo','Villa Pesqueira','Yécora','General Plutarco Elías Calles',
        'Benito Juárez','San Ignacio Río Muerto'
        );
        locacion.forValue("Tabasco").addOptions('Balancán','Cárdenas','Centla','Centro','Comalcalco',
        'Cunduacán','Emiliano Zapata','Huimanguillo','Jalapa','Jalpa de Méndez',
        'Jonuta','Macuspana','Nacajuca','Paraíso','Tacotalpa',
        'Teapa','Tenosique'
            );
        locacion.forValue("Tamaulipas").addOptions('Abasolo','Aldama','Altamira','Antiguo Morelos','Burgos',
        'Bustamante','Camargo','Casas','Ciudad Madero','Cruillas',
        'Gómez Farías','González','Güémez','Guerrero','Gustavo Díaz Ordaz',
        'Hidalgo','Jaumave','Jiménez','Llera','Mainero',
        'El Mante','Matamoros','Méndez','Mier','Miguel Alemán',
        'Miquihuana','Nuevo Laredo','Nuevo Morelos','Ocampo','Padilla',
        'Palmillas','Reynosa','Río Bravo','San Carlos','San Fernando',
        'San Nicolás','Soto la Marina','Tampico','Tula','Valle Hermoso',
        'Victoria','Villagrán','Xicoténcatl'
                );
        locacion.forValue("Tlaxcala").addOptions('Tetla de la Solidaridad','Tetlatlahuca','Tlaxcala','Tlaxco','Tocatlán',
'Totolac','Ziltlaltépec de Trinidad Sánchez Santos','Tzompantepec','Xaloztoc','Xaltocan',
'Papalotla de Xicohténcatl','Xicohtzinco','Yauhquemehcan','Zacatelco','Benito Juárez',
'Emiliano Zapata','Lázaro Cárdenas','La Magdalena Tlaltelulco','San Damián Texóloc','San Francisco Tetlanohcan',
'San Jerónimo Zacualpan','San José Teacalco','San Juan Huactzinco','San Lorenzo Axocomanitla','San Lucas Tecopilco',
'Santa Ana Nopalucan','Santa Apolonia Teacalco','Santa Catarina Ayometla','Santa Cruz Quilehtla','Santa Isabel Xiloxoxtla');
        locacion.forValue("Veracruz").addOptions('Acajete','Acatlán','Acayucan','Actopan','Acula',
'Acultzingo','Camarón de Tejeda','Alpatláhuac','Alto Lucero de Gutiérrez Barrios','Altotonga',
'Alvarado','Amatitlán','Naranjos Amatlán','Amatlán de los Reyes','Angel R. Cabada',
'La Antigua','Apazapan','Aquila','Astacinga','Atlahuilco',
'Atoyac','Atzacan','Atzalan','Tlaltetela','Ayahualulco',
'Banderilla','Benito Juárez','Boca del Río','Calcahualco','Camerino Z. Mendoza',
'Carrillo Puerto','Catemaco','Cazones de Herrera','Cerro Azul','Citlaltépetl',
'Coacoatzintla','Coahuitlán','Coatepec','Coatzacoalcos','Coatzintla',
'Coetzala','Colipa','Comapa','Córdoba','Cosamaloapan de Carpio',
'Cosautlán de Carvajal','Coscomatepec','Cosoleacaque','Cotaxtla','Coxquihui',
'Coyutla','Cuichapa','Cuitláhuac','Chacaltianguis','Chalma',
'Chiconamel','Chiconquiaco','Chicontepec','Chinameca','Chinampa de Gorostiza',
'Las Choapas','Chocamán','Chontla','Chumatlán','Emiliano Zapata',
'Espinal','Filomeno Mata','Fortín','Gutiérrez Zamora','Hidalgotitlán',
'Huatusco','Huayacocotla','Hueyapan de Ocampo','Huiloapan de Cuauhtémoc','Ignacio de la Llave','Ilamatlán','Isla','Ixcatepec','Ixhuacán de los Reyes','Ixhuatlán del Café',
'Ixhuatlancillo','Ixhuatlán del Sureste','Ixhuatlán de Madero','Ixmatlahuacan','Ixtaczoquitlán','Jalacingo','Xalapa','Jalcomulco','Jáltipan','Jamapa',
'Jesús Carranza','Xico','Jilotepec','Juan Rodríguez Clara','Juchique de Ferrer','Landero y Coss','Lerdo de Tejada','Magdalena','Maltrata','Manlio Fabio Altamirano',
'Mariano Escobedo','Martínez de la Torre','Mecatlán','Mecayapan','Medellín de Bravo','Miahuatlán','Las Minas','Minatitlán','Misantla','Mixtla de Altamirano',
'Moloacán','Naolinco','Naranjal','Nautla','Nogales','Oluta','Omealca','Orizaba','Otatitlán','Oteapan',
'Ozuluama de Mascareñas','Pajapan','Pánuco','Papantla','Paso del Macho','Paso de Ovejas','La Perla','Perote','Platón Sánchez','Playa Vicente',
'Poza Rica de Hidalgo','Las Vigas de Ramírez','Pueblo Viejo','Puente Nacional','Rafael Delgado','Rafael Lucio','Los Reyes','Río Blanco','Saltabarranca','San Andrés Tenejapan',
'San Andrés Tuxtla','San Juan Evangelista','Santiago Tuxtla','Sayula de Alemán','Soconusco','Sochiapa','Soledad Atzompa','Soledad de Doblado','Soteapan','Tamalín',
'Tamiahua','Tampico Alto','Tancoco','Tantima','Tantoyuca','Tatatila','Castillo de Teayo','Tecolutla','Tehuipango','Álamo Temapache',
'Tempoal','Tenampa','Tenochtitlán','Teocelo','Tepatlaxco','Tepetlán','Tepetzintla','Tequila','José Azueta','Texcatepec',
'Texhuacán','Texistepec','Tezonapa','Tierra Blanca','Tihuatlán','Tlacojalpan','Tlacolulan','Tlacotalpan','Tlacotepec de Mejía','Tlachichilco',
'Tlalixcoyan','Tlalnelhuayocan','Tlapacoyan','Tlaquilpa','Tlilapan','Tomatlán','Tonayán','Totutla','Tuxpan','Tuxtilla',
'Ursulo Galván','Vega de Alatorre','Veracruz','Villa Aldama','Xoxocotla','Yanga','Yecuatla','Zacualpan','Zaragoza','Zentla',
'Zongolica','Zontecomatlán de López y Fuentes','Zozocolco de Hidalgo','Agua Dulce','El Higo','Nanchital de Lázaro Cárdenas del Río','Tres Valles','Carlos A. Carrillo','Tatahuicapan de Juárez','Uxpanapa','San Rafael','Santiago Sochiapan');
    locacion.forValue("Yucatán").addOptions('Abalá','Acanceh','Akil','Baca','Bokobá',
'Buctzotz','Cacalchén','Calotmul','Cansahcab','Cantamayec',
'Celestún','Cenotillo','Conkal','Cuncunul','Cuzamá',
'Chacsinkín','Chankom','Chapab','Chemax','Chicxulub Pueblo',
'Chichimilá','Chikindzonot','Chocholá','Chumayel','Dzán',
'Dzemul','Dzidzantún','Dzilam de Bravo','Dzilam González','Dzitás',
'Dzoncauich','Espita','Halachó','Hocabá','Hoctún',
'Homún','Huhí','Hunucmá','Ixil','Izamal',
'Kanasín','Kantunil','Kaua','Kinchil','Kopomá',
'Mama','Maní','Maxcanú','Mayapán','Mérida',
'Mocochá','Motul','Muna','Muxupip','Opichén',
'Oxkutzcab','Panabá','Peto','Progreso','Quintana Roo',
'Río Lagartos','Sacalum','Samahil','Sanahcat','San Felipe',
'Santa Elena','Seyé','Sinanché','Sotuta','Sucilá',
'Sudzal','Suma','Tahdziú','Tahmek','Teabo','Tecoh','Tekal de Venegas','Tekantó','Tekax','Tekit',
'Tekom','Telchac Pueblo','Telchac Puerto','Temax','Temozón','Tepakán','Tetiz','Teya','Ticul','Timucuy',
'Tinum','Tixcacalcupul','Tixkokob','Tixmehuac','Tixpéhual','Tizimín','Tunkás','Tzucacab','Uayma','Ucú',
'Umán','Valladolid','Xocchel','Yaxcabá','Yaxkukul','Yobaín'
        );
    locacion.forValue("Zacatecas").addOptions('Apozol','Apulco','Atolinga','Benito Juárez','Calera',
'Cañitas de Felipe Pescador','Concepción del Oro','Cuauhtémoc','Chalchihuites','Fresnillo',
'Trinidad García de la Cadena','Genaro Codina','General Enrique Estrada','General Francisco R. Murguía','El Plateado de Joaquín Amaro',
'General Pánfilo Natera','Guadalupe','Huanusco','Jalpa','Jerez',
'Jiménez del Teul','Juan Aldama','Juchipila','Loreto','Luis Moya',
'Mazapil','Melchor Ocampo','Mezquital del Oro','Miguel Auza','Momax',
'Monte Escobedo','Morelos','Moyahua de Estrada','Nochistlán de Mejía','Noria de Ángeles',
'Ojocaliente','Pánuco','Pinos','Río Grande','Sain Alto',
'El Salvador','Sombrerete','Susticacán','Tabasco','Tepechitlán',
'Tepetongo','Teúl de González Ortega','Tlaltenango de Sánchez Román','Valparaíso','Vetagrande',
'Villa de Cos','Villa García','Villa González Ortega','Villa Hidalgo','Villanueva',
'Zacatecas','Trancoso','Santa María de la Paz'
            );
    locacion.forValue("Nuevo León").setDefaultOptions("San Pedro Garza García");
      </script>
                                  <option value="Aguascalientes">Aguascalientes</option>
                                  <option value="Baja California">Baja California</option>
                                  <option value="Baja California Sur">Baja California Sur</option>
                                  <option value="Campeche">Campeche</option>
                                  <option value="Coahuila">Coahuila</option>
                                  <option value="Colima">Colima</option>
                                  <option value="Chiapas">Chiapas</option>
                                  <option value="Chihuahua">Chihuahua</option>
                                  <option value="Distrito Federal">Distrito Federal</option>
                                  <option value="Durango">Durango</option>
                                  <option value="Guanajuato">Guanajuato</option>
                                  <option value="Guerrero">Guerrero</option>
                                  <option value="Hidalgo">Hidalgo</option>
                                  <option value="Jalisco">Jalisco</option>
                                  <option value="México">México</option>
                                  <option value="Michoacán">Michoacán</option>
                                  <option value="Morelos">Morelos</option>
                                  <option value="Nayarit">Nayarit</option>
                                  <option value="Nuevo León" selected>Nuevo León</option>
                                  <option value="Oaxaca">Oaxaca</option>
                                  <option value="Puebla">Puebla</option>
                                  <option value="Querétaro">Querétaro</option>
                                  <option value="Quintana Roo">Quintana Roo</option>
                                  <option value="San Luis Potosí">San Luis Potosí</option>
                                  <option value="Sinaloa">Sinaloa</option>
                                  <option value="Sonora">Sonora</option>
                                  <option value="Tabasco">Tabasco</option>
                                  <option value="Tamaulipas">Tamaulipas</option>
                                  <option value="Tlaxcala">Tlaxcala</option>
                                  <option value="Veracruz">Veracruz</option>
                                  <option value="Yucatán">Yucatán</option>
                                  <option value="Zacatecas">Zacatecas</option>

                              </select>
                              Municipio: <select name="municipio" id="municipio">

                              </select><br><br>
                              <input type="text" name="domicilio" id="domicilio"  placeholder="Domicilio" REQUIRED maxlength="40">
      <!--<input type="text" name="municipio" id="municipio" placeholder="Municipio" REQUIRED maxlength="20" onkeypress="return Solo_Texto(event)"><br><br></center> -->
                              <input type="text" name="telefono" id="telefono"  placeholder="Teléfono" REQUIRED onkeypress="return numeros(event)" maxlength="10"><br><br>
                              <div id="menorEdad" style="display:none;">
                                  Tutor:<select style="width:30%; font-family:NewJuneBold; font-size:15px; margin-left:36px;" name="tutor" id="tutor">
                                  <?php
                                      include("conexion.php");
                                      $query="SELECT nombre,apellidoPaterno,apellidoMaterno FROM cliente WHERE edad>=18";
                                      $resultado=mysqli_query($conexion,$query);
                                      while($row=$resultado->fetch_assoc()){
                                  ?>
                                      <option value="<?php echo $row['nombre']." ".$row['apellidoPaterno']." ".$row['apellidoMaterno']; ?>"><?php echo $row['nombre']." ".$row['apellidoPaterno']." ".$row['apellidoMaterno'];?>
                                      </option>
                                  <?php


          //echo "<option" . $row['nombre']." ".$row['apellidoPaterno']." ".$row['apellidoMaterno'] . "</option>";

                                        }
                                  ?>
                                  </select>
      <!--  <input style="width:30%; font-family:NewJuneBold; font-size:15px; margin-left:36px;" type="text" name="tutor" id="tutor" placeholder="Tutor" REQUIRED maxlength="40" onkeypress="return Solo_Texto(event)"> -->
                                      <input style="width:30%; font-family:NewJuneBold; font-size:15px; margin-left:36px;" type="text" name="parentesco" id="parentesco"  placeholder="Parentesco" maxlength="15"onkeypress="return Solo_Texto(event)">
                              </div>
                              <br>
                              <input type="submit" class="btn btn-success" style="float:right;" value="Registrar Cliente" id="registrar">
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- Cierra el modal-->



            <!-- ABRE LO QUE VIENE SIENDO EL CONTENIDO DE LA PAGINA Y NO EL POP UP-->

            <div id="informacionAMostrar" class="jumbotron" style="margin-left:15px; padding-right: 8%; padding-left: 8%;height: 100%">
                <center><div id="pensare"><h3 style="font-size: 30px; font-family: NewJuneBold; margin-top: 40px; background-color: #FFF500; padding-top: 15px; padding-bottom: 15px; color: #333333;">Clientes</h3></div></center>
                <div id="error" style="  text-align: right; margin-left: 380px; font-size:20px; color:#cc0000; font-family:NewJuneBold;"><?php echo isset($error)? utf8_decode($error): ''; ?></div>
                <a href="#mregistro" data-toggle="modal" style="float: right;"><img src="imagenes/agregar.png"></a><h3 style="text-align: right;">Agregar Nuevo</h3>
                <h4>Lista de Clientes</h4><br>
                <form  id="fbusqueda" action="<?php $_SERVER['PHP_SELF'];?>" method="GET">   <!-- ESTE FORM TMBN LLEVARA A ESTA MISMA PAGINA SOLO Q POR METODO GET PARA PODER MANIPULAR DOS FUNCIONES EN LA MISMA UNO CON POST QUE ES EL REGISTRO Y UNO CON GET QUE ES EL DE BUSQUEDA  -->
                    <input type="text" placeholder="Buscar" id="buscar" name="buscar" style="width: 40%; float: right;">
                </form><br><br>
                <div style="overflow:scroll; height:460px;" id="tabla table table-responsive" style="width: 100%;">
                  <table class="sortable">
                      <th style="padding:20px; font-size:15px;">Nombre</th>
                      <th style="padding:20px; font-size:15px;">Teléfono</th>
                      <th style="padding:20px; font-size:15px;">Colonia</th>
                      <th style="padding:20px; font-size:15px;">Municipio</th>
                      <th style="padding:20px; font-size:15px;">Fecha de Nacimiento</th>
                      <th style="padding:20px; font-size:15px;" colspan="2"><center>Acciones</center></th>
                      <?php
                          include("conexion.php");

                          if(!empty($_GET)){  //SI LLEGO POR METODO GET OSEA CUANDO SE TECLEO ALGO EN EL SEARCHBAR
                              $busqueda=$_GET['buscar'];  //COMO SE VUELVE A CARGAR LA MISMA PAGINA AGARRAMOS EL VALOR ENVIADO POR METODO GET PARA HACER EL QUERY


                    //REALIZAMOS UN QUERY BUSCANDO TODOS LOS CLIENTES EN LA BASE DE DATOS QUE CONTENGAN ESA PALABRA A BUSCAR ENVIADA POR METODO GET
                              $query="SELECT * FROM cliente WHERE historico='no' and nombre like '".$busqueda."%'";
                              $resultado=mysqli_query($conexion,$query); //SE EJECUTA LA CONSULTA ENTONCES ME TRAERA TODOS LOS DATOS QUE TENGAN ESA PALABRA BUSQUEDA ENVIADA POR EL METODO GET
                              while($row=$resultado->fetch_assoc()){  //HACEMOS UN WHILE PORQUE PUEDE ENCONTRAR VARIOS CON LOS MISMOS NOMBRES O APELLIDOS O POR COLONIA O POR MUNICIPIO O POR TELEFONO

                      //Y LO QUE HARA SERA RELLENAR LA TABLA CON LOS QUE CUMPLAN CON EL PARAMETRO
                        ?>
                      <tr>
                          <td style="padding:5px; font-size:15px;"><?php echo $row['nombre']." ".$row['apellidoPaterno']." ".$row['apellidoMaterno'];?></td>
                          <td style="padding:5px; font-size:15px;"><?php echo $row['telefono'];?></td>
                          <td style="padding:5px; font-size:15px;"><?php echo $row['colonia'];?></td>
                          <td style="padding:5px; font-size:15px;"><?php echo $row['municipio'];?></td>
                          <td style="padding:5px; font-size:15px;"><?php echo $row['fechaNacimiento'];?></td>
                          <td><a href="informacionDetallada.php?id=<?php echo $row['matricula'];?>"><center><img src="imagenes/consultar.png"></center></a></td> <!-- AQUI MANDAREMOS A INFORMACION DETALLADA EL ID DEL CLIENTE OSEA LA MATRICULA PARA LLEGAR A ESA Y JALAR UNICAMENTE TODOS LOS DATOS DE ESE PACIENTE EN EXCLUSIVO -->
                          <td><a href="javascript: historico(<?php echo $row['matricula']?>)"><center><img src="imagenes/delete.png"></center></a></td>
                      </tr>
                      <?php
                          } //cierra el while
                        } //cierra el if AQUI CIERRA LO QUE SE REALIZARA CUANDO ESTA PAGINA PONGAN ALGO EN EL SEARCHBAR
                        else{  //AQUI SE EJECUTARA TODO LO QUE PASA CUANDO CARGUE POR PRIMERA VEZ LA PAGINA OSEA POR DEFAULT LA NORMAL
                        $query= "SELECT * FROM cliente WHERE historico='no'";
                        $resultado= mysqli_query($conexion,$query);

                        while($row=$resultado->fetch_assoc()){
                      ?>
                    <tr>
                        <td style="padding:5px; font-size:15px;"><?php echo $row['nombre']." ".$row['apellidoPaterno']." ".$row['apellidoMaterno'];?></td>
                        <td style="padding:5px; font-size:15px;"><?php echo $row['telefono'];?></td>
                        <td style="padding:5px; font-size:15px;"><?php echo $row['colonia'];?></td>
                        <td style="padding:5px; font-size:15px;"><?php echo $row['municipio'];?></td>
                        <td style="padding:5px; font-size:15px;"><?php echo $row['fechaNacimiento'];?></td>
                        <?php
                            $id=$row['matricula'];
                        ?>
                        <center><td><a href="informacionDetallada.php?id=<?php echo $row['matricula'];?>"><center><img src="imagenes/consultar.png"></center></a></td> <!-- AQUI MANDAREMOS A INFORMACION DETALLADA EL ID DEL CLIENTE OSEA LA MATRICULA PARA LLEGAR A ESA Y JALAR UNICAMENTE TODOS LOS DATOS DE ESE PACIENTE EN EXCLUSIVO -->
                        <td><a href="javascript: historico(<?php echo $row['matricula']?>)"><center><img src="imagenes/delete.png"></center></a></td></center>
                    </tr>
                    <?php
                        } //cierra el while
                      }//cierra el else
                    ?>
                </table>
              </div>
            </div>
            <script>
              function historico(matricula){
                var pregunta=confirm("Esta seguro que desea eliminar el cliente?");
                if(pregunta){
                  var id=matricula;
                  window.location="mandarHistorico.php?id="+id;
                }
              }
            </script>



          </div>   <!-- Cierra el container-flui -->
      </div>       <!-- Cierra el page content-->
    </div>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>


    <script>
      $("#menu-toggle").click(function(e){
        e.preventDefault();
        $("#wrapper").toggleClass("menuDisplayed");
      });
    </script>

  </body>
</html>
