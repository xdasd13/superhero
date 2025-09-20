# 🦸‍♂️ Sistema de Búsqueda de Superhéroes

Un sistema web completo desarrollado en **CodeIgniter 4** para buscar, visualizar y generar reportes PDF de superhéroes con sus atributos y poderes.

## 🚀 Características Principales

- **🔍 Búsqueda en tiempo real** con autocompletado inteligente
- **⚡ Sugerencias dinámicas** mientras escribes (ej: "bat" → Batman, Batgirl)
- **📊 Visualización de atributos** con barras de progreso interactivas
- **🎯 Mostrar poderes** como badges coloridos y organizados
- **📄 Generación de PDF** con solo los poderes del superhéroe seleccionado
- **📱 Diseño responsive** que funciona en móviles, tablets y desktop
- **🎨 Interfaz moderna** con Bootstrap 5 y Font Awesome

## 🛠️ Tecnologías Utilizadas

- **Backend:** CodeIgniter 4 (PHP 8.1+)
- **Base de Datos:** MySQL
- **Frontend:** Bootstrap 5, JavaScript ES6+
- **PDF:** Html2pdf.js (generación en el cliente)
- **Iconos:** Font Awesome 6
- **AJAX:** Fetch API con async/await

## 📋 Requisitos del Sistema

- **PHP:** 8.1 o superior
- **Composer:** 2.0 o superior (gestor de dependencias PHP)
- **MySQL:** 5.7 o superior
- **Servidor Web:** Apache/Nginx (Laragon, XAMPP, WAMP, etc.)
- **Extensiones PHP:** mysqli, json, mbstring, intl, curl, openssl
- **Navegador:** Chrome, Firefox, Safari, Edge (versiones modernas)
- **Conexión a Internet:** Para cargar Html2pdf.js desde CDN (opcional si se instala localmente)

## 🔧 Instalación y Configuración

### 1. **Clonar o Descargar el Proyecto**
```bash
git clone [URL_DEL_REPOSITORIO] superhero
cd superhero
```

### 2. **Instalar Composer (si no está instalado)**

#### **Windows:**

**Si usas Laragon:**
Laragon ya incluye Composer. Verificar con:
```bash
composer --version
```

**Si no tienes Composer o usas otro servidor:**
1. Descargar desde: https://getcomposer.org/download/
2. Ejecutar el instalador `Composer-Setup.exe`
3. Verificar instalación:
```bash
composer --version
```

#### **Linux/Mac:**
```bash
# Descargar e instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Verificar instalación
composer --version
```

### 3. **Instalar Dependencias del Proyecto**
```bash
# En la carpeta del proyecto
composer install

# Si hay problemas, forzar actualización
composer update
```

### 4. **Configurar el Entorno**
```bash
# Copiar archivo de configuración
copy env .env

# Editar .env con tus datos
notepad .env
```

**Configuración de Base de Datos en `.env`:**
```env
# Base de datos
database.default.hostname = localhost
database.default.database = superhero
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi

# URL base (ajustar según tu servidor)
app.baseURL = 'http://localhost/superhero/public/'
```

### 5. **Configurar la Base de Datos**

#### **Opción A: Usar phpMyAdmin (Recomendado)**
1. Abrir `http://localhost/phpmyadmin`
2. Crear base de datos `superhero`
3. Ejecutar los archivos SQL en orden:
   - `app/Database/01_reference_data.sql`
   - `app/Database/02_hero_attribute.sql`
   - `app/Database/03_hero_power.sql`

#### **Opción B: Línea de Comandos MySQL**
```bash
mysql -u root -p
CREATE DATABASE superhero;
USE superhero;
source app/Database/01_reference_data.sql;
source app/Database/02_hero_attribute.sql;
source app/Database/03_hero_power.sql;
```

### 6. **Configurar Html2pdf.js para Generación de PDF**

El sistema utiliza **Html2pdf.js** para generar PDFs en el navegador. Esta librería se carga automáticamente desde CDN, pero aquí tienes opciones adicionales:

#### **Opción A: CDN (Ya configurado - Recomendado)**
La vista ya incluye la librería desde CDN:
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
```

#### **Opción B: Instalación Local**
Si prefieres tener la librería localmente:

1. **Descargar Html2pdf.js:**
```bash
# Opción 1: Con npm (si tienes Node.js)
npm install html2pdf.js

# Opción 2: Descargar manualmente
# Ir a: https://github.com/eKoopmans/html2pdf.js/releases
# Descargar html2pdf.bundle.min.js
```

2. **Colocar en el proyecto:**
```bash
# Crear carpeta para assets
mkdir public/assets/js

# Copiar el archivo descargado
copy html2pdf.bundle.min.js public/assets/js/
```

3. **Actualizar la vista** (`app/Views/Reportes/reporte5.php`):
```html
<!-- Cambiar esta línea: -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Por esta: -->
<script src="<?= base_url('assets/js/html2pdf.bundle.min.js') ?>"></script>
```

#### **Opción C: Composer (Alternativa PHP)**
Si prefieres generar PDFs en el servidor con PHP:
```bash
# Instalar librería PHP para PDF
composer require dompdf/dompdf

# O usar mPDF
composer require mpdf/mpdf
```

### 7. **Verificar Instalación**
1. Abrir navegador en: `http://localhost/superhero/public/test`
2. Verificar que todas las tablas aparezcan como ✅
3. Confirmar que hay datos de muestra
4. Probar generación de PDF en: `http://localhost/superhero/public/hero`

