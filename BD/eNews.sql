create database esportNews;

use esportNews;

create table usuario(
	idUsuario int auto_increment primary key not null,
	nomeUsuario	varchar(100),
    nivel int,
    statusUsuario char(1), 
	sexo char(1),
    email varchar(100),
    senha char(40),
    foto varchar(37)
);

create table categoria(
	idCategoria int primary key auto_increment not null,
    nomeCategoria varchar(20)
);

insert into categoria (nomeCategoria) 
values	('FPS'),
		('RPG'),
        ('MOBA'),
        ('AVENTURA'),
        ('AÇÃO'),
        ('CORRIDA'),
        ('ESPORTE');

create table noticias(
	idNoticia int auto_increment primary key not null,
	tituloNoticia VARCHAR(200),
	dataNoticia VARCHAR(16),
    idCategoria int,
	foreign key (idCategoria) references categoria(idCategoria),
	idUsuario int,
	foreign key (idUsuario) references usuario(idUsuario),
    conteudoNoticia text
);

drop table noticias;

create table comentario (
	idComentario int primary key not null auto_increment,
	comentario varchar (255),
    dataComentario VARCHAR(16),
    idNoticia int,
    foreign key (idNoticia) references noticias(idNoticia),
	idUsuario int,
	foreign key (idUsuario) references usuario(idUsuario),
    foto varchar(37),
    status_comentario int 
);

drop table comentario;

select * from comentario;
insert into usuario(nomeUsuario,nivel, statusUsuario, sexo, email, senha, foto)VALUES
('admin',3,0,'M','admin@admin.com','21232f297a57a5a743894a0e4a801fc3', 'admin.jpg');
select*from usuario;


DELETE FROM comentario WHERE idComentario = 19