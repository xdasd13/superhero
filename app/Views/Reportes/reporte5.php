<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Superhéroes</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        .hero-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .hero-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .suggestion-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .suggestion-item:hover {
            background-color: #f8f9fa;
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestions-container {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-container {
            position: relative;
        }
        .attribute-bar {
            height: 20px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }
        .attribute-value {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        .loading {
            display: none;
        }
        .hero-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
        .power-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-primary">
                        <i class="fas fa-mask"></i> Búsqueda de Superhéroes
                    </h1>
                    <p class="lead text-muted">Encuentra tu superhéroe favorito y descubre sus poderes</p>
                </div>

                <!-- Search Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="search-container">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       id="heroSearch" 
                                       class="form-control" 
                                       placeholder="Escribe el nombre de un superhéroe (ej: bat, spider, super...)"
                                       autocomplete="off">
                                <button class="btn btn-primary" type="button" id="searchBtn">
                                    <span class="loading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                    <span class="search-text">Buscar</span>
                                </button>
                            </div>
                            
                            <!-- Suggestions Dropdown -->
                            <div id="suggestions" class="suggestions-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Hero Results -->
                <div id="heroResults" class="d-none">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-shield"></i> Información del Superhéroe
                            </h5>
                            <button id="generatePDF" class="btn btn-light btn-sm">
                                <i class="fas fa-file-pdf text-danger"></i> Generar PDF
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="heroInfo"></div>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Buscando superhéroes...</p>
                </div>

                <!-- Error Alert -->
                <div id="errorAlert" class="alert alert-danger d-none" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="errorMessage"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        class HeroSearch {
            constructor() {
                this.searchInput = document.getElementById('heroSearch');
                this.searchBtn = document.getElementById('searchBtn');
                this.suggestions = document.getElementById('suggestions');
                this.heroResults = document.getElementById('heroResults');
                this.heroInfo = document.getElementById('heroInfo');
                this.loadingIndicator = document.getElementById('loadingIndicator');
                this.errorAlert = document.getElementById('errorAlert');
                this.generatePDFBtn = document.getElementById('generatePDF');
                
                this.currentHero = null;
                this.searchTimeout = null;
                
                this.initEventListeners();
            }

            initEventListeners() {
                // Búsqueda en tiempo real
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    const term = e.target.value.trim();
                    
                    if (term.length >= 2) {
                        this.searchTimeout = setTimeout(() => {
                            this.searchHeroes(term);
                        }, 300);
                    } else {
                        this.hideSuggestions();
                    }
                });

                // Ocultar sugerencias al hacer clic fuera
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.search-container')) {
                        this.hideSuggestions();
                    }
                });

                // Botón de búsqueda
                this.searchBtn.addEventListener('click', () => {
                    const term = this.searchInput.value.trim();
                    if (term.length >= 2) {
                        this.searchHeroes(term);
                    }
                });

                // Enter en el input
                this.searchInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        const term = this.searchInput.value.trim();
                        if (term.length >= 2) {
                            this.searchHeroes(term);
                        }
                    }
                });

                // Generar PDF
                this.generatePDFBtn.addEventListener('click', () => {
                    if (this.currentHero) {
                        this.generatePDF();
                    }
                });
            }

            async searchHeroes(term) {
                try {
                    this.showLoading();
                    this.hideError();

                    const response = await fetch('/hero/search', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ term: term })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.displaySuggestions(data.heroes);
                    } else {
                        this.showError(data.message);
                    }
                } catch (error) {
                    this.showError('Error de conexión: ' + error.message);
                } finally {
                    this.hideLoading();
                }
            }

            displaySuggestions(heroes) {
                if (heroes.length === 0) {
                    this.suggestions.innerHTML = '<div class="suggestion-item text-muted">No se encontraron superhéroes</div>';
                    this.showSuggestions();
                    return;
                }

                const suggestionsHTML = heroes.map(hero => `
                    <div class="suggestion-item" data-hero-id="${hero.id}">
                        <div class="d-flex align-items-center">
                            ${hero.image ? `<img src="${hero.image}" alt="${hero.name}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">` : '<div class="bg-secondary rounded-circle me-3" style="width: 40px; height: 40px;"></div>'}
                            <div>
                                <div class="fw-bold">${hero.name}</div>
                                ${hero.alias ? `<small class="text-muted">${hero.alias}</small>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');

                this.suggestions.innerHTML = suggestionsHTML;
                this.showSuggestions();

                // Agregar event listeners a las sugerencias
                this.suggestions.querySelectorAll('.suggestion-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const heroId = item.dataset.heroId;
                        this.selectHero(heroId);
                    });
                });
            }

            async selectHero(heroId) {
                try {
                    this.hideSuggestions();
                    this.showLoading();

                    const response = await fetch(`/hero/${heroId}`);
                    const data = await response.json();

                    if (data.success) {
                        this.currentHero = data.hero;
                        this.displayHeroInfo(data.hero);
                        this.searchInput.value = data.hero.name;
                    } else {
                        this.showError(data.message);
                    }
                } catch (error) {
                    this.showError('Error al obtener información del superhéroe: ' + error.message);
                } finally {
                    this.hideLoading();
                }
            }

            displayHeroInfo(hero) {
                const attributesHTML = hero.attributes.map(attr => {
                    const value = parseInt(attr.attribute_value) || 0;
                    const percentage = Math.min(value, 100);
                    
                    return `
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold">${attr.attribute_name}</span>
                                <span class="badge bg-primary">${value}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: ${percentage}%"></div>
                            </div>
                        </div>
                    `;
                }).join('');

                const powersHTML = hero.powers.map(power => `
                    <span class="badge power-badge me-2 mb-2">${power.power_name}</span>
                `).join('');

                this.heroInfo.innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            ${hero.image ? `<img src="${hero.image}" alt="${hero.name}" class="hero-image mb-3">` : '<div class="hero-image bg-secondary d-flex align-items-center justify-content-center mb-3"><i class="fas fa-user fa-3x text-white"></i></div>'}
                            <h3 class="text-primary">${hero.name}</h3>
                            ${hero.alias ? `<p class="text-muted">${hero.alias}</p>` : ''}
                        </div>
                        <div class="col-md-8">
                            <h5 class="text-secondary mb-3">
                                <i class="fas fa-chart-bar"></i> Atributos
                            </h5>
                            <div class="row">
                                ${attributesHTML}
                            </div>
                            
                            <h5 class="text-secondary mb-3 mt-4">
                                <i class="fas fa-bolt"></i> Poderes
                            </h5>
                            <div class="powers-container">
                                ${powersHTML || '<span class="text-muted">No se encontraron poderes registrados</span>'}
                            </div>
                        </div>
                    </div>
                `;

                this.heroResults.classList.remove('d-none');
            }

            async generatePDF() {
                if (!this.currentHero) return;

                try {
                    const response = await fetch('/hero/generatePDFData', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ heroId: this.currentHero.id })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.createPDF(data.hero, data.powers);
                    } else {
                        this.showError(data.message);
                    }
                } catch (error) {
                    this.showError('Error al generar PDF: ' + error.message);
                }
            }

            createPDF(hero, powers) {
                const powersHTML = powers.map(power => `
                    <li style="margin-bottom: 8px; padding: 5px; background: #f8f9fa; border-left: 3px solid #007bff;">
                        ${power.power_name}
                    </li>
                `).join('');

                const pdfContent = `
                    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto;">
                        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 20px;">
                            <h1 style="color: #007bff; margin: 0; font-size: 28px;">
                                ${hero.name}
                            </h1>
                            ${hero.alias ? `<p style="color: #666; font-size: 16px; margin: 5px 0 0 0;">${hero.alias}</p>` : ''}
                        </div>
                        
                        <div style="margin-top: 30px;">
                            <h2 style="color: #333; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                                <i class="fas fa-bolt"></i> Poderes y Habilidades
                            </h2>
                            ${powers.length > 0 ? `
                                <ul style="list-style: none; padding: 0;">
                                    ${powersHTML}
                                </ul>
                            ` : '<p style="color: #666; font-style: italic;">No se encontraron poderes registrados para este superhéroe.</p>'}
                        </div>
                        
                        <div style="margin-top: 40px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px;">
                            <p>Reporte generado el ${new Date().toLocaleDateString('es-ES')}</p>
                        </div>
                    </div>
                `;

                const opt = {
                    margin: 1,
                    filename: `${hero.name.replace(/\s+/g, '_')}_Poderes.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(pdfContent).save();
            }

            showSuggestions() {
                this.suggestions.style.display = 'block';
            }

            hideSuggestions() {
                this.suggestions.style.display = 'none';
            }

            showLoading() {
                this.loadingIndicator.classList.remove('d-none');
                this.searchBtn.querySelector('.loading').style.display = 'inline-block';
                this.searchBtn.querySelector('.search-text').textContent = 'Buscando...';
            }

            hideLoading() {
                this.loadingIndicator.classList.add('d-none');
                this.searchBtn.querySelector('.loading').style.display = 'none';
                this.searchBtn.querySelector('.search-text').textContent = 'Buscar';
            }

            showError(message) {
                this.errorAlert.querySelector('#errorMessage').textContent = message;
                this.errorAlert.classList.remove('d-none');
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    this.hideError();
                }, 5000);
            }

            hideError() {
                this.errorAlert.classList.add('d-none');
            }
        }

        // Initialize the application
        document.addEventListener('DOMContentLoaded', () => {
            new HeroSearch();
        });
    </script>
</body>
</html>
