USE cafeteria;
CREATE TABLE registros (
  matricula INT(10) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  carrera VARCHAR(100) NOT NULL,
  grupo VARCHAR(10) NOT NULL
);


CREATE TABLE menu (
    id_comida     INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(30),
    precio        INT(5),
    descripcion   VARCHAR(250),
    imagen        VARCHAR(100),
    categoria     VARCHAR(30),
    disponibilidad BOOLEAN
);

CREATE TABLE orden (
    id_orden      INT AUTO_INCREMENT PRIMARY KEY,
    matricula     int(10),
    fecha         DATE DEFAULT (CURRENT_DATE),
    hora          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (matricula) REFERENCES registros(matricula)
);


CREATE TABLE compra (
    id_compra       INT AUTO_INCREMENT PRIMARY KEY,
    id_orden        INT,
    id_comida       INT,
    precio          INT(5),
    cantidad        INT,
    precio_total    INT(5),
    FOREIGN KEY (id_orden) REFERENCES orden(id_orden),
    FOREIGN KEY (id_comida) REFERENCES menu(id_comida)
);



LOAD DATA INFILE 'C:\xampp\htdocs\Proyecto integrador\comida.csv'
INTO TABLE menu
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SHOW VARIABLES LIKE 'secure_file_priv';
secure-file-priv="C:/ProgramData/MySQL/MySQL Server 8.0/Uploads"

LOAD DATA LOCAL INFILE 'C:\xampp\htdocs\Proyecto integrador\comida.csv' INTO TABLE menu FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS;
ALTER TABLE registros
ADD COLUMN apellidoPaterno VARCHAR(100) NOT NULL,
ADD COLUMN apellidoMaterno VARCHAR(100) NOT NULL,
ADD COLUMN contraseña VARCHAR(255) NOT NULL,
ADD COLUMN periodo VARCHAR(20) NOT NULL;
