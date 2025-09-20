# ⚡ Guía Rápida de Inicio - Sistema de Superhéroes

## 🚀 Inicio Rápido (5 minutos)

### **1. Instalar Dependencias**
```bash
# Verificar Composer (Laragon ya lo incluye)
composer --version

# Instalar dependencias del proyecto
composer install
```

### **2. Configurar Base de Datos**
```sql
-- En phpMyAdmin o MySQL:
CREATE DATABASE superhero;
USE superhero;

-- Ejecutar archivos SQL en orden:
-- 1. app/Database/01_reference_data.sql
-- 2. app/Database/02_hero_attribute.sql  
-- 3. app/Database/03_hero_power.sql
```

### **3. Configurar .env**
```env
# Copiar env a .env y configurar:
database.default.database = superhero
database.default.username = root
database.default.password = 
app.baseURL = 'http://localhost/superhero/public/'
```

### **4. Probar el Sistema**
1. **Verificar:** `http://localhost/superhero/public/test`
2. **Usar:** `http://localhost/superhero/public/hero`

## 🎯 Uso Básico

### **Buscar Superhéroes:**
- Escribir "bat" → Ver Batman, Batgirl
- Escribir "spider" → Ver Spider-Man, Spider-Woman
- Escribir "super" → Ver Superman, Supergirl

### **Ver Información:**
- Hacer clic en sugerencia
- Ver atributos con barras de progreso
- Ver poderes como badges coloridos

### **Generar PDF:**
- Seleccionar superhéroe
- Hacer clic "Generar PDF"
- Se abre en nueva pestaña

## 🔧 Solución Rápida de Problemas

| Problema | Solución |
|----------|----------|
| 404 Error | Verificar que el servidor apunte a `/public/` |
| No hay datos | Ejecutar archivos SQL de la carpeta `/Database/` |
| PDF no funciona | Verificar conexión a internet (Html2pdf.js CDN) |
| Búsqueda no funciona | Revisar configuración de base de datos en `.env` |

## 📱 URLs Importantes

- **Sistema Principal:** `/hero`
- **Pruebas:** `/test`
- **Búsqueda AJAX:** `/hero/search` (POST)

¡Listo para usar! 🦸‍♂️
