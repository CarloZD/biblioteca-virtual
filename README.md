# 📚 Sistema Biblioteca Virtual - Instalación Fácil

## 🚀 INSTALACIÓN EN 5 PASOS SIMPLES

### **Paso 1: Descargar el proyecto**
```bash
git clone https://github.com/tu-usuario/biblioteca-virtual.git
cd biblioteca-virtual
```

### **Paso 2: Instalar dependencias PHP**
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### **Paso 3: Configurar .env**
Edita el archivo `.env` y cambia estas líneas:
```env
DB_CONNECTION=oracle
DB_HOST=localhost
DB_PORT=1521
DB_DATABASE=XEPDB1
DB_USERNAME=biblioteca
DB_PASSWORD=biblioteca123
```

### **Paso 4: Ejecutar script de base de datos**
```bash
# Conectar a Oracle como administrador
sqlplus sys/password@localhost:1521/XEPDB1 as sysdba

# Ejecutar el script (copiar y pegar todo el contenido del archivo database/install.sql)
```

### **Paso 5: Iniciar el sistema**
```bash
php artisan serve
```

Ir a: **http://localhost:8000**

**Credenciales:**
- Admin: admin@biblioteca.com / password
- Usuario: usuario@biblioteca.com / password

---

# 📄 SCRIPT DE BASE DE DATOS COMPLETO

## Archivo: `database/install.sql`