## 🎯 Uso del Sistema

### **Página Principal**
Navegar a: `http://localhost/superhero/public/hero`

### **Funcionalidades Disponibles:**

#### 1. **🔍 Búsqueda de Superhéroes**
- Escribir al menos 2 caracteres en el campo de búsqueda
- Ver sugerencias automáticas mientras escribes
- Hacer clic en una sugerencia para seleccionar el héroe

#### 2. **📊 Visualización de Información**
- **Información básica:** Nombre y alias del superhéroe
- **Atributos:** Barras de progreso con valores de 0-100
  - Inteligencia
  - Fuerza
  - Velocidad
  - Durabilidad
  - Poder
  - Combate
- **Poderes:** Lista de badges coloridos con todos los poderes

#### 3. **📄 Generación de PDF**
- Hacer clic en "Generar PDF"
- Se abre en nueva pestaña con:
  - Nombre del superhéroe en el encabezado
  - Lista completa de poderes
  - Formato profesional listo para imprimir

## 🏗️ Estructura del Proyecto

```
superhero/
├── app/
│   ├── Controllers/
│   │   ├── HeroController.php      # Controlador principal
│   │   └── TestController.php      # Pruebas de conectividad
│   ├── Models/
│   │   └── HeroModel.php           # Modelo de datos
│   ├── Views/
│   │   ├── Reportes/
│   │   │   └── reporte5.php        # Vista principal
│   │   └── test_connectivity.php   # Vista de pruebas
│   ├── Config/
│   │   └── Routes.php              # Configuración de rutas
│   └── Database/
│       ├── 01_reference_data.sql   # Datos de superhéroes
│       ├── 02_hero_attribute.sql   # Atributos
│       └── 03_hero_power.sql       # Poderes
├── public/
│   └── index.php                   # Punto de entrada
└── .env                            # Configuración
```

## 🔗 Rutas Disponibles

| Ruta | Método | Descripción |
|------|--------|-------------|
| `/hero` | GET | Página principal de búsqueda |
| `/hero/search` | POST | Endpoint AJAX para búsqueda |
| `/hero/{id}` | GET | Obtener información completa de un héroe |
| `/hero/generatePDFData` | POST | Datos para generación de PDF |
| `/test` | GET | Verificar conectividad y tablas |

## 🗄️ Estructura de Base de Datos

### **Tablas Principales:**

#### `superhero`
- `id` (INT) - ID único del superhéroe
- `superhero_name` (VARCHAR) - Nombre del superhéroe
- `full_name` (VARCHAR) - Nombre completo/alias

#### `hero_attribute`
- `hero_id` (INT) - Referencia al superhéroe
- `attribute_id` (INT) - Referencia al atributo
- `attribute_value` (INT) - Valor del atributo (0-100)

#### `attribute`
- `id` (INT) - ID único del atributo
- `attribute_name` (VARCHAR) - Nombre del atributo

#### `hero_power`
- `hero_id` (INT) - Referencia al superhéroe
- `power_id` (INT) - Referencia al poder

#### `superpower`
- `id` (INT) - ID único del poder
- `power_name` (VARCHAR) - Nombre del poder

## 🎨 Personalización

### **Modificar Estilos**
Editar el CSS en `app/Views/Reportes/reporte5.php`:
```css
/* Personalizar colores principales */
:root {
    --primary-color: #007bff;
    --success-color: #28a745;
    --warning-color: #ffc107;
}
```

### **Agregar Nuevos Atributos**
1. Insertar en tabla `attribute`
2. Agregar datos en `hero_attribute`
3. El sistema los mostrará automáticamente

### **Personalizar PDF**
Modificar la función `createPDF()` en `reporte5.php`:
```javascript
// Cambiar formato, colores, fuentes, etc.
const opt = {
    margin: 1,
    filename: 'superhero-powers.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
};
```

## 🐛 Solución de Problemas

### **Error: "Table doesn't exist"**
- Verificar que se ejecutaron todos los archivos SQL
- Comprobar configuración de base de datos en `.env`
- Usar `/test` para diagnosticar

### **Error: "404 Not Found"**
- Verificar que el servidor apunte a la carpeta `public/`
- Comprobar configuración de `app.baseURL` en `.env`

### **Error: "No se muestran resultados"**
- Verificar que hay datos en las tablas
- Comprobar consultas SQL en el modelo
- Revisar consola del navegador para errores JavaScript

### **PDF no se genera**
- Verificar que Html2pdf.js se carga correctamente
- Comprobar que hay poderes para el superhéroe seleccionado
- Revisar consola del navegador

## 📱 Compatibilidad

### **Navegadores Soportados:**
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

### **Dispositivos:**
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (320px - 767px)

## 🔒 Seguridad

- **Validación de entrada:** Todos los inputs son validados
- **Consultas preparadas:** Protección contra SQL injection
- **Sanitización:** Datos escapados antes de mostrar
- **CSRF:** Protección habilitada en CodeIgniter

## 👥 Contribuir

1. Fork del proyecto
2. Crear rama para nueva funcionalidad (`git checkout -b feature/nueva-funcionalidad`)
3. Commit de cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

**¡Disfruta explorando el mundo de los superhéroes! 🦸‍♂️🦸‍♀️**

---

*Desarrollado con ❤️ usando CodeIgniter 4*
