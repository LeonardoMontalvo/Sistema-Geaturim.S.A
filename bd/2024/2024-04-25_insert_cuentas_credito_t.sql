set search_path to imbacasa;
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '1.1.02.12.01.03 ', 'CREDITO TRIBUTARIO   T 15%  IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '1.1.02.12.01.04 ', 'CREDITO TRIBUTARIO   T 5%  IVA', 'M', 'Activo');
INSERT INTO plan_cuentas(id_plan_cuentas, codigo_plan, descripcion, cuenta, estado)
    VALUES ((select max(id_plan_cuentas)+1 from plan_cuentas), '1.1.02.12.01.05 ', 'CREDITO TRIBUTARIO   T IVA DIFERENCIADO', 'M', 'Activo');
