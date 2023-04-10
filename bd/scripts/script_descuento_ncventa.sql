with x as(
INSERT INTO plan_cuentas(
id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.09.03', '(-) DESCUENTO POR CONCEPTO', 'G', 'Activo')returning id_plan_cuentas
),
y as(
INSERT INTO plan_cuentas(
id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
VALUES ((select id_plan_cuentas+1 from x), '4.1.09.03.01', '(-) DESCUENTO POR CONCEPTO', 'M', 'Activo') returning id_plan_cuentas
)
INSERT INTO parametros(id_parametro, descripcion, valor, cuenta_debito, cuenta_credito)
VALUES (
(select max(id_parametro)+1 from  parametros), 
'DSCTOS VENTAS POR CONCEPTO', null, (select id_plan_cuentas from y), null);
