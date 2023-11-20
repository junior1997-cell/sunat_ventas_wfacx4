(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ES (Spanish; Español)
 * Region: PE (Perú)
 */
$.extend( $.validator.messages, {
	required: "Campo requerido.",
	remote: "Por favor, llene este campo.",
	email: "Ingrese correo válido.",
	url: "Ingrese una URL válida.",
	date: "Ingrese fecha válida.",
	dateISO: "Ingrese fecha fecha (ISO) válida.",
	number: "Ingrese un número.",
	digits: "Por favor, escriba sólo dígitos.",
	creditcard: "Por favor, escriba un número de tarjeta válido.",
	equalTo: "Repita el mismo valor de nuevo.",
	extension: "Por favor, escriba un valor con una extensión permitida.",
	maxlength: $.validator.format( "Máximo {0} caracteres." ),
	minlength: $.validator.format( "Mínimo {0} caracteres." ),
	rangelength: $.validator.format( "Por favor, escriba un valor entre {0} y {1} caracteres." ),
	range: $.validator.format( "Por favor, escriba un valor entre {0} y {1}." ),
	max: $.validator.format( "Máximo {0}." ),
	min: $.validator.format( "Mínimo {0}." ),
	nifES: "Por favor, escriba un NIF válido.",
	nieES: "Por favor, escriba un NIE válido.",
	cifES: "Por favor, escriba un CIF válido.",
	// step: a.validator.format("Ingrese numero mayor a {0}."),
} );
return $;
}));