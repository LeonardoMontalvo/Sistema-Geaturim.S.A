set search_path to imbacasa;

alter table tarifa_impuesto 
add column valor numeric;

alter table tarifa_impuesto 
add column estado text;

INSERT INTO tarifa_impuesto(
    id_taimpuesto, 
    id_timpu, 
    codigo_taimpuesto, 
    nombre_taimpuesto, 
    descripcion_taimpuesto, 
    valor,
    estado)
VALUES (6, 1, '4', '15%', null, null, null),
(7, 1, '5', '5%', null,null, null),
(8, 1, '10', '13%', null,null, null);


update tarifa_impuesto
set valor = 0,estado='Activo'
where codigo_taimpuesto='0';

update tarifa_impuesto
set valor = 12,estado='Pasivo'
where codigo_taimpuesto='2';

update tarifa_impuesto
set valor = 14,estado='Pasivo'
where codigo_taimpuesto='3';

update tarifa_impuesto
set valor = 15,estado='Activo'
where codigo_taimpuesto='4';

update tarifa_impuesto
set valor = 5,estado='Activo'
where codigo_taimpuesto='5';

update tarifa_impuesto
set valor = 0,estado='Pasivo'
where codigo_taimpuesto='6';

update tarifa_impuesto
set valor = 0,estado='Pasivo'
where codigo_taimpuesto='7';

update tarifa_impuesto
set valor = null,estado='Pasivo'
where codigo_taimpuesto='8';

update tarifa_impuesto
set valor = 13,estado='Pasivo'
where codigo_taimpuesto='10';