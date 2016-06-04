<div class="container-fluid" style="height: 27px; background-color: #263345;">
	<div class="container">
		<p class="text-right sans" style="color: #fff; line-height: 27px; font-size: 13px;">Registrarme <span style="font-weight: 700;">GRATIS</span> como un Profesional del Derecho | Login</p>
	</div>
</div>
<div class="container-fluid" style="height: 70px; background-color: #f6f6f6; border-bottom: 1px solid #ededed;">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<a href="index.php"><img src="src/img/logo2.png" style="line-height: 70px; max-width: 100%;"></a>
			</div>
			<div class="col-md-9">
				<ul style="display:inline; list-style: none; font-weight: 700; color: #263345; line-height: 70px;" class="pull-right text-uppercase">
					<a href="directorio.php" style="text-decoration: none; color: inherit;"><li style="display: inline; padding: 15px 15px;" class="sans">directorio</li></a>
					<div class="dropdowns">
					<button onclick="myFunction()" class="dropbtn text-uppercase">Especialidades <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></button>
					  <div id="myDropdown" class="dropdown-content">
					    <a href="#home" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho de Familia</a>
					    <a href="#about" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Civil</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Corporativo</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Mercantil</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Laboral</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Administrativo</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Contencioso Adminsitrativo</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Migratorio</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Constitucional</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Aduanero</a>
					    <a href="#contact" style="line-height: 20px; font-size: 13px;" class="text-capitalize">Derecho Internacional</a>
					  </div>
					</div>
					<a href="preguntas_landing.php" style="text-decoration: none; color: inherit;"><li style="display: inline; padding: 15px 15px;" class="sans">preguntar gratis</li></a>
					<a href="blog.php" style="text-decoration: none; color: inherit;"><li style="display: inline; padding: 15px 15px;" class="sans">blog</li></a>
					<!-- <li style="display: inline; padding: 15px 15px;" class="sans">login</li> -->
					<a href="abogado_landing.php" style="text-decoration: none; color: inherit;"><li style="display: inline; padding: 15px 0px 0px 15px;" class="sans">soy un abogado</li></a>
				</ul>
			</div>
		</div>
	</div>
</div>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
