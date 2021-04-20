create database siscaval;
use siscaval;
select * from dependences;
select * from users;
delete from users where id =21;
insert into dependences(cost_center,description,email,status,created_at,updated_at) values ('CO0001','Administracion','poli@elpoli.edu.co',1,current_time(),current_time());

delete from dependences where id = 6;