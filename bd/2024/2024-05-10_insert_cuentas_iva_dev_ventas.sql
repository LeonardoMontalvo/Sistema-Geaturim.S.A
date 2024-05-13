set search_path to imbacasa;
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.10.01.03', 'DEVOL. EN VENTA TARIFA 5% IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.10.01.04', 'DEVOL. EN VENTA TARIFA 15% IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.10.01.05', 'DEVOL. EN VENTA TARIFA IVA DIFERENCIADO', 'M', 'Activo');
