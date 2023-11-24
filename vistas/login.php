<?php
require_once '../config/global.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- Favicon -->
  <link rel="apple-touch-icon" href="../assets/images/brand-logos/toggle-logo.png">
  <link rel="shortcut icon" href="../assets/images/brand-logos/toggle-logo.png">

	<!-- SEO meta tags -->
	<title>Sistema de Facturación Electrónica y Gestión de Inventario</title>
	<meta name="description"
		content="Demo de un sistema robusto de facturación electrónica y gestión de inventario. Mejora la eficiencia de tu negocio con nuestra solución." />
	<meta name="keywords" content="facturación electrónica, gestión de inventario, sistema de facturación, demo">
	<meta name="author" content="WFACX">

	<!-- Open Graph (para redes sociales como Facebook) -->
	<meta property="og:title" content="Demo Sistema de Facturación Electrónica y Gestión de Inventario" />
	<meta property="og:description"
		content="Demo de un sistema robusto de facturación electrónica y gestión de inventario. Mejora la eficiencia de tu negocio con nuestra solución." />
	<meta property="og:image" content="https://wfacx.com/seo/Wfacx_Portada.png" />
	<meta property="og:url" content="https://wfacx.com/sistema/vistas/login" />

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="Demo Sistema de Facturación Electrónica y Gestión de Inventario">
	<meta name="twitter:description"
		content="Demo de un sistema robusto de facturación electrónica y gestión de inventario. Mejora la eficiencia de tu negocio con nuestra solución.">
	<meta name="twitter:image" content="https://wfacx.com/seo/Wfacx_Portada.png">

	<!-- JSON-LD para datos estructurados -->
	<script type="application/ld+json">
	{
	  "@context": "http://schema.org",
	  "@type": "SoftwareApplication",
	  "name": "Sistema de Facturación Electrónica y Gestión de Inventario",
	  "description": "Demo de un sistema robusto de facturación electrónica y gestión de inventario.",
	  "applicationCategory": "BusinessApplication",
	  "operatingSystem": "Web",
	  "screenshot": "https://wfacx.com/seo/Wfacx_Portada.png",
	  "offers": {
		"@type": "Offer",
		"price": "450.00",
		"priceCurrency": "PEN",
		"availability": "http://schema.org/InStock",
		"url": "https://wfacx.com/sistema/vistas/login"
	  }
	}
	</script>

	<link rel="stylesheet" href="../custom/css/login.css" />
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
	<div class="wrapper">
		<span class="bg-animate"></span>
		<span class="bg-animate2"></span>
		<div class="form-box login">
			<h2 class="animation" style="--i:0; --j:21;">Login</h2>
			<form method="post" id="frmAcceso" name="frmAcceso" onload="document.frmAcceso.logina.focus()"
				action="../config/global.php">
				<div class="input-box animation" style="--i:1; --j:22;">
					<input value="" id="logina" name="logina" type="text" required />
					<label>Usuario</label>
					<i class="bx bxs-user"></i>
				</div>
				<div class="input-box animation" style="--i:2; --j:23;">
					<input value="" id="clavea" name="clavea" type="password" required />
					<label>Password</label>
					<i class="bx bxs-lock-alt"></i>
				</div>
				<div class="form-group has-feedback" hidden>
					<!--<label>EMPRESA</label><br>-->
					<select name="empresa" id="empresa" class="form-control">
						<option value="<?php echo DB_NAME; ?>" selected="true">
							<?php echo DB_NAME; ?>
						</option>
					</select>
					<span class="fa fa-bd form-control-feedback"></span>
				</div>
				<button id="btnIngresar" type="submit" class="btn animation" style="--i:3; --j:24;">Entrar</button>
				<div class="logreg-link animation" style="--i:4; --j:25;">
					<p>
						Olvidaste tu clave? <a href="#" class="register-link">Clic aqui</a>
					</p>
				</div>
			</form>
		</div>

		<div class="info-text login">
			<h2 class="animation" style="--i:0; --j:19;">Wfacx</h2>
			<p class="animation" style="--i:1; --j:20;">Facturación Electrónica y<br>Gestión Empresarial</p>
		</div>

		<div class="form-box register">
			<h2 class="animation" style="--i:17; --j:0;">Recuperar Contraseña</h2>
			<form action="">
				<div class="input-box animation" style="--i:18; --j:1;">
					<input type="text" required />
					<label>Correo Electrónico</label>
					<i class="bx bxs-user"></i>
				</div>
				<div hidden class="input-box animation" style="--i:19; --j:2;">
					<input type="password" required />
					<label>Password</label>
					<i class="bx bxs-lock-alt"></i>
				</div>
				<button type="submit" class="btn animation" style="--i:20; --j:3;">Cambiar</button>
				<div class="logreg-link animation" style="--i:21; --j:4;">
					<p>
						Ya tienes cuenta? <a href="#" class="login-link">Clic aqui</a>
					</p>
				</div>
			</form>
		</div>

		<div class="info-text register">
			<h2 class="animation" style="--i:17; --j:0;">Wfacx</h2>
			<p class="animation" style="--i:18; --j:1;">Facturación Electrónica y<br>Gestión Empresarial</p>
		</div>
	</div>

	<script src="../public/js/jquery-3.1.1.min.js"></script>
	<script src="../custom/js/evento.js"></script>
	<script type="text/javascript" src="scripts/login.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>