```sql
-- =============================================
-- SCRIPT DE INSTALACIÓN AUTOMÁTICA
-- SISTEMA BIBLIOTECA VIRTUAL - ORACLE
-- =============================================

-- PASO 1: CREAR USUARIO Y PERMISOS
-- (Ejecutar como SYS)
CREATE USER biblioteca IDENTIFIED BY biblioteca123;
GRANT CONNECT, RESOURCE, CREATE SESSION TO biblioteca;
GRANT CREATE TABLE, CREATE SEQUENCE, CREATE PROCEDURE TO biblioteca;
GRANT CREATE TRIGGER, CREATE PACKAGE TO biblioteca;
GRANT UNLIMITED TABLESPACE TO biblioteca;

-- PASO 2: CONECTAR COMO USUARIO BIBLIOTECA
-- Ejecutar: CONNECT biblioteca/biblioteca123@localhost:1521/XEPDB1;

-- PASO 3: CREAR TABLAS
CREATE TABLE usuarios (
    id NUMBER CONSTRAINT pk_usuarios PRIMARY KEY,
    nombre VARCHAR2(100) NOT NULL,
    email VARCHAR2(150) NOT NULL CONSTRAINT uk_usuarios_email UNIQUE,
    password VARCHAR2(255) NOT NULL,
    rol VARCHAR2(20) DEFAULT 'USUARIO' 
        CONSTRAINT ck_usuarios_rol CHECK (rol IN ('BIBLIOTECARIO', 'USUARIO')),
    activo NUMBER(1) DEFAULT 1 
        CONSTRAINT ck_usuarios_activo CHECK (activo IN (0, 1)),
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE
);

CREATE TABLE libros (
    id NUMBER CONSTRAINT pk_libros PRIMARY KEY,
    titulo VARCHAR2(200) NOT NULL,
    autor VARCHAR2(150) NOT NULL,
    isbn VARCHAR2(20) CONSTRAINT uk_libros_isbn UNIQUE,
    categoria VARCHAR2(50),
    descripcion CLOB,
    disponible NUMBER(1) DEFAULT 1 
        CONSTRAINT ck_libros_disponible CHECK (disponible IN (0, 1)),
    fecha_publicacion DATE,
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE
);

-- PASO 4: CREAR SECUENCIAS
CREATE SEQUENCE seq_usuarios START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_libros START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;

-- PASO 5: CREAR TRIGGERS
CREATE OR REPLACE TRIGGER trg_usuarios_bi
    BEFORE INSERT ON usuarios
    FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        :NEW.id := seq_usuarios.NEXTVAL;
    END IF;
    :NEW.created_at := SYSDATE;
    :NEW.updated_at := SYSDATE;
END;
/

CREATE OR REPLACE TRIGGER trg_usuarios_bu
    BEFORE UPDATE ON usuarios
    FOR EACH ROW
BEGIN
    :NEW.updated_at := SYSDATE;
END;
/

CREATE OR REPLACE TRIGGER trg_libros_bi
    BEFORE INSERT ON libros
    FOR EACH ROW
BEGIN
    IF :NEW.id IS NULL THEN
        :NEW.id := seq_libros.NEXTVAL;
    END IF;
    :NEW.created_at := SYSDATE;
    :NEW.updated_at := SYSDATE;
END;
/

CREATE OR REPLACE TRIGGER trg_libros_bu
    BEFORE UPDATE ON libros
    FOR EACH ROW
BEGIN
    :NEW.updated_at := SYSDATE;
END;
/

-- PASO 6: CREAR PAQUETES PL/SQL
CREATE OR REPLACE PACKAGE pkg_usuarios AS
    PROCEDURE crear_usuario(
        p_nombre IN VARCHAR2,
        p_email IN VARCHAR2,
        p_password IN VARCHAR2,
        p_rol IN VARCHAR2 DEFAULT 'USUARIO',
        p_result OUT NUMBER
    );
    
    FUNCTION listar_usuarios RETURN SYS_REFCURSOR;
END pkg_usuarios;
/

CREATE OR REPLACE PACKAGE BODY pkg_usuarios AS
    PROCEDURE crear_usuario(
        p_nombre IN VARCHAR2,
        p_email IN VARCHAR2,
        p_password IN VARCHAR2,
        p_rol IN VARCHAR2 DEFAULT 'USUARIO',
        p_result OUT NUMBER
    ) IS
        v_count NUMBER;
    BEGIN
        SELECT COUNT(*) INTO v_count 
        FROM usuarios 
        WHERE UPPER(email) = UPPER(p_email);
        
        IF v_count > 0 THEN
            p_result := -2; 
            RETURN;
        END IF;
        
        INSERT INTO usuarios (nombre, email, password, rol)
        VALUES (p_nombre, p_email, p_password, NVL(p_rol, 'USUARIO'));
        
        p_result := 1;
        COMMIT;
        
    EXCEPTION
        WHEN OTHERS THEN
            p_result := 0;
            ROLLBACK;
    END crear_usuario;
    
    FUNCTION listar_usuarios RETURN SYS_REFCURSOR IS
        v_cursor SYS_REFCURSOR;
    BEGIN
        OPEN v_cursor FOR
            SELECT id, nombre, email, rol, activo, created_at
            FROM usuarios
            WHERE activo = 1
            ORDER BY nombre;
        RETURN v_cursor;
    END listar_usuarios;
END pkg_usuarios;
/

CREATE OR REPLACE PACKAGE pkg_libros AS
    PROCEDURE crear_libro(
        p_titulo IN VARCHAR2,
        p_autor IN VARCHAR2,
        p_isbn IN VARCHAR2,
        p_categoria IN VARCHAR2,
        p_descripcion IN CLOB,
        p_fecha_publicacion IN DATE,
        p_result OUT NUMBER
    );
    
    PROCEDURE actualizar_libro(
        p_id IN NUMBER,
        p_titulo IN VARCHAR2,
        p_autor IN VARCHAR2,
        p_isbn IN VARCHAR2,
        p_categoria IN VARCHAR2,
        p_descripcion IN CLOB,
        p_disponible IN NUMBER,
        p_result OUT NUMBER
    );
    
    FUNCTION listar_libros RETURN SYS_REFCURSOR;
    FUNCTION buscar_libros(p_termino IN VARCHAR2) RETURN SYS_REFCURSOR;
END pkg_libros;
/

CREATE OR REPLACE PACKAGE BODY pkg_libros AS
    PROCEDURE crear_libro(
        p_titulo IN VARCHAR2,
        p_autor IN VARCHAR2,
        p_isbn IN VARCHAR2,
        p_categoria IN VARCHAR2,
        p_descripcion IN CLOB,
        p_fecha_publicacion IN DATE,
        p_result OUT NUMBER
    ) IS
        v_count NUMBER;
    BEGIN
        IF p_isbn IS NOT NULL THEN
            SELECT COUNT(*) INTO v_count 
            FROM libros 
            WHERE isbn = p_isbn;
            
            IF v_count > 0 THEN
                p_result := -2;
                RETURN;
            END IF;
        END IF;
        
        INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion)
        VALUES (p_titulo, p_autor, p_isbn, p_categoria, p_descripcion, p_fecha_publicacion);
        
        p_result := 1;
        COMMIT;
        
    EXCEPTION
        WHEN OTHERS THEN
            p_result := 0;
            ROLLBACK;
    END crear_libro;
    
    PROCEDURE actualizar_libro(
        p_id IN NUMBER,
        p_titulo IN VARCHAR2,
        p_autor IN VARCHAR2,
        p_isbn IN VARCHAR2,
        p_categoria IN VARCHAR2,
        p_descripcion IN CLOB,
        p_disponible IN NUMBER,
        p_result OUT NUMBER
    ) IS
        v_count NUMBER;
    BEGIN
        SELECT COUNT(*) INTO v_count 
        FROM libros 
        WHERE id = p_id;
        
        IF v_count = 0 THEN
            p_result := -1;
            RETURN;
        END IF;
        
        UPDATE libros 
        SET titulo = p_titulo,
            autor = p_autor,
            isbn = p_isbn,
            categoria = p_categoria,
            descripcion = p_descripcion,
            disponible = NVL(p_disponible, 1)
        WHERE id = p_id;
        
        p_result := 1;
        COMMIT;
        
    EXCEPTION
        WHEN OTHERS THEN
            p_result := 0;
            ROLLBACK;
    END actualizar_libro;
    
    FUNCTION listar_libros RETURN SYS_REFCURSOR IS
        v_cursor SYS_REFCURSOR;
    BEGIN
        OPEN v_cursor FOR
            SELECT id, titulo, autor, isbn, categoria, disponible, fecha_publicacion, created_at
            FROM libros
            ORDER BY titulo;
        RETURN v_cursor;
    END listar_libros;
    
    FUNCTION buscar_libros(p_termino IN VARCHAR2) RETURN SYS_REFCURSOR IS
        v_cursor SYS_REFCURSOR;
    BEGIN
        OPEN v_cursor FOR
            SELECT id, titulo, autor, isbn, categoria, disponible, fecha_publicacion
            FROM libros
            WHERE UPPER(titulo) LIKE '%' || UPPER(p_termino) || '%'
               OR UPPER(autor) LIKE '%' || UPPER(p_termino) || '%'
               OR UPPER(categoria) LIKE '%' || UPPER(p_termino) || '%'
            ORDER BY titulo;
        RETURN v_cursor;
    END buscar_libros;
END pkg_libros;
/

-- PASO 7: INSERTAR DATOS DE PRUEBA
INSERT INTO usuarios (nombre, email, password, rol) 
VALUES ('Administrador Sistema', 'admin@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BIBLIOTECARIO');

INSERT INTO usuarios (nombre, email, password, rol) 
VALUES ('Usuario Demo', 'usuario@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USUARIO');

INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion) 
VALUES ('Cien años de soledad', 'Gabriel García Márquez', '978-0307389732', 'Literatura', 'Obra maestra del realismo mágico', DATE '1967-06-05');

INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion) 
VALUES ('1984', 'George Orwell', '978-0452284234', 'Ciencia Ficción', 'Novela distópica sobre un mundo totalitario', DATE '1949-06-08');

INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion) 
VALUES ('El principito', 'Antoine de Saint-Exupéry', '978-0156012195', 'Infantil', 'Cuento filosófico sobre la amistad y el amor', DATE '1943-04-06');

INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion) 
VALUES ('Don Quijote de La Mancha', 'Miguel de Cervantes', '978-8424930301', 'Literatura', 'La historia del ingenioso hidalgo', DATE '1605-01-16');

INSERT INTO libros (titulo, autor, isbn, categoria, descripcion, fecha_publicacion) 
VALUES ('Sapiens', 'Yuval Noah Harari', '978-0062316097', 'Historia', 'Una breve historia de la humanidad', DATE '2011-01-01');

COMMIT;

-- PASO 8: VERIFICAR INSTALACIÓN
SELECT 'Instalación completada exitosamente' as status FROM DUAL;
SELECT 'Usuarios creados: ' || COUNT(*) as usuarios FROM usuarios;
SELECT 'Libros creados: ' || COUNT(*) as libros FROM libros;
SELECT 'Procedimientos: pkg_usuarios, pkg_libros' as procedimientos FROM DUAL;

-- ¡LISTO! El sistema está instalado y funcionando
```

