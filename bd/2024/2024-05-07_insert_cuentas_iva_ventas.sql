set search_path to imbacasa;
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.01.01.03', 'VENTAS Q TARIFA 5% IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.01.01.04', 'VENTAS Q TARIFA 15% IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '4.1.01.01.05', 'VENTAS Q TARIFA IVA DIFERENCIADO', 'M', 'Activo');

INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '2.1.07.01.01.02', 'IMPTO AL VALOR AGREGADO VTAS T 5%', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '2.1.07.01.01.03', 'IMPTO AL VALOR AGREGADO VTAS T 15%', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '2.1.07.01.01.04', 'IMPTO AL VALOR AGREGADO VTAS T DIFERENCIADO', 'M', 'Activo');
