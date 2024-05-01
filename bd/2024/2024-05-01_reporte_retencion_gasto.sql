set seach_path to imbacasa;
INSERT INTO parametros_empresa(
            id_parametros_empresa, nombre_parametro, valor_parametro)
VALUES (23, 'formato_imperesion_retencion_gasto', 33);

INSERT INTO parametros_tipos_formato_impresion(
            id_tipo_formato, nombre_formato)
    VALUES (6, 'RETENCION GASTO');

INSERT INTO parametros_formatos_impresion(
            id_formato, nombre_formato, archivo_formato, id_tipo_formato)
    VALUES (33, 'Retencion Gasto 1', 'generarPDFReten_impri_gasto.php', 6);
