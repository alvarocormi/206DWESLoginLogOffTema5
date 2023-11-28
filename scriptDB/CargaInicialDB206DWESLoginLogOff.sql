  -- Me posiciono en la base de datos
USE DB206DWESLoginLogOff;

-- Inserto los datos iniciales en la tabla Departamento
INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio, T02_FechaBajaDepartamento) VALUES
('AAA', 'Departamento de Ventas', NOW(), 100000.50, NULL),
('AAB', 'Departamento de Marketing', NOW(), 50089.50, NULL),
('AAC', 'Departamento de Finanzas', NOW(), 600.50, '2023-11-13 13:06:00');

-- Inserto los datos iniciales en la tabla T01_Usuario con contraseñas cifradas en SHA-256
-- Insetar campos en la tabla usuarios

INSERT INTO T01_Usuario (
    T01_CodUsuario,
    T01_Password,
    T01_DescUsuario,
    T01_Perfil
) VALUES 
    ('admin', SHA2('adminpaso', 256), 'Descripción del Usuario 0', 'administrador'),
    ('alvaro', SHA2('alvaropaso', 256), 'Descripción del Usuario 1', 'usuario'),
    ('carlos', SHA2('carlospaso', 256), 'Descripción del Usuario 2', 'usuario'),
    ('oscar', SHA2('oscarpaso', 256), 'Descripción del Usuario 3', 'usuario'),
    ('borja', SHA2('borjapaso', 256), 'Descripción del Usuario 4', 'usuario'),
    ('rebeca', SHA2('rebecapaso', 256), 'Descripción del Usuario 5', 'usuario'),
    ('erika', SHA2('erikapaso', 256), 'Descripción del Usuario 6', 'usuario'),
    ('ismael', SHA2('ismaelpaso', 256), 'Descripción del Usuario 7', 'usuario'),
    ('heraclio', SHA2('heracliopaso', 256), 'Descripción del Usuario 7', 'administrador'),
    ('amor', SHA2('amorpaso', 256), 'Descripción del Usuario 8', 'administrador'),
    ('antonio', SHA2('antoniopaso', 256), 'Descripción del Usuario 9', 'administrador'),
    ('alberto', SHA2('antoniopaso', 256), 'Descripción del Usuario 10', 'administrador');
