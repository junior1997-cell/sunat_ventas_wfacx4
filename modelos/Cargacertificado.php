<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
 
Class Cargacertificado
{
    //Implementamos nuestro constructor
    public function __construct()
    {
 
    }
 
    //Implementamos un método para insertar registros
    public function insertar($numeroruc,$razon_social,$usuarioSol,$claveSol,$rutacertificado,$rutaserviciosunat,$nombrepem, $keypfx,
        $webserviceguia)
    {
        $sw=true;
        $sql="insert into
         sunatconfig 
         (  numeroruc,
            razon_social,
            usuarioSol,
            claveSol,
            rutacertificado, 
            rutaserviciosuna,
            $nombrepem,
            passcerti,
            webserviceguia
            )
        values 
            (
        '$numeroruc',
        '$razon_social',
        '$usuarioSol',
        '$claveSol',
        '$rutacertificado',
        '$rutaserviciosunat',
        '$nombrepem',
        '$keypfx',
        '$webserviceguia'
        )";

        ejecutarConsulta($sql) or $sw = false;
        return $sw;
    }
 
    //Implementamos un método para editar registros
    public function editar($idcarga, $numeroruc,$razon_social,$usuarioSol,$claveSol,$rutacertificado,$rutaserviciosunat,$nombrepem,
        $keypfx, $webserviceguia)
    {
        $sw=true;
        $sql="UPDATE sunatconfig 
        set 
        numeroruc='$numeroruc', 
        razon_social='$razon_social', 
        usuarioSol='$usuarioSol', 
        usuarioSol='$usuarioSol', 
        claveSol='$claveSol', 
        rutacertificado='$rutacertificado', 
        rutaserviciosunat='$rutaserviciosunat',
        nombrepem='$nombrepem',
        passcerti='$keypfx',
        webserviceguia='$webserviceguia'
        where 
        idcarga='$idcarga'";
        ejecutarConsulta($sql) or $sw = false;
        return $sw;
    }


   
    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar()
    {
        $sql="SELECT  *  from
         sunatconfig where idcarga='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

 
}
 
?>