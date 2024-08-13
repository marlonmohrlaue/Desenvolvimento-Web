create database quadrado;
use quadrado;

CREATE TABLE quadrados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_unidade INT NOT NULL, 
	lado INT NOT NULL,
    cor VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_unidade) REFERENCES unidades(id) 
);


CREATE TABLE unidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL, 
    unidade VARCHAR(10) NOT NULL 
);
    