---

# 🛠️ REQUISITOS PREVIOS

## 1. Oracle 21c Express Edition
- Descargar desde: https://www.oracle.com/database/technologies/xe-downloads.html
- Instalar con configuración por defecto
- Puerto: 1521, SID: XE

## 2. PHP 8.2+ con OCI8
- XAMPP recomendado
- Descargar extensión OCI8 para PHP 8.2
- Configurar php.ini: `extension=php_oci8_19`

## 3. Composer
- Descargar desde: https://getcomposer.org/

---

# ❓ SOLUCIÓN DE PROBLEMAS

## Error: "ORA-01017 invalid username/password"
```bash
# Verificar que Oracle esté ejecutándose
net start | findstr -i oracle

# Iniciar servicios si están detenidos
net start OracleServiceXE
net start OracleXETNSListener
```

## Error: "could not find driver oci8"
- Verificar que php_oci8_19.dll esté en la carpeta php/ext/
- Comprobar que está habilitado en php.ini
- Reiniciar Apache

## Error de conexión Laravel
```bash
php artisan config:clear
php artisan cache:clear
```

---

# 📱 CONTACTO Y SOPORTE

- **Repositorio**: https://github.com/tu-usuario/biblioteca-virtual
- **Documentación**: Ver README.md del proyecto
- **Issues**: Crear issue en GitHub si hay problemas

---

**🎉 ¡Sistema listo para usar en cualquier PC con Oracle!** 🎉