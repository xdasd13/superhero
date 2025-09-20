# 🦸‍♂️ Sistema de Búsqueda de Superhéroes - CodeIgniter 4

## 📋 Descripción
Sistema completo de búsqueda de superhéroes con funcionalidades avanzadas de autocompletado, visualización de datos y generación de reportes PDF.

## 🚀 Características Principales

### ✨ Búsqueda Inteligente
- **Autocompletado en tiempo real**: Escribe mínimo 2 caracteres
- **Sugerencias visuales**: Lista desplegable con imagen, nombre y alias
- **Búsqueda flexible**: Busca por nombre o alias del superhéroe

### 📊 Visualización de Datos
- **Información completa**: Datos básicos, atributos y poderes
- **Atributos visuales**: Barras de progreso para estadísticas
- **Poderes destacados**: Badges coloridos para cada poder
- **Diseño responsive**: Compatible con móviles y tablets

### 📄 Generación de PDF
- **Reportes personalizados**: PDF con solo los poderes del héroe
- **Diseño profesional**: Encabezado con nombre del héroe
- **Descarga automática**: Se abre en nueva pestaña

## 🛠️ Instalación y Configuración

### 1. Estructura de Base de Datos Requerida

```sql
-- Tabla principal de superhéroes
CREATE TABLE reference_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    alias VARCHAR(255),
    image VARCHAR(500)
);

-- Tabla de atributos
CREATE TABLE hero_attribute (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hero_id INT,
    attribute_name VARCHAR(100),
    attribute_value INT,
    FOREIGN KEY (hero_id) REFERENCES reference_data(id)
);

-- Tabla de poderes
CREATE TABLE superpower (
    id INT PRIMARY KEY AUTO_INCREMENT,
    power_name VARCHAR(255) NOT NULL
);

-- Relación héroe-poderes
CREATE TABLE hero_power (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hero_id INT,
    power_id INT,
    FOREIGN KEY (hero_id) REFERENCES reference_data(id),
    FOREIGN KEY (power_id) REFERENCES superpower(id)
);
```

### 2. Configuración de CodeIgniter 4

1. **Configurar base de datos** en `.env`:
```env
database.default.hostname = localhost
database.default.database = tu_base_de_datos
database.default.username = tu_usuario
database.default.password = tu_contraseña
database.default.DBDriver = MySQLi
```

2. **Verificar archivos creados**:
   - `app/Controllers/HeroController.php`
   - `app/Models/HeroModel.php`
   - `app/Views/Reportes/reporte5.php`
   - `app/Controllers/TestController.php`
   - `app/Views/test_connectivity.php`

## 🔗 URLs del Sistema

### Principales
- **Sistema de búsqueda**: `/hero`
- **Test de conectividad**: `/test`

### API Endpoints
- **Búsqueda AJAX**: `POST /hero/search`
- **Información de héroe**: `GET /hero/{id}`
- **Datos para PDF**: `POST /hero/generatePDFData`

## 🧪 Cómo Probar el Sistema

### 1. Verificar Conectividad
1. Ve a: `http://localhost/superhero/public/test`
2. Verifica que todas las tablas existan
3. Prueba la búsqueda con el botón "Probar Búsqueda"

### 2. Usar el Sistema de Búsqueda
1. Ve a: `http://localhost/superhero/public/hero`
2. Escribe términos como:
   - `"bat"` → Batman, Batgirl, Batwoman
   - `"spider"` → Spider-Man, Spider-Woman
   - `"super"` → Superman, Supergirl, Superboy

### 3. Generar PDF
1. Selecciona un superhéroe de las sugerencias
2. Haz clic en "Generar PDF"
3. El PDF se abrirá en una nueva pestaña

## 🎨 Tecnologías Utilizadas

### Backend
- **CodeIgniter 4**: Framework PHP
- **MySQL**: Base de datos
- **PHP 8.1+**: Lenguaje de programación

### Frontend
- **Bootstrap 5**: Framework CSS responsive
- **Font Awesome**: Iconos
- **JavaScript ES6+**: Funcionalidad AJAX
- **Html2pdf.js**: Generación de PDF

## 📁 Estructura de Archivos

```
app/
├── Controllers/
│   ├── HeroController.php      # Controlador principal
│   └── TestController.php      # Controlador de pruebas
├── Models/
│   └── HeroModel.php          # Modelo de datos
├── Views/
│   ├── Reportes/
│   │   └── reporte5.php       # Vista principal
│   └── test_connectivity.php   # Vista de pruebas
└── Config/
    └── Routes.php             # Configuración de rutas
```

## 🔧 Funciones del Modelo (HeroModel.php)

- `searchHeroesByName($term)` - Búsqueda por nombre/alias
- `getHeroById($id)` - Obtener héroe específico
- `getHeroAttributes($heroId)` - Atributos del héroe
- `getHeroPowers($heroId)` - Poderes del héroe
- `getHeroCompleteInfo($heroId)` - Información completa
- `getHeroStats($heroId)` - Estadísticas de atributos

## 🎯 Ejemplos de Uso

### Búsqueda por Término
```javascript
// Ejemplo de llamada AJAX
const response = await fetch('/hero/search', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ term: 'bat' })
});
```

### Obtener Información Completa
```javascript
// Obtener héroe por ID
const response = await fetch('/hero/1');
const data = await response.json();
```

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos
1. Verifica la configuración en `.env`
2. Asegúrate de que MySQL esté ejecutándose
3. Confirma que las tablas existan

### No Aparecen Sugerencias
1. Verifica que haya datos en `reference_data`
2. Comprueba que el término tenga al menos 2 caracteres
3. Revisa la consola del navegador para errores

### PDF No Se Genera
1. Verifica que Html2pdf.js esté cargado
2. Asegúrate de que el héroe tenga poderes registrados
3. Comprueba que no haya bloqueadores de pop-ups

## 📞 Soporte

Si encuentras algún problema:
1. Revisa la consola del navegador (F12)
2. Verifica los logs de CodeIgniter en `writable/logs/`
3. Usa la página de test (`/test`) para diagnosticar problemas

## 🎉 ¡Disfruta Buscando Superhéroes!

El sistema está listo para usar. Explora las funcionalidades y personaliza según tus necesidades.
