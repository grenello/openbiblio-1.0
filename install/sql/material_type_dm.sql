create table material_type_dm (
  code smallint auto_increment primary key
  ,description varchar(40) not null
  ,default_flg char(1) not null
  ,adult_checkout_limit tinyint unsigned not null
  ,juvenile_checkout_limit tinyint unsigned not null
  ,image_file varchar(128) null
)
;

insert into material_type_dm values (null,'audio tapes','N',10,5,'tape.gif');
insert into material_type_dm values (null,'book','Y',20,10,'book.gif');
insert into material_type_dm values (null,'cd audio','N',10,5,'cd.gif');
insert into material_type_dm values (null,'cd computer','N',5,3,'cd.gif');
insert into material_type_dm values (null,'equipment','N',3,0,'case.gif');
insert into material_type_dm values (null,'magazines','N',10,5,'mag.gif');
insert into material_type_dm values (null,'maps','N',5,3,'map.gif');
insert into material_type_dm values (null,'video/dvd','N',5,3,'camera.gif');

