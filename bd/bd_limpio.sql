-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-10-2023 a las 22:20:17
-- Versión del servidor: 5.7.43-cll-lve
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jdl_base_de_datos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`qsjtkvq326yd`@`localhost` PROCEDURE `InsertarDatos` (IN `p_codigo` VARCHAR(255), IN `p_familia_descripcion` VARCHAR(45), IN `p_nombre` VARCHAR(500), IN `p_marca` VARCHAR(100), IN `p_descrip` VARCHAR(500), IN `p_costo_compra` FLOAT(12,2), IN `p_precio_venta` DECIMAL(12,2), IN `p_stock` FLOAT(12,2), IN `p_saldo_iniu` FLOAT(12,2), IN `p_valor_iniu` FLOAT(12,2), IN `p_tipoitem` VARCHAR(50), IN `p_codigott` VARCHAR(50), IN `p_desctt` VARCHAR(50), IN `p_codigointtt` VARCHAR(50), IN `p_nombrett` VARCHAR(50), IN `p_nombre_almacen` VARCHAR(45))   BEGIN
    DECLARE fam_id INT;
    DECLARE alm_id INT;

    -- Insertar en familia si no existe, o recuperar ID si existe
    INSERT INTO familia (descripcion, estado) 
    VALUES (p_familia_descripcion, 1)
    ON DUPLICATE KEY UPDATE idfamilia=LAST_INSERT_ID(idfamilia), descripcion=p_familia_descripcion;

    SET fam_id = LAST_INSERT_ID();

    -- Insertar en almacén si no existe, o recuperar ID si existe
    INSERT INTO almacen (nombre, estado) 
    VALUES (p_nombre_almacen, 1)
    ON DUPLICATE KEY UPDATE idalmacen=LAST_INSERT_ID(idalmacen), nombre=p_nombre_almacen;

    SET alm_id = LAST_INSERT_ID();

    -- Insertar en articulo
    INSERT INTO articulo (
        idalmacen, codigo, nombre, idfamilia, unidad_medida, costo_compra, saldo_iniu, 
        valor_iniu, stock, precio_venta, estado, codigott, desctt, codigointtt, nombrett, 
        marca, descrip, fecharegistro, tipoitem, umedidacompra, factorc,
        fechafabricacion, fechavencimiento, fechaingalm, fechafinalma, proveedor, limitestock, lote, procedencia,
        fabricante, registrosanitario, codigo_proveedor, seriefaccompra, numerofaccompra,
        fechafacturacompra, saldo_finu, valor_finu, comprast, ventast, portador, merma, 
        imagen, valor_fin_kardex, precio_final_kardex, codigosunat, ccontable, precio2, 
        precio3, costofinal, cicbper, nticbperi, ctticbperi, mticbperu
    ) 
    VALUES (
        alm_id, p_codigo, p_nombre, fam_id, 58, p_costo_compra, p_saldo_iniu, p_valor_iniu, 
        p_stock, p_precio_venta, 1, p_codigott, p_desctt, p_codigointtt, p_nombrett,
        p_marca, p_descrip, NOW(), p_tipoitem, 58, 1.00, 
        '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '', '0.00', '', '',
        '', '', '-', '', '', 
        '0000-00-00', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 
        '', 0.00, 0.00, '', '', 0.00, 
        0.00, NULL, '', '', '', 0.00
    );

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `idalmacen` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`idalmacen`, `nombre`, `direccion`, `idempresa`, `estado`) VALUES
(1, 'PRINCIPAL', 'PRINCIPAL', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE `articulo` (
  `idarticulo` int(11) NOT NULL,
  `idalmacen` int(11) NOT NULL,
  `codigo_proveedor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `idfamilia` int(11) NOT NULL,
  `unidad_medida` int(11) DEFAULT NULL,
  `costo_compra` float(12,2) NOT NULL,
  `saldo_iniu` float(12,2) DEFAULT NULL,
  `valor_iniu` float(12,2) DEFAULT NULL,
  `saldo_finu` float(12,2) DEFAULT NULL,
  `valor_finu` float(12,2) DEFAULT NULL,
  `stock` float(12,2) DEFAULT NULL,
  `comprast` float(12,2) DEFAULT NULL,
  `ventast` float(12,2) DEFAULT NULL,
  `portador` float(12,2) DEFAULT NULL,
  `merma` float(12,2) DEFAULT NULL,
  `precio_venta` decimal(12,2) DEFAULT NULL,
  `imagen` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1',
  `valor_fin_kardex` float(12,2) DEFAULT NULL,
  `precio_final_kardex` float(12,2) DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `codigosunat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccontable` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `precio2` float(14,2) DEFAULT NULL,
  `precio3` float(14,2) DEFAULT NULL,
  `costofinal` float(14,2) DEFAULT NULL,
  `cicbper` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nticbperi` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ctticbperi` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mticbperu` float(14,2) DEFAULT NULL,
  `codigott` varchar(50) COLLATE utf8_unicode_ci DEFAULT '1000',
  `desctt` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'IGV Impuesto general a las ventas',
  `codigointtt` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'VAT',
  `nombrett` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'IGV',
  `lote` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechafabricacion` date DEFAULT NULL,
  `fechavencimiento` date DEFAULT NULL,
  `procedencia` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fabricante` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registrosanitario` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaingalm` date DEFAULT NULL,
  `fechafinalma` date DEFAULT NULL,
  `proveedor` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seriefaccompra` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numerofaccompra` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechafacturacompra` date DEFAULT NULL,
  `limitestock` float(14,2) DEFAULT NULL,
  `tipoitem` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `umedidacompra` int(11) DEFAULT NULL,
  `factorc` float(14,2) DEFAULT NULL,
  `descrip` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boleta`
--

CREATE TABLE `boleta` (
  `idboleta` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_emision_01` datetime NOT NULL,
  `firma_digital_36` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `tipo_documento_06` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion_07` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `codigo_tipo_15_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto_15_2` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_1` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_2` float(12,2) DEFAULT NULL,
  `codigo_tributo_18_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_tributo_18_4` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_internacional_18_5` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_total_23` decimal(12,2) DEFAULT NULL,
  `codigo_leyenda_26_1` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion_leyenda_26_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_documento_25_1` int(11) DEFAULT NULL,
  `guia_remision_25` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_ubl_37` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_estructura_38` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_moneda_24` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tasa_igv` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipodocuCliente` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rucCliente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RazonSocial` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `comentario_baja` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdescuento` float(12,2) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcambio` float(14,2) DEFAULT NULL,
  `tipopago` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nroreferencia` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipagado` float(14,2) DEFAULT NULL,
  `saldo` float(14,2) DEFAULT NULL,
  `icbper` float(14,2) NOT NULL,
  `CodigoRptaSunat` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DetalleSunat` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipoboleta` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hashc` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `formapago` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montofpago` float(14,2) DEFAULT NULL,
  `monedafpago` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccuotas` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montocuota` float(14,2) DEFAULT NULL,
  `fechavecredito` date DEFAULT NULL,
  `transferencia` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotransferencia` float(14,2) DEFAULT NULL,
  `tarjetadc` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotarjetadc` float(14,2) DEFAULT NULL,
  `ntrans` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechavenc` date DEFAULT NULL,
  `efectivo` decimal(14,2) DEFAULT NULL,
  `visa` decimal(14,2) DEFAULT NULL,
  `yape` decimal(14,2) DEFAULT NULL,
  `plin` decimal(14,2) DEFAULT NULL,
  `mastercard` decimal(14,2) DEFAULT NULL,
  `deposito` decimal(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletaempresa`
--

CREATE TABLE `boletaempresa` (
  `idempresab` int(10) UNSIGNED NOT NULL,
  `nombreEmpresa` varchar(100) DEFAULT NULL,
  `rucEmpresa` char(11) DEFAULT NULL,
  `essalud` float(14,2) DEFAULT NULL,
  `serieBoleta` char(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletapago`
--

CREATE TABLE `boletapago` (
  `idboletaPago` int(10) UNSIGNED NOT NULL,
  `idempleado` int(11) NOT NULL,
  `mes` char(20) DEFAULT NULL,
  `ano` char(10) DEFAULT NULL,
  `diasT` float(14,2) DEFAULT NULL,
  `totaldiast` float(14,2) DEFAULT NULL,
  `horasEx` float(14,2) DEFAULT NULL,
  `totalhorasEx` float(14,2) DEFAULT NULL,
  `totalBruto` float(14,2) DEFAULT NULL,
  `taoafp` float(14,2) DEFAULT NULL,
  `tinvsob` float(14,2) DEFAULT NULL,
  `tcomiafp` float(14,2) DEFAULT NULL,
  `tsnp` float(14,2) DEFAULT NULL,
  `total5t` float(14,2) DEFAULT NULL,
  `totalDcto` float(14,2) DEFAULT NULL,
  `sueldoPagar` float(14,2) DEFAULT NULL,
  `fechaPago` date DEFAULT NULL,
  `totalAporteE` float(14,2) DEFAULT NULL,
  `nroBoleta` char(20) DEFAULT NULL,
  `conceptoadicional` char(50) DEFAULT NULL,
  `importeconcepto` float(14,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletaservicio`
--

CREATE TABLE `boletaservicio` (
  `idboleta` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_emision_01` datetime NOT NULL,
  `firma_digital_36` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `tipo_documento_06` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion_07` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `codigo_tipo_15_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto_15_2` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_1` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_2` float(12,2) DEFAULT NULL,
  `codigo_tributo_18_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_tributo_18_4` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_internacional_18_5` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_total_23` float(12,2) NOT NULL,
  `codigo_leyenda_26_1` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion_leyenda_26_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_documento_25_1` int(11) DEFAULT NULL,
  `guia_remision_25` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_ubl_37` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_estructura_38` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_moneda_24` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tasa_igv` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipodocuCliente` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rucCliente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RazonSocial` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `comentario_baja` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdescuento` float(12,2) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcambio` float(14,2) DEFAULT NULL,
  `icbper` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `idcaja` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `montoi` float(14,2) DEFAULT NULL,
  `montof` decimal(14,2) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo1`
--

CREATE TABLE `catalogo1` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `un1001` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo1`
--

INSERT INTO `catalogo1` (`codigo`, `descripcion`, `un1001`) VALUES
('01', 'FACTURA', '380'),
('03', 'BOLETA', '346'),
('07', 'NOTACRE.', '381'),
('08', 'NOTADEB.', '383'),
('09', 'GUIA DE REMISIÓN REMITENTE', ''),
('12', 'TICKET \n\nDE MAQUINA REGISTRADORA', ''),
('13', 'DOCUMENTO EMITIDO POR BANCOS U OTROS', ''),
('18', 'SBS', ''),
('20', 'COTIZACION', NULL),
('30', 'DOCUMENTO DE COBRANZA', NULL),
('31', 'DOCUMENTOS EMITIDOS POR LAS AFP', ''),
('50', 'NOTA DE PEDIDO', NULL),
('56', 'GUIA REMISION TRANSPORTISTA', ''),
('90', 'BOLETA DE PAGO', NULL),
('99', 'OSERVICIO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo5`
--

CREATE TABLE `catalogo5` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unece5153` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo5`
--

INSERT INTO `catalogo5` (`id`, `codigo`, `descripcion`, `unece5153`, `estado`) VALUES
(1, '1000', 'IGV', 'VAT', 1),
(2, '1016', 'IVAP', NULL, 0),
(3, '2000', 'ISC', 'EXC', 0),
(4, '9996', 'GRA', 'GRA', 0),
(5, '9997', 'EXO', 'VAT', 1),
(6, '9998', 'INA', 'INA', 0),
(7, '9999', 'OTROS', 'OTH', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo6`
--

CREATE TABLE `catalogo6` (
  `id` int(11) NOT NULL,
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abrev` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo6`
--

INSERT INTO `catalogo6` (`id`, `codigo`, `descripcion`, `abrev`, `estado`) VALUES
(1, '0', 'DOC.TRIB.NO.DOM.SIN.RUC', NULL, 1),
(2, '1', 'DNI', NULL, 1),
(3, '4', 'CARNET DE EXTRANJERIA', NULL, 1),
(4, '6', 'RUC', NULL, 1),
(5, '7', 'PASAPORTE', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo7`
--

CREATE TABLE `catalogo7` (
  `id` int(11) NOT NULL,
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo7`
--

INSERT INTO `catalogo7` (`id`, `codigo`, `descripcion`, `estado`) VALUES
(1, '10', 'GRAVADO - OPERACION ONEROSA', 1),
(2, '11', 'GRAVADO - RETIRO POR PREMIO', 1),
(3, '12', 'GRAVADO - RETIRO POR \n\nDONACION', 1),
(4, '13', 'GRAVADO - RETIRO', 1),
(5, '14', 'GRAVADO - RETIRO POR PUBLICIDAD', 1),
(6, '15', 'GRAVADO - BONIFICACIONES', 1),
(7, '16', 'GRAVADO - RETIRO POR ENTREGA A TRABAJADORES', 1),
(8, '17', 'GRAVADO - IVAP', 1),
(9, '20', 'EXONERADO - OPERACION ONEROSA', 1),
(10, '21', 'EXONERADO - TRANSFERENCIA GRATUITA', 1),
(11, '30', 'INAFECTO - OPERACION ONEROSA', 1),
(12, '31', 'INAFECTO - RETIRO POR \n\nBONIFICACION', 1),
(13, '32', 'INAFECTO - RETIRO', 1),
(14, '33', 'INAFECTO - RETIRO POR MUESTRAS MEDICAS', 1),
(15, '34', 'INAFECTO - RETIRIO \n\nPOR CONVENIO COLECTIVO', 1),
(16, '35', 'INAFECTO - RETIRO POR PREMIO', 1),
(17, '36', 'INAFECTO - RETIRO POR PUBLICIDAD', 1),
(18, '40', 'EXPORTACION', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo8`
--

CREATE TABLE `catalogo8` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo9`
--

CREATE TABLE `catalogo9` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo9`
--

INSERT INTO `catalogo9` (`codigo`, `descripcion`) VALUES
('01', 'ANULACIÓN DE LA OPERACIÓN'),
('02', 'ANULACIÓN POR ERROR EN EL RUC'),
('03', 'CORRECCIÓN POR ERROR EN LA DESCRIPCIÓN'),
('04', 'DESCUENTO GLOBAL'),
('05', 'DESCUENTO POR ITEM'),
('06', 'DEVOLUCIÓN TOTAL'),
('07', 'DEVOLUCIÓN POR ITEM'),
('08', 'BONIFICACIÓN'),
('09', 'DISMININUCIÓN EN EL VALOR'),
('10', 'OTROS CONCEPTOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo10`
--

CREATE TABLE `catalogo10` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo10`
--

INSERT INTO `catalogo10` (`codigo`, `descripcion`) VALUES
('01', 'INTERESES POR MORA'),
('02', 'AUMENTO EN EL VALOR'),
('03', 'PENALIDADES/OTROS CONCEPTOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo11`
--

CREATE TABLE `catalogo11` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo12`
--

CREATE TABLE `catalogo12` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo14`
--

CREATE TABLE `catalogo14` (
  `codigo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo15`
--

CREATE TABLE `catalogo15` (
  `codigo` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo16`
--

CREATE TABLE `catalogo16` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo17`
--

CREATE TABLE `catalogo17` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo18`
--

CREATE TABLE `catalogo18` (
  `codigo` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `catalogo18`
--

INSERT INTO `catalogo18` (`codigo`, `descripcion`) VALUES
('01', 'TRANSPORTE PUBLICO'),
('02', 'TRANSPORTE PRIVADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo19`
--

CREATE TABLE `catalogo19` (
  `codigo` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo20`
--

CREATE TABLE `catalogo20` (
  `idcatalogo` int(10) UNSIGNED NOT NULL,
  `codigo` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf32 COLLATE utf32_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `catalogo20`
--

INSERT INTO `catalogo20` (`idcatalogo`, `codigo`, `descripcion`) VALUES
(1, '01', 'VENTA'),
(2, '14', 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR'),
(3, '02', 'COMPRA'),
(4, '04', 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA'),
(5, '18', 'TRASLADO EMISOR ITINERANTE CP'),
(6, '08', 'IMPORTACION'),
(7, '09', 'EXPORTACION'),
(8, '19', 'TRASLADO A ZONA PRIMARIA'),
(9, '13', 'OTROS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriainsumos`
--

CREATE TABLE `categoriainsumos` (
  `idcategoriai` int(10) UNSIGNED NOT NULL,
  `descripcionc` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_plato`
--

CREATE TABLE `categoria_plato` (
  `idcategoria` int(10) UNSIGNED NOT NULL,
  `nombreCategoria` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierrecaja`
--

CREATE TABLE `cierrecaja` (
  `idcierrecaja` int(11) NOT NULL,
  `fecha_cierre` date DEFAULT NULL,
  `total_caja` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `idciudad` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `iddepartamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`idciudad`, `nombre`, `iddepartamento`) VALUES
(1, 'BAGUA GRANDE', 1),
(2, 'CHACHAPOYAS', 1),
(3, 'CHIMBOTE', 2),
(4, 'HUARAZ', 2),
(5, 'CASMA', 2),
(6, 'ABANCAY', 3),
(7, 'ANDAHUAYLAS', 3),
(8, 'AREQUIPA', 4),
(9, 'CAMANA', 4),
(10, 'ISLAY', 4),
(11, 'AYACUCHO', 5),
(12, 'HUANTA', 5),
(13, 'CAJAMARCA', 6),
(14, 'JAÉN', 6),
(15, 'CUSCO', 7),
(16, 'CANCHIS', 7),
(17, 'LA CONVENCIÓN', 7),
(18, 'YAURI', 7),
(19, 'HUANCAVELICA', 8),
(20, 'HUÁNUCO', 9),
(21, 'LEONCIO PRADO', 9),
(22, 'ICA', 10),
(23, 'CHINCHA ALTA', 10),
(24, 'PISCO', 10),
(25, 'NAZCA', 10),
(26, 'HUANCAYO', 11),
(27, 'TARMA', 11),
(28, 'LA OROYA', 11),
(29, 'JAUJA', 11),
(30, 'TRUJILLO', 12),
(31, 'CHEPÉN', 12),
(32, 'GUADALUPE', 12),
(33, 'CASA GRANDE', 12),
(34, 'PACASMAYO', 12),
(35, 'HUAMACHUCO', 12),
(36, 'LAREDO', 12),
(37, 'MOCHE', 12),
(38, 'CHICLAYO', 13),
(39, 'LAMBAYEQUE', 13),
(40, 'FERREÑAFE', 13),
(41, 'TUMAN', 13),
(42, 'MONSEFU', 13),
(43, 'LIMA METROPOLITANA', 14),
(44, 'HUACHO', 14),
(45, 'HUARAL', 14),
(46, 'SAN VICENTE DE CAÑETE', 14),
(47, 'BARRANCA', 14),
(48, 'HUAURA', 14),
(49, 'PARAMONGA', 14),
(50, 'CHANCAY', 14),
(51, 'MALA', 14),
(52, 'SUPE', 14),
(53, 'IQUITOS', 15),
(54, 'YURIMAGUAS', 15),
(55, 'PUERTO MALDONADO', 16),
(56, 'ILO', 17),
(57, 'MOQUEGUA', 17),
(58, 'CERRO DE PASCO', 18),
(59, 'PIURA', 19),
(60, 'SULLANA', 19),
(61, 'TALARA', 19),
(62, 'CATACAOS', 19),
(63, 'PAITA', 19),
(64, 'CHULUCANAS', 19),
(65, 'SECHURA', 19),
(66, 'JULIACA', 20),
(67, 'PUNO', 20),
(68, 'AYAVIRI', 20),
(69, 'ILAVE', 20),
(70, 'TARAPOTO', 21),
(71, 'MOYOBAMBA', 21),
(72, 'RIOJA', 21),
(73, 'TACNA', 22),
(74, 'TUMBES', 23),
(75, 'PUCALPA', 24),
(76, 'S/C', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idcompra` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo_documento` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `serie` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `guia` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` float(12,2) NOT NULL,
  `igv` float(12,2) NOT NULL,
  `total` float(12,2) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `subtotal_$` float(14,2) DEFAULT NULL,
  `igv_$` float(14,2) DEFAULT NULL,
  `total_$` float(14,2) DEFAULT NULL,
  `tcambio` float(14,3) DEFAULT NULL,
  `moneda` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `idconfiguracion` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `porDesc` float(14,2) NOT NULL,
  `igv` float(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`idconfiguracion`, `idempresa`, `porDesc`, `igv`) VALUES
(1, 1, 0.00, 18.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correo`
--

CREATE TABLE `correo` (
  `idcorreo` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smtpsecure` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `mensaje` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correoavisos` char(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `idcotizacion` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `serienota` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `moneda` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaemision` datetime NOT NULL,
  `tipocotizacion` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtotal` float(14,2) NOT NULL,
  `impuesto` float(14,2) NOT NULL,
  `total` float(14,2) NOT NULL,
  `observacion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `vendedor` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipocambio` float(14,2) DEFAULT NULL,
  `fechavalidez` date DEFAULT NULL,
  `nrofactura` char(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas`
--

CREATE TABLE `cuotas` (
  `idcuota` int(10) UNSIGNED NOT NULL,
  `tipocomprobante` char(2) NOT NULL,
  `idcomprobante` int(11) NOT NULL,
  `ncuota` int(11) NOT NULL,
  `montocuota` decimal(12,2) DEFAULT NULL,
  `fechacuota` date NOT NULL,
  `estadocuota` tinyint(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `iddepartamento` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`iddepartamento`, `nombre`) VALUES
(1, 'AMAZONAS'),
(2, 'ANCASH'),
(3, 'APURIMAC'),
(4, 'AREQUIPA'),
(5, 'AYACUCHO'),
(6, 'CAJAMARCA'),
(7, 'CUSCO'),
(8, 'HUANCAVELICA'),
(9, 'HUANUCO'),
(10, 'ICA'),
(11, 'JUNÍN'),
(12, 'LA LIBERTAD'),
(13, 'LAMBAYEQUE'),
(14, 'LIMA'),
(15, 'LORETO'),
(16, 'MADRE DE DIOS'),
(17, 'MOQUEGUA'),
(18, 'PASCO'),
(19, 'PIURA'),
(20, 'PUNO'),
(21, 'SAN MARTÍN'),
(22, 'TACNA'),
(23, 'TUMBES'),
(24, 'UCAYALI'),
(25, 'S/D');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_articulo_almacen`
--

CREATE TABLE `detalle_articulo_almacen` (
  `iddetalle` int(10) UNSIGNED NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `idalmacen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_articulo_cotizacion`
--

CREATE TABLE `detalle_articulo_cotizacion` (
  `id` int(11) NOT NULL,
  `idcotizacion` int(11) NOT NULL,
  `iditem` int(11) NOT NULL,
  `codigo` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float(14,2) NOT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `norden` int(11) DEFAULT NULL,
  `valorunitario` float(14,5) NOT NULL,
  `valorventa` float(14,2) NOT NULL,
  `igvvalorventa` float(14,2) DEFAULT NULL,
  `igvitem` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_boleta_producto`
--

CREATE TABLE `detalle_boleta_producto` (
  `iddetalle` int(11) NOT NULL,
  `idboleta` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `numero_orden_item_29` int(11) DEFAULT NULL,
  `cantidad_item_12` float(12,2) NOT NULL,
  `codigo_precio_14_1` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `precio_uni_item_14_2` decimal(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_1` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_3` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_4` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_5` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_6` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `igv_item` float(12,2) NOT NULL,
  `valor_uni_item_31` float(12,5) NOT NULL,
  `valor_venta_item_32` float(12,2) NOT NULL,
  `dcto_item` float(12,2) DEFAULT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `umedida` char(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_boleta_producto_ser`
--

CREATE TABLE `detalle_boleta_producto_ser` (
  `iddetalle` int(11) NOT NULL,
  `idboleta` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `numero_orden_item_29` int(11) DEFAULT NULL,
  `cantidad_item_12` float(12,2) NOT NULL,
  `codigo_precio_14_1` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `precio_uni_item_14_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_1` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_3` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_4` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_5` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_6` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `igv_item` float(12,2) NOT NULL,
  `valor_uni_item_31` float(12,5) NOT NULL,
  `valor_venta_item_32` float(12,2) NOT NULL,
  `dcto_item` float(12,2) DEFAULT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra_producto`
--

CREATE TABLE `detalle_compra_producto` (
  `iddetalle` int(11) NOT NULL,
  `idcompra` int(11) DEFAULT NULL,
  `idarticulo` int(11) DEFAULT NULL,
  `valor_unitario` float(12,3) DEFAULT NULL,
  `cantidad` float(12,2) DEFAULT NULL,
  `subtotal` float(12,2) DEFAULT NULL,
  `valor_unitario_$` float(14,2) DEFAULT NULL,
  `subtotal_$` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_doccobranza`
--

CREATE TABLE `detalle_doccobranza` (
  `id` int(11) NOT NULL,
  `iddoccobranza` int(11) NOT NULL,
  `iditem` int(11) NOT NULL,
  `codigo` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float(14,2) NOT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `norden` int(11) DEFAULT NULL,
  `igvvalorventa` float(14,2) DEFAULT NULL,
  `igvitem` float(14,2) DEFAULT NULL,
  `vuniitem` float(14,5) DEFAULT NULL,
  `valorventa` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_fac_art`
--

CREATE TABLE `detalle_fac_art` (
  `iddetalle` int(11) NOT NULL,
  `idfactura` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `numero_orden_item_33` int(11) NOT NULL,
  `cantidad_item_12` float(12,2) NOT NULL,
  `codigo_precio_15_1` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `precio_venta_item_15_2` decimal(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_1` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_3` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_4` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_5` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_6` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `igv_item` float(12,2) NOT NULL,
  `valor_uni_item_14` float(12,5) NOT NULL,
  `valor_venta_item_21` float(12,2) NOT NULL,
  `dcto_item` float(12,2) DEFAULT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `umedida` char(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_fac_art_ser`
--

CREATE TABLE `detalle_fac_art_ser` (
  `iddetalle` int(11) NOT NULL,
  `idfactura` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `numero_orden_item_33` int(11) NOT NULL,
  `cantidad_item_12` float(12,2) NOT NULL,
  `codigo_precio_15_1` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `precio_venta_item_15_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_1` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_16_3` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_4` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_5` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_item_16_6` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `igv_item` float(12,2) NOT NULL,
  `valor_uni_item_14` float(12,5) NOT NULL,
  `valor_venta_item_21` float(12,2) NOT NULL,
  `dcto_item` float(12,2) DEFAULT NULL,
  `descdet` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_item_liquidacion`
--

CREATE TABLE `detalle_item_liquidacion` (
  `iddetalle` int(10) UNSIGNED NOT NULL,
  `idliquidacion` int(11) DEFAULT NULL,
  `iditem` int(11) DEFAULT NULL,
  `cantidad` decimal(14,2) NOT NULL,
  `valorunitario` decimal(14,2) NOT NULL,
  `igv` decimal(14,2) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_notacd_art`
--

CREATE TABLE `detalle_notacd_art` (
  `iddetalle` int(11) NOT NULL,
  `idnotacd` int(11) DEFAULT NULL,
  `idarticulo` int(11) DEFAULT NULL,
  `nro_orden` int(11) DEFAULT NULL,
  `cantidad` float(12,2) NOT NULL,
  `precio_venta` float(12,2) NOT NULL,
  `igv` float(12,2) NOT NULL,
  `valor_unitario` float(12,5) NOT NULL,
  `valor_venta` float(12,2) NOT NULL,
  `aigv` varchar(20) DEFAULT NULL,
  `codtrib` varchar(20) DEFAULT NULL,
  `nomtrib` varchar(20) DEFAULT NULL,
  `coditrib` varchar(20) DEFAULT NULL,
  `numorden` varchar(20) DEFAULT NULL,
  `descripitem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_notapedido_producto`
--

CREATE TABLE `detalle_notapedido_producto` (
  `iddetalle` int(11) NOT NULL,
  `idboleta` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `numero_orden_item_29` int(11) DEFAULT NULL,
  `cantidad_item_12` float(12,2) NOT NULL,
  `codigo_precio_14_1` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `precio_uni_item_14_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_1` float(12,2) DEFAULT NULL,
  `afectacion_igv_item_monto_27_2` float(12,2) DEFAULT NULL,
  `afectacion_igv_3` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_4` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_5` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `afectacion_igv_6` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `igv_item` float(12,2) NOT NULL,
  `valor_uni_item_31` float(12,5) NOT NULL,
  `valor_venta_item_32` float(12,2) NOT NULL,
  `dcto_item` float(12,2) DEFAULT NULL,
  `descdet` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `umedida` char(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ordenservicio_articulo`
--

CREATE TABLE `detalle_ordenservicio_articulo` (
  `iddetalle` int(11) NOT NULL,
  `idorden` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad` float(12,2) NOT NULL,
  `valorcosto` float(12,5) NOT NULL,
  `totalunitario` float(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_plato_pedido`
--

CREATE TABLE `detalle_plato_pedido` (
  `iddetalle` int(10) UNSIGNED NOT NULL,
  `idplato` int(11) NOT NULL,
  `idpedido` int(11) NOT NULL,
  `norden` int(11) DEFAULT NULL,
  `cantidad` float(14,2) DEFAULT NULL,
  `precioitem` float(14,2) DEFAULT NULL,
  `valoruniitem` float(14,4) DEFAULT NULL,
  `igvitem` float(14,2) DEFAULT NULL,
  `valorventaitem` float(14,2) DEFAULT NULL,
  `igvvaloritem` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_servicio_cotizacion`
--

CREATE TABLE `detalle_servicio_cotizacion` (
  `id` int(11) NOT NULL,
  `idcotizacion` int(11) NOT NULL,
  `iditem` int(11) NOT NULL,
  `codigo` char(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_tablaxml_comprobante`
--

CREATE TABLE `detalle_tablaxml_comprobante` (
  `iddetalle` int(10) UNSIGNED NOT NULL,
  `idtablaxml` int(11) NOT NULL,
  `idcomprobante` int(11) NOT NULL,
  `tipocomprobante` char(4) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_usuario_numeracion`
--

CREATE TABLE `detalle_usuario_numeracion` (
  `iddetalle` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idnumeracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalle_usuario_numeracion`
--

INSERT INTO `detalle_usuario_numeracion` (`iddetalle`, `idusuario`, `idnumeracion`) VALUES
(12, 1, 1),
(13, 1, 2),
(14, 1, 3),
(15, 1, 4),
(16, 1, 5),
(17, 1, 6),
(18, 1, 7),
(19, 1, 8),
(20, 1, 9),
(21, 1, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_usuario_sesion`
--

CREATE TABLE `detalle_usuario_sesion` (
  `iddetalle` int(10) UNSIGNED NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `tcomprobante` char(5) COLLATE utf32_unicode_ci DEFAULT NULL,
  `idcomprobante` int(11) DEFAULT NULL,
  `fechahora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_usuario_sesion`
--

INSERT INTO `detalle_usuario_sesion` (`iddetalle`, `idusuario`, `tcomprobante`, `idcomprobante`, `fechahora`) VALUES
(1, 1, '', 0, '2023-09-24 15:24:17'),
(2, 1, '', 0, '2023-09-24 15:28:37'),
(3, 1, '', 0, '2023-09-24 15:38:49'),
(4, 1, '', 0, '2023-10-10 10:37:46'),
(5, 1, '', 0, '2023-10-28 19:35:02'),
(6, 1, '', 0, '2023-10-28 20:05:04'),
(7, 1, '', 0, '2023-10-28 22:07:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

CREATE TABLE `distrito` (
  `iddistrito` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_postal` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idciudad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `distrito`
--

INSERT INTO `distrito` (`iddistrito`, `nombre`, `codigo_postal`, `idciudad`) VALUES
(1, 'BAGUA GRANDE', NULL, 1),
(2, 'CHACHAPOYAS', NULL, 2),
(3, 'CHIMBOTE', NULL, 3),
(4, 'COISHCO', NULL, 3),
(5, 'NUEVO CHIMBOTE', NULL, 3),
(6, 'HUARAZ', NULL, 4),
(7, 'INDEPENDENCIA', NULL, 4),
(8, 'CASMA', NULL, 5),
(9, 'ABANCAY', NULL, 6),
(10, 'TAMBURCO', NULL, 6),
(11, 'ANDAHUAYLAS', NULL, 7),
(12, 'SAN JERÓNIMO', NULL, 7),
(13, 'TALAVERA', NULL, 7),
(14, 'AREQUIPA', NULL, 8),
(15, 'ALTO SELVA ALEGRE', NULL, 8),
(16, 'CAYMA', NULL, 8),
(17, 'CERRO COLORADO', NULL, 8),
(18, 'JACOBO HUNTER', NULL, 8),
(19, 'MARIANO MELGAR', NULL, 8),
(20, 'MIRAFLORES', NULL, 8),
(21, 'PAUCARPATA', NULL, 8),
(22, 'SABANDÍA', NULL, 8),
(23, 'SACHACA', NULL, 8),
(24, 'SOCABAYA', NULL, 8),
(25, 'TIABYA', NULL, 8),
(26, 'YANAHUARA', NULL, 8),
(27, 'JOSE LUIS BUSTAMANTE', NULL, 8),
(28, 'CAMANÁ', NULL, 9),
(29, 'JOSE MARÍA QUIMPER', NULL, 9),
(30, 'SAMUEL PASTOR', NULL, 9),
(31, 'MOLLENDO', NULL, 10),
(32, 'AYACUCHO', NULL, 11),
(33, 'CARMEN ALTO', NULL, 11),
(34, 'SAN JUAN BAUTISTA', NULL, 11),
(35, 'JESUS NAZARENO', NULL, 11),
(36, 'HUANTA', NULL, 12),
(37, 'CAJAMARCA', NULL, 13),
(38, 'LOS BAÑOS DEL INCA', NULL, 13),
(39, 'JAÉN', NULL, 14),
(40, 'CUSCO', NULL, 15),
(41, 'SAN JERÓNIMO', NULL, 15),
(42, 'SAN SEBASTIÁN', NULL, 15),
(43, 'SANTIAGO', NULL, 15),
(44, 'WANCHAQ', NULL, 15),
(45, 'SICUANI', NULL, 16),
(46, 'SANTA ANA', NULL, 17),
(47, 'ESPINAR', NULL, 18),
(48, 'HUANCAVELICA', NULL, 19),
(49, 'ASCENCIÓN', NULL, 19),
(50, 'HUÁNUCO', NULL, 20),
(51, 'AMARILIS', NULL, 20),
(52, 'PILLCO MARCA', NULL, 20),
(53, 'RUPA-RUPA', NULL, 21),
(54, 'ICA', NULL, 22),
(55, 'LA TINGUIÑA', NULL, 22),
(56, 'PARCONA', NULL, 22),
(57, 'SUBTANJALLA', NULL, 2),
(58, 'CHINCHA ALTA', NULL, 23),
(59, 'GROCIO PRADO', NULL, 23),
(60, 'PUEBLO NUEVO', NULL, 23),
(61, 'SUNAMPE', NULL, 23),
(62, 'PISCO', NULL, 24),
(63, 'SAN ANDRÉS', NULL, 24),
(64, 'SAN CLEMENTE', NULL, 24),
(65, 'TÚPAC AMARU INCA', NULL, 24),
(66, 'NAZCA', NULL, 25),
(67, 'VISTA ALEGRE', NULL, 25),
(68, 'HUANCAYO', NULL, 26),
(69, 'CHILCA', NULL, 26),
(70, 'EL TAMBO', NULL, 26),
(71, 'TARMA', NULL, 27),
(72, 'LA OROYA', NULL, 28),
(73, 'SANTA ROSA DE SACCO', NULL, 28),
(74, 'JAUJA', NULL, 29),
(75, 'TRUJILLO', NULL, 30),
(76, 'EL PORVENIR', NULL, 3),
(77, 'FLORENCIA DE MORA', NULL, 30),
(78, 'LA ESPERANZA', NULL, 30),
(79, 'VICTOR LARCO HERRERA', NULL, 30),
(80, 'CHEPÉN', NULL, 31),
(81, 'GUADALUPE', NULL, 32),
(82, 'CASA GRANDE', NULL, 33),
(83, 'PACASMAYO', NULL, 34),
(84, 'HUAMACHUCO', NULL, 35),
(85, 'LAREDO', NULL, 36),
(86, 'MOCHE', NULL, 37),
(87, 'CHICLAYO', NULL, 38),
(88, 'JOSE LEONARDO ORTIZ', NULL, 38),
(89, 'LA VICTORIA', NULL, 38),
(90, 'PIMENTEL', NULL, 38),
(91, 'LAMBAYEQUE', NULL, 39),
(92, 'FERREÑAFE', NULL, 40),
(93, 'PUEBLO NUEVO', NULL, 40),
(94, 'TUMAN', NULL, 41),
(95, 'MONSEFU', NULL, 42),
(96, 'LIMA', NULL, 43),
(97, 'ANCÓN', NULL, 43),
(98, 'ATE', NULL, 43),
(99, 'BARRANCO', NULL, 43),
(100, 'BREÑA', NULL, 43),
(101, 'CARABAYLLO', NULL, 43),
(102, 'CHACLACAYO', NULL, 43),
(103, 'CHORRILLOS', NULL, 43),
(104, 'CIENEGUILLA', NULL, 43),
(105, 'COMAS', NULL, 43),
(106, 'EL AGUSTINO', NULL, 43),
(107, 'INDEPENDENCIA', NULL, 43),
(108, 'JESÚS MARIA', NULL, 43),
(109, 'LA MOLINA', NULL, 43),
(110, 'LA VICTORIA', NULL, 43),
(111, 'LINCE', NULL, 43),
(112, 'LOS OLIVOS', NULL, 43),
(113, 'LURIGANCHO', NULL, 43),
(114, 'LURIN', NULL, 43),
(115, 'MAGDALENA DEL MAR', NULL, 43),
(116, 'MAGDALENA VIEJA', NULL, 43),
(117, 'MIRAFLORES', NULL, 43),
(118, 'PACHACAMAC', NULL, 43),
(119, 'PUCUSANA', NULL, 43),
(120, 'PUENTE PIEDRA', NULL, 43),
(121, 'PUNTA HERMOSA', NULL, 43),
(122, 'PUNTA NEGRA', NULL, 43),
(123, 'RÍMAC', NULL, 43),
(124, 'SAN BARTOLO', NULL, 43),
(125, 'SAN BORJA', NULL, 43),
(126, 'SAN ISIDRO', NULL, 43),
(127, 'SAN JUAN DE LURIGANCHO', NULL, 43),
(128, 'SAN JUAN DE MIRAFLORES', NULL, 43),
(129, 'SAN LUIS', NULL, 43),
(130, 'SAN MARTÍN DE PORRES', NULL, 43),
(131, 'SAN MIGUEL', NULL, 43),
(132, 'SANTA ANITA', NULL, 43),
(133, 'SANTA MARIA DEL MAR', NULL, 43),
(134, 'SANTA ROSA', NULL, 43),
(135, 'SANTIAGO DE SURCO', NULL, 43),
(136, 'SURQUILLO', NULL, 43),
(137, 'VILLA EL SALVADOR', NULL, 43),
(138, 'VILLA MARÍA DEL TRIUNFO', NULL, 43),
(139, 'CALLAO', NULL, 43),
(140, 'BELLAVISTA', NULL, 43),
(141, 'CARMEN DE LA LEGUA REYNOSO', NULL, 43),
(142, 'LA PERLA', NULL, 43),
(143, 'LA PUNTA', NULL, 43),
(144, 'VENTANILLA', NULL, 43),
(145, 'HUACHO', NULL, 44),
(146, 'CALETA DE CARQUÍN', NULL, 44),
(147, 'HUALMAY', NULL, 44),
(148, 'HUARAL', NULL, 45),
(149, 'SAN VICENTE DE CAÑETA IMPERIAL', NULL, 46),
(150, 'BARRANCA', NULL, 47),
(151, 'HUAURA', NULL, 48),
(152, 'SANTA MARÍA', NULL, 48),
(153, 'PARAMONGA', NULL, 49),
(154, 'PATIVILCA', NULL, 49),
(155, 'CHANCAY', NULL, 50),
(156, 'MALA', NULL, 51),
(157, 'NUEVO IMPERIAL', NULL, 51),
(158, 'SUPE', NULL, 52),
(159, 'SUPE PUERTO', NULL, 52),
(160, 'IQUITOS', NULL, 53),
(161, 'PUNCHANA', NULL, 53),
(162, 'BELÉN', NULL, 53),
(163, 'SAN JUAN BAUTISTA', NULL, 53),
(164, 'YURIMAGUAS', NULL, 54),
(165, 'TAMBOPATA', NULL, 55),
(166, 'ILO', NULL, 56),
(167, 'MOQUEGUA', NULL, 57),
(168, 'SAMEGUA', NULL, 57),
(169, 'CHAUPIMARCA', NULL, 58),
(170, 'SIMON BOLIVAR', NULL, 58),
(171, 'YANACANCHA', NULL, 58),
(172, 'PIURA', NULL, 59),
(173, 'CASTILLA', NULL, 59),
(174, 'SULLANA', NULL, 60),
(175, 'BELLAVISTA', NULL, 60),
(176, 'PARINAS', NULL, 61),
(177, 'CATACAOS', NULL, 62),
(178, 'PAITA', NULL, 63),
(179, 'CHULUCANAS', NULL, 64),
(180, 'SECHURA', NULL, 65),
(181, 'JULIACA', NULL, 66),
(182, 'PUNO', NULL, 67),
(183, 'AYAVIRI', NULL, 68),
(184, 'ILAVE', NULL, 69),
(185, 'TARAPOTO', NULL, 70),
(186, 'LA BANDA DE SHILCAYO', NULL, 70),
(187, 'MORALES', NULL, 70),
(188, 'MOYOBAMBA', NULL, 71),
(189, 'RIOJA', NULL, 72),
(190, 'TACNA', NULL, 73),
(191, 'ALTO DE ALIANZA', NULL, 73),
(192, 'CIUDAD NUEVA', NULL, 73),
(193, 'POCOLLAY', NULL, 73),
(194, 'CORONEL GREGORIO ALBARRACIN LANCHIPA', NULL, 73),
(195, 'TUMBES', NULL, 74),
(196, 'CALLARIA', NULL, 75),
(197, 'YARINACHOCHA', NULL, 75),
(198, 'S/D', 'SCP', 76);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doccobranza`
--

CREATE TABLE `doccobranza` (
  `idccobranza` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `condicion` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `fechaemision` datetime NOT NULL,
  `serienumero` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `tarifa` float(14,2) NOT NULL,
  `neta` float(14,2) NOT NULL,
  `igv` float(14,2) NOT NULL,
  `otros` float(14,2) NOT NULL,
  `deduccion` float(14,2) NOT NULL,
  `total` float(14,2) NOT NULL,
  `observacion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `tcambio` float(14,2) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipo_moneda` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipodoccobranza` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nfactura` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleadoboleta`
--

CREATE TABLE `empleadoboleta` (
  `idempleado` int(10) UNSIGNED NOT NULL,
  `idempresab` int(11) NOT NULL,
  `nombresE` varchar(100) DEFAULT NULL,
  `apellidosE` varchar(100) DEFAULT NULL,
  `fechaingreso` date DEFAULT NULL,
  `ocupacion` char(50) DEFAULT NULL,
  `tiporemuneracion` char(20) DEFAULT NULL,
  `dni` char(10) DEFAULT NULL,
  `autogenessa` char(100) DEFAULT NULL,
  `cusspp` char(50) DEFAULT NULL,
  `sueldoBruto` float(14,2) DEFAULT NULL,
  `horasT` float(14,2) DEFAULT NULL,
  `asigFam` float(14,2) DEFAULT NULL,
  `trabNoct` float(14,2) DEFAULT NULL,
  `idtipoSeguro` int(11) NOT NULL,
  `nombreSeguro` char(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` int(11) NOT NULL,
  `nombre_razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nombre_comercial` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `domicilio_fiscal` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `numero_ruc` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono1` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono2` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `web` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `webconsul` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nresolucion` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ubigueo` char(5) COLLATE utf8_unicode_ci DEFAULT '0000',
  `codubigueo` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ciudad` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `distrito` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interior` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigopais` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuentacci1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuentacci2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuentacci3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuentacci4` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipoimpresion` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `essalud` float(14,2) DEFAULT NULL,
  `seriebolteapago` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `textolibre` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observacion` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`idempresa`, `nombre_razon_social`, `nombre_comercial`, `domicilio_fiscal`, `numero_ruc`, `telefono1`, `telefono2`, `correo`, `web`, `webconsul`, `logo`, `nresolucion`, `ubigueo`, `codubigueo`, `ciudad`, `distrito`, `interior`, `codigopais`, `cuenta1`, `banco1`, `cuenta2`, `banco2`, `cuenta3`, `banco3`, `cuenta4`, `banco4`, `cuentacci1`, `cuentacci2`, `cuentacci3`, `cuentacci4`, `tipoimpresion`, `essalud`, `seriebolteapago`, `textolibre`, `observacion`) VALUES
(1, 'EMPRESA WFACX', 'FACX', 'LIMA - LIMA', '10459585961', '917575562', '01 000000', 'soporte@wfacx.com', 'https://www.wfacx.com/', 'https://wfacx/cpeconsultas', '1658522609.png', NULL, '0000', '140101', 'LIMA', 'LIMA', 'LIMA', 'PE', '', '', '', '', '', '', '', '', '', '', '', '', '00', NULL, NULL, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enviocorreo`
--

CREATE TABLE `enviocorreo` (
  `id` int(11) NOT NULL,
  `numero_documento` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comprobante` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idfactura` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_emision_01` datetime NOT NULL,
  `firmadigital_02` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `tipo_documento_07` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion_08` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idcliente` int(11) NOT NULL,
  `total_operaciones_gravadas_codigo_18_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_operaciones_gravadas_monto_18_2` float(12,2) DEFAULT NULL,
  `sumatoria_igv_22_1` float(12,2) NOT NULL,
  `sumatoria_igv_22_2` float(12,2) DEFAULT NULL,
  `codigo_tributo_22_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_tributo_22_4` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_internacional_22_5` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_total_venta_27` decimal(12,2) DEFAULT NULL,
  `tipo_documento_29_1` int(11) DEFAULT NULL,
  `guia_remision_29_2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_leyenda_31_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion_leyenda_31_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_ubl_36` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `version_estructura_37` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_moneda_28` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `tasa_igv` float(12,2) DEFAULT '0.18',
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipodocuCliente` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rucCliente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RazonSocial` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `comentario_baja` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdescuento` float(12,2) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcambio` float(14,2) DEFAULT NULL,
  `tipopago` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nroreferencia` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipagado` float(14,2) DEFAULT NULL,
  `saldo` float(14,2) DEFAULT NULL,
  `CodigoRptaSunat` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DetalleSunat` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icbper` float(14,2) DEFAULT NULL,
  `tipofactura` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocompra` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `margengan` float(14,2) DEFAULT NULL,
  `hashc` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formapago` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montofpago` float(14,2) DEFAULT NULL,
  `monedafpago` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccuotas` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechavecredito` date DEFAULT NULL,
  `montocuota` float(14,2) DEFAULT NULL,
  `otroscargos` float(14,2) DEFAULT '0.00',
  `transferencia` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotransferencia` float(14,2) DEFAULT NULL,
  `tarjetadc` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotarjetadc` float(14,2) DEFAULT NULL,
  `ntrans` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechavenc` date DEFAULT NULL,
  `retencion` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `porcret` decimal(2,2) DEFAULT NULL,
  `efectivo` decimal(14,2) DEFAULT NULL,
  `visa` decimal(14,2) DEFAULT NULL,
  `yape` decimal(14,2) DEFAULT NULL,
  `plin` decimal(14,2) DEFAULT NULL,
  `mastercard` decimal(14,2) DEFAULT NULL,
  `deposito` decimal(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturaservicio`
--

CREATE TABLE `facturaservicio` (
  `idfactura` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_emision_01` datetime NOT NULL,
  `firmadigital_02` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `tipo_documento_07` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion_08` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idcliente` int(11) NOT NULL,
  `total_operaciones_gravadas_codigo_18_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_operaciones_gravadas_monto_18_2` float(12,2) DEFAULT NULL,
  `sumatoria_igv_22_1` float(12,2) NOT NULL,
  `sumatoria_igv_22_2` float(12,2) DEFAULT NULL,
  `codigo_tributo_22_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_tributo_22_4` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_internacional_22_5` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_total_venta_27` float(12,2) NOT NULL,
  `tipo_documento_29_1` int(11) DEFAULT NULL,
  `guia_remision_29_2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_leyenda_31_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion_leyenda_31_2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_ubl_36` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `version_estructura_37` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_moneda_28` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `tasa_igv` float(12,2) DEFAULT '0.18',
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipodocuCliente` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rucCliente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RazonSocial` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idguia` int(11) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `comentario_baja` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdescuento` float(12,2) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icbper` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia`
--

CREATE TABLE `familia` (
  `idfamilia` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `familia`
--

INSERT INTO `familia` (`idfamilia`, `descripcion`, `estado`) VALUES
(1, 'GENERAL', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ftpparam`
--

CREATE TABLE `ftpparam` (
  `ftphost` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ftpusername` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ftppassword` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia`
--

CREATE TABLE `guia` (
  `idguia` int(11) NOT NULL,
  `snumero` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pllegada` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `destinatario` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nruc` char(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ppartida` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechat` date DEFAULT NULL,
  `ncomprobante` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ocompra` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `motivo` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idcomprobante` int(11) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `comprobante` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `fechatraslado` date DEFAULT NULL,
  `rsocialtransportista` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ructran` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `placa` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cinc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `container` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nlicencia` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ncoductor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npedido` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vendedor` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `costmt` float(14,2) DEFAULT NULL,
  `fechacomprobante` date DEFAULT NULL,
  `observaciones` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipocomprefe` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pesobruto` float(14,2) DEFAULT NULL,
  `umedidapbruto` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codtipotras` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipodoctrans` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dniconductor` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodigoRptaSunat` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hashc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `ubigeopartida` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ubigeollegada` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DetalleSunat` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresocaja`
--

CREATE TABLE `ingresocaja` (
  `idingreso` int(11) NOT NULL,
  `idcaja` int(11) NOT NULL,
  `concepto` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto` float(14,2) DEFAULT NULL,
  `tipo` char(20) COLLATE utf8_unicode_ci DEFAULT 'INGRESO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `idinsumo` int(10) UNSIGNED NOT NULL,
  `tipodato` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `idcategoriai` int(11) NOT NULL,
  `fecharegistro` date NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `valor` float(14,5) NOT NULL,
  `igv` float(14,2) NOT NULL,
  `gasto` float(14,2) NOT NULL,
  `ingreso` float(14,2) NOT NULL,
  `saldo_inicial` float(14,2) DEFAULT NULL,
  `documnIDE` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numDOCIDE` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acredor` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `itemliquidacion`
--

CREATE TABLE `itemliquidacion` (
  `iditemli` int(10) UNSIGNED NOT NULL,
  `tipoitem` char(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  `aerolinea` char(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  `nvuelo` char(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  `fechainicio` datetime DEFAULT NULL,
  `fechafin` datetime DEFAULT NULL,
  `umedida` char(10) COLLATE utf32_unicode_ci DEFAULT NULL,
  `destino` char(50) COLLATE utf32_unicode_ci NOT NULL,
  `descripcion` varchar(300) COLLATE utf32_unicode_ci NOT NULL,
  `nombreitem` char(100) COLLATE utf32_unicode_ci NOT NULL,
  `valoruni` decimal(14,2) NOT NULL,
  `preciouni` decimal(14,2) NOT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

CREATE TABLE `kardex` (
  `idkardex` int(11) NOT NULL,
  `idcomprobante` int(11) DEFAULT NULL,
  `idarticulo` int(11) DEFAULT NULL,
  `transaccion` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `tipo_documento` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_doc` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cantidad` float(14,2) DEFAULT NULL,
  `costo_1` float(12,5) DEFAULT NULL,
  `unidad_medida` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `saldo_final` float(14,2) DEFAULT NULL,
  `costo_2` float(14,2) DEFAULT NULL,
  `valor_final` float(14,2) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `tcambio` float(14,5) DEFAULT NULL,
  `moneda` float(14,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `liquidacion`
--

CREATE TABLE `liquidacion` (
  `idliquidacion` int(10) UNSIGNED NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `fechaemision` datetime NOT NULL,
  `condiciones` varchar(500) COLLATE utf32_unicode_ci NOT NULL,
  `observacion` varchar(500) COLLATE utf32_unicode_ci NOT NULL,
  `observaciontarifa` varchar(500) COLLATE utf32_unicode_ci NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `igv` decimal(14,2) NOT NULL,
  `total` decimal(14,2) NOT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `margenganancia`
--

CREATE TABLE `margenganancia` (
  `idmargeng` int(10) UNSIGNED NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `mes` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `ano` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `totalventas` float(14,2) DEFAULT NULL,
  `devolucionventas` float(14,2) DEFAULT NULL,
  `totalcompras` float(14,2) DEFAULT NULL,
  `devolucioncompras` float(14,2) DEFAULT NULL,
  `ganancia` float(14,2) DEFAULT NULL,
  `porcentaje` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `idmesa` int(10) UNSIGNED NOT NULL,
  `nromesa` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notacd`
--

CREATE TABLE `notacd` (
  `idnota` int(11) NOT NULL,
  `nombre` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numeroserienota` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `codigo_nota` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `codtiponota` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc_motivo` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_doc_mod` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `serie_numero` char(13) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_doc_ide` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `numero_doc_ide` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `razon_social` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_moneda` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `sum_ot_car` float(12,2) NOT NULL,
  `total_val_venta_og` float(12,2) NOT NULL,
  `total_val_venta_oi` float(12,2) NOT NULL,
  `total_val_venta_oe` float(12,2) NOT NULL,
  `sum_igv` float(12,2) NOT NULL,
  `sum_isc` float(12,2) NOT NULL,
  `sum_ot` float(12,2) NOT NULL,
  `importe_total` float(12,2) DEFAULT NULL,
  `estado` char(2) COLLATE utf8_unicode_ci DEFAULT '1',
  `idcomprobante` int(11) DEFAULT NULL,
  `fechacomprobante` datetime NOT NULL,
  `adicional` float(12,2) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `difComprobante` char(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icbper` float(14,2) DEFAULT NULL,
  `CodigoRptaSunat` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DetalleSunat` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `motivonota` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcambio` float(14,2) DEFAULT NULL,
  `tiponotacd` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `hashc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comentario_baja` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formapago` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montofpago` float(14,2) DEFAULT NULL,
  `monedafpago` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccuotas` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montocuota` float(14,2) DEFAULT NULL,
  `fechavecredito` date DEFAULT NULL,
  `transferencia` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotransferencia` float(14,2) DEFAULT NULL,
  `tarjetadc` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotarjetadc` float(14,2) DEFAULT NULL,
  `descripitem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `efectivo` decimal(10,2) DEFAULT '0.00',
  `visa` decimal(10,2) DEFAULT '0.00',
  `yape` decimal(10,2) DEFAULT '0.00',
  `plin` decimal(10,2) DEFAULT '0.00',
  `mastercard` decimal(10,2) DEFAULT '0.00',
  `deposito` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notapedido`
--

CREATE TABLE `notapedido` (
  `idboleta` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `fecha_emision_01` datetime NOT NULL,
  `firma_digital_36` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `tipo_documento_06` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numeracion_07` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `codigo_tipo_15_1` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto_15_2` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_1` float(12,2) DEFAULT NULL,
  `sumatoria_igv_18_2` float(12,2) DEFAULT NULL,
  `codigo_tributo_18_3` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_tributo_18_4` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_internacional_18_5` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `importe_total_23` float(12,2) NOT NULL,
  `codigo_leyenda_26_1` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion_leyenda_26_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_documento_25_1` int(11) DEFAULT NULL,
  `guia_remision_25` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_ubl_37` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version_estructura_38` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_moneda_24` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tasa_igv` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipodocuCliente` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rucCliente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RazonSocial` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `comentario_baja` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdescuento` float(12,2) DEFAULT NULL,
  `vendedorsitio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icbper` float(14,2) DEFAULT NULL,
  `tiponota` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tcambio` float(14,2) DEFAULT NULL,
  `formapago` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montofpago` float(14,2) DEFAULT NULL,
  `monedafpago` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccuotas` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montocuota` float(14,2) DEFAULT NULL,
  `fechavecredito` date DEFAULT NULL,
  `transferencia` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotransferencia` float(14,2) DEFAULT NULL,
  `tarjetadc` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `montotarjetadc` float(14,2) DEFAULT NULL,
  `ntrans` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ncotizacion` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ambtra` float(14,2) DEFAULT NULL,
  `adelanto` float(14,2) NOT NULL,
  `faltante` float(14,2) NOT NULL,
  `efectivo` decimal(14,2) DEFAULT NULL,
  `visa` decimal(14,2) DEFAULT NULL,
  `yape` decimal(14,2) DEFAULT NULL,
  `plin` decimal(14,2) DEFAULT NULL,
  `mastercard` decimal(14,2) DEFAULT NULL,
  `deposito` decimal(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `idnotificacion` int(10) UNSIGNED NOT NULL,
  `codigonotificacion` char(10) COLLATE utf32_unicode_ci NOT NULL,
  `nombrenotificacion` char(100) COLLATE utf32_unicode_ci NOT NULL,
  `fechacreacion` date NOT NULL,
  `fechaaviso` date NOT NULL,
  `continuo` tinyint(4) NOT NULL,
  `tipocomprobante` char(2) COLLATE utf32_unicode_ci DEFAULT NULL,
  `idpersona` int(11) DEFAULT NULL,
  `contador` int(11) DEFAULT '3',
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeracion`
--

CREATE TABLE `numeracion` (
  `idnumeracion` int(11) NOT NULL,
  `tipo_documento` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serie` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `numeracion`
--

INSERT INTO `numeracion` (`idnumeracion`, `tipo_documento`, `serie`, `numero`, `estado`) VALUES
(1, '01', 'F001', '0', 1),
(2, '03', 'B001', '0', 1),
(3, '07', 'FN01', '0', 1),
(4, '08', 'FD01', '0', 1),
(5, '90', '0001', '0', 1),
(6, '20', 'CT01', '0', 1),
(7, '50', 'NP01', '0', 1),
(8, '09', 'T001', '0', 1),
(9, '56', 'VT01', '0', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeracionple`
--

CREATE TABLE `numeracionple` (
  `ano` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `primerRegistro` int(11) DEFAULT NULL,
  `ultimoRegistro` int(11) DEFAULT NULL,
  `totalRegistros` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenservicio`
--

CREATE TABLE `ordenservicio` (
  `idorden` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `serienumero` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `fechaemision` datetime NOT NULL,
  `formapago` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `formaentrega` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idempresa` int(11) NOT NULL,
  `fechaentrega` date NOT NULL,
  `anotaciones` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subtotal` float(12,2) NOT NULL,
  `igv` float(12,2) NOT NULL,
  `total` float(12,2) NOT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidoplatos`
--

CREATE TABLE `pedidoplatos` (
  `idpedido` int(10) UNSIGNED NOT NULL,
  `idmesa` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `nropedido` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravado` float(14,2) DEFAULT NULL,
  `igv` float(14,2) DEFAULT NULL,
  `total` float(14,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`idpermiso`, `nombre`) VALUES
(1, 'Dashboard'),
(2, 'Logistica'),
(3, 'Ventas'),
(4, 'Contabilidad'),
(5, 'RRHH'),
(6, 'consultac'),
(7, 'consultav'),
(8, 'Configuracion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL,
  `tipo_persona` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `nombres` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_documento` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `numero_documento` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `razon_social` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `nombre_comercial` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `domicilio_fiscal` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `departamento` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ciudad` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `distrito` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono1` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `telefono2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`idpersona`, `tipo_persona`, `nombres`, `apellidos`, `tipo_documento`, `numero_documento`, `razon_social`, `nombre_comercial`, `domicilio_fiscal`, `departamento`, `ciudad`, `distrito`, `telefono1`, `telefono2`, `email`, `estado`) VALUES
(1, 'CLIENTE', 'VARIOS', 'VARIOS', '0', 'VARIOS', 'VARIOS', 'VARIOS', '-', '', '', '', '-', '-', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `idplato` int(10) UNSIGNED NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `codigo` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ctacontable` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `precio` float(12,2) DEFAULT NULL,
  `imagen` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reginventariosanos`
--

CREATE TABLE `reginventariosanos` (
  `idregistro` int(11) NOT NULL,
  `codigo` char(50) COLLATE utf32_unicode_ci DEFAULT NULL,
  `denominacion` varchar(300) COLLATE utf32_unicode_ci DEFAULT NULL,
  `costoinicial` float(14,2) NOT NULL,
  `saldoinicial` float(14,2) NOT NULL,
  `valorinicial` float(14,2) NOT NULL,
  `compras` float(14,2) NOT NULL,
  `ventas` float(14,2) NOT NULL,
  `saldofinal` float(14,2) NOT NULL,
  `costo` float(14,2) NOT NULL,
  `valorfinal` float(14,2) NOT NULL,
  `ano` char(10) COLLATE utf32_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrocopiabd`
--

CREATE TABLE `registrocopiabd` (
  `idregistro` int(10) UNSIGNED NOT NULL,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nombrearchivo` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` char(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `idruta` int(11) NOT NULL,
  `rutadata` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutafirma` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutaenvio` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutarpta` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutadatalt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutabaja` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutaresumen` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutadescargas` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutaple` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `unziprpta` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutaarticulos` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutalogo` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutausuarios` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salidafacturas` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salidaboletas` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutacertificado` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salidanotapedidos` char(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`idruta`, `rutadata`, `rutafirma`, `rutaenvio`, `rutarpta`, `rutadatalt`, `rutabaja`, `rutaresumen`, `rutadescargas`, `rutaple`, `idempresa`, `unziprpta`, `rutaarticulos`, `rutalogo`, `rutausuarios`, `salidafacturas`, `salidaboletas`, `rutacertificado`, `salidanotapedidos`) VALUES
(1, '../sfs/data/', '../sfs/firma/', '../sfs/envio/', '../sfs/rpta/', '../sfs/dataalterna/', '../sfs/baja/', '../sfs/resumen/', '../sfs/descargas/', '../sfs/ple/', 1, '../sfs/unziprpta/', '../files/articulos/', '../files/logo/', '../files/usuarios/', '../facturasPDF/', '../boletasPDF/', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saldocaja`
--

CREATE TABLE `saldocaja` (
  `idsaldoini` int(11) NOT NULL,
  `saldo_inicial` decimal(14,2) NOT NULL DEFAULT '0.00',
  `caja_abierta` bit(1) DEFAULT b'0',
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidacaja`
--

CREATE TABLE `salidacaja` (
  `idsalida` int(11) NOT NULL,
  `idcaja` int(11) NOT NULL,
  `concepto` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto` float(14,2) DEFAULT NULL,
  `tipo` char(20) COLLATE utf8_unicode_ci DEFAULT 'SALIDA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_inmuebles`
--

CREATE TABLE `servicios_inmuebles` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` float(12,5) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1',
  `idempresa` int(11) DEFAULT NULL,
  `tipo` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ccontable` char(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subarticulo`
--

CREATE TABLE `subarticulo` (
  `idsubarticulo` int(10) UNSIGNED NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `codigobarra` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `valorunitario` float(14,2) DEFAULT NULL,
  `preciounitario` float(14,2) DEFAULT NULL,
  `stock` float(14,2) DEFAULT NULL,
  `umventa` int(11) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sunatconfig`
--

CREATE TABLE `sunatconfig` (
  `idcarga` int(11) NOT NULL,
  `numeroruc` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `razon_social` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuarioSol` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `claveSol` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutacertificado` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rutaserviciosunat` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombrepem` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `webserviceguia` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passcerti` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sunatconfig`
--

INSERT INTO `sunatconfig` (`idcarga`, `numeroruc`, `razon_social`, `usuarioSol`, `claveSol`, `rutacertificado`, `rutaserviciosunat`, `nombrepem`, `webserviceguia`, `passcerti`) VALUES
(1, '20000000001', 'EMPRESA DEMO', 'MODDATOS', 'moddatos', '../certificado/', 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl', '10459585961.pem', 'https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl', '10459585961');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tcambio`
--

CREATE TABLE `tcambio` (
  `idtipocambio` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `compra` float(14,3) DEFAULT NULL,
  `venta` float(14,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tempnumeracionxml`
--

CREATE TABLE `tempnumeracionxml` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha` date DEFAULT NULL,
  `numero` int(11) DEFAULT '0',
  `numticket` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `comentario` varchar(100) DEFAULT NULL,
  `nombrebaja` varchar(100) DEFAULT NULL,
  `comprobante` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temporal_articulo`
--

CREATE TABLE `temporal_articulo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `um` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cant` float(12,2) DEFAULT NULL,
  `costo_c` float(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temporizador`
--

CREATE TABLE `temporizador` (
  `id` int(11) NOT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `temporizador`
--

INSERT INTO `temporizador` (`id`, `tiempo`, `estado`) VALUES
(1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposeguro`
--

CREATE TABLE `tiposeguro` (
  `idtipoSeguro` int(10) UNSIGNED NOT NULL,
  `tipoSeguro` char(20) DEFAULT NULL,
  `nombreSeguro` char(50) NOT NULL,
  `aoafp` float(14,2) DEFAULT NULL,
  `invsob` float(14,2) DEFAULT NULL,
  `comiafp` float(14,2) DEFAULT NULL,
  `snp` float(14,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tiposeguro`
--

INSERT INTO `tiposeguro` (`idtipoSeguro`, `tipoSeguro`, `nombreSeguro`, `aoafp`, `invsob`, `comiafp`, `snp`) VALUES
(1, 'SNP', 'SISTEMA NACIONAL DE PENSIONES', 0.00, 0.00, 0.00, 13.00),
(2, 'AFP', 'INTEGRA', 10.00, 1.74, 1.55, 0.00),
(3, 'AFP', 'PROFUTURO', 10.00, 1.74, 1.69, 0.00),
(4, 'AFP', 'PRIMA', 10.00, 1.74, 1.60, 0.00),
(5, 'AFP', 'HABITAT', 10.00, 1.74, 1.74, 0.00),
(6, 'AFP', 'INTEGRA', 10.00, 1.74, 1.55, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubdepartamento`
--

CREATE TABLE `ubdepartamento` (
  `idDepa` int(11) NOT NULL DEFAULT '0',
  `departamento` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ubdepartamento`
--

INSERT INTO `ubdepartamento` (`idDepa`, `departamento`) VALUES
(1, 'AMAZONAS'),
(2, 'ANCASH'),
(3, 'APURIMAC'),
(4, 'AREQUIPA'),
(5, 'AYACUCHO'),
(6, 'CAJAMARCA'),
(7, 'CALLAO'),
(8, 'CUSCO'),
(9, 'HUANCAVELICA'),
(10, 'HUANUCO'),
(11, 'ICA'),
(12, 'JUNIN'),
(13, 'LA LIBERTAD'),
(14, 'LAMBAYEQUE'),
(15, 'LIMA'),
(16, 'LORETO'),
(17, 'MADRE DE DIOS'),
(18, 'MOQUEGUA'),
(19, 'PASCO'),
(20, 'PIURA'),
(21, 'PUNO'),
(22, 'SAN MARTIN'),
(23, 'TACNA'),
(24, 'TUMBES'),
(25, 'UCAYALI');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubdistrito`
--

CREATE TABLE `ubdistrito` (
  `idDist` int(11) NOT NULL DEFAULT '0',
  `distrito` varchar(50) DEFAULT NULL,
  `idProv` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ubdistrito`
--

INSERT INTO `ubdistrito` (`idDist`, `distrito`, `idProv`) VALUES
(1, 'CHACHAPOYAS', 1),
(2, 'ASUNCION', 1),
(3, 'BALSAS', 1),
(4, 'CHETO', 1),
(5, 'CHILIQUIN', 1),
(6, 'CHUQUIBAMBA', 1),
(7, 'GRANADA', 1),
(8, 'HUANCAS', 1),
(9, 'LA JALCA', 1),
(10, 'LEIMEBAMBA', 1),
(11, 'LEVANTO', 1),
(12, 'MAGDALENA', 1),
(13, 'MARISCAL CASTILLA', 1),
(14, 'MOLINOPAMPA', 1),
(15, 'MONTEVIDEO', 1),
(16, 'OLLEROS', 1),
(17, 'QUINJALCA', 1),
(18, 'SAN FRANCISCO DE DAGUAS', 1),
(19, 'SAN ISIDRO DE MAINO', 1),
(20, 'SOLOCO', 1),
(21, 'SONCHE', 1),
(22, 'LA PECA', 2),
(23, 'ARAMANGO', 2),
(24, 'COPALLIN', 2),
(25, 'EL PARCO', 2),
(26, 'IMAZA', 2),
(27, 'JUMBILLA', 3),
(28, 'CHISQUILLA', 3),
(29, 'CHURUJA', 3),
(30, 'COROSHA', 3),
(31, 'CUISPES', 3),
(32, 'FLORIDA', 3),
(33, 'JAZAN', 3),
(34, 'RECTA', 3),
(35, 'SAN CARLOS', 3),
(36, 'SHIPASBAMBA', 3),
(37, 'VALERA', 3),
(38, 'YAMBRASBAMBA', 3),
(39, 'NIEVA', 4),
(40, 'EL CENEPA', 4),
(41, 'RIO SANTIAGO', 4),
(42, 'LAMUD', 5),
(43, 'CAMPORREDONDO', 5),
(44, 'COCABAMBA', 5),
(45, 'COLCAMAR', 5),
(46, 'CONILA', 5),
(47, 'INGUILPATA', 5),
(48, 'LONGUITA', 5),
(49, 'LONYA CHICO', 5),
(50, 'LUYA', 5),
(51, 'LUYA VIEJO', 5),
(52, 'MARIA', 5),
(53, 'OCALLI', 5),
(54, 'OCUMAL', 5),
(55, 'PISUQUIA', 5),
(56, 'PROVIDENCIA', 5),
(57, 'SAN CRISTOBAL', 5),
(58, 'SAN FRANCISCO DEL YESO', 5),
(59, 'SAN JERONIMO', 5),
(60, 'SAN JUAN DE LOPECANCHA', 5),
(61, 'SANTA CATALINA', 5),
(62, 'SANTO TOMAS', 5),
(63, 'TINGO', 5),
(64, 'TRITA', 5),
(65, 'SAN NICOLAS', 6),
(66, 'CHIRIMOTO', 6),
(67, 'COCHAMAL', 6),
(68, 'HUAMBO', 6),
(69, 'LIMABAMBA', 6),
(70, 'LONGAR', 6),
(71, 'MARISCAL BENAVIDES', 6),
(72, 'MILPUC', 6),
(73, 'OMIA', 6),
(74, 'SANTA ROSA', 6),
(75, 'TOTORA', 6),
(76, 'VISTA ALEGRE', 6),
(77, 'BAGUA GRANDE', 7),
(78, 'CAJARURO', 7),
(79, 'CUMBA', 7),
(80, 'EL MILAGRO', 7),
(81, 'JAMALCA', 7),
(82, 'LONYA GRANDE', 7),
(83, 'YAMON', 7),
(84, 'HUARAZ', 8),
(85, 'COCHABAMBA', 8),
(86, 'COLCABAMBA', 8),
(87, 'HUANCHAY', 8),
(88, 'INDEPENDENCIA', 8),
(89, 'JANGAS', 8),
(90, 'LA LIBERTAD', 8),
(91, 'OLLEROS', 8),
(92, 'PAMPAS', 8),
(93, 'PARIACOTO', 8),
(94, 'PIRA', 8),
(95, 'TARICA', 8),
(96, 'AIJA', 9),
(97, 'CORIS', 9),
(98, 'HUACLLAN', 9),
(99, 'LA MERCED', 9),
(100, 'SUCCHA', 9),
(101, 'LLAMELLIN', 10),
(102, 'ACZO', 10),
(103, 'CHACCHO', 10),
(104, 'CHINGAS', 10),
(105, 'MIRGAS', 10),
(106, 'SAN JUAN DE RONTOY', 10),
(107, 'CHACAS', 11),
(108, 'ACOCHACA', 11),
(109, 'CHIQUIAN', 12),
(110, 'ABELARDO PARDO LEZAMETA', 12),
(111, 'ANTONIO RAYMONDI', 12),
(112, 'AQUIA', 12),
(113, 'CAJACAY', 12),
(114, 'CANIS', 12),
(115, 'COLQUIOC', 12),
(116, 'HUALLANCA', 12),
(117, 'HUASTA', 12),
(118, 'HUAYLLACAYAN', 12),
(119, 'LA PRIMAVERA', 12),
(120, 'MANGAS', 12),
(121, 'PACLLON', 12),
(122, 'SAN MIGUEL DE CORPANQUI', 12),
(123, 'TICLLOS', 12),
(124, 'CARHUAZ', 13),
(125, 'ACOPAMPA', 13),
(126, 'AMASHCA', 13),
(127, 'ANTA', 13),
(128, 'ATAQUERO', 13),
(129, 'MARCARA', 13),
(130, 'PARIAHUANCA', 13),
(131, 'SAN MIGUEL DE ACO', 13),
(132, 'SHILLA', 13),
(133, 'TINCO', 13),
(134, 'YUNGAR', 13),
(135, 'SAN LUIS', 14),
(136, 'SAN NICOLAS', 14),
(137, 'YAUYA', 14),
(138, 'CASMA', 15),
(139, 'BUENA VISTA ALTA', 15),
(140, 'COMANDANTE NOEL', 15),
(141, 'YAUTAN', 15),
(142, 'CORONGO', 16),
(143, 'ACO', 16),
(144, 'BAMBAS', 16),
(145, 'CUSCA', 16),
(146, 'LA PAMPA', 16),
(147, 'YANAC', 16),
(148, 'YUPAN', 16),
(149, 'HUARI', 17),
(150, 'ANRA', 17),
(151, 'CAJAY', 17),
(152, 'CHAVIN DE HUANTAR', 17),
(153, 'HUACACHI', 17),
(154, 'HUACCHIS', 17),
(155, 'HUACHIS', 17),
(156, 'HUANTAR', 17),
(157, 'MASIN', 17),
(158, 'PAUCAS', 17),
(159, 'PONTO', 17),
(160, 'RAHUAPAMPA', 17),
(161, 'RAPAYAN', 17),
(162, 'SAN MARCOS', 17),
(163, 'SAN PEDRO DE CHANA', 17),
(164, 'UCO', 17),
(165, 'HUARMEY', 18),
(166, 'COCHAPETI', 18),
(167, 'CULEBRAS', 18),
(168, 'HUAYAN', 18),
(169, 'MALVAS', 18),
(170, 'CARAZ', 26),
(171, 'HUALLANCA', 26),
(172, 'HUATA', 26),
(173, 'HUAYLAS', 26),
(174, 'MATO', 26),
(175, 'PAMPAROMAS', 26),
(176, 'PUEBLO LIBRE', 26),
(177, 'SANTA CRUZ', 26),
(178, 'SANTO TORIBIO', 26),
(179, 'YURACMARCA', 26),
(180, 'PISCOBAMBA', 27),
(181, 'CASCA', 27),
(182, 'ELEAZAR GUZMAN BARRON', 27),
(183, 'FIDEL OLIVAS ESCUDERO', 27),
(184, 'LLAMA', 27),
(185, 'LLUMPA', 27),
(186, 'LUCMA', 27),
(187, 'MUSGA', 27),
(188, 'OCROS', 21),
(189, 'ACAS', 21),
(190, 'CAJAMARQUILLA', 21),
(191, 'CARHUAPAMPA', 21),
(192, 'COCHAS', 21),
(193, 'CONGAS', 21),
(194, 'LLIPA', 21),
(195, 'SAN CRISTOBAL DE RAJAN', 21),
(196, 'SAN PEDRO', 21),
(197, 'SANTIAGO DE CHILCAS', 21),
(198, 'CABANA', 22),
(199, 'BOLOGNESI', 22),
(200, 'CONCHUCOS', 22),
(201, 'HUACASCHUQUE', 22),
(202, 'HUANDOVAL', 22),
(203, 'LACABAMBA', 22),
(204, 'LLAPO', 22),
(205, 'PALLASCA', 22),
(206, 'PAMPAS', 22),
(207, 'SANTA ROSA', 22),
(208, 'TAUCA', 22),
(209, 'POMABAMBA', 23),
(210, 'HUAYLLAN', 23),
(211, 'PAROBAMBA', 23),
(212, 'QUINUABAMBA', 23),
(213, 'RECUAY', 24),
(214, 'CATAC', 24),
(215, 'COTAPARACO', 24),
(216, 'HUAYLLAPAMPA', 24),
(217, 'LLACLLIN', 24),
(218, 'MARCA', 24),
(219, 'PAMPAS CHICO', 24),
(220, 'PARARIN', 24),
(221, 'TAPACOCHA', 24),
(222, 'TICAPAMPA', 24),
(223, 'CHIMBOTE', 25),
(224, 'CACERES DEL PERU', 25),
(225, 'COISHCO', 25),
(226, 'MACATE', 25),
(227, 'MORO', 25),
(228, 'NEPE&Ntilde;A', 25),
(229, 'SAMANCO', 25),
(230, 'SANTA', 25),
(231, 'NUEVO CHIMBOTE', 25),
(232, 'SIHUAS', 26),
(233, 'ACOBAMBA', 26),
(234, 'ALFONSO UGARTE', 26),
(235, 'CASHAPAMPA', 26),
(236, 'CHINGALPO', 26),
(237, 'HUAYLLABAMBA', 26),
(238, 'QUICHES', 26),
(239, 'RAGASH', 26),
(240, 'SAN JUAN', 26),
(241, 'SICSIBAMBA', 26),
(242, 'YUNGAY', 27),
(243, 'CASCAPARA', 27),
(244, 'MANCOS', 27),
(245, 'MATACOTO', 27),
(246, 'QUILLO', 27),
(247, 'RANRAHIRCA', 27),
(248, 'SHUPLUY', 27),
(249, 'YANAMA', 27),
(250, 'ABANCAY', 28),
(251, 'CHACOCHE', 28),
(252, 'CIRCA', 28),
(253, 'CURAHUASI', 28),
(254, 'HUANIPACA', 28),
(255, 'LAMBRAMA', 28),
(256, 'PICHIRHUA', 28),
(257, 'SAN PEDRO DE CACHORA', 28),
(258, 'TAMBURCO', 28),
(259, 'ANDAHUAYLAS', 29),
(260, 'ANDARAPA', 29),
(261, 'CHIARA', 29),
(262, 'HUANCARAMA', 29),
(263, 'HUANCARAY', 29),
(264, 'HUAYANA', 29),
(265, 'KISHUARA', 29),
(266, 'PACOBAMBA', 29),
(267, 'PACUCHA', 29),
(268, 'PAMPACHIRI', 29),
(269, 'POMACOCHA', 29),
(270, 'SAN ANTONIO DE CACHI', 29),
(271, 'SAN JERONIMO', 29),
(272, 'SAN MIGUEL DE CHACCRAMPA', 29),
(273, 'SANTA MARIA DE CHICMO', 29),
(274, 'TALAVERA', 29),
(275, 'TUMAY HUARACA', 29),
(276, 'TURPO', 29),
(277, 'KAQUIABAMBA', 29),
(278, 'ANTABAMBA', 30),
(279, 'EL ORO', 30),
(280, 'HUAQUIRCA', 30),
(281, 'JUAN ESPINOZA MEDRANO', 30),
(282, 'OROPESA', 30),
(283, 'PACHACONAS', 30),
(284, 'SABAINO', 30),
(285, 'CHALHUANCA', 31),
(286, 'CAPAYA', 31),
(287, 'CARAYBAMBA', 31),
(288, 'CHAPIMARCA', 31),
(289, 'COLCABAMBA', 31),
(290, 'COTARUSE', 31),
(291, 'HUAYLLO', 31),
(292, 'JUSTO APU SAHUARAURA', 31),
(293, 'LUCRE', 31),
(294, 'POCOHUANCA', 31),
(295, 'SAN JUAN DE CHAC&Ntilde;A', 31),
(296, 'SA&Ntilde;AYCA', 31),
(297, 'SORAYA', 31),
(298, 'TAPAIRIHUA', 31),
(299, 'TINTAY', 31),
(300, 'TORAYA', 31),
(301, 'YANACA', 31),
(302, 'TAMBOBAMBA', 32),
(303, 'COTABAMBAS', 32),
(304, 'COYLLURQUI', 32),
(305, 'HAQUIRA', 32),
(306, 'MARA', 32),
(307, 'CHALLHUAHUACHO', 32),
(308, 'CHINCHEROS', 33),
(309, 'ANCO-HUALLO', 33),
(310, 'COCHARCAS', 33),
(311, 'HUACCANA', 33),
(312, 'OCOBAMBA', 33),
(313, 'ONGOY', 33),
(314, 'URANMARCA', 33),
(315, 'RANRACANCHA', 33),
(316, 'CHUQUIBAMBILLA', 34),
(317, 'CURPAHUASI', 34),
(318, 'GAMARRA', 34),
(319, 'HUAYLLATI', 34),
(320, 'MAMARA', 34),
(321, 'MICAELA BASTIDAS', 34),
(322, 'PATAYPAMPA', 34),
(323, 'PROGRESO', 34),
(324, 'SAN ANTONIO', 34),
(325, 'SANTA ROSA', 34),
(326, 'TURPAY', 34),
(327, 'VILCABAMBA', 34),
(328, 'VIRUNDO', 34),
(329, 'CURASCO', 34),
(330, 'AREQUIPA', 35),
(331, 'ALTO SELVA ALEGRE', 35),
(332, 'CAYMA', 35),
(333, 'CERRO COLORADO', 35),
(334, 'CHARACATO', 35),
(335, 'CHIGUATA', 35),
(336, 'JACOBO HUNTER', 35),
(337, 'LA JOYA', 35),
(338, 'MARIANO MELGAR', 35),
(339, 'MIRAFLORES', 35),
(340, 'MOLLEBAYA', 35),
(341, 'PAUCARPATA', 35),
(342, 'POCSI', 35),
(343, 'POLOBAYA', 35),
(344, 'QUEQUE&Ntilde;A', 35),
(345, 'SABANDIA', 35),
(346, 'SACHACA', 35),
(347, 'SAN JUAN DE SIGUAS', 35),
(348, 'SAN JUAN DE TARUCANI', 35),
(349, 'SANTA ISABEL DE SIGUAS', 35),
(350, 'SANTA RITA DE SIGUAS', 35),
(351, 'SOCABAYA', 35),
(352, 'TIABAYA', 35),
(353, 'UCHUMAYO', 35),
(354, 'VITOR', 35),
(355, 'YANAHUARA', 35),
(356, 'YARABAMBA', 35),
(357, 'YURA', 35),
(358, 'JOSE LUIS BUSTAMANTE Y RIVERO', 35),
(359, 'CAMANA', 36),
(360, 'JOSE MARIA QUIMPER', 36),
(361, 'MARIANO NICOLAS VALCARCEL', 36),
(362, 'MARISCAL CACERES', 36),
(363, 'NICOLAS DE PIEROLA', 36),
(364, 'OCO&Ntilde;A', 36),
(365, 'QUILCA', 36),
(366, 'SAMUEL PASTOR', 36),
(367, 'CARAVELI', 37),
(368, 'ACARI', 37),
(369, 'ATICO', 37),
(370, 'ATIQUIPA', 37),
(371, 'BELLA UNION', 37),
(372, 'CAHUACHO', 37),
(373, 'CHALA', 37),
(374, 'CHAPARRA', 37),
(375, 'HUANUHUANU', 37),
(376, 'JAQUI', 37),
(377, 'LOMAS', 37),
(378, 'QUICACHA', 37),
(379, 'YAUCA', 37),
(380, 'APLAO', 38),
(381, 'ANDAGUA', 38),
(382, 'AYO', 38),
(383, 'CHACHAS', 38),
(384, 'CHILCAYMARCA', 38),
(385, 'CHOCO', 38),
(386, 'HUANCARQUI', 38),
(387, 'MACHAGUAY', 38),
(388, 'ORCOPAMPA', 38),
(389, 'PAMPACOLCA', 38),
(390, 'TIPAN', 38),
(391, 'U&Ntilde;ON', 38),
(392, 'URACA', 38),
(393, 'VIRACO', 38),
(394, 'CHIVAY', 39),
(395, 'ACHOMA', 39),
(396, 'CABANACONDE', 39),
(397, 'CALLALLI', 39),
(398, 'CAYLLOMA', 39),
(399, 'COPORAQUE', 39),
(400, 'HUAMBO', 39),
(401, 'HUANCA', 39),
(402, 'ICHUPAMPA', 39),
(403, 'LARI', 39),
(404, 'LLUTA', 39),
(405, 'MACA', 39),
(406, 'MADRIGAL', 39),
(407, 'SAN ANTONIO DE CHUCA', 39),
(408, 'SIBAYO', 39),
(409, 'TAPAY', 39),
(410, 'TISCO', 39),
(411, 'TUTI', 39),
(412, 'YANQUE', 39),
(413, 'MAJES', 39),
(414, 'CHUQUIBAMBA', 40),
(415, 'ANDARAY', 40),
(416, 'CAYARANI', 40),
(417, 'CHICHAS', 40),
(418, 'IRAY', 40),
(419, 'RIO GRANDE', 40),
(420, 'SALAMANCA', 40),
(421, 'YANAQUIHUA', 40),
(422, 'MOLLENDO', 41),
(423, 'COCACHACRA', 41),
(424, 'DEAN VALDIVIA', 41),
(425, 'ISLAY', 41),
(426, 'MEJIA', 41),
(427, 'PUNTA DE BOMBON', 41),
(428, 'COTAHUASI', 42),
(429, 'ALCA', 42),
(430, 'CHARCANA', 42),
(431, 'HUAYNACOTAS', 42),
(432, 'PAMPAMARCA', 42),
(433, 'PUYCA', 42),
(434, 'QUECHUALLA', 42),
(435, 'SAYLA', 42),
(436, 'TAURIA', 42),
(437, 'TOMEPAMPA', 42),
(438, 'TORO', 42),
(439, 'AYACUCHO', 43),
(440, 'ACOCRO', 43),
(441, 'ACOS VINCHOS', 43),
(442, 'CARMEN ALTO', 43),
(443, 'CHIARA', 43),
(444, 'OCROS', 43),
(445, 'PACAYCASA', 43),
(446, 'QUINUA', 43),
(447, 'SAN JOSE DE TICLLAS', 43),
(448, 'SAN JUAN BAUTISTA', 43),
(449, 'SANTIAGO DE PISCHA', 43),
(450, 'SOCOS', 43),
(451, 'TAMBILLO', 43),
(452, 'VINCHOS', 43),
(453, 'JESUS NAZARENO', 43),
(454, 'CANGALLO', 44),
(455, 'CHUSCHI', 44),
(456, 'LOS MOROCHUCOS', 44),
(457, 'MARIA PARADO DE BELLIDO', 44),
(458, 'PARAS', 44),
(459, 'TOTOS', 44),
(460, 'SANCOS', 45),
(461, 'CARAPO', 45),
(462, 'SACSAMARCA', 45),
(463, 'SANTIAGO DE LUCANAMARCA', 45),
(464, 'HUANTA', 46),
(465, 'AYAHUANCO', 46),
(466, 'HUAMANGUILLA', 46),
(467, 'IGUAIN', 46),
(468, 'LURICOCHA', 46),
(469, 'SANTILLANA', 46),
(470, 'SIVIA', 46),
(471, 'LLOCHEGUA', 46),
(472, 'SAN MIGUEL', 47),
(473, 'ANCO', 47),
(474, 'AYNA', 47),
(475, 'CHILCAS', 47),
(476, 'CHUNGUI', 47),
(477, 'LUIS CARRANZA', 47),
(478, 'SANTA ROSA', 47),
(479, 'TAMBO', 47),
(480, 'PUQUIO', 48),
(481, 'AUCARA', 48),
(482, 'CABANA', 48),
(483, 'CARMEN SALCEDO', 48),
(484, 'CHAVI&Ntilde;A', 48),
(485, 'CHIPAO', 48),
(486, 'HUAC-HUAS', 48),
(487, 'LARAMATE', 48),
(488, 'LEONCIO PRADO', 48),
(489, 'LLAUTA', 48),
(490, 'LUCANAS', 48),
(491, 'OCA&Ntilde;A', 48),
(492, 'OTOCA', 48),
(493, 'SAISA', 48),
(494, 'SAN CRISTOBAL', 48),
(495, 'SAN JUAN', 48),
(496, 'SAN PEDRO', 48),
(497, 'SAN PEDRO DE PALCO', 48),
(498, 'SANCOS', 48),
(499, 'SANTA ANA DE HUAYCAHUACHO', 48),
(500, 'SANTA LUCIA', 48),
(501, 'CORACORA', 49),
(502, 'CHUMPI', 49),
(503, 'CORONEL CASTA&Ntilde;EDA', 49),
(504, 'PACAPAUSA', 49),
(505, 'PULLO', 49),
(506, 'PUYUSCA', 49),
(507, 'SAN FRANCISCO DE RAVACAYCO', 49),
(508, 'UPAHUACHO', 49),
(509, 'PAUSA', 50),
(510, 'COLTA', 50),
(511, 'CORCULLA', 50),
(512, 'LAMPA', 50),
(513, 'MARCABAMBA', 50),
(514, 'OYOLO', 50),
(515, 'PARARCA', 50),
(516, 'SAN JAVIER DE ALPABAMBA', 50),
(517, 'SAN JOSE DE USHUA', 50),
(518, 'SARA SARA', 50),
(519, 'QUEROBAMBA', 51),
(520, 'BELEN', 51),
(521, 'CHALCOS', 51),
(522, 'CHILCAYOC', 51),
(523, 'HUACA&Ntilde;A', 51),
(524, 'MORCOLLA', 51),
(525, 'PAICO', 51),
(526, 'SAN PEDRO DE LARCAY', 51),
(527, 'SAN SALVADOR DE QUIJE', 51),
(528, 'SANTIAGO DE PAUCARAY', 51),
(529, 'SORAS', 51),
(530, 'HUANCAPI', 52),
(531, 'ALCAMENCA', 52),
(532, 'APONGO', 52),
(533, 'ASQUIPATA', 52),
(534, 'CANARIA', 52),
(535, 'CAYARA', 52),
(536, 'COLCA', 52),
(537, 'HUAMANQUIQUIA', 52),
(538, 'HUANCARAYLLA', 52),
(539, 'HUAYA', 52),
(540, 'SARHUA', 52),
(541, 'VILCANCHOS', 52),
(542, 'VILCAS HUAMAN', 53),
(543, 'ACCOMARCA', 53),
(544, 'CARHUANCA', 53),
(545, 'CONCEPCION', 53),
(546, 'HUAMBALPA', 53),
(547, 'INDEPENDENCIA', 53),
(548, 'SAURAMA', 53),
(549, 'VISCHONGO', 53),
(550, 'CAJAMARCA', 54),
(551, 'CAJAMARCA', 54),
(552, 'ASUNCION', 54),
(553, 'CHETILLA', 54),
(554, 'COSPAN', 54),
(555, 'ENCA&Ntilde;ADA', 54),
(556, 'JESUS', 54),
(557, 'LLACANORA', 54),
(558, 'LOS BA&Ntilde;OS DEL INCA', 54),
(559, 'MAGDALENA', 54),
(560, 'MATARA', 54),
(561, 'NAMORA', 54),
(562, 'SAN JUAN', 54),
(563, 'CAJABAMBA', 55),
(564, 'CACHACHI', 55),
(565, 'CONDEBAMBA', 55),
(566, 'SITACOCHA', 55),
(567, 'CELENDIN', 56),
(568, 'CHUMUCH', 56),
(569, 'CORTEGANA', 56),
(570, 'HUASMIN', 56),
(571, 'JORGE CHAVEZ', 56),
(572, 'JOSE GALVEZ', 56),
(573, 'MIGUEL IGLESIAS', 56),
(574, 'OXAMARCA', 56),
(575, 'SOROCHUCO', 56),
(576, 'SUCRE', 56),
(577, 'UTCO', 56),
(578, 'LA LIBERTAD DE PALLAN', 56),
(579, 'CHOTA', 57),
(580, 'ANGUIA', 57),
(581, 'CHADIN', 57),
(582, 'CHIGUIRIP', 57),
(583, 'CHIMBAN', 57),
(584, 'CHOROPAMPA', 57),
(585, 'COCHABAMBA', 57),
(586, 'CONCHAN', 57),
(587, 'HUAMBOS', 57),
(588, 'LAJAS', 57),
(589, 'LLAMA', 57),
(590, 'MIRACOSTA', 57),
(591, 'PACCHA', 57),
(592, 'PION', 57),
(593, 'QUEROCOTO', 57),
(594, 'SAN JUAN DE LICUPIS', 57),
(595, 'TACABAMBA', 57),
(596, 'TOCMOCHE', 57),
(597, 'CHALAMARCA', 57),
(598, 'CONTUMAZA', 58),
(599, 'CHILETE', 58),
(600, 'CUPISNIQUE', 58),
(601, 'GUZMANGO', 58),
(602, 'SAN BENITO', 58),
(603, 'SANTA CRUZ DE TOLED', 58),
(604, 'TANTARICA', 58),
(605, 'YONAN', 58),
(606, 'CUTERVO', 59),
(607, 'CALLAYUC', 59),
(608, 'CHOROS', 59),
(609, 'CUJILLO', 59),
(610, 'LA RAMADA', 59),
(611, 'PIMPINGOS', 59),
(612, 'QUEROCOTILLO', 59),
(613, 'SAN ANDRES DE CUTERVO', 59),
(614, 'SAN JUAN DE CUTERVO', 59),
(615, 'SAN LUIS DE LUCMA', 59),
(616, 'SANTA CRUZ', 59),
(617, 'SANTO DOMINGO DE LA CAPILLA', 59),
(618, 'SANTO TOMAS', 59),
(619, 'SOCOTA', 59),
(620, 'TORIBIO CASANOVA', 59),
(621, 'BAMBAMARCA', 60),
(622, 'CHUGUR', 60),
(623, 'HUALGAYOC', 60),
(624, 'JAEN', 61),
(625, 'BELLAVISTA', 61),
(626, 'CHONTALI', 61),
(627, 'COLASAY', 61),
(628, 'HUABAL', 61),
(629, 'LAS PIRIAS', 61),
(630, 'POMAHUACA', 61),
(631, 'PUCARA', 61),
(632, 'SALLIQUE', 61),
(633, 'SAN FELIPE', 61),
(634, 'SAN JOSE DEL ALTO', 61),
(635, 'SANTA ROSA', 61),
(636, 'SAN IGNACIO', 62),
(637, 'CHIRINOS', 62),
(638, 'HUARANGO', 62),
(639, 'LA COIPA', 62),
(640, 'NAMBALLE', 62),
(641, 'SAN JOSE DE LOURDES', 62),
(642, 'TABACONAS', 62),
(643, 'PEDRO GALVEZ', 63),
(644, 'CHANCAY', 63),
(645, 'EDUARDO VILLANUEVA', 63),
(646, 'GREGORIO PITA', 63),
(647, 'ICHOCAN', 63),
(648, 'JOSE MANUEL QUIROZ', 63),
(649, 'JOSE SABOGAL', 63),
(650, 'SAN MIGUEL', 64),
(651, 'SAN MIGUEL', 64),
(652, 'BOLIVAR', 64),
(653, 'CALQUIS', 64),
(654, 'CATILLUC', 64),
(655, 'EL PRADO', 64),
(656, 'LA FLORIDA', 64),
(657, 'LLAPA', 64),
(658, 'NANCHOC', 64),
(659, 'NIEPOS', 64),
(660, 'SAN GREGORIO', 64),
(661, 'SAN SILVESTRE DE COCHAN', 64),
(662, 'TONGOD', 64),
(663, 'UNION AGUA BLANCA', 64),
(664, 'SAN PABLO', 65),
(665, 'SAN BERNARDINO', 65),
(666, 'SAN LUIS', 65),
(667, 'TUMBADEN', 65),
(668, 'SANTA CRUZ', 66),
(669, 'ANDABAMBA', 66),
(670, 'CATACHE', 66),
(671, 'CHANCAYBA&Ntilde;OS', 66),
(672, 'LA ESPERANZA', 66),
(673, 'NINABAMBA', 66),
(674, 'PULAN', 66),
(675, 'SAUCEPAMPA', 66),
(676, 'SEXI', 66),
(677, 'UTICYACU', 66),
(678, 'YAUYUCAN', 66),
(679, 'CALLAO', 67),
(680, 'BELLAVISTA', 67),
(681, 'CARMEN DE LA LEGUA REYNOSO', 67),
(682, 'LA PERLA', 67),
(683, 'LA PUNTA', 67),
(684, 'VENTANILLA', 67),
(685, 'CUSCO', 67),
(686, 'CCORCA', 67),
(687, 'POROY', 67),
(688, 'SAN JERONIMO', 67),
(689, 'SAN SEBASTIAN', 67),
(690, 'SANTIAGO', 67),
(691, 'SAYLLA', 67),
(692, 'WANCHAQ', 67),
(693, 'ACOMAYO', 68),
(694, 'ACOPIA', 68),
(695, 'ACOS', 68),
(696, 'MOSOC LLACTA', 68),
(697, 'POMACANCHI', 68),
(698, 'RONDOCAN', 68),
(699, 'SANGARARA', 68),
(700, 'ANTA', 69),
(701, 'ANCAHUASI', 69),
(702, 'CACHIMAYO', 69),
(703, 'CHINCHAYPUJIO', 69),
(704, 'HUAROCONDO', 69),
(705, 'LIMATAMBO', 69),
(706, 'MOLLEPATA', 69),
(707, 'PUCYURA', 69),
(708, 'ZURITE', 69),
(709, 'CALCA', 70),
(710, 'COYA', 70),
(711, 'LAMAY', 70),
(712, 'LARES', 70),
(713, 'PISAC', 70),
(714, 'SAN SALVADOR', 70),
(715, 'TARAY', 70),
(716, 'YANATILE', 70),
(717, 'YANAOCA', 71),
(718, 'CHECCA', 71),
(719, 'KUNTURKANKI', 71),
(720, 'LANGUI', 71),
(721, 'LAYO', 71),
(722, 'PAMPAMARCA', 71),
(723, 'QUEHUE', 71),
(724, 'TUPAC AMARU', 71),
(725, 'SICUANI', 72),
(726, 'CHECACUPE', 72),
(727, 'COMBAPATA', 72),
(728, 'MARANGANI', 72),
(729, 'PITUMARCA', 72),
(730, 'SAN PABLO', 72),
(731, 'SAN PEDRO', 72),
(732, 'TINTA', 72),
(733, 'SANTO TOMAS', 73),
(734, 'CAPACMARCA', 73),
(735, 'CHAMACA', 73),
(736, 'COLQUEMARCA', 73),
(737, 'LIVITACA', 73),
(738, 'LLUSCO', 73),
(739, 'QUI&Ntilde;OTA', 73),
(740, 'VELILLE', 73),
(741, 'ESPINAR', 74),
(742, 'CONDOROMA', 74),
(743, 'COPORAQUE', 74),
(744, 'OCORURO', 74),
(745, 'PALLPATA', 74),
(746, 'PICHIGUA', 74),
(747, 'SUYCKUTAMBO', 74),
(748, 'ALTO PICHIGUA', 74),
(749, 'SANTA ANA', 75),
(750, 'ECHARATE', 75),
(751, 'HUAYOPATA', 75),
(752, 'MARANURA', 75),
(753, 'OCOBAMBA', 75),
(754, 'QUELLOUNO', 75),
(755, 'KIMBIRI', 75),
(756, 'SANTA TERESA', 75),
(757, 'VILCABAMBA', 75),
(758, 'PICHARI', 75),
(759, 'PARURO', 76),
(760, 'ACCHA', 76),
(761, 'CCAPI', 76),
(762, 'COLCHA', 76),
(763, 'HUANOQUITE', 76),
(764, 'OMACHA', 76),
(765, 'PACCARITAMBO', 76),
(766, 'PILLPINTO', 76),
(767, 'YAURISQUE', 76),
(768, 'PAUCARTAMBO', 77),
(769, 'CAICAY', 77),
(770, 'CHALLABAMBA', 77),
(771, 'COLQUEPATA', 77),
(772, 'HUANCARANI', 77),
(773, 'KOS&Ntilde;IPATA', 77),
(774, 'URCOS', 78),
(775, 'ANDAHUAYLILLAS', 78),
(776, 'CAMANTI', 78),
(777, 'CCARHUAYO', 78),
(778, 'CCATCA', 78),
(779, 'CUSIPATA', 78),
(780, 'HUARO', 78),
(781, 'LUCRE', 78),
(782, 'MARCAPATA', 78),
(783, 'OCONGATE', 78),
(784, 'OROPESA', 78),
(785, 'QUIQUIJANA', 78),
(786, 'URUBAMBA', 79),
(787, 'CHINCHERO', 79),
(788, 'HUAYLLABAMBA', 79),
(789, 'MACHUPICCHU', 79),
(790, 'MARAS', 79),
(791, 'OLLANTAYTAMBO', 79),
(792, 'YUCAY', 79),
(793, 'HUANCAVELICA', 80),
(794, 'ACOBAMBILLA', 80),
(795, 'ACORIA', 80),
(796, 'CONAYCA', 80),
(797, 'CUENCA', 80),
(798, 'HUACHOCOLPA', 80),
(799, 'HUAYLLAHUARA', 80),
(800, 'IZCUCHACA', 80),
(801, 'LARIA', 80),
(802, 'MANTA', 80),
(803, 'MARISCAL CACERES', 80),
(804, 'MOYA', 80),
(805, 'NUEVO OCCORO', 80),
(806, 'PALCA', 80),
(807, 'PILCHACA', 80),
(808, 'VILCA', 80),
(809, 'YAULI', 80),
(810, 'ASCENSION', 80),
(811, 'HUANDO', 80),
(812, 'ACOBAMBA', 81),
(813, 'ANDABAMBA', 81),
(814, 'ANTA', 81),
(815, 'CAJA', 81),
(816, 'MARCAS', 81),
(817, 'PAUCARA', 81),
(818, 'POMACOCHA', 81),
(819, 'ROSARIO', 81),
(820, 'LIRCAY', 82),
(821, 'ANCHONGA', 82),
(822, 'CALLANMARCA', 82),
(823, 'CCOCHACCASA', 82),
(824, 'CHINCHO', 82),
(825, 'CONGALLA', 82),
(826, 'HUANCA-HUANCA', 82),
(827, 'HUAYLLAY GRANDE', 82),
(828, 'JULCAMARCA', 82),
(829, 'SAN ANTONIO DE ANTAPARCO', 82),
(830, 'SANTO TOMAS DE PATA', 82),
(831, 'SECCLLA', 82),
(832, 'CASTROVIRREYNA', 83),
(833, 'ARMA', 83),
(834, 'AURAHUA', 83),
(835, 'CAPILLAS', 83),
(836, 'CHUPAMARCA', 83),
(837, 'COCAS', 83),
(838, 'HUACHOS', 83),
(839, 'HUAMATAMBO', 83),
(840, 'MOLLEPAMPA', 83),
(841, 'SAN JUAN', 83),
(842, 'SANTA ANA', 83),
(843, 'TANTARA', 83),
(844, 'TICRAPO', 83),
(845, 'CHURCAMPA', 84),
(846, 'ANCO', 84),
(847, 'CHINCHIHUASI', 84),
(848, 'EL CARMEN', 84),
(849, 'LA MERCED', 84),
(850, 'LOCROJA', 84),
(851, 'PAUCARBAMBA', 84),
(852, 'SAN MIGUEL DE MAYOCC', 84),
(853, 'SAN PEDRO DE CORIS', 84),
(854, 'PACHAMARCA', 84),
(855, 'HUAYTARA', 85),
(856, 'AYAVI', 85),
(857, 'CORDOVA', 85),
(858, 'HUAYACUNDO ARMA', 85),
(859, 'LARAMARCA', 85),
(860, 'OCOYO', 85),
(861, 'PILPICHACA', 85),
(862, 'QUERCO', 85),
(863, 'QUITO-ARMA', 85),
(864, 'SAN ANTONIO DE CUSICANCHA', 85),
(865, 'SAN FRANCISCO DE SANGAYAICO', 85),
(866, 'SAN ISIDRO', 85),
(867, 'SANTIAGO DE CHOCORVOS', 85),
(868, 'SANTIAGO DE QUIRAHUARA', 85),
(869, 'SANTO DOMINGO DE CAPILLAS', 85),
(870, 'TAMBO', 85),
(871, 'PAMPAS', 86),
(872, 'ACOSTAMBO', 86),
(873, 'ACRAQUIA', 86),
(874, 'AHUAYCHA', 86),
(875, 'COLCABAMBA', 86),
(876, 'DANIEL HERNANDEZ', 86),
(877, 'HUACHOCOLPA', 86),
(878, 'HUARIBAMBA', 86),
(879, '&Ntilde;AHUIMPUQUIO', 86),
(880, 'PAZOS', 86),
(881, 'QUISHUAR', 86),
(882, 'SALCABAMBA', 86),
(883, 'SALCAHUASI', 86),
(884, 'SAN MARCOS DE ROCCHAC', 86),
(885, 'SURCUBAMBA', 86),
(886, 'TINTAY PUNCU', 86),
(887, 'HUANUCO', 87),
(888, 'AMARILIS', 87),
(889, 'CHINCHAO', 87),
(890, 'CHURUBAMBA', 87),
(891, 'MARGOS', 87),
(892, 'QUISQUI', 87),
(893, 'SAN FRANCISCO DE CAYRAN', 87),
(894, 'SAN PEDRO DE CHAULAN', 87),
(895, 'SANTA MARIA DEL VALLE', 87),
(896, 'YARUMAYO', 87),
(897, 'PILLCO MARCA', 87),
(898, 'AMBO', 88),
(899, 'CAYNA', 88),
(900, 'COLPAS', 88),
(901, 'CONCHAMARCA', 88),
(902, 'HUACAR', 88),
(903, 'SAN FRANCISCO', 88),
(904, 'SAN RAFAEL', 88),
(905, 'TOMAY KICHWA', 88),
(906, 'LA UNION', 89),
(907, 'CHUQUIS', 89),
(908, 'MARIAS', 89),
(909, 'PACHAS', 89),
(910, 'QUIVILLA', 89),
(911, 'RIPAN', 89),
(912, 'SHUNQUI', 89),
(913, 'SILLAPATA', 89),
(914, 'YANAS', 89),
(915, 'HUACAYBAMBA', 90),
(916, 'CANCHABAMBA', 90),
(917, 'COCHABAMBA', 90),
(918, 'PINRA', 90),
(919, 'LLATA', 91),
(920, 'ARANCAY', 91),
(921, 'CHAVIN DE PARIARCA', 91),
(922, 'JACAS GRANDE', 91),
(923, 'JIRCAN', 91),
(924, 'MIRAFLORES', 91),
(925, 'MONZON', 91),
(926, 'PUNCHAO', 91),
(927, 'PU&Ntilde;OS', 91),
(928, 'SINGA', 91),
(929, 'TANTAMAYO', 91),
(930, 'RUPA-RUPA', 92),
(931, 'DANIEL ALOMIA ROBLES', 92),
(932, 'HERMILIO VALDIZAN', 92),
(933, 'JOSE CRESPO Y CASTILLO', 92),
(934, 'LUYANDO', 92),
(935, 'MARIANO DAMASO BERAUN', 92),
(936, 'HUACRACHUCO', 93),
(937, 'CHOLON', 93),
(938, 'SAN BUENAVENTURA', 93),
(939, 'PANAO', 94),
(940, 'CHAGLLA', 94),
(941, 'MOLINO', 94),
(942, 'UMARI', 94),
(943, 'PUERTO INCA', 95),
(944, 'CODO DEL POZUZO', 95),
(945, 'HONORIA', 95),
(946, 'TOURNAVISTA', 95),
(947, 'YUYAPICHIS', 95),
(948, 'JESUS', 96),
(949, 'BA&Ntilde;OS', 96),
(950, 'JIVIA', 96),
(951, 'QUEROPALCA', 96),
(952, 'RONDOS', 96),
(953, 'SAN FRANCISCO DE ASIS', 96),
(954, 'SAN MIGUEL DE CAURI', 96),
(955, 'CHAVINILLO', 97),
(956, 'CAHUAC', 97),
(957, 'CHACABAMBA', 97),
(958, 'APARICIO POMARES', 97),
(959, 'JACAS CHICO', 97),
(960, 'OBAS', 97),
(961, 'PAMPAMARCA', 97),
(962, 'CHORAS', 97),
(963, 'ICA', 98),
(964, 'LA TINGUI&Ntilde;A', 98),
(965, 'LOS AQUIJES', 98),
(966, 'OCUCAJE', 98),
(967, 'PACHACUTEC', 98),
(968, 'PARCONA', 98),
(969, 'PUEBLO NUEVO', 98),
(970, 'SALAS', 98),
(971, 'SAN JOSE DE LOS MOLINOS', 98),
(972, 'SAN JUAN BAUTISTA', 98),
(973, 'SANTIAGO', 98),
(974, 'SUBTANJALLA', 98),
(975, 'TATE', 98),
(976, 'YAUCA DEL ROSARIO', 98),
(977, 'CHINCHA ALTA', 99),
(978, 'ALTO LARAN', 99),
(979, 'CHAVIN', 99),
(980, 'CHINCHA BAJA', 99),
(981, 'EL CARMEN', 99),
(982, 'GROCIO PRADO', 99),
(983, 'PUEBLO NUEVO', 99),
(984, 'SAN JUAN DE YANAC', 99),
(985, 'SAN PEDRO DE HUACARPANA', 99),
(986, 'SUNAMPE', 99),
(987, 'TAMBO DE MORA', 99),
(988, 'NAZCA', 100),
(989, 'CHANGUILLO', 100),
(990, 'EL INGENIO', 100),
(991, 'MARCONA', 100),
(992, 'VISTA ALEGRE', 100),
(993, 'PALPA', 101),
(994, 'LLIPATA', 101),
(995, 'RIO GRANDE', 101),
(996, 'SANTA CRUZ', 101),
(997, 'TIBILLO', 101),
(998, 'PISCO', 102),
(999, 'HUANCANO', 102),
(1000, 'HUMAY', 102),
(1001, 'INDEPENDENCIA', 102),
(1002, 'PARACAS', 102),
(1003, 'SAN ANDRES', 102),
(1004, 'SAN CLEMENTE', 102),
(1005, 'TUPAC AMARU INCA', 102),
(1006, 'HUANCAYO', 103),
(1007, 'CARHUACALLANGA', 103),
(1008, 'CHACAPAMPA', 103),
(1009, 'CHICCHE', 103),
(1010, 'CHILCA', 103),
(1011, 'CHONGOS ALTO', 103),
(1012, 'CHUPURO', 103),
(1013, 'COLCA', 103),
(1014, 'CULLHUAS', 103),
(1015, 'EL TAMBO', 103),
(1016, 'HUACRAPUQUIO', 103),
(1017, 'HUALHUAS', 103),
(1018, 'HUANCAN', 103),
(1019, 'HUASICANCHA', 103),
(1020, 'HUAYUCACHI', 103),
(1021, 'INGENIO', 103),
(1022, 'PARIAHUANCA', 103),
(1023, 'PILCOMAYO', 103),
(1024, 'PUCARA', 103),
(1025, 'QUICHUAY', 103),
(1026, 'QUILCAS', 103),
(1027, 'SAN AGUSTIN', 103),
(1028, 'SAN JERONIMO DE TUNAN', 103),
(1029, 'SA&Ntilde;O', 103),
(1030, 'SAPALLANGA', 103),
(1031, 'SICAYA', 103),
(1032, 'SANTO DOMINGO DE ACOBAMBA', 103),
(1033, 'VIQUES', 103),
(1034, 'CONCEPCION', 104),
(1035, 'ACO', 104),
(1036, 'ANDAMARCA', 104),
(1037, 'CHAMBARA', 104),
(1038, 'COCHAS', 104),
(1039, 'COMAS', 104),
(1040, 'HEROINAS TOLEDO', 104),
(1041, 'MANZANARES', 104),
(1042, 'MARISCAL CASTILLA', 104),
(1043, 'MATAHUASI', 104),
(1044, 'MITO', 104),
(1045, 'NUEVE DE JULIO', 104),
(1046, 'ORCOTUNA', 104),
(1047, 'SAN JOSE DE QUERO', 104),
(1048, 'SANTA ROSA DE OCOPA', 104),
(1049, 'CHANCHAMAYO', 105),
(1050, 'PERENE', 105),
(1051, 'PICHANAQUI', 105),
(1052, 'SAN LUIS DE SHUARO', 105),
(1053, 'SAN RAMON', 105),
(1054, 'VITOC', 105),
(1055, 'JAUJA', 106),
(1056, 'ACOLLA', 106),
(1057, 'APATA', 106),
(1058, 'ATAURA', 106),
(1059, 'CANCHAYLLO', 106),
(1060, 'CURICACA', 106),
(1061, 'EL MANTARO', 106),
(1062, 'HUAMALI', 106),
(1063, 'HUARIPAMPA', 106),
(1064, 'HUERTAS', 106),
(1065, 'JANJAILLO', 106),
(1066, 'JULCAN', 106),
(1067, 'LEONOR ORDO&Ntilde;EZ', 106),
(1068, 'LLOCLLAPAMPA', 106),
(1069, 'MARCO', 106),
(1070, 'MASMA', 106),
(1071, 'MASMA CHICCHE', 106),
(1072, 'MOLINOS', 106),
(1073, 'MONOBAMBA', 106),
(1074, 'MUQUI', 106),
(1075, 'MUQUIYAUYO', 106),
(1076, 'PACA', 106),
(1077, 'PACCHA', 106),
(1078, 'PANCAN', 106),
(1079, 'PARCO', 106),
(1080, 'POMACANCHA', 106),
(1081, 'RICRAN', 106),
(1082, 'SAN LORENZO', 106),
(1083, 'SAN PEDRO DE CHUNAN', 106),
(1084, 'SAUSA', 106),
(1085, 'SINCOS', 106),
(1086, 'TUNAN MARCA', 106),
(1087, 'YAULI', 106),
(1088, 'YAUYOS', 106),
(1089, 'JUNIN', 107),
(1090, 'CARHUAMAYO', 107),
(1091, 'ONDORES', 107),
(1092, 'ULCUMAYO', 107),
(1093, 'SATIPO', 108),
(1094, 'COVIRIALI', 108),
(1095, 'LLAYLLA', 108),
(1096, 'MAZAMARI', 108),
(1097, 'PAMPA HERMOSA', 108),
(1098, 'PANGOA', 108),
(1099, 'RIO NEGRO', 108),
(1100, 'RIO TAMBO', 108),
(1101, 'TARMA', 109),
(1102, 'ACOBAMBA', 109),
(1103, 'HUARICOLCA', 109),
(1104, 'HUASAHUASI', 109),
(1105, 'LA UNION', 109),
(1106, 'PALCA', 109),
(1107, 'PALCAMAYO', 109),
(1108, 'SAN PEDRO DE CAJAS', 109),
(1109, 'TAPO', 109),
(1110, 'LA OROYA', 110),
(1111, 'CHACAPALPA', 110),
(1112, 'HUAY-HUAY', 110),
(1113, 'MARCAPOMACOCHA', 110),
(1114, 'MOROCOCHA', 110),
(1115, 'PACCHA', 110),
(1116, 'SANTA BARBARA DE CARHUACAYAN', 110),
(1117, 'SANTA ROSA DE SACCO', 110),
(1118, 'SUITUCANCHA', 110),
(1119, 'YAULI', 110),
(1120, 'CHUPACA', 111),
(1121, 'AHUAC', 111),
(1122, 'CHONGOS BAJO', 111),
(1123, 'HUACHAC', 111),
(1124, 'HUAMANCACA CHICO', 111),
(1125, 'SAN JUAN DE ISCOS', 111),
(1126, 'SAN JUAN DE JARPA', 111),
(1127, 'TRES DE DICIEMBRE', 111),
(1128, 'YANACANCHA', 111),
(1129, 'TRUJILLO', 112),
(1130, 'EL PORVENIR', 112),
(1131, 'FLORENCIA DE MORA', 112),
(1132, 'HUANCHACO', 112),
(1133, 'LA ESPERANZA', 112),
(1134, 'LAREDO', 112),
(1135, 'MOCHE', 112),
(1136, 'POROTO', 112),
(1137, 'SALAVERRY', 112),
(1138, 'SIMBAL', 112),
(1139, 'VICTOR LARCO HERRERA', 112),
(1140, 'ASCOPE', 113),
(1141, 'CHICAMA', 113),
(1142, 'CHOCOPE', 113),
(1143, 'MAGDALENA DE CAO', 113),
(1144, 'PAIJAN', 113),
(1145, 'RAZURI', 113),
(1146, 'SANTIAGO DE CAO', 113),
(1147, 'CASA GRANDE', 113),
(1148, 'BOLIVAR', 114),
(1149, 'BAMBAMARCA', 114),
(1150, 'CONDORMARCA', 114),
(1151, 'LONGOTEA', 114),
(1152, 'UCHUMARCA', 114),
(1153, 'UCUNCHA', 114),
(1154, 'CHEPEN', 115),
(1155, 'PACANGA', 115),
(1156, 'PUEBLO NUEVO', 115),
(1157, 'JULCAN', 116),
(1158, 'CALAMARCA', 116),
(1159, 'CARABAMBA', 116),
(1160, 'HUASO', 116),
(1161, 'OTUZCO', 117),
(1162, 'AGALLPAMPA', 117),
(1163, 'CHARAT', 117),
(1164, 'HUARANCHAL', 117),
(1165, 'LA CUESTA', 117),
(1166, 'MACHE', 117),
(1167, 'PARANDAY', 117),
(1168, 'SALPO', 117),
(1169, 'SINSICAP', 117),
(1170, 'USQUIL', 117),
(1171, 'SAN PEDRO DE LLOC', 118),
(1172, 'GUADALUPE', 118),
(1173, 'JEQUETEPEQUE', 118),
(1174, 'PACASMAYO', 118),
(1175, 'SAN JOSE', 118),
(1176, 'TAYABAMBA', 119),
(1177, 'BULDIBUYO', 119),
(1178, 'CHILLIA', 119),
(1179, 'HUANCASPATA', 119),
(1180, 'HUAYLILLAS', 119),
(1181, 'HUAYO', 119),
(1182, 'ONGON', 119),
(1183, 'PARCOY', 119),
(1184, 'PATAZ', 119),
(1185, 'PIAS', 119),
(1186, 'SANTIAGO DE CHALLAS', 119),
(1187, 'TAURIJA', 119),
(1188, 'URPAY', 119),
(1189, 'HUAMACHUCO', 120),
(1190, 'CHUGAY', 120),
(1191, 'COCHORCO', 120),
(1192, 'CURGOS', 120),
(1193, 'MARCABAL', 120),
(1194, 'SANAGORAN', 120),
(1195, 'SARIN', 120),
(1196, 'SARTIMBAMBA', 120),
(1197, 'SANTIAGO DE CHUCO', 121),
(1198, 'ANGASMARCA', 121),
(1199, 'CACHICADAN', 121),
(1200, 'MOLLEBAMBA', 121),
(1201, 'MOLLEPATA', 121),
(1202, 'QUIRUVILCA', 121),
(1203, 'SANTA CRUZ DE CHUCA', 121),
(1204, 'SITABAMBA', 121),
(1205, 'GRAN CHIMU', 122),
(1206, 'CASCAS', 122),
(1207, 'LUCMA', 122),
(1208, 'MARMOT', 122),
(1209, 'SAYAPULLO', 122),
(1210, 'VIRU', 123),
(1211, 'CHAO', 123),
(1212, 'GUADALUPITO', 123),
(1213, 'CHICLAYO', 124),
(1214, 'CHONGOYAPE', 124),
(1215, 'ETEN', 124),
(1216, 'ETEN PUERTO', 124),
(1217, 'JOSE LEONARDO ORTIZ', 124),
(1218, 'LA VICTORIA', 124),
(1219, 'LAGUNAS', 124),
(1220, 'MONSEFU', 124),
(1221, 'NUEVA ARICA', 124),
(1222, 'OYOTUN', 124),
(1223, 'PICSI', 124),
(1224, 'PIMENTEL', 124),
(1225, 'REQUE', 124),
(1226, 'SANTA ROSA', 124),
(1227, 'SA&Ntilde;A', 124),
(1228, 'CAYALTI', 124),
(1229, 'PATAPO', 124),
(1230, 'POMALCA', 124),
(1231, 'PUCALA', 124),
(1232, 'TUMAN', 124),
(1233, 'FERRE&Ntilde;AFE', 125),
(1234, 'CA&Ntilde;ARIS', 125),
(1235, 'INCAHUASI', 125),
(1236, 'MANUEL ANTONIO MESONES MURO', 125),
(1237, 'PITIPO', 125),
(1238, 'PUEBLO NUEVO', 125),
(1239, 'LAMBAYEQUE', 126),
(1240, 'CHOCHOPE', 126),
(1241, 'ILLIMO', 126),
(1242, 'JAYANCA', 126),
(1243, 'MOCHUMI', 126),
(1244, 'MORROPE', 126),
(1245, 'MOTUPE', 126),
(1246, 'OLMOS', 126),
(1247, 'PACORA', 126),
(1248, 'SALAS', 126),
(1249, 'SAN JOSE', 126),
(1250, 'TUCUME', 126),
(1251, 'LIMA', 127),
(1252, 'ANCON', 127),
(1253, 'ATE', 127),
(1254, 'BARRANCO', 127),
(1255, 'BRE&Ntilde;A', 127),
(1256, 'CARABAYLLO', 127),
(1257, 'CHACLACAYO', 127),
(1258, 'CHORRILLOS', 127),
(1259, 'CIENEGUILLA', 127),
(1260, 'COMAS', 127),
(1261, 'EL AGUSTINO', 127),
(1262, 'INDEPENDENCIA', 127),
(1263, 'JESUS MARIA', 127),
(1264, 'LA MOLINA', 127),
(1265, 'LA VICTORIA', 127),
(1266, 'LINCE', 127),
(1267, 'LOS OLIVOS', 127),
(1268, 'LURIGANCHO', 127),
(1269, 'LURIN', 127),
(1270, 'MAGDALENA DEL MAR', 127),
(1271, 'MAGDALENA VIEJA', 127),
(1272, 'MIRAFLORES', 127),
(1273, 'PACHACAMAC', 127),
(1274, 'PUCUSANA', 127),
(1275, 'PUENTE PIEDRA', 127),
(1276, 'PUNTA HERMOSA', 127),
(1277, 'PUNTA NEGRA', 127),
(1278, 'RIMAC', 127),
(1279, 'SAN BARTOLO', 127),
(1280, 'SAN BORJA', 127),
(1281, 'SAN ISIDRO', 127),
(1282, 'SAN JUAN DE LURIGANCHO', 127),
(1283, 'SAN JUAN DE MIRAFLORES', 127),
(1284, 'SAN LUIS', 127),
(1285, 'SAN MARTIN DE PORRES', 127),
(1286, 'SAN MIGUEL', 127),
(1287, 'SANTA ANITA', 127),
(1288, 'SANTA MARIA DEL MAR', 127),
(1289, 'SANTA ROSA', 127),
(1290, 'SANTIAGO DE SURCO', 127),
(1291, 'SURQUILLO', 127),
(1292, 'VILLA EL SALVADOR', 127),
(1293, 'VILLA MARIA DEL TRIUNFO', 127),
(1294, 'BARRANCA', 128),
(1295, 'PARAMONGA', 128),
(1296, 'PATIVILCA', 128),
(1297, 'SUPE', 128),
(1298, 'SUPE PUERTO', 128),
(1299, 'CAJATAMBO', 129),
(1300, 'COPA', 129),
(1301, 'GORGOR', 129),
(1302, 'HUANCAPON', 129),
(1303, 'MANAS', 129),
(1304, 'CANTA', 130),
(1305, 'ARAHUAY', 130),
(1306, 'HUAMANTANGA', 130),
(1307, 'HUAROS', 130),
(1308, 'LACHAQUI', 130),
(1309, 'SAN BUENAVENTURA', 130),
(1310, 'SANTA ROSA DE QUIVES', 130),
(1311, 'SAN VICENTE DE CA&Ntilde;ETE', 131),
(1312, 'ASIA', 131),
(1313, 'CALANGO', 131),
(1314, 'CERRO AZUL', 131),
(1315, 'CHILCA', 131),
(1316, 'COAYLLO', 131),
(1317, 'IMPERIAL', 131),
(1318, 'LUNAHUANA', 131),
(1319, 'MALA', 131),
(1320, 'NUEVO IMPERIAL', 131),
(1321, 'PACARAN', 131),
(1322, 'QUILMANA', 131),
(1323, 'SAN ANTONIO', 131),
(1324, 'SAN LUIS', 131),
(1325, 'SANTA CRUZ DE FLORES', 131),
(1326, 'ZU&Ntilde;IGA', 131),
(1327, 'HUARAL', 132),
(1328, 'ATAVILLOS ALTO', 132),
(1329, 'ATAVILLOS BAJO', 132),
(1330, 'AUCALLAMA', 132),
(1331, 'CHANCAY', 132),
(1332, 'IHUARI', 132),
(1333, 'LAMPIAN', 132),
(1334, 'PACARAOS', 132),
(1335, 'SAN MIGUEL DE ACOS', 132),
(1336, 'SANTA CRUZ DE ANDAMARCA', 132),
(1337, 'SUMBILCA', 132),
(1338, 'VEINTISIETE DE NOVIEMBRE', 132),
(1339, 'MATUCANA', 133),
(1340, 'ANTIOQUIA', 133),
(1341, 'CALLAHUANCA', 133),
(1342, 'CARAMPOMA', 133),
(1343, 'CHICLA', 133),
(1344, 'CUENCA', 133),
(1345, 'HUACHUPAMPA', 133),
(1346, 'HUANZA', 133),
(1347, 'HUAROCHIRI', 133),
(1348, 'LAHUAYTAMBO', 133),
(1349, 'LANGA', 133),
(1350, 'LARAOS', 133),
(1351, 'MARIATANA', 133),
(1352, 'RICARDO PALMA', 133),
(1353, 'SAN ANDRES DE TUPICOCHA', 133),
(1354, 'SAN ANTONIO', 133),
(1355, 'SAN BARTOLOME', 133),
(1356, 'SAN DAMIAN', 133),
(1357, 'SAN JUAN DE IRIS', 133),
(1358, 'SAN JUAN DE TANTARANCHE', 133),
(1359, 'SAN LORENZO DE QUINTI', 133),
(1360, 'SAN MATEO', 133),
(1361, 'SAN MATEO DE OTAO', 133),
(1362, 'SAN PEDRO DE CASTA', 133),
(1363, 'SAN PEDRO DE HUANCAYRE', 133),
(1364, 'SANGALLAYA', 133),
(1365, 'SANTA CRUZ DE COCACHACRA', 133),
(1366, 'SANTA EULALIA', 133),
(1367, 'SANTIAGO DE ANCHUCAYA', 133),
(1368, 'SANTIAGO DE TUNA', 133),
(1369, 'SANTO DOMINGO DE LOS OLLEROS', 133),
(1370, 'SURCO', 133),
(1371, 'HUACHO', 134),
(1372, 'AMBAR', 134),
(1373, 'CALETA DE CARQUIN', 134),
(1374, 'CHECRAS', 134),
(1375, 'HUALMAY', 134),
(1376, 'HUAURA', 134),
(1377, 'LEONCIO PRADO', 134),
(1378, 'PACCHO', 134),
(1379, 'SANTA LEONOR', 134),
(1380, 'SANTA MARIA', 134),
(1381, 'SAYAN', 134),
(1382, 'VEGUETA', 134),
(1383, 'OYON', 135),
(1384, 'ANDAJES', 135),
(1385, 'CAUJUL', 135),
(1386, 'COCHAMARCA', 135),
(1387, 'NAVAN', 135),
(1388, 'PACHANGARA', 135),
(1389, 'YAUYOS', 136),
(1390, 'ALIS', 136),
(1391, 'AYAUCA', 136),
(1392, 'AYAVIRI', 136),
(1393, 'AZANGARO', 136),
(1394, 'CACRA', 136),
(1395, 'CARANIA', 136),
(1396, 'CATAHUASI', 136),
(1397, 'CHOCOS', 136),
(1398, 'COCHAS', 136),
(1399, 'COLONIA', 136),
(1400, 'HONGOS', 136),
(1401, 'HUAMPARA', 136),
(1402, 'HUANCAYA', 136),
(1403, 'HUANGASCAR', 136),
(1404, 'HUANTAN', 136),
(1405, 'HUA&Ntilde;EC', 136),
(1406, 'LARAOS', 136),
(1407, 'LINCHA', 136),
(1408, 'MADEAN', 136),
(1409, 'MIRAFLORES', 136),
(1410, 'OMAS', 136),
(1411, 'PUTINZA', 136),
(1412, 'QUINCHES', 136),
(1413, 'QUINOCAY', 136),
(1414, 'SAN JOAQUIN', 136),
(1415, 'SAN PEDRO DE PILAS', 136),
(1416, 'TANTA', 136),
(1417, 'TAURIPAMPA', 136),
(1418, 'TOMAS', 136),
(1419, 'TUPE', 136),
(1420, 'VI&Ntilde;AC', 136),
(1421, 'VITIS', 136),
(1422, 'IQUITOS', 137),
(1423, 'ALTO NANAY', 137),
(1424, 'FERNANDO LORES', 137),
(1425, 'INDIANA', 137),
(1426, 'LAS AMAZONAS', 137),
(1427, 'MAZAN', 137),
(1428, 'NAPO', 137),
(1429, 'PUNCHANA', 137),
(1430, 'PUTUMAYO', 137),
(1431, 'TORRES CAUSANA', 137),
(1432, 'BELEN', 137),
(1433, 'SAN JUAN BAUTISTA', 137),
(1434, 'YURIMAGUAS', 138),
(1435, 'BALSAPUERTO', 138),
(1436, 'BARRANCA', 138),
(1437, 'CAHUAPANAS', 138),
(1438, 'JEBEROS', 138),
(1439, 'LAGUNAS', 138),
(1440, 'MANSERICHE', 138),
(1441, 'MORONA', 138),
(1442, 'PASTAZA', 138),
(1443, 'SANTA CRUZ', 138),
(1444, 'TENIENTE CESAR LOPEZ ROJAS', 138),
(1445, 'NAUTA', 139),
(1446, 'PARINARI', 139),
(1447, 'TIGRE', 139),
(1448, 'TROMPETEROS', 139),
(1449, 'URARINAS', 139),
(1450, 'RAMON CASTILLA', 140),
(1451, 'PEBAS', 140),
(1452, 'YAVARI', 140),
(1453, 'SAN PABLO', 140),
(1454, 'REQUENA', 141),
(1455, 'ALTO TAPICHE', 141),
(1456, 'CAPELO', 141),
(1457, 'EMILIO SAN MARTIN', 141),
(1458, 'MAQUIA', 141),
(1459, 'PUINAHUA', 141),
(1460, 'SAQUENA', 141),
(1461, 'SOPLIN', 141),
(1462, 'TAPICHE', 141),
(1463, 'JENARO HERRERA', 141),
(1464, 'YAQUERANA', 141),
(1465, 'CONTAMANA', 142),
(1466, 'INAHUAYA', 142),
(1467, 'PADRE MARQUEZ', 142),
(1468, 'PAMPA HERMOSA', 142),
(1469, 'SARAYACU', 142),
(1470, 'VARGAS GUERRA', 142),
(1471, 'TAMBOPATA', 143),
(1472, 'INAMBARI', 143),
(1473, 'LAS PIEDRAS', 143),
(1474, 'LABERINTO', 143),
(1475, 'MANU', 144),
(1476, 'FITZCARRALD', 144),
(1477, 'MADRE DE DIOS', 144),
(1478, 'HUEPETUHE', 144),
(1479, 'I&Ntilde;APARI', 145),
(1480, 'IBERIA', 145),
(1481, 'TAHUAMANU', 145),
(1482, 'MOQUEGUA', 146),
(1483, 'CARUMAS', 146),
(1484, 'CUCHUMBAYA', 146),
(1485, 'SAMEGUA', 146),
(1486, 'SAN CRISTOBAL', 146),
(1487, 'TORATA', 146),
(1488, 'OMATE', 147),
(1489, 'CHOJATA', 147),
(1490, 'COALAQUE', 147),
(1491, 'ICHU&Ntilde;A', 147),
(1492, 'LA CAPILLA', 147),
(1493, 'LLOQUE', 147),
(1494, 'MATALAQUE', 147),
(1495, 'PUQUINA', 147),
(1496, 'QUINISTAQUILLAS', 147),
(1497, 'UBINAS', 147),
(1498, 'YUNGA', 147),
(1499, 'ILO', 148),
(1500, 'EL ALGARROBAL', 148),
(1501, 'PACOCHA', 148),
(1502, 'CHAUPIMARCA', 149),
(1503, 'HUACHON', 149),
(1504, 'HUARIACA', 149),
(1505, 'HUAYLLAY', 149),
(1506, 'NINACACA', 149),
(1507, 'PALLANCHACRA', 149),
(1508, 'PAUCARTAMBO', 149),
(1509, 'SAN FCO.DE ASIS DE YARUSYACAN', 149),
(1510, 'SIMON BOLIVAR', 149),
(1511, 'TICLACAYAN', 149),
(1512, 'TINYAHUARCO', 149),
(1513, 'VICCO', 149),
(1514, 'YANACANCHA', 149),
(1515, 'YANAHUANCA', 150),
(1516, 'CHACAYAN', 150),
(1517, 'GOYLLARISQUIZGA', 150),
(1518, 'PAUCAR', 150),
(1519, 'SAN PEDRO DE PILLAO', 150),
(1520, 'SANTA ANA DE TUSI', 150),
(1521, 'TAPUC', 150),
(1522, 'VILCABAMBA', 150),
(1523, 'OXAPAMPA', 151),
(1524, 'CHONTABAMBA', 151),
(1525, 'HUANCABAMBA', 151),
(1526, 'PALCAZU', 151),
(1527, 'POZUZO', 151),
(1528, 'PUERTO BERMUDEZ', 151),
(1529, 'VILLA RICA', 151),
(1530, 'PIURA', 152),
(1531, 'CASTILLA', 152),
(1532, 'CATACAOS', 152),
(1533, 'CURA MORI', 152),
(1534, 'EL TALLAN', 152),
(1535, 'LA ARENA', 152),
(1536, 'LA UNION', 152),
(1537, 'LAS LOMAS', 152),
(1538, 'TAMBO GRANDE', 152),
(1539, 'AYABACA', 153),
(1540, 'FRIAS', 153),
(1541, 'JILILI', 153),
(1542, 'LAGUNAS', 153),
(1543, 'MONTERO', 153),
(1544, 'PACAIPAMPA', 153),
(1545, 'PAIMAS', 153),
(1546, 'SAPILLICA', 153),
(1547, 'SICCHEZ', 153),
(1548, 'SUYO', 153),
(1549, 'HUANCABAMBA', 154),
(1550, 'CANCHAQUE', 154),
(1551, 'EL CARMEN DE LA FRONTERA', 154),
(1552, 'HUARMACA', 154),
(1553, 'LALAQUIZ', 154),
(1554, 'SAN MIGUEL DE EL FAIQUE', 154),
(1555, 'SONDOR', 154),
(1556, 'SONDORILLO', 154),
(1557, 'CHULUCANAS', 155),
(1558, 'BUENOS AIRES', 155),
(1559, 'CHALACO', 155),
(1560, 'LA MATANZA', 155),
(1561, 'MORROPON', 155),
(1562, 'SALITRAL', 155),
(1563, 'SAN JUAN DE BIGOTE', 155),
(1564, 'SANTA CATALINA DE MOSSA', 155),
(1565, 'SANTO DOMINGO', 155),
(1566, 'YAMANGO', 155),
(1567, 'PAITA', 156),
(1568, 'AMOTAPE', 156),
(1569, 'ARENAL', 156),
(1570, 'COLAN', 156),
(1571, 'LA HUACA', 156),
(1572, 'TAMARINDO', 156),
(1573, 'VICHAYAL', 156),
(1574, 'SULLANA', 157),
(1575, 'BELLAVISTA', 157),
(1576, 'IGNACIO ESCUDERO', 157),
(1577, 'LANCONES', 157),
(1578, 'MARCAVELICA', 157),
(1579, 'MIGUEL CHECA', 157),
(1580, 'QUERECOTILLO', 157),
(1581, 'SALITRAL', 157),
(1582, 'PARI&Ntilde;AS', 158),
(1583, 'EL ALTO', 158),
(1584, 'LA BREA', 158),
(1585, 'LOBITOS', 158),
(1586, 'LOS ORGANOS', 158),
(1587, 'MANCORA', 158),
(1588, 'SECHURA', 159),
(1589, 'BELLAVISTA DE LA UNION', 159),
(1590, 'BERNAL', 159),
(1591, 'CRISTO NOS VALGA', 159),
(1592, 'VICE', 159),
(1593, 'RINCONADA LLICUAR', 159),
(1594, 'PUNO', 160),
(1595, 'ACORA', 160),
(1596, 'AMANTANI', 160),
(1597, 'ATUNCOLLA', 160),
(1598, 'CAPACHICA', 160),
(1599, 'CHUCUITO', 160),
(1600, 'COATA', 160),
(1601, 'HUATA', 160),
(1602, 'MA&Ntilde;AZO', 160),
(1603, 'PAUCARCOLLA', 160),
(1604, 'PICHACANI', 160),
(1605, 'PLATERIA', 160),
(1606, 'SAN ANTONIO', 160),
(1607, 'TIQUILLACA', 160),
(1608, 'VILQUE', 160),
(1609, 'AZANGARO', 161),
(1610, 'ACHAYA', 161),
(1611, 'ARAPA', 161),
(1612, 'ASILLO', 161),
(1613, 'CAMINACA', 161),
(1614, 'CHUPA', 161),
(1615, 'JOSE DOMINGO CHOQUEHUANCA', 161),
(1616, 'MU&Ntilde;ANI', 161),
(1617, 'POTONI', 161),
(1618, 'SAMAN', 161),
(1619, 'SAN ANTON', 161),
(1620, 'SAN JOSE', 161),
(1621, 'SAN JUAN DE SALINAS', 161),
(1622, 'SANTIAGO DE PUPUJA', 161),
(1623, 'TIRAPATA', 161),
(1624, 'MACUSANI', 162),
(1625, 'AJOYANI', 162),
(1626, 'AYAPATA', 162),
(1627, 'COASA', 162),
(1628, 'CORANI', 162),
(1629, 'CRUCERO', 162),
(1630, 'ITUATA', 162),
(1631, 'OLLACHEA', 162),
(1632, 'SAN GABAN', 162),
(1633, 'USICAYOS', 162),
(1634, 'JULI', 163),
(1635, 'DESAGUADERO', 163),
(1636, 'HUACULLANI', 163),
(1637, 'KELLUYO', 163),
(1638, 'PISACOMA', 163),
(1639, 'POMATA', 163),
(1640, 'ZEPITA', 163),
(1641, 'ILAVE', 164),
(1642, 'CAPAZO', 164),
(1643, 'PILCUYO', 164),
(1644, 'SANTA ROSA', 164),
(1645, 'CONDURIRI', 164),
(1646, 'HUANCANE', 165),
(1647, 'COJATA', 165),
(1648, 'HUATASANI', 165),
(1649, 'INCHUPALLA', 165),
(1650, 'PUSI', 165),
(1651, 'ROSASPATA', 165),
(1652, 'TARACO', 165),
(1653, 'VILQUE CHICO', 165),
(1654, 'LAMPA', 166),
(1655, 'CABANILLA', 166),
(1656, 'CALAPUJA', 166),
(1657, 'NICASIO', 166),
(1658, 'OCUVIRI', 166),
(1659, 'PALCA', 166),
(1660, 'PARATIA', 166),
(1661, 'PUCARA', 166),
(1662, 'SANTA LUCIA', 166),
(1663, 'VILAVILA', 166),
(1664, 'AYAVIRI', 167),
(1665, 'ANTAUTA', 167),
(1666, 'CUPI', 167),
(1667, 'LLALLI', 167),
(1668, 'MACARI', 167),
(1669, 'NU&Ntilde;OA', 167),
(1670, 'ORURILLO', 167),
(1671, 'SANTA ROSA', 167),
(1672, 'UMACHIRI', 167),
(1673, 'MOHO', 168),
(1674, 'CONIMA', 168),
(1675, 'HUAYRAPATA', 168),
(1676, 'TILALI', 168),
(1677, 'PUTINA', 169),
(1678, 'ANANEA', 169),
(1679, 'PEDRO VILCA APAZA', 169),
(1680, 'QUILCAPUNCU', 169),
(1681, 'SINA', 169),
(1682, 'JULIACA', 170),
(1683, 'CABANA', 170),
(1684, 'CABANILLAS', 170),
(1685, 'CARACOTO', 170),
(1686, 'SANDIA', 171),
(1687, 'CUYOCUYO', 171),
(1688, 'LIMBANI', 171),
(1689, 'PATAMBUCO', 171),
(1690, 'PHARA', 171),
(1691, 'QUIACA', 171),
(1692, 'SAN JUAN DEL ORO', 171),
(1693, 'YANAHUAYA', 171),
(1694, 'ALTO INAMBARI', 171),
(1695, 'YUNGUYO', 172),
(1696, 'ANAPIA', 172),
(1697, 'COPANI', 172),
(1698, 'CUTURAPI', 172),
(1699, 'OLLARAYA', 172),
(1700, 'TINICACHI', 172),
(1701, 'UNICACHI', 172),
(1702, 'MOYOBAMBA', 173),
(1703, 'CALZADA', 173),
(1704, 'HABANA', 173),
(1705, 'JEPELACIO', 173),
(1706, 'SORITOR', 173),
(1707, 'YANTALO', 173),
(1708, 'BELLAVISTA', 174),
(1709, 'ALTO BIAVO', 174),
(1710, 'BAJO BIAVO', 174),
(1711, 'HUALLAGA', 174),
(1712, 'SAN PABLO', 174),
(1713, 'SAN RAFAEL', 174),
(1714, 'SAN JOSE DE SISA', 175),
(1715, 'AGUA BLANCA', 175),
(1716, 'SAN MARTIN', 175),
(1717, 'SANTA ROSA', 175),
(1718, 'SHATOJA', 175),
(1719, 'SAPOSOA', 176),
(1720, 'ALTO SAPOSOA', 176),
(1721, 'EL ESLABON', 176),
(1722, 'PISCOYACU', 176),
(1723, 'SACANCHE', 176),
(1724, 'TINGO DE SAPOSOA', 176),
(1725, 'LAMAS', 177),
(1726, 'ALONSO DE ALVARADO', 177),
(1727, 'BARRANQUITA', 177),
(1728, 'CAYNARACHI', 177),
(1729, 'CU&Ntilde;UMBUQUI', 177),
(1730, 'PINTO RECODO', 177),
(1731, 'RUMISAPA', 177),
(1732, 'SAN ROQUE DE CUMBAZA', 177),
(1733, 'SHANAO', 177),
(1734, 'TABALOSOS', 177),
(1735, 'ZAPATERO', 177),
(1736, 'JUANJUI', 178),
(1737, 'CAMPANILLA', 178),
(1738, 'HUICUNGO', 178),
(1739, 'PACHIZA', 178),
(1740, 'PAJARILLO', 178),
(1741, 'PICOTA', 179),
(1742, 'BUENOS AIRES', 179),
(1743, 'CASPISAPA', 179),
(1744, 'PILLUANA', 179),
(1745, 'PUCACACA', 179),
(1746, 'SAN CRISTOBAL', 179),
(1747, 'SAN HILARION', 179),
(1748, 'SHAMBOYACU', 179),
(1749, 'TINGO DE PONASA', 179),
(1750, 'TRES UNIDOS', 179),
(1751, 'RIOJA', 180),
(1752, 'AWAJUN', 180),
(1753, 'ELIAS SOPLIN VARGAS', 180),
(1754, 'NUEVA CAJAMARCA', 180),
(1755, 'PARDO MIGUEL', 180),
(1756, 'POSIC', 180),
(1757, 'SAN FERNANDO', 180),
(1758, 'YORONGOS', 180),
(1759, 'YURACYACU', 180),
(1760, 'TARAPOTO', 181),
(1761, 'ALBERTO LEVEAU', 181),
(1762, 'CACATACHI', 181),
(1763, 'CHAZUTA', 181),
(1764, 'CHIPURANA', 181),
(1765, 'EL PORVENIR', 181),
(1766, 'HUIMBAYOC', 181),
(1767, 'JUAN GUERRA', 181),
(1768, 'LA BANDA DE SHILCAYO', 181),
(1769, 'MORALES', 181),
(1770, 'PAPAPLAYA', 181),
(1771, 'SAN ANTONIO', 181),
(1772, 'SAUCE', 181),
(1773, 'SHAPAJA', 181),
(1774, 'TOCACHE', 182),
(1775, 'NUEVO PROGRESO', 182),
(1776, 'POLVORA', 182),
(1777, 'SHUNTE', 182),
(1778, 'UCHIZA', 182),
(1779, 'TACNA', 183),
(1780, 'ALTO DE LA ALIANZA', 183),
(1781, 'CALANA', 183),
(1782, 'CIUDAD NUEVA', 183),
(1783, 'INCLAN', 183),
(1784, 'PACHIA', 183),
(1785, 'PALCA', 183),
(1786, 'POCOLLAY', 183),
(1787, 'SAMA', 183),
(1788, 'CORONEL GREGORIO ALBARRACIN LANCHIPA', 183),
(1789, 'CANDARAVE', 184),
(1790, 'CAIRANI', 184),
(1791, 'CAMILACA', 184),
(1792, 'CURIBAYA', 184),
(1793, 'HUANUARA', 184),
(1794, 'QUILAHUANI', 184),
(1795, 'LOCUMBA', 185),
(1796, 'ILABAYA', 185),
(1797, 'ITE', 185),
(1798, 'TARATA', 186),
(1799, 'CHUCATAMANI', 186),
(1800, 'ESTIQUE', 186),
(1801, 'ESTIQUE-PAMPA', 186),
(1802, 'SITAJARA', 186),
(1803, 'SUSAPAYA', 186),
(1804, 'TARUCACHI', 186),
(1805, 'TICACO', 186),
(1806, 'TUMBES', 187),
(1807, 'CORRALES', 187),
(1808, 'LA CRUZ', 187),
(1809, 'PAMPAS DE HOSPITAL', 187),
(1810, 'SAN JACINTO', 187),
(1811, 'SAN JUAN DE LA VIRGEN', 187),
(1812, 'ZORRITOS', 188),
(1813, 'CASITAS', 188),
(1814, 'ZARUMILLA', 189),
(1815, 'AGUAS VERDES', 189),
(1816, 'MATAPALO', 189),
(1817, 'PAPAYAL', 189),
(1818, 'CALLERIA', 190),
(1819, 'CAMPOVERDE', 190),
(1820, 'IPARIA', 190),
(1821, 'MASISEA', 190),
(1822, 'YARINACOCHA', 190),
(1823, 'NUEVA REQUENA', 190),
(1824, 'RAYMONDI', 191),
(1825, 'SEPAHUA', 191),
(1826, 'TAHUANIA', 191),
(1827, 'YURUA', 191),
(1828, 'PADRE ABAD', 192),
(1829, 'IRAZOLA', 192),
(1830, 'CURIMANA', 192),
(1831, 'PURUS', 193);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubprovincia`
--

CREATE TABLE `ubprovincia` (
  `idProv` int(11) NOT NULL DEFAULT '0',
  `provincia` varchar(50) DEFAULT NULL,
  `idDepa` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ubprovincia`
--

INSERT INTO `ubprovincia` (`idProv`, `provincia`, `idDepa`) VALUES
(1, 'CHACHAPOYAS ', 1),
(2, 'BAGUA', 1),
(3, 'BONGARA', 1),
(4, 'CONDORCANQUI', 1),
(5, 'LUYA', 1),
(6, 'RODRIGUEZ DE MENDOZA', 1),
(7, 'UTCUBAMBA', 1),
(8, 'HUARAZ', 2),
(9, 'AIJA', 2),
(10, 'ANTONIO RAYMONDI', 2),
(11, 'ASUNCION', 2),
(12, 'BOLOGNESI', 2),
(13, 'CARHUAZ', 2),
(14, 'CARLOS FERMIN FITZCARRALD', 2),
(15, 'CASMA', 2),
(16, 'CORONGO', 2),
(17, 'HUARI', 2),
(18, 'HUARMEY', 2),
(19, 'HUAYLAS', 2),
(20, 'MARISCAL LUZURIAGA', 2),
(21, 'OCROS', 2),
(22, 'PALLASCA', 2),
(23, 'POMABAMBA', 2),
(24, 'RECUAY', 2),
(25, 'SANTA', 2),
(26, 'SIHUAS', 2),
(27, 'YUNGAY', 2),
(28, 'ABANCAY', 3),
(29, 'ANDAHUAYLAS', 3),
(30, 'ANTABAMBA', 3),
(31, 'AYMARAES', 3),
(32, 'COTABAMBAS', 3),
(33, 'CHINCHEROS', 3),
(34, 'GRAU', 3),
(35, 'AREQUIPA', 4),
(36, 'CAMANA', 4),
(37, 'CARAVELI', 4),
(38, 'CASTILLA', 4),
(39, 'CAYLLOMA', 4),
(40, 'CONDESUYOS', 4),
(41, 'ISLAY', 4),
(42, 'LA UNION', 4),
(43, 'HUAMANGA', 5),
(44, 'CANGALLO', 5),
(45, 'HUANCA SANCOS', 5),
(46, 'HUANTA', 5),
(47, 'LA MAR', 5),
(48, 'LUCANAS', 5),
(49, 'PARINACOCHAS', 5),
(50, 'PAUCAR DEL SARA SARA', 5),
(51, 'SUCRE', 5),
(52, 'VICTOR FAJARDO', 5),
(53, 'VILCAS HUAMAN', 5),
(54, 'CAJAMARCA', 6),
(55, 'CAJABAMBA', 6),
(56, 'CELENDIN', 6),
(57, 'CHOTA ', 6),
(58, 'CONTUMAZA', 6),
(59, 'CUTERVO', 6),
(60, 'HUALGAYOC', 6),
(61, 'JAEN', 6),
(62, 'SAN IGNACIO', 6),
(63, 'SAN MARCOS', 6),
(64, 'SAN PABLO', 6),
(65, 'SANTA CRUZ', 6),
(66, 'CALLAO', 7),
(67, 'CUSCO', 8),
(68, 'ACOMAYO', 8),
(69, 'ANTA', 8),
(70, 'CALCA', 8),
(71, 'CANAS', 8),
(72, 'CANCHIS', 8),
(73, 'CHUMBIVILCAS', 8),
(74, 'ESPINAR', 8),
(75, 'LA CONVENCION', 8),
(76, 'PARURO', 8),
(77, 'PAUCARTAMBO', 8),
(78, 'QUISPICANCHI', 8),
(79, 'URUBAMBA', 8),
(80, 'HUANCAVELICA', 9),
(81, 'ACOBAMBA', 9),
(82, 'ANGARAES', 9),
(83, 'CASTROVIRREYNA', 9),
(84, 'CHURCAMPA', 9),
(85, 'HUAYTARA', 9),
(86, 'TAYACAJA', 9),
(87, 'HUANUCO', 10),
(88, 'AMBO', 10),
(89, 'DOS DE MAYO', 10),
(90, 'HUACAYBAMBA', 10),
(91, 'HUAMALIES', 10),
(92, 'LEONCIO PRADO', 10),
(93, 'MARA&Ntilde;ON', 10),
(94, 'PACHITEA', 10),
(95, 'PUERTO INCA', 10),
(96, 'LAURICOCHA', 10),
(97, 'YAROWILCA', 10),
(98, 'ICA', 11),
(99, 'CHINCHA', 11),
(100, 'NAZCA', 11),
(101, 'PALPA', 11),
(102, 'PISCO', 11),
(103, 'HUANCAYO', 12),
(104, 'CONCEPCION', 12),
(105, 'CHANCHAMAYO', 12),
(106, 'JAUJA', 12),
(107, 'JUNIN', 12),
(108, 'SATIPO', 12),
(109, 'TARMA', 12),
(110, 'YAULI', 12),
(111, 'CHUPACA', 12),
(112, 'TRUJILLO', 13),
(113, 'ASCOPE', 13),
(114, 'BOLIVAR', 13),
(115, 'CHEPEN', 13),
(116, 'JULCAN', 13),
(117, 'OTUZCO', 13),
(118, 'PACASMAYO', 13),
(119, 'PATAZ', 13),
(120, 'SANCHEZ CARRION', 13),
(121, 'SANTIAGO DE CHUCO', 13),
(122, 'GRAN CHIMU', 13),
(123, 'VIRU', 13),
(124, 'CHICLAYO', 14),
(125, 'FERRE&Ntilde;AFE', 14),
(126, 'LAMBAYEQUE', 14),
(127, 'LIMA', 15),
(128, 'BARRANCA', 15),
(129, 'CAJATAMBO', 15),
(130, 'CANTA', 15),
(131, 'CA&Ntilde;ETE', 15),
(132, 'HUARAL', 15),
(133, 'HUAROCHIRI', 15),
(134, 'HUAURA', 15),
(135, 'OYON', 15),
(136, 'YAUYOS', 15),
(137, 'MAYNAS', 16),
(138, 'ALTO AMAZONAS', 16),
(139, 'LORETO', 16),
(140, 'MARISCAL RAMON CASTILLA', 16),
(141, 'REQUENA', 16),
(142, 'UCAYALI', 16),
(143, 'TAMBOPATA', 17),
(144, 'MANU', 17),
(145, 'TAHUAMANU', 17),
(146, 'MARISCAL NIETO', 18),
(147, 'GENERAL SANCHEZ CERRO', 18),
(148, 'ILO', 18),
(149, 'PASCO', 19),
(150, 'DANIEL ALCIDES CARRION', 19),
(151, 'OXAPAMPA', 19),
(152, 'PIURA', 20),
(153, 'AYABACA', 20),
(154, 'HUANCABAMBA', 20),
(155, 'MORROPON', 20),
(156, 'PAITA', 20),
(157, 'SULLANA', 20),
(158, 'TALARA', 20),
(159, 'SECHURA', 20),
(160, 'PUNO', 21),
(161, 'AZANGARO', 21),
(162, 'CARABAYA', 21),
(163, 'CHUCUITO', 21),
(164, 'EL COLLAO', 21),
(165, 'HUANCANE', 21),
(166, 'LAMPA', 21),
(167, 'MELGAR', 21),
(168, 'MOHO', 21),
(169, 'SAN ANTONIO DE PUTINA', 21),
(170, 'SAN ROMAN', 21),
(171, 'SANDIA', 21),
(172, 'YUNGUYO', 21),
(173, 'MOYOBAMBA', 22),
(174, 'BELLAVISTA', 22),
(175, 'EL DORADO', 22),
(176, 'HUALLAGA', 22),
(177, 'LAMAS', 22),
(178, 'MARISCAL CACERES', 22),
(179, 'PICOTA', 22),
(180, 'RIOJA', 22),
(181, 'SAN MARTIN', 22),
(182, 'TOCACHE', 22),
(183, 'TACNA', 23),
(184, 'CANDARAVE', 23),
(185, 'JORGE BASADRE', 23),
(186, 'TARATA', 23),
(187, 'TUMBES', 24),
(188, 'CONTRALMIRANTE VILLAR', 24),
(189, 'ZARUMILLA', 24),
(190, 'CORONEL PORTILLO', 25),
(191, 'ATALAYA', 25),
(192, 'PADRE ABAD', 25),
(193, 'PURUS', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `umedida`
--

CREATE TABLE `umedida` (
  `idunidad` int(10) UNSIGNED NOT NULL,
  `nombreum` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `abre` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `equivalencia` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `umedida`
--

INSERT INTO `umedida` (`idunidad`, `nombreum`, `abre`, `estado`, `equivalencia`) VALUES
(1, 'BALDE', 'BJ', 0, NULL),
(2, 'BARRILES', 'BLL', 0, NULL),
(3, 'BOBINAS', '4A', 0, NULL),
(4, 'BOLSA', 'BG', 0, NULL),
(5, 'BOTELLAS', 'BO', 1, NULL),
(6, 'CAJAS', 'BX', 1, NULL),
(7, 'CARTONES', 'CT', 1, NULL),
(8, 'CENTIMETRO CUADRADO', 'CMK', 1, 0.00),
(9, 'CENTIMETRO CUBICO', 'CMQ', 1, 0.00),
(10, 'CENTIMETRO LINEAL', 'CMT', 1, 0.01),
(11, 'CIENTO DE UNIDADES', 'CEN', 1, NULL),
(12, 'CILINDRO', 'CY', 1, NULL),
(13, 'CONOS', 'CJ', 1, NULL),
(14, 'DOCENA', 'DZN', 1, 12.00),
(15, 'DOCENA POR 10**6', 'DZP', 1, 12000000.00),
(16, 'FARDO', 'BE', 1, NULL),
(17, 'GALON INGLES (4,545956L)', 'GLI', 1, 4.55),
(18, 'GRAMO', 'GRM', 1, 0.00),
(19, 'GRUESA', 'GRO', 1, 144.00),
(20, 'HELECTROLITO', 'HLT', 1, NULL),
(21, 'HOJA', 'LEF', 1, NULL),
(22, 'JUEGO', 'SET', 1, NULL),
(23, 'KILOGRAMO', 'KGM', 1, 1.00),
(24, 'KILOMETRO', 'KTM', 1, 1000.00),
(25, 'KILOVATIO HORA', 'KWH', 1, NULL),
(26, 'KIT', 'KT', 1, NULL),
(27, 'LATAS', 'CA', 1, NULL),
(28, 'LIBRAS', 'LBR', 1, 0.45),
(29, 'LITROS', 'LTR', 1, 1.00),
(30, 'MEGAWHAT HORA', 'MWH', 1, NULL),
(31, 'METRO', 'MTR', 1, 1.00),
(32, 'METRO CUADRADO', 'MTK', 1, 1.00),
(33, 'METRO CUBICO', 'MTQ', 1, 1.00),
(34, 'MILIGRAMOS', 'MGM', 1, 0.00),
(35, 'MILILITRO', 'MLT', 1, 0.00),
(36, 'MILIMETRO', 'MMT', 1, 0.00),
(37, 'MILIMETRO CUADRADO', 'MMK', 1, 0.00),
(38, 'MILIMETRO CUBICO', 'MMQ', 1, 0.00),
(39, 'MILLARES', 'MLL', 1, 1000.00),
(40, 'MILLON DE UNIDADES', 'MU', 1, 1000000.00),
(41, 'ONZAS', 'ONZ', 1, 0.03),
(42, 'PALETAS', 'PF', 1, NULL),
(43, 'PAQUETE', 'PK', 1, NULL),
(44, 'PAR', 'PR', 1, 2.00),
(45, 'PIES', 'FOT', 1, 0.30),
(46, 'PIES CUADRADOS', 'FTK', 1, 0.09),
(47, 'PIES CUBICOS', 'FTQ', 1, 0.03),
(48, 'PIEZAS', 'C62', 1, NULL),
(49, 'PLACAS', 'PG', 1, NULL),
(50, 'PLIEGO', 'ST', 1, NULL),
(51, 'PULGADAS', 'INH', 1, 0.03),
(52, 'RESMA', 'RM', 1, NULL),
(53, 'TAMBOR', 'DR', 1, NULL),
(54, 'TONELADA CORTA', 'STN', 1, NULL),
(55, 'TONELADA LARGA', 'LTN', 1, NULL),
(56, 'TONELADAS', 'TNE', 1, 1000.00),
(57, 'TUBOS', 'TU', 1, NULL),
(58, 'UNIDADADES', 'NIU', 1, 1.00),
(59, 'SERVICIOS', 'ZZ', 1, NULL),
(60, 'US GALON (3,7843 L)', 'GLL', 1, 3.78),
(61, 'YARDA', 'YRD', 1, 0.91),
(62, 'YARDA CUADRADA', 'YDK', 1, 0.84),
(63, 'SIX PACK', 'P6', 1, 6.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_documento` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `num_documento` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cargo` tinyint(4) DEFAULT NULL,
  `login` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clave` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imagen` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `apellidos`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `cargo`, `login`, `clave`, `imagen`, `condicion`) VALUES
(1, 'ENRIQUE', 'ADMIN', 'DNI', '99999999', 'AV LUIS GONZALES 1315 CERCADO DE CHICLAYO CHICLAYO LAMBAYEQUE', '971971063', 'empresademo@gmail.com', 0, 'ADMIN', '4dbc7590d9fa8efe6a6c90cbb501356f668bbf836dadbfd930ceb36987747790', '1683069524.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_empresa`
--

CREATE TABLE `usuario_empresa` (
  `idusuario` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_empresa`
--

INSERT INTO `usuario_empresa` (`idusuario`, `idempresa`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permiso`
--

CREATE TABLE `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario_permiso`
--

INSERT INTO `usuario_permiso` (`idusuario_permiso`, `idusuario`, `idpermiso`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `utilidadgi`
--

CREATE TABLE `utilidadgi` (
  `idutilidad` int(10) UNSIGNED NOT NULL,
  `fecha1` date DEFAULT NULL,
  `fecha2` date DEFAULT NULL,
  `totalgastos` float(14,2) DEFAULT NULL,
  `totalventas` float(14,2) DEFAULT NULL,
  `utilidad` float(14,2) DEFAULT NULL,
  `porcentaje` float(14,2) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valfinarticulo`
--

CREATE TABLE `valfinarticulo` (
  `id` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `codigoart` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ano` int(11) NOT NULL,
  `costoi` float(14,2) NOT NULL,
  `saldoi` float(14,2) NOT NULL,
  `valori` float(14,2) NOT NULL,
  `costof` float(14,2) NOT NULL,
  `saldof` float(14,2) NOT NULL,
  `valorf` float(14,2) NOT NULL,
  `fechag` date NOT NULL,
  `tcompras` float(14,2) DEFAULT NULL,
  `tventas` float(14,2) DEFAULT NULL,
  `fechain` date DEFAULT NULL,
  `fechaout` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedorsitio`
--

CREATE TABLE `vendedorsitio` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1',
  `idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventadiaria`
--

CREATE TABLE `ventadiaria` (
  `idventa` int(10) UNSIGNED NOT NULL,
  `idcategoriav` int(11) NOT NULL,
  `fecharegistroingreso` date NOT NULL,
  `tipo` char(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `base` float(14,5) DEFAULT NULL,
  `igv` float(14,2) DEFAULT NULL,
  `total` float(14,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ventadiaria`
--

INSERT INTO `ventadiaria` (`idventa`, `idcategoriav`, `fecharegistroingreso`, `tipo`, `base`, `igv`, `total`) VALUES
(1, 0, '2023-10-28', 'efectivot', NULL, NULL, 0.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`idalmacen`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`idarticulo`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `fk_producto_familia_idx` (`idfamilia`),
  ADD KEY `fk_producto_almacen_idx` (`idalmacen`);

--
-- Indices de la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD PRIMARY KEY (`idboleta`),
  ADD UNIQUE KEY `numeracion_07` (`numeracion_07`),
  ADD KEY `fk_boleta_empresa_idx` (`idempresa`),
  ADD KEY `fk_boleta_usuario_idx` (`idusuario`),
  ADD KEY `fk_boleta_usuario_idx1` (`idcliente`);

--
-- Indices de la tabla `boletaempresa`
--
ALTER TABLE `boletaempresa`
  ADD PRIMARY KEY (`idempresab`);

--
-- Indices de la tabla `boletapago`
--
ALTER TABLE `boletapago`
  ADD PRIMARY KEY (`idboletaPago`);

--
-- Indices de la tabla `boletaservicio`
--
ALTER TABLE `boletaservicio`
  ADD PRIMARY KEY (`idboleta`),
  ADD UNIQUE KEY `numeracion_07` (`numeracion_07`),
  ADD KEY `fk_boleta_empresa_idx` (`idempresa`),
  ADD KEY `fk_boleta_usuario_idx` (`idusuario`),
  ADD KEY `fk_boleta_usuario_idx1` (`idcliente`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`idcaja`);

--
-- Indices de la tabla `catalogo1`
--
ALTER TABLE `catalogo1`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo5`
--
ALTER TABLE `catalogo5`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo6`
--
ALTER TABLE `catalogo6`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo7`
--
ALTER TABLE `catalogo7`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo8`
--
ALTER TABLE `catalogo8`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo9`
--
ALTER TABLE `catalogo9`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo10`
--
ALTER TABLE `catalogo10`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo11`
--
ALTER TABLE `catalogo11`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo12`
--
ALTER TABLE `catalogo12`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo14`
--
ALTER TABLE `catalogo14`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo15`
--
ALTER TABLE `catalogo15`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo16`
--
ALTER TABLE `catalogo16`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo17`
--
ALTER TABLE `catalogo17`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo18`
--
ALTER TABLE `catalogo18`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo19`
--
ALTER TABLE `catalogo19`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `catalogo20`
--
ALTER TABLE `catalogo20`
  ADD PRIMARY KEY (`idcatalogo`);

--
-- Indices de la tabla `categoriainsumos`
--
ALTER TABLE `categoriainsumos`
  ADD PRIMARY KEY (`idcategoriai`);

--
-- Indices de la tabla `categoria_plato`
--
ALTER TABLE `categoria_plato`
  ADD PRIMARY KEY (`idcategoria`);

--
-- Indices de la tabla `cierrecaja`
--
ALTER TABLE `cierrecaja`
  ADD PRIMARY KEY (`idcierrecaja`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`idciudad`),
  ADD KEY `fk_ciudad_departamento_idx` (`iddepartamento`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idcompra`),
  ADD KEY `fk_proveedor_persona_idx` (`idproveedor`),
  ADD KEY `fk_compra_usuario_idx` (`idusuario`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`idconfiguracion`);

--
-- Indices de la tabla `correo`
--
ALTER TABLE `correo`
  ADD PRIMARY KEY (`idcorreo`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`idcotizacion`),
  ADD KEY `fk_cotizacion_persona` (`idcliente`),
  ADD KEY `fk_cotizacion_empresa` (`idempresa`);

--
-- Indices de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD PRIMARY KEY (`idcuota`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`iddepartamento`);

--
-- Indices de la tabla `detalle_articulo_almacen`
--
ALTER TABLE `detalle_articulo_almacen`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_articulo_cotizacion`
--
ALTER TABLE `detalle_articulo_cotizacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cotizacion_detalle` (`idcotizacion`);

--
-- Indices de la tabla `detalle_boleta_producto`
--
ALTER TABLE `detalle_boleta_producto`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_boleta_idx` (`idboleta`),
  ADD KEY `fk_detalle_producto_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_boleta_producto_ser`
--
ALTER TABLE `detalle_boleta_producto_ser`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_boleta_idx` (`idboleta`),
  ADD KEY `fk_detalle_producto_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_compra_producto`
--
ALTER TABLE `detalle_compra_producto`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_compra_idx` (`idcompra`),
  ADD KEY `fk_detalle_producto_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_doccobranza`
--
ALTER TABLE `detalle_doccobranza`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_fac_art`
--
ALTER TABLE `detalle_fac_art`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_fact_idx` (`idfactura`),
  ADD KEY `fk_detalle_prod_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_fac_art_ser`
--
ALTER TABLE `detalle_fac_art_ser`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_fact_idx` (`idfactura`),
  ADD KEY `fk_detalle_prod_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_item_liquidacion`
--
ALTER TABLE `detalle_item_liquidacion`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_notacd_art`
--
ALTER TABLE `detalle_notacd_art`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `idnotacd` (`idnotacd`),
  ADD KEY `idarticulo` (`idarticulo`);

--
-- Indices de la tabla `detalle_notapedido_producto`
--
ALTER TABLE `detalle_notapedido_producto`
  ADD PRIMARY KEY (`iddetalle`),
  ADD KEY `fk_detalle_boleta_idx` (`idboleta`),
  ADD KEY `fk_detalle_producto_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_ordenservicio_articulo`
--
ALTER TABLE `detalle_ordenservicio_articulo`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_plato_pedido`
--
ALTER TABLE `detalle_plato_pedido`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_servicio_cotizacion`
--
ALTER TABLE `detalle_servicio_cotizacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_tablaxml_comprobante`
--
ALTER TABLE `detalle_tablaxml_comprobante`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_usuario_numeracion`
--
ALTER TABLE `detalle_usuario_numeracion`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `detalle_usuario_sesion`
--
ALTER TABLE `detalle_usuario_sesion`
  ADD PRIMARY KEY (`iddetalle`);

--
-- Indices de la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD PRIMARY KEY (`iddistrito`),
  ADD KEY `fk_distrito_ciudad_idx` (`idciudad`);

--
-- Indices de la tabla `doccobranza`
--
ALTER TABLE `doccobranza`
  ADD PRIMARY KEY (`idccobranza`);

--
-- Indices de la tabla `empleadoboleta`
--
ALTER TABLE `empleadoboleta`
  ADD PRIMARY KEY (`idempleado`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`);

--
-- Indices de la tabla `enviocorreo`
--
ALTER TABLE `enviocorreo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idfactura`),
  ADD UNIQUE KEY `numeracion_08` (`numeracion_08`),
  ADD KEY `fk_factura_empresa_idx` (`idempresa`),
  ADD KEY `fk_factura_usuario_idx` (`idusuario`),
  ADD KEY `fk_factura_persona_idx` (`idcliente`);

--
-- Indices de la tabla `facturaservicio`
--
ALTER TABLE `facturaservicio`
  ADD PRIMARY KEY (`idfactura`),
  ADD UNIQUE KEY `numeracion_08` (`numeracion_08`),
  ADD KEY `fk_factura_empresa_idx` (`idempresa`),
  ADD KEY `fk_factura_usuario_idx` (`idusuario`),
  ADD KEY `fk_factura_persona_idx` (`idcliente`);

--
-- Indices de la tabla `familia`
--
ALTER TABLE `familia`
  ADD PRIMARY KEY (`idfamilia`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `guia`
--
ALTER TABLE `guia`
  ADD PRIMARY KEY (`idguia`);

--
-- Indices de la tabla `ingresocaja`
--
ALTER TABLE `ingresocaja`
  ADD PRIMARY KEY (`idingreso`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`idinsumo`);

--
-- Indices de la tabla `itemliquidacion`
--
ALTER TABLE `itemliquidacion`
  ADD PRIMARY KEY (`iditemli`);

--
-- Indices de la tabla `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`idkardex`);

--
-- Indices de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD PRIMARY KEY (`idliquidacion`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idempresa` (`idempresa`);

--
-- Indices de la tabla `margenganancia`
--
ALTER TABLE `margenganancia`
  ADD PRIMARY KEY (`idmargeng`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`idmesa`);

--
-- Indices de la tabla `notacd`
--
ALTER TABLE `notacd`
  ADD PRIMARY KEY (`idnota`);

--
-- Indices de la tabla `notapedido`
--
ALTER TABLE `notapedido`
  ADD PRIMARY KEY (`idboleta`),
  ADD UNIQUE KEY `numeracion_07` (`numeracion_07`),
  ADD KEY `fk_boleta_empresa_idx` (`idempresa`),
  ADD KEY `fk_boleta_usuario_idx` (`idusuario`),
  ADD KEY `fk_boleta_usuario_idx1` (`idcliente`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`idnotificacion`);

--
-- Indices de la tabla `numeracion`
--
ALTER TABLE `numeracion`
  ADD PRIMARY KEY (`idnumeracion`),
  ADD UNIQUE KEY `serie_unica` (`serie`);

--
-- Indices de la tabla `ordenservicio`
--
ALTER TABLE `ordenservicio`
  ADD PRIMARY KEY (`idorden`);

--
-- Indices de la tabla `pedidoplatos`
--
ALTER TABLE `pedidoplatos`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `ref_pedcli` (`idcliente`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`idpersona`),
  ADD KEY `fk_persona_catalogo6_idx` (`tipo_documento`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`idplato`);

--
-- Indices de la tabla `reginventariosanos`
--
ALTER TABLE `reginventariosanos`
  ADD PRIMARY KEY (`idregistro`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `registrocopiabd`
--
ALTER TABLE `registrocopiabd`
  ADD PRIMARY KEY (`idregistro`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`idruta`);

--
-- Indices de la tabla `saldocaja`
--
ALTER TABLE `saldocaja`
  ADD PRIMARY KEY (`idsaldoini`);

--
-- Indices de la tabla `salidacaja`
--
ALTER TABLE `salidacaja`
  ADD PRIMARY KEY (`idsalida`);

--
-- Indices de la tabla `servicios_inmuebles`
--
ALTER TABLE `servicios_inmuebles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subarticulo`
--
ALTER TABLE `subarticulo`
  ADD PRIMARY KEY (`idsubarticulo`);

--
-- Indices de la tabla `sunatconfig`
--
ALTER TABLE `sunatconfig`
  ADD PRIMARY KEY (`idcarga`);

--
-- Indices de la tabla `tcambio`
--
ALTER TABLE `tcambio`
  ADD PRIMARY KEY (`idtipocambio`);

--
-- Indices de la tabla `tempnumeracionxml`
--
ALTER TABLE `tempnumeracionxml`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `temporizador`
--
ALTER TABLE `temporizador`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tiposeguro`
--
ALTER TABLE `tiposeguro`
  ADD PRIMARY KEY (`idtipoSeguro`);

--
-- Indices de la tabla `ubdepartamento`
--
ALTER TABLE `ubdepartamento`
  ADD PRIMARY KEY (`idDepa`);

--
-- Indices de la tabla `ubdistrito`
--
ALTER TABLE `ubdistrito`
  ADD PRIMARY KEY (`idDist`);

--
-- Indices de la tabla `ubprovincia`
--
ALTER TABLE `ubprovincia`
  ADD PRIMARY KEY (`idProv`);

--
-- Indices de la tabla `umedida`
--
ALTER TABLE `umedida`
  ADD PRIMARY KEY (`idunidad`),
  ADD UNIQUE KEY `abre` (`abre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`);

--
-- Indices de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD PRIMARY KEY (`idusuario_permiso`),
  ADD KEY `fk_permiso_detalle_idx` (`idpermiso`),
  ADD KEY `fk_usuario_detalle_idx` (`idusuario`);

--
-- Indices de la tabla `utilidadgi`
--
ALTER TABLE `utilidadgi`
  ADD PRIMARY KEY (`idutilidad`);

--
-- Indices de la tabla `valfinarticulo`
--
ALTER TABLE `valfinarticulo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `vendedorsitio`
--
ALTER TABLE `vendedorsitio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventadiaria`
--
ALTER TABLE `ventadiaria`
  ADD PRIMARY KEY (`idventa`),
  ADD UNIQUE KEY `idx_fecha_tipo` (`fecharegistroingreso`,`tipo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `idalmacen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `idarticulo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boleta`
--
ALTER TABLE `boleta`
  MODIFY `idboleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boletaempresa`
--
ALTER TABLE `boletaempresa`
  MODIFY `idempresab` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boletapago`
--
ALTER TABLE `boletapago`
  MODIFY `idboletaPago` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `boletaservicio`
--
ALTER TABLE `boletaservicio`
  MODIFY `idboleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `idcaja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo5`
--
ALTER TABLE `catalogo5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `catalogo6`
--
ALTER TABLE `catalogo6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `catalogo7`
--
ALTER TABLE `catalogo7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `catalogo20`
--
ALTER TABLE `catalogo20`
  MODIFY `idcatalogo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `categoriainsumos`
--
ALTER TABLE `categoriainsumos`
  MODIFY `idcategoriai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria_plato`
--
ALTER TABLE `categoria_plato`
  MODIFY `idcategoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cierrecaja`
--
ALTER TABLE `cierrecaja`
  MODIFY `idcierrecaja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `idciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idcompra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `idconfiguracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `correo`
--
ALTER TABLE `correo`
  MODIFY `idcorreo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `idcotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `idcuota` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `iddepartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `detalle_articulo_almacen`
--
ALTER TABLE `detalle_articulo_almacen`
  MODIFY `iddetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_articulo_cotizacion`
--
ALTER TABLE `detalle_articulo_cotizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_boleta_producto`
--
ALTER TABLE `detalle_boleta_producto`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_boleta_producto_ser`
--
ALTER TABLE `detalle_boleta_producto_ser`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra_producto`
--
ALTER TABLE `detalle_compra_producto`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_doccobranza`
--
ALTER TABLE `detalle_doccobranza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_fac_art`
--
ALTER TABLE `detalle_fac_art`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_fac_art_ser`
--
ALTER TABLE `detalle_fac_art_ser`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_item_liquidacion`
--
ALTER TABLE `detalle_item_liquidacion`
  MODIFY `iddetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_notacd_art`
--
ALTER TABLE `detalle_notacd_art`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_notapedido_producto`
--
ALTER TABLE `detalle_notapedido_producto`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_ordenservicio_articulo`
--
ALTER TABLE `detalle_ordenservicio_articulo`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_plato_pedido`
--
ALTER TABLE `detalle_plato_pedido`
  MODIFY `iddetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_servicio_cotizacion`
--
ALTER TABLE `detalle_servicio_cotizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_tablaxml_comprobante`
--
ALTER TABLE `detalle_tablaxml_comprobante`
  MODIFY `iddetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_usuario_numeracion`
--
ALTER TABLE `detalle_usuario_numeracion`
  MODIFY `iddetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `detalle_usuario_sesion`
--
ALTER TABLE `detalle_usuario_sesion`
  MODIFY `iddetalle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `distrito`
--
ALTER TABLE `distrito`
  MODIFY `iddistrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT de la tabla `doccobranza`
--
ALTER TABLE `doccobranza`
  MODIFY `idccobranza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleadoboleta`
--
ALTER TABLE `empleadoboleta`
  MODIFY `idempleado` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `enviocorreo`
--
ALTER TABLE `enviocorreo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idfactura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturaservicio`
--
ALTER TABLE `facturaservicio`
  MODIFY `idfactura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familia`
--
ALTER TABLE `familia`
  MODIFY `idfamilia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `guia`
--
ALTER TABLE `guia`
  MODIFY `idguia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresocaja`
--
ALTER TABLE `ingresocaja`
  MODIFY `idingreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idinsumo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `itemliquidacion`
--
ALTER TABLE `itemliquidacion`
  MODIFY `iditemli` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kardex`
--
ALTER TABLE `kardex`
  MODIFY `idkardex` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  MODIFY `idliquidacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `margenganancia`
--
ALTER TABLE `margenganancia`
  MODIFY `idmargeng` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `idmesa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notacd`
--
ALTER TABLE `notacd`
  MODIFY `idnota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notapedido`
--
ALTER TABLE `notapedido`
  MODIFY `idboleta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `idnotificacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `numeracion`
--
ALTER TABLE `numeracion`
  MODIFY `idnumeracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ordenservicio`
--
ALTER TABLE `ordenservicio`
  MODIFY `idorden` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidoplatos`
--
ALTER TABLE `pedidoplatos`
  MODIFY `idpedido` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `idplato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reginventariosanos`
--
ALTER TABLE `reginventariosanos`
  MODIFY `idregistro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registrocopiabd`
--
ALTER TABLE `registrocopiabd`
  MODIFY `idregistro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `idruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `saldocaja`
--
ALTER TABLE `saldocaja`
  MODIFY `idsaldoini` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salidacaja`
--
ALTER TABLE `salidacaja`
  MODIFY `idsalida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios_inmuebles`
--
ALTER TABLE `servicios_inmuebles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subarticulo`
--
ALTER TABLE `subarticulo`
  MODIFY `idsubarticulo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sunatconfig`
--
ALTER TABLE `sunatconfig`
  MODIFY `idcarga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tcambio`
--
ALTER TABLE `tcambio`
  MODIFY `idtipocambio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tempnumeracionxml`
--
ALTER TABLE `tempnumeracionxml`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `temporizador`
--
ALTER TABLE `temporizador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tiposeguro`
--
ALTER TABLE `tiposeguro`
  MODIFY `idtipoSeguro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `umedida`
--
ALTER TABLE `umedida`
  MODIFY `idunidad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  MODIFY `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `utilidadgi`
--
ALTER TABLE `utilidadgi`
  MODIFY `idutilidad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `valfinarticulo`
--
ALTER TABLE `valfinarticulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vendedorsitio`
--
ALTER TABLE `vendedorsitio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventadiaria`
--
ALTER TABLE `ventadiaria`
  MODIFY `idventa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `fk_producto_almacen` FOREIGN KEY (`idalmacen`) REFERENCES `almacen` (`idalmacen`),
  ADD CONSTRAINT `fk_producto_familia` FOREIGN KEY (`idfamilia`) REFERENCES `familia` (`idfamilia`);

--
-- Filtros para la tabla `boleta`
--
ALTER TABLE `boleta`
  ADD CONSTRAINT `boleta_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`),
  ADD CONSTRAINT `fk_boleta_empresa` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `fk_boleta_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD CONSTRAINT `fk_ciudad_departamento` FOREIGN KEY (`iddepartamento`) REFERENCES `departamento` (`iddepartamento`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fk_compra_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`),
  ADD CONSTRAINT `fk_proveedor_persona` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`);

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `fk_cotizacion_empresa` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `fk_cotizacion_persona` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`);

--
-- Filtros para la tabla `detalle_articulo_cotizacion`
--
ALTER TABLE `detalle_articulo_cotizacion`
  ADD CONSTRAINT `fk_cotizacion_detalle` FOREIGN KEY (`idcotizacion`) REFERENCES `cotizacion` (`idcotizacion`);

--
-- Filtros para la tabla `detalle_boleta_producto`
--
ALTER TABLE `detalle_boleta_producto`
  ADD CONSTRAINT `fk_detalleb_boleta` FOREIGN KEY (`idboleta`) REFERENCES `boleta` (`idboleta`),
  ADD CONSTRAINT `fk_detalleb_producto` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `detalle_compra_producto`
--
ALTER TABLE `detalle_compra_producto`
  ADD CONSTRAINT `fk_detalle_compra` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`),
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `detalle_fac_art`
--
ALTER TABLE `detalle_fac_art`
  ADD CONSTRAINT `fk_detallef_fact` FOREIGN KEY (`idfactura`) REFERENCES `factura` (`idfactura`),
  ADD CONSTRAINT `fk_detallef_prod` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `detalle_notacd_art`
--
ALTER TABLE `detalle_notacd_art`
  ADD CONSTRAINT `detalle_notacd_art_ibfk_1` FOREIGN KEY (`idnotacd`) REFERENCES `notacd` (`idnota`);

--
-- Filtros para la tabla `distrito`
--
ALTER TABLE `distrito`
  ADD CONSTRAINT `fk_distrito_ciudad` FOREIGN KEY (`idciudad`) REFERENCES `ciudad` (`idciudad`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_factura_empresa` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  ADD CONSTRAINT `fk_factura_persona` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`),
  ADD CONSTRAINT `fk_factura_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`);

--
-- Filtros para la tabla `liquidacion`
--
ALTER TABLE `liquidacion`
  ADD CONSTRAINT `liquidacion_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`),
  ADD CONSTRAINT `liquidacion_ibfk_2` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`);

--
-- Filtros para la tabla `pedidoplatos`
--
ALTER TABLE `pedidoplatos`
  ADD CONSTRAINT `ref_pedcli` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`);

--
-- Filtros para la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD CONSTRAINT `fk_permiso_detalle` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`),
  ADD CONSTRAINT `fk_usuario_detalle` